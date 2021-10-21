<?php

namespace common\models;

use yii\base\Model;

class BatchStudentFeePaymentBillingForm extends Model
{
    public $billingChargeId;
    public $fee;
    public $balance;
    public $amountPaid;
    public $isActive;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [["amountPaid", "balance"], "number"],
            [["fee"], "string"],
            [['billingChargeId', 'isActive',], "integer"],
            [
                ['billing_charge_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => BillingCharge::class,
                'targetAttribute' => ['billingChargeId' => 'id']
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            "fee" => "Fee",
            'billing_charge_id' => 'Billing Charge ID',
            "balance" => "Balance",
            "amountPaid" => "Amount",
            "isActive" => "Save"
        ];
    }


    public function fillModel($customerId, $billingCharge)
    {
        $this->billingChargeId = $billingCharge->id;
        $this->fee = BillingChargeModel::getBillingChargeFeeName($billingCharge);

        $balance =
            BillingModel::calculateOutstandingAmountOnBillingCharge(
                $billingCharge->id,
                $customerId
            );

        $this->balance = $balance;
        $this->amountPaid = $balance;
        $this->isActive = 0;
    }


    public function validateModel()
    {
        if (
            $this->isActive == true
            && $this->amountPaid > 0
            && $this->amountPaid <= $this->balance
        ) {
            return true;
        }
        return false;
    }


    public function isSelected()
    {
        return $this->isActive;
    }
}
