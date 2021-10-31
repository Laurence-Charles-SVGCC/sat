<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use common\models\AcademicOfferingModel;
use common\models\ApplicantModel;
use common\models\BatchStudentFeePaymentForm;
use common\models\BillingChargeModel;
use common\models\BillingTypeModel;
use common\models\ErrorObject;
use common\models\PaymentMethodModel;
use common\models\ReceiptModel;
use common\models\SingleStudentFeePaymentForm;
use common\models\StudentModel;
use common\models\StudentRegistrationModel;
use common\models\UserModel;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

class StudentPaymentsController extends \yii\web\Controller
{
    public function actionScheduledFeeReport($username, $studentRegistrationId)
    {
        $user = Yii::$app->user->identity;

        $studentRegistration =
            StudentRegistrationModel::getStudentRegistrationByID(
                $studentRegistrationId
            );

        $customer = UserModel::getUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);
        $applicant = ApplicantModel::getApplicantByPersonid($customer->personid);

        $applicationPeriodId =
            ApplicantModel::getApplicantApplicationPeriodID($applicant);

        $batchStudentFeePaymentForm = new BatchStudentFeePaymentForm();

        $batchStudentFeePaymentForm->fillModel(
            $customer,
            $user->personid,
            $userFullname,
            $applicationPeriodId
        );

        $batchStudentFeePaymentBillingForms =
            $batchStudentFeePaymentForm->generateDefaultBillingFormsForStudent(
                $studentRegistration
            );

        if (empty($batchStudentFeePaymentBillingForms)) {
            $outstandingFeesExist = false;
        } else {
            $outstandingFeesExist = true;
        }

        $paymentMethods =
            ArrayHelper::map(
                PaymentMethodModel::getActivePaymentMethods(),
                "paymentmethodid",
                "name"
            );

        $programme =
            AcademicOfferingModel::getProgrammeNameByStudentRegistrationId(
                $studentRegistrationId
            );

        $feesDataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    StudentModel::prepareFeePaymentReportByRegistration(
                        $studentRegistration
                    ),
                    "pagination" => ["pageSize" => 100],
                    "sort" => [
                        "defaultOrder" => ["billingTypeName" => SORT_ASC],
                        "attributes" => ["billingTypeName", "programme"]
                    ]
                ]
            );

        if ($postData = Yii::$app->request->post()) {
            if (
                $batchStudentFeePaymentForm->load($postData) == true
                && Model::loadMultiple(
                    $batchStudentFeePaymentBillingForms,
                    $postData
                )
                == true
            ) {
                $receipt =
                    $batchStudentFeePaymentForm->processEnrolledStudentPaymentRequest(
                        $batchStudentFeePaymentBillingForms,
                        $studentRegistrationId
                    );

                if ($receipt instanceof ErrorObject) {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        $receipt->getMessage()
                    );
                } else {
                    return $this->redirect([
                        "preview-receipt",
                        "receiptId" => $receipt->id,
                        "studentRegistrationId" => $studentRegistrationId
                    ]);
                }
            } else {
                Yii::$app->getSession()->setFlash(
                    "warning",
                    "Error occurred load payment"
                );
            }
        }

        return $this->render(
            "scheduled-fee-report",
            [
                "userFullname" => $userFullname,
                "username" => $customer->username,
                "dataProvider" => $feesDataProvider,
                "batchStudentFeePaymentForm" => $batchStudentFeePaymentForm,
                "paymentMethods" => $paymentMethods,
                "programme" => $programme,
                "studentRegistrationId" => $studentRegistrationId,

                "batchStudentFeePaymentBillingForms" =>
                $batchStudentFeePaymentBillingForms,

                "outstandingFeesExist" => $outstandingFeesExist,
            ]
        );
    }


    public function actionMakeFeePayment(
        $username,
        $billingChargeId,
        $studentRegistrationId
    ) {
        $user = Yii::$app->user->identity;

        $customer = UserModel::getUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);

        $billingCharge =
            BillingChargeModel::getBillingChargeById($billingChargeId);

        $billingType =
            BillingTypeModel::getBillingTypeByID(
                $billingCharge->billing_type_id
            );

        $model = new SingleStudentFeePaymentForm();
        $model->fillModel($customer, $userFullname, $billingCharge);

        $paymentMethods =
            ArrayHelper::map(
                PaymentMethodModel::getActivePaymentMethods(),
                "paymentmethodid",
                "name"
            );

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true) {
                $receipt =
                    $model->processIndividualBillingPaymentRequest(
                        $user->personid,
                        $this
                    );

                if ($receipt instanceof ErrorObject) {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        $receipt->getMessage()
                    );
                } else {
                    return $this->redirect([
                        "preview-receipt",
                        "receiptId" => $receipt->id,
                        "studentRegistrationId" => $studentRegistrationId
                    ]);
                }
            } else {
                Yii::$app->getSession()->setFlash(
                    "warning",
                    "Error occurred loading payment"
                );
            }
        }

        return $this->render(
            "make-fee-payment",
            [
                "model" => $model,
                "userFullname" => $userFullname,
                "username" => $customer->username,
                "fee" => $billingType->name,
                "paymentMethods" => $paymentMethods,
                "studentRegistrationId" => $studentRegistrationId
            ]
        );
    }


    public function actionPreviewReceipt($receiptId, $studentRegistrationId)
    {
        $receipt = ReceiptModel::getReceiptById($receiptId);
        $billings = ReceiptModel::getBillings($receipt);
        $customer = UserModel::getUserById($receipt->customer_id);
        $applicantName = UserModel::getUserFullname($customer);
        $applicantId = $customer->username;
        $total = number_format(ReceiptModel::calculateReceiptTotal($receipt), 2);

        return $this->render(
            "preview-receipt",
            [
                "receipt" => $receipt,
                "billings" => $billings,
                "total" => $total,
                "applicantName" => $applicantName,
                "applicantId" => $applicantId,
                "studentRegistrationId" => $studentRegistrationId
            ]
        );
    }


    public function actionRedoReceipt($receiptId, $studentRegistrationId)
    {
        $user = Yii::$app->user->identity;
        $receipt = ReceiptModel::getReceiptById($receiptId);
        $billings = ReceiptModel::getBillings($receipt);
        $customer = UserModel::getUserById($receipt->customer_id);
        $applicantId = $customer->username;

        if (ReceiptModel::deleteReceipt(
            $receipt,
            $billings,
            $user->personid
        ) == true) {
            return $this->redirect([
                "scheduled-fee-report",
                "username" => $applicantId,
                "studentRegistrationId" => $studentRegistrationId
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


    public function actionApproveAndPublishReceipt($receiptId)
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
            "payments/view-receipt",
            "id" => $receiptId,
            "username" => UserModel::getUserById($receipt->customer_id)->username
        ]);
    }
}
