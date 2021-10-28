<?php

namespace common\models;

use yii\base\Model;

class ApplicationSubmissionPaymentForm extends Model
{
    public $username;
    public $fullName;
    public $amount;
    public $paymentMethodId;
    public $includeAmendmentFee;
    // public $receiptNumber;
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
                    "includeAmendmentFee",
                    "datePaid",
                    "autoPublish",
                    "customerId",
                    "applicationPeriodId",
                    "billingChargeId"
                ],
                "required"
            ],
            [["amount"], "number"],
            [[/*"receiptNumber",*/"username", "fullName"], "string"],
            [
                [
                    "paymentMethodId",
                    "includeAmendmentFee",
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
            //"receiptNumber" => "Receipt Number",
            "username" => "ApplicantID",
            "fullName" => "Full Name",
            "amount" => "Amount",
            "paymentMethodId" => "Payment Method",
            "includeAmendmentFee" => "Does applicant wish to pay amendment fee as well?",
            "datePaid" => "Date of payment",
            "autoPublish" => "Email invoice to applicant"
        ];
    }

    private function generateReceiptNumber()
    {
        return "00000000";
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
        $receipt->receipt_number = $this->generateReceiptNumber();
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


    private function generateCorrespondingAmendmentBilling(
        $receipt,
        $applicationAmendment,
        $customerId,
        $staffID
    ) {
        $billing = new Billing();
        $billing->receipt_id = $receipt->id;
        $billing->billing_charge_id = $applicationAmendment->id;
        $billing->customer_id = $customerId;
        $billing->application_period_id = $this->applicationPeriodId;
        $billing->created_by = $staffID;
        $billing->cost = $applicationAmendment->cost;

        $billing->amount_paid =
            $this->determineAmountPayable($receipt->payment_method_id);

        return $billing;
    }


    private function processSubmissionAndAmendmentPayments(
        $customerId,
        $staffID,
        $applicationSubmissionCost,
        $applicationAmendment,
        $controller
    ) {
        $receipt =
            $this->generateReceipt($customerId, $staffID);

        if ($receipt == null) {
            return new ErrorObject("Error ocurred generating receipt model");
        } else {
            $submissionBilling =
                $this->generateBilling(
                    $receipt,
                    $applicationSubmissionCost,
                    $customerId,
                    $staffID
                );

            $amendmentBilling =
                $this->generateCorrespondingAmendmentBilling(
                    $receipt,
                    $applicationAmendment,
                    $customerId,
                    $staffID
                );

            $submissionBillingFeedback = $submissionBilling->save();
            $amendmentBillingFeedback = $amendmentBilling->save();

            if ($submissionBillingFeedback == false) {
                return new ErrorObject(
                    "Error ocurred generating application submission payment billing."
                );
            } elseif ($amendmentBillingFeedback == false) {
                return new ErrorObject(
                    "Error ocurred generating application amendment payment billing."
                );
            } elseif (
                $submissionBillingFeedback == true
                && $amendmentBillingFeedback == true
            ) {
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


    private function processSubmissionPayment(
        $customerId,
        $staffID,
        $cost,
        $controller
    ) {
        $receipt = $this->generateReceipt($customerId, $staffID);

        if ($receipt == null) {
            return new ErrorObject("Error ocurred generating receipt model");
        } else {
            $submissionBilling =
                $this->generateBilling(
                    $receipt,
                    $cost,
                    $customerId,
                    $staffID
                );

            if ($submissionBilling->save() == false) {
                return new ErrorObject(
                    "Error ocurred generating application submission payment billing."
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


    public function processPaymentRequest(
        $customerId,
        $staffID,
        $applicationSubmissionCost,
        $controller
    ) {
        if ($this->includeAmendmentFee == true) {
            $applicationAmendment =
                BillingChargeModel::getActiveApplicationAmendmentBillingChargeByApplicationPeriodId(
                    $this->applicationPeriodId
                );

            return $this->processSubmissionAndAmendmentPayments(
                $customerId,
                $staffID,
                $applicationSubmissionCost,
                $applicationAmendment,
                $controller
            );
        } else {
            return $this->processSubmissionPayment(
                $customerId,
                $staffID,
                $applicationSubmissionCost,
                $controller
            );
        }
    }
}
