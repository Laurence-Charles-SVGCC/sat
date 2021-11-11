<?php

namespace common\models;

class ProgrammeFeeForm extends \yii\base\Model
{
    public $billing_type_id;
    public $cost;
    public $payable_on_enrollment;

    public function rules()
    {
        return [
            [['billing_type_id', 'cost', 'payable_on_enrollment'], 'required'],
            [["cost"], "number"],
            [['billing_type_id', 'payable_on_enrollment'], 'integer'],
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
            'billing_type_id' => 'Fee',
            'payable_on_enrollment' => 'Payable On Enrollment',
        ];
    }


    public function generateBillingCharge(
        $applicationPeriodId,
        $academicOfferingId,
        $userId
    ) {
        $charge = new BillingCharge();
        $charge->billing_type_id = $this->billing_type_id;
        $charge->payable_on_enrollment = $this->payable_on_enrollment;
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
