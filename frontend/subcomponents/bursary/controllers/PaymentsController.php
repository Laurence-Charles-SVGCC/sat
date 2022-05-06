<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use yii\base\Model;
use common\models\Receipt;
use common\models\UserModel;
use yii\helpers\ArrayHelper;
use common\models\ErrorObject;
use common\models\BillingModel;
use common\models\ReceiptModel;
use common\models\ApplicantModel;
use common\models\ApplicationModel;

use common\models\PaymentMethodModel;
use common\models\PaymentReceiptForm;
use common\models\ApplicationPeriodModel;
use common\models\StudentRegistrationModel;
use common\models\BatchStudentFeePaymentForm;
use common\models\RegisteredStudentPaymentModifier;
use common\models\StaffProfile;


class PaymentsController extends \yii\web\Controller
{

    public function actionViewReceipt($id, $username)
    {
        $user = Yii::$app->user->identity;
        $receipt = ReceiptModel::getReceiptById($id);
        $billings = ReceiptModel::getBillings($receipt);
        $user = UserModel::findUserByUsername($username);
        $fullName = UserModel::getUserFullname($user);
        $receiptTotal = ReceiptModel::calculateReceiptTotal($receipt);

        $canModifyPayment =
            $receipt->student_registration_id == null ? false : true;

        return $this->render(
            "view-receipt",
            [
                "receipt" => $receipt,
                "username" => $username,
                "userFullname" => $fullName,
                "billings" => $billings,
                "paymentMethod" => ReceiptModel::getPaymentMethod($receipt),
                "applicationPeriod" =>
                ApplicationPeriodModel::getApplicationPeriodNameByID(
                    $billings[0]->application_period_id
                ),
                "registration" => null,
                "receiptTotal" => $receiptTotal,
                "canModifyPayment" => $canModifyPayment
            ]
        );
    }

    public function actionDeleteBilling($billingId)
    {
        $user = Yii::$app->user->identity;
        $billing = BillingModel::getBillingByID($billingId);
        $receipt = BillingModel::getReceipt($billing);

        if (BillingModel::deleteBilling($billing, $user->personid) == false) {
            Yii::$app->getSession()->setFlash(
                'warning',
                'Error occurred deleting billing.'
            );
        }

        return $this->redirect([
            "view-receipt",
            "id" => $receipt->id,
            "username" => UserModel::getUserById($billing->customer_id)->username
        ]);
    }


    public function actionVoidReceipt($receiptId)
    {
        $user = Yii::$app->user->identity;
        $receipt = ReceiptModel::getReceiptById($receiptId);
        $customer = UserModel::getUserById($receipt->customer_id);
        $billings = ReceiptModel::getBillings($receipt);

        if (ReceiptModel::deleteReceipt(
            $receipt,
            $billings,
            $user->personid
        ) == true) {
            return $this->redirect([
                "profiles/redirect-to-customer-profile",
                "username" => $customer->username
            ]);
        } else {
            Yii::$app->getSession()->setFlash(
                'warning',
                'Error occurred deleting receipt.'
            );
        }
        return $this->redirect([
            "view-receipt",
            "id" => $receiptId,
            "username" => UserModel::getUserById($receipt->customer_id)->username,
        ]);
    }

    public function actionPublishReceipt($receiptId)
    {
        $receipt = ReceiptModel::getReceiptById($receiptId);
        $billings = ReceiptModel::getBillings($receipt);
        $customer = UserModel::getUserById($receipt->customer_id);
        $applicantName = UserModel::getUserFullname($customer);
        $applicantId = $customer->username;

        ReceiptModel::publishReceipt(
            $this,
            $receipt,
            $billings,
            $applicantName,
            $applicantId
        );

        $receipt->publish_count += 1;
        if ($receipt->save() == true) {
            Yii::$app->getSession()->setFlash(
                "success",
                "Receipt published successfully."
            );
        } else {
            Yii::$app->getSession()->setFlash(
                "warning",
                "Error occurred publishing receipt."
            );
        }

        return $this->redirect([
            "view-receipt",
            "id" => $receiptId,
            "username" => UserModel::getUserById($receipt->customer_id)->username
        ]);
    }


    public function actionViewReceiptPdf($receiptId)
    {
        $receipt = ReceiptModel::getReceiptById($receiptId);
        $billings = ReceiptModel::getBillings($receipt);
        $customer = UserModel::getUserById($receipt->customer_id);
        $applicantName = UserModel::getUserFullname($customer);
        $applicantId = $customer->username;

        return ReceiptModel::generateReceiptForDownload(
            $this,
            $receipt,
            $billings,
            $applicantName,
            $applicantId
        );
    }

    public function actionViewVoidedReceipt($id)
    {
        $user = Yii::$app->user->identity;
        $receipt = ReceiptModel::getReceiptById($id);
        $billings = ReceiptModel::getBillings($receipt);
        $user = UserModel::findUserByID($id);
        $fullName = UserModel::getUserFullname($user);
        $receiptTotal = ReceiptModel::calculateReceiptTotal($receipt);

        return $this->render(
            "view-voided-receipt",
            [
                "receipt" => $receipt,
                "username" => $user->username,
                "userFullname" => $fullName,
                "billings" => $billings,
                "paymentMethod" => ReceiptModel::getPaymentMethod($receipt),
                "applicationPeriod" =>
                ApplicationPeriodModel::getApplicationPeriodNameByID(
                    $billings[0]->application_period_id
                ),
                "registration" => null,
                "receiptTotal" => $receiptTotal
            ]
        );
    }


    public function actionModifyPayment($receiptId)
    {
        $staff = Yii::$app->user->identity;
        $staffName = UserModel::getUserFullname($staff);
        $receipt = ReceiptModel::getReceiptById($receiptId);
        $billings = ReceiptModel::getBillings($receipt);
        $paymentReceiptForm = new PaymentReceiptForm();
        $paymentReceiptForm->loadReceipt($receipt);

        $staffProfile = new
            StaffProfile($staff->personid, $staff->username, $staffName);

        $paymentMethods =
            ArrayHelper::map(
                PaymentMethodModel::getNonWaiverPaymentMethods(),
                "paymentmethodid",
                "name"
            );

        $billingForms =
            $paymentReceiptForm->generateBatchStudentFeePaymentBillingForms(
                $receipt,
                $billings
            );

        if ($postData = Yii::$app->request->post()) {
            if (
                $paymentReceiptForm->load($postData) == true
                && Model::loadMultiple($billingForms, $postData) == true
            ) {

                $paymentModifier =
                    new RegisteredStudentPaymentModifier(
                        $receipt,
                        $billings,
                        $paymentReceiptForm,
                        $billingForms,
                        $staffProfile
                    );

                $newReceipt = $paymentModifier->execute();

                if ($newReceipt instanceof ErrorObject) {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        $newReceipt->getMessage()
                    );
                } else {
                    return $this->redirect([
                        "view-receipt",
                        "id" => $newReceipt->id,
                        "username" => $newReceipt->username
                    ]);
                }
            } else {
                Yii::$app->getSession()->setFlash(
                    "warning",
                    "Error processing payment modification"
                );
            }
        }

        return $this->render(
            "modify-payment",
            [
                "receiptNumber" => $receipt->receipt_number,
                "receiptId" => $receiptId,
                "customerFullName" => $paymentReceiptForm->fullName,
                "customerUsername" => $paymentReceiptForm->username,
                "paymentReceiptForm" => $paymentReceiptForm,
                "billingForms" => $billingForms,
                "paymentMethods" => $paymentMethods
            ]
        );
    }
}
