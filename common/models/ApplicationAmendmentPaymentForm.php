<?php

namespace common\models;

use yii\base\Model;

class ApplicationAmendmentPaymentForm extends Model
{
    public $username;
    public $fullName;
    public $amount;
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
                    "amount",
                    "paymentMethodId",
                    "datePaid",
                    "autoPublish",
                    "customerId",
                    "applicationPeriodId",
                    "billingChargeId"
                ],
                "required"
            ],
            [["amount"], "number"],
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
            "amount" => "Amount",
            "paymentMethodId" => "Payment Method",
            "datePaid" => "Date of payment",
            "autoPublish" => "Email invoice to applicant"
        ];
    }


    private function generateReceipt(
        $customerId,
        $staffID
    ) {
        $receipt = new Receipt();
        $receipt->payment_method_id = $this->paymentMethodId;
        $receipt->customer_id = $customerId;
        $receipt->student_registration_id = null;
        $receipt->created_by = $staffID;
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

    private function billingEligibleForWaiver($paymentMethodId)
    {
        $billingCharge =
            BillingChargeModel::getBillingChargeById($this->billingChargeId);

        $billingType =
            BillingChargeModel::getBillingChargeFeeName($billingCharge);

        $paymentMethod =
            PaymentMethodModel::getPaymentMethodByID($paymentMethodId);

        if (
            $paymentMethod->name == "Vaccination Waiver"
            && in_array(
                $billingType,
                ["Application Submission", "Application Amendment"]
            )
        ) {
            return true;
        }
        return false;
    }

    private function determineAmountPayable($paymentMethodId)
    {
        if ($this->billingEligibleForWaiver($paymentMethodId) == true) {
            return 0;
        } else {
            return $this->amount;
        }
    }


    private function generateBilling(
        $receipt,
        $cost,
        $customerId,
        $staffID
    ) {
        $billing = new Billing();
        $billing->receipt_id = $receipt->id;
        $billing->billing_charge_id = $this->billingChargeId;
        $billing->customer_id = $customerId;
        $billing->application_period_id = $this->applicationPeriodId;
        $billing->created_by = $staffID;
        $billing->cost = $cost;

        $billing->amount_paid =
            $this->determineAmountPayable($receipt->payment_method_id);

        return $billing;
    }


    public function processPaymentRequest(
        $customerId,
        $staffID,
        $applicationAmendmentCost,
        $controller
    ) {
        $receipt = $this->generateReceipt($customerId, $staffID);

        if ($receipt == null) {
            return new ErrorObject("Error ocurred generating receipt model");
        } else {
            $amendmentBilling =
                $this->generateBilling(
                    $receipt,
                    $applicationAmendmentCost,
                    $customerId,
                    $staffID
                );

            if ($amendmentBilling->save() == false) {
                return new ErrorObject(
                    "Error ocurred generating application amendment payment billing."
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


    public function processAdditionalPaymentRequest(
        $staffID,
        $controller
    ) {
        $receipt = new Receipt();
        $receipt->payment_method_id = $this->paymentMethodId;
        $receipt->customer_id = $this->customerId;
        $receipt->academic_offering_id = null;
        $receipt->student_registration_id = null;
        $receipt->application_period_id = $this->applicationPeriodId;
        $receipt->created_by = $staffID;
        $receipt->username = $this->username;
        $receipt->full_name = $this->fullName;
        $receipt->receipt_number = $this->receiptNumber;
        $receipt->date_paid = $this->datePaid;
        $receipt->email = EmailModel::getEmailByPersonid($this->customerId)->email;
        $receipt->auto_publish = $this->autoPublish;
        $receipt->timestamp = date("Y-m-d H:i:s");
        if ($receipt->save() == true) {
            $amendmentBilling = new Billing();
            $amendmentBilling->receipt_id = $receipt->id;
            $amendmentBilling->billing_charge_id = $this->billingChargeId;
            $amendmentBilling->customer_id = $this->customerId;
            $amendmentBilling->application_period_id = $this->applicationPeriodId;
            $amendmentBilling->created_by = $staffID;
            $billingCharge = BillingChargeModel::getBillingChargeById($this->billingChargeId);
            $amendmentBilling->cost = $billingCharge->cost;
            $amendmentBilling->amount_paid = $this->amount;

            if ($amendmentBilling->save() == true) {
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
        return null;
    }
}
