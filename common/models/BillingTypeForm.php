<?php

namespace common\models;

class BillingTypeForm extends \yii\base\Model
{
    public $name;
    public $billing_category_id;
    public $dasgs_administered;
    public $dtve_administered;
    public $dte_administered;
    public $dne_administered;

    public function rules()
    {
        return [
            [['billing_category_id', 'name'], 'required'],
            [
                [
                    'billing_category_id',
                    'dasgs_administered',
                    'dtve_administered',
                    'dte_administered',
                    'dne_administered'
                ],
                'integer'
            ],
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
            'name' => 'Name',
            'billing_category_id' => 'Billing Category',
            'dasgs_administered' => 'Include in DASGS Catalog',
            'dtve_administered' => 'Include in DTVE Catalog',
            'dte_administered' => 'Include in DTE Catalog',
            'dne_administered' => 'Include in DNE Catalog'
        ];
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


    public function generateBillingTypeModel()
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
}
