<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "billing_charge".
 *
 * @property int $id
 * @property int $billing_type_id
 * @property int $application_period_id
 * @property int $academic_offering_id
 * @property int $modifier_id
 * @property string $cost
 * @property int $payable_on_enrollment
 * @property int $is_active
 * @property int $is_deleted
 *
 * @property Billing[] $billings
 * @property AcademicOffering $academicOffering
 * @property ApplicationPeriod $applicationPeriod
 * @property BillingType $billingType
 * @property User $modifier
 */
class BillingCharge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'billing_charge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['billing_type_id', 'application_period_id', 'modifier_id', 'cost'], 'required'],
            [['billing_type_id', 'application_period_id', 'academic_offering_id', 'modifier_id', 'payable_on_enrollment', 'is_active', 'is_deleted'], 'integer'],
            [['cost'], 'number'],
            [['academic_offering_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicOffering::class, 'targetAttribute' => ['academic_offering_id' => 'academicofferingid']],
            [['application_period_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationPeriod::class, 'targetAttribute' => ['application_period_id' => 'applicationperiodid']],
            [['billing_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => BillingType::class, 'targetAttribute' => ['billing_type_id' => 'id']],
            [['modifier_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['modifier_id' => 'personid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'billing_type_id' => 'Billing Type ID',
            'application_period_id' => 'Application Period ID',
            'academic_offering_id' => 'Academic Offering ID',
            'modifier_id' => 'Modifier ID',
            'cost' => 'Cost',
            'payable_on_enrollment' => 'Payable On Enrollment',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillings()
    {
        return $this->hasMany(Billing::class, ['billing_charge_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicOffering()
    {
        return $this->hasOne(AcademicOffering::class, ['academicofferingid' => 'academic_offering_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationPeriod()
    {
        return $this->hasOne(ApplicationPeriod::class, ['applicationperiodid' => 'application_period_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillingType()
    {
        return $this->hasOne(BillingType::class, ['id' => 'billing_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifier()
    {
        return $this->hasOne(User::class, ['personid' => 'modifier_id']);
    }
}
