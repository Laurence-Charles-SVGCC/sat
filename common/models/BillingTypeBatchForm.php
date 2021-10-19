<?php

namespace common\models;

class BillingTypeBatchForm extends \yii\base\Model
{
    public $billing_category_id;
    public $dasgs_administered;
    public $dtve_administered;
    public $dte_administered;
    public $dne_administered;
    public $name;
    public $is_active;

    public function rules()
    {
        return [
            [
                [
                    'is_active',
                    'billing_category_id',
                    'dasgs_administered',
                    'dtve_administered',
                    'dte_administered',
                    'dne_administered'
                ],
                'integer'
            ],
            [['name'], 'string', 'max' => 255],
            [
                ['billing_category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => BillingCategory::class,
                'targetAttribute' => ['billing_category_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'billing_category_id' => 'Billing Category',
            'name' => 'Name',
            'is_active' => 'Save',
            'dasgs_administered' => 'DASGS',
            'dtve_administered' => 'DTVE',
            'dte_administered' => 'DTE',
            'dne_administered' => 'DNE'
        ];
    }


    public static function generateBlankForms($count)
    {
        $billingTypes = array();
        for ($i = 0; $i < $count; $i++) {
            $billingTypes[] = new BillingTypeBatchForm();
        }

        return $billingTypes;
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


    public function generateBillingTypeModels()
    {
        $billingTypes = array();

        if ($this->dasgs_administered == true) {
            $dasgsBillingType = $this->createBillingType(4);
            if ($dasgsBillingType == true) {
                $billingTypes[] = $dasgsBillingType;
            }
        }
        if ($this->dtve_administered == true) {
            $dtveBillingType = $this->createBillingType(5);
            if ($dtveBillingType == true) {
                $billingTypes[] = $dtveBillingType;
            }
        }
        if ($this->dte_administered == true) {
            $dteBillingType = $this->createBillingType(6);
            if ($dteBillingType == true) {
                $billingTypes[] = $dteBillingType;
            }
        }
        if ($this->dne_administered == true) {
            $dneBillingType = $this->createBillingType(7);
            if ($dneBillingType == true) {
                $billingTypes[] = $dneBillingType;
            }
        }
        return $billingTypes;
    }


    public function selectedButFailsValidation()
    {
        if (
            ($this->is_active == true
                && ($this->billing_category_id == false || $this->name == false))
            ||
            ($this->is_active == true
                && $this->dasgs_administered == false
                && $this->dtve_administered == false
                && $this->dte_administered == false
                && $this->dne_administered == false)
        ) {
            return true;
        }
        return false;
    }


    public function selectedAndPassesValidation()
    {
        if (
            $this->is_active == true
            && $this->billing_category_id == true
            && $this->name == true
            && ($this->dasgs_administered == true
                || $this->dtve_administered == true
                || $this->dte_administered == true
                || $this->dne_administered == true)
        ) {
            return true;
        }
        return false;
    }
}
