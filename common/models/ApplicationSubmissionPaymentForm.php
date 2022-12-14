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
    public $datePaid;
    public $autoPublish;
    public $customerId;
    public $applicationPeriodId;
    public $billingChargeId;
    public $cheque_number;

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
            [["username", "fullName", "cheque_number"], "string"],
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
            "username" => "ApplicantID",
            "fullName" => "Full Name",
            "amount" => "Amount",
            "paymentMethodId" => "Payment Method",
            "includeAmendmentFee" => "Does applicant wish to pay amendment fee as well?",
            "datePaid" => "Date of payment",
            "autoPublish" => "Email invoice to applicant",
            "cheque_number" => "Cheque Number",
        ];
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
        $receipt->cheque_number = $this->cheque_number;
        $receipt->is_active = 1;
        $receipt->is_deleted = 0;
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
