<?php

namespace common\models;

use yii\base\Model;

class PaymentReceiptForm extends Model
{
    public $receiptId;
    public $username;
    public $fullName;
    public $paymentMethodId;
    public $datePaid;
    public $chequeNumber;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ["username", "fullName", "paymentMethodId", "datePaid"],
                "required"
            ],
            [["username", "fullName", "chequeNumber"], "string"],
            [["paymentMethodId", "receiptId"], "integer"],
            [["datePaid"], "safe"]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            "receiptId" => "Receipt ID",
            "username" => "ApplicantID",
            "fullName" => "Full Name",
            "paymentMethodId" => "Payment Method",
            "datePaid" => "Date of payment",
            "chequeNumber" => "Cheque Number"
        ];
    }


    public function loadReceipt($receipt)
    {
        $this->receiptId = $receipt->id;
        if ($receipt->student_registration_id == null) {
            $this->username = $receipt->username;
        } else {
            $customer = UserModel::getUserById($receipt->customer_id);
            $this->username = $customer->username;
        }

        $this->fullName = $receipt->full_name;
        $this->paymentMethodId = $receipt->payment_method_id;
        $this->datePaid =
            date_format(new \DateTime($receipt->date_paid), "Y-m-d");
        $this->chequeNumber = $receipt->cheque_number;
        return $this;
    }


    public function generateExistingBillingForms($billings)
    {
        $records = array();
        if (!empty($billings)) {
            foreach ($billings as $billing) {
                $billingCharge = $billing->getBillingCharge()->one();
                $billingChargeType = $billingCharge->getBillingType()->one();
                $model = new BatchStudentFeePaymentBillingForm();
                $model->billingChargeId = $billingCharge->id;
                $model->fee = $billingChargeType->name;
                $model->balance = $billing->cost;
                $model->amountPaid = $billing->amount_paid;
                $model->isActive = 1;
                $records[] = $model;
            }
        }
        return $records;
    }


    private function extractBillingChargeIdsFromBillings($billings)
    {
        $ids = array();
        if (!empty($billings)) {
            foreach ($billings as $billing) {
                $ids[] = $billing->billing_charge_id;
            }
        }
        return $ids;
    }


    public function generateNewBillingForms($receipt, $existingBillings)
    {
        $records = array();

        $existingBillingChargeIds =
            $this->extractBillingChargeIdsFromBillings($existingBillings);

        $application = ReceiptModel::getAssociatedApplication($receipt);

        if ($application == true) {
            $billingCharges =
                BillingChargeModel::getFirstAndSecondYearBillingChargesForApplication(
                    $application
                );

            foreach ($billingCharges as $billingCharge) {
                if (
                    in_array($billingCharge->id, $existingBillingChargeIds)
                    == false
                ) {
                    $record = new BatchStudentFeePaymentBillingForm();
                    $record->fillModel($receipt->customer_id, $billingCharge);
                    $records[] = $record;
                }
            }
        }
        return $records;
    }


    public function generateBatchStudentFeePaymentBillingForms(
        $receipt,
        $billings
    ) {
        $existingBillingForms = $this->generateExistingBillingForms($billings);

        $outstandingBillingForms =
            $this->generateNewBillingForms($receipt, $billings);

        return array_merge($existingBillingForms, $outstandingBillingForms);
    }
}
