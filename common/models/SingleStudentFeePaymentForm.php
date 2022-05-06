<?php

namespace common\models;

use yii\base\Model;

class SingleStudentFeePaymentForm extends Model
{
    public $username;
    public $fullName;
    public $balance;
    public $amountPaid;
    public $paymentMethodId;
    public $datePaid;
    public $customerId;
    public $applicationPeriodId;
    public $billingChargeId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    "username",
                    "fullName",
                    "balance",
                    "amountPaid",
                    "paymentMethodId",
                    "includeAmendmentFee",
                    "datePaid",
                    "customerId",
                    "applicationPeriodId",
                    "billingChargeId"
                ],
                "required"
            ],
            [["amountPaid", "balance"], "number"],
            [["username", "fullName"], "string"],
            [
                [
                    "paymentMethodId",
                    "customerId",
                    "applicationPeriodId",
                    "billingChargeId"
                ],
                "integer"
            ],
            [["datePaid"], "safe"]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            "username" => "ApplicantID",
            "fullName" => "Full Name",
            "balance" => "Balance",
            "amountPaid" => "AmountPaid",
            "paymentMethodId" => "Payment Method",
            "datePaid" => "Date of payment"
        ];
    }


    public function fillModel($customer, $userFullname, $billingCharge)
    {
        $this->username = $customer->username;
        $this->fullName = $userFullname;
        $balance =
            BillingModel::calculateOutstandingAmountOnBillingCharge(
                $billingCharge->id,
                $customer->personid
            );
        $this->amountPaid = $balance;
        $this->balance = $balance;
        $this->customerId = $customer->personid;
        $this->applicationPeriodId = $billingCharge->application_period_id;
        $this->billingChargeId = $billingCharge->id;
    }

    private function padToFourCharacterString($numAsString)
    {
        $length = strlen($numAsString);
        if ($length == 1) {
            return "000{$numAsString}";
        } elseif ($length == 2) {
            return "00{$numAsString}";
        } elseif ($length == 3) {
            return "0{$numAsString}";
        } elseif ($length == 4) {
            return $numAsString;
        }
    }

    private function getLastReceiptId()
    {
        $receipts = Receipt::find()->orderBy("id DESC")->all();
        if (!empty($receipts)) {
            $id = $receipts[0]->id;
            $idAsFourCharacterString = strval($id % 10000);
            return $this->padToFourCharacterString($idAsFourCharacterString);
        } else {
            return "0000";
        }
    }

    private function generateReceiptNumber()
    {
        $unformattedDate = date('Y-m-d');
        $yearSegment = substr($unformattedDate, 0, 4);
        $monthSegment = substr($unformattedDate, 5, 2);
        $daySegment = substr($unformattedDate, 8, 2);
        $idSegment = $this->getLastReceiptId();

        return "{$yearSegment}{$monthSegment}{$daySegment}{$idSegment}";
    }


    private function generateReceipt(
        $customerId,
        $staffId
    ) {
        $receipt = new Receipt();
        $receipt->payment_method_id = $this->paymentMethodId;
        $receipt->customer_id = $customerId;
        $receipt->student_registration_id = null;
        $receipt->created_by = $staffId;
        $receipt->username = $this->username;
        $receipt->full_name = $this->fullName;
        $receipt->receipt_number = $this->generateReceiptNumber();
        $receipt->email = EmailModel::getEmailByPersonid($customerId)->email;
        $receipt->date_paid = $this->datePaid;
        $receipt->timestamp = date("Y-m-d H:i:s");
        if ($receipt->save() == true) {
            return $receipt;
        } else {
            return null;
        }
    }


    private function generateBilling(
        $receipt,
        $customerId,
        $staffId
    ) {
        $billing = new Billing();
        $billing->receipt_id = $receipt->id;
        $billing->billing_charge_id = $this->billingChargeId;
        $billing->customer_id = $customerId;
        $billing->application_period_id = $this->applicationPeriodId;
        $billing->created_by = $staffId;
        $billing->cost = $this->balance;
        $billing->amount_paid = $this->amountPaid;
        return $billing;
    }


    public function processIndividualBillingPaymentRequest($staffId)
    {
        $receipt = $this->generateReceipt($this->customerId, $staffId);

        if ($receipt == null) {
            return new ErrorObject("Error ocurred generating receipt model");
        } else {
            $billing =
                $this->generateBilling(
                    $receipt,
                    $this->customerId,
                    $staffId
                );

            if ($billing->save() == false) {
                return new ErrorObject(
                    "Error ocurred generating application submission payment"
                        . " billing."
                );
            } else {
                return $receipt;
            }
        }
    }
}
