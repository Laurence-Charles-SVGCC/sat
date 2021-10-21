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
    public $receiptNumber;
    public $datePaid;
    public $autoPublish;
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
                    "autoPublish",
                    "customerId",
                    "applicationPeriodId",
                    "billingChargeId"
                ],
                "required"
            ],
            [["amountPaid", "balance"], "number"],
            [["receiptNumber", "username", "fullName"], "string"],
            [
                [
                    "paymentMethodId",
                    "autoPublish",
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
            "receiptNumber" => "Receipt Number",
            "username" => "ApplicantID",
            "fullName" => "Full Name",
            "balance" => "Balance",
            "amountPaid" => "AmountPaid",
            "paymentMethodId" => "Payment Method",
            "datePaid" => "Date of payment",
            "autoPublish" => "Email invoice to applicant"
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
        $this->autoPublish = 1;
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
        $receipt->receipt_number = $this->receiptNumber;
        $receipt->email = EmailModel::getEmailByPersonid($customerId)->email;
        $receipt->date_paid = $this->datePaid;
        $receipt->auto_publish = $this->autoPublish;
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


    public function processIndividualBillingPaymentRequest(
        $staffId,
        $controller
    ) {
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
                $billings = ReceiptModel::getBillings($receipt);
                $customer = UserModel::getUserById($receipt->customer_id);
                $applicantName = UserModel::getUserFullname($customer);
                $applicantId = $customer->username;
                if ($this->autoPublish == true) {
                    ReceiptModel::publishReceipt(
                        $controller,
                        $receipt,
                        $billings,
                        $applicantName,
                        $applicantId
                    );
                }
                return $receipt;
            }
        }
    }
}
