<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use common\models\ApplicationPeriodModel;
use common\models\BillingModel;
use common\models\ReceiptModel;
use common\models\UserModel;

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
                "receiptTotal" => $receiptTotal
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
}
