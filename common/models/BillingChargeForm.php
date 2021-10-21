<?php

namespace common\models;

class BillingChargeForm extends \yii\base\Model
{
    public $billing_type_id;
    public $academic_offering_id;
    public $cost;
    public $payable_on_enrollment;

    public function rules()
    {
        return [
            [
                [
                    'billing_type_id',
                    'academic_offering_id',
                    'payable_on_enrollment'
                ],
                'integer'
            ],
            [['cost'], 'number'],
            [
                ['billing_type_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => BillingType::class,
                'targetAttribute' => ['billing_type_id' => 'id']
            ],
            [
                ['academic_offering_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => AcademicOffering::class,
                'targetAttribute' => ['academic_offering_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'billing_type_id' => 'Type',
            'academic_offering_id' => 'Programme',
            'cost' => 'Cost',
            'payable_on_enrollment' => 'Payable On Enrollment',
        ];
    }


    public function hasDuplicateRecord($applicationPeriodId)
    {
        if ($this->academic_offering_id == true) {
            $target =
                BillingCharge::find()
                ->where([
                    "application_period_id" => $applicationPeriodId,
                    "billing_type_id" => $this->billing_type_id,
                    "academic_offering_id" => $this->academic_offering_id,
                    "is_active" => 1,
                    "is_deleted" => 0
                ])
                ->all();
        } else {
            $target =
                BillingCharge::find()
                ->where([
                    "application_period_id" => $applicationPeriodId,
                    "billing_type_id" => $this->billing_type_id,
                    "is_active" => 1,
                    "is_deleted" => 0
                ])
                ->all();
        }

        if ($target == true) {
            return true;
        }
        return false;
    }


    public function isValid()
    {
        if (
            $this->billing_type_id == true
            && $this->cost == true
            && $this->payable_on_enrollment
        ) {
            return true;
        } else {
            return false;
        }
    }


    public function generateBillingChargeModel(
        $applicationPeriodId,
        $userId
    ) {
        $billingCharge = new BillingCharge();
        $billingCharge->application_period_id = $applicationPeriodId;
        if ($this->academic_offering_id == true) {
            $billingCharge->academic_offering_id =
                intval($this->academic_offering_id);
        } else {
            $billingCharge->academic_offering_id = null;
        }

        $billingCharge->billing_type_id = intval($this->billing_type_id);
        $billingCharge->cost = $this->cost;
        $billingCharge->modifier_id = $userId;
        $billingCharge->payable_on_enrollment = $this->payable_on_enrollment;
        $billingCharge->is_active = 1;
        $billingCharge->is_deleted = 0;
        return $billingCharge->save();
    }
}
