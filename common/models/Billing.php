<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "billing".
 *
 * @property int $id
 * @property int $receipt_id
 * @property int $billing_charge_id
 * @property int $customer_id
 * @property int $student_registration_id
 * @property int $academic_offering_id
 * @property int $application_period_id
 * @property int $created_by
 * @property int $modified_by
 * @property string $cost
 * @property string $amount_paid
 * @property int $is_active
 * @property int $is_deleted
 *
 * @property Receipt $receipt
 * @property BillingCharge $billingCharge
 * @property Person $customer
 * @property StudentRegistration $studentRegistration
 * @property AcademicOffering $academicOffering
 * @property Person $createdBy
 * @property Person $modifiedBy
 * @property ApplicationPeriod $applicationPeriod
 */
class Billing extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'billing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receipt_id', 'billing_charge_id', 'customer_id', 'application_period_id', 'created_by', 'cost', 'amount_paid'], 'required'],
            [['receipt_id', 'billing_charge_id', 'customer_id', 'student_registration_id', 'academic_offering_id', 'application_period_id', 'created_by', 'modified_by', 'is_active', 'is_deleted'], 'integer'],
            [['cost', 'amount_paid'], 'number'],
            [['receipt_id'], 'exist', 'skipOnError' => true, 'targetClass' => Receipt::class, 'targetAttribute' => ['receipt_id' => 'id']],
            [['billing_charge_id'], 'exist', 'skipOnError' => true, 'targetClass' => BillingCharge::class, 'targetAttribute' => ['billing_charge_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'personid']],
            [['student_registration_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentRegistration::class, 'targetAttribute' => ['student_registration_id' => 'studentregistrationid']],
            [['academic_offering_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicOffering::class, 'targetAttribute' => ['academic_offering_id' => 'academicofferingid']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'personid']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['modified_by' => 'personid']],
            [['application_period_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationPeriod::class, 'targetAttribute' => ['application_period_id' => 'applicationperiodid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'receipt_id' => 'Receipt ID',
            'billing_charge_id' => 'Billing Charge ID',
            'customer_id' => 'Customer ID',
            'student_registration_id' => 'Student Registration ID',
            'academic_offering_id' => 'Academic Offering ID',
            'application_period_id' => 'Application Period ID',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'cost' => 'Cost',
            'amount_paid' => 'Amount Paid',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceipt()
    {
        return $this->hasOne(Receipt::class, ['id' => 'receipt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillingCharge()
    {
        return $this->hasOne(BillingCharge::class, ['id' => 'billing_charge_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['personid' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentRegistration()
    {
        return $this->hasOne(StudentRegistration::class, ['studentregistrationid' => 'student_registration_id']);
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
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['personid' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifiedBy()
    {
        return $this->hasOne(User::class, ['personid' => 'modified_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationPeriod()
    {
        return $this->hasOne(ApplicationPeriod::class, ['applicationperiodid' => 'application_period_id']);
    }
}
