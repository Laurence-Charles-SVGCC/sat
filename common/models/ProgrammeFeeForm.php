<?php

namespace common\models;

class ProgrammeFeeForm extends \yii\base\Model
{
    public $billing_type_id;
    public $cost;

    public function rules()
    {
        return [
            [['billing_type_id', 'cost'], 'required'],
            [["cost"], "number"],
            [['billing_type_id'], 'integer'],
            [
                ['billing_type_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => BillingType::class,
                'targetAttribute' => ['billing_type_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cost' => 'Cost',
            'billing_type_id' => 'Fee'
        ];
    }


    public function generateBillingCharge(
        $applicationPeriodId,
        $academicOfferingId,
        $userId
    ) {
        $charge = new BillingCharge();
        $charge->billing_type_id = $this->billing_type_id;
        $charge->application_period_id = $applicationPeriodId;
        $charge->academic_offering_id = $academicOfferingId;
        $charge->modifier_id = $userId;
        $charge->cost = $this->cost;
        return $charge->save();
    }


    public function createBillingType($divisionId)
    {
        $billingType = new BillingType();
        $billingType->name = $this->name;
        $billingType->billing_category_id = $this->billing_category_id;
        $billingType->division_id = $divisionId;
        if ($billingType->save() == true) {
            return $billingType;
        }
        return null;
    }
}
