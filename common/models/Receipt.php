<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "receipt".
 *
 * @property integer $id
 * @property integer $payment_method_id
 * @property integer $customer_id
 * @property integer $student_registration_id
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $username
 * @property string $full_name
 * @property string $receipt_number
 * @property string $email
 * @property string $notes
 * @property integer $publish_count
 * @property integer $auto_publish
 * @property string $date_paid
 * @property string $timestamp
 * @property integer $is_active
 * @property integer $is_deleted
 * @property string $cheque_number
 *
 * @property Billing[] $billings
 * @property User $createdBy
 * @property User $modifiedBy
 * @property User $customer
 * @property PaymentMethod $paymentMethod
 * @property StudentRegistration $studentRegistration
 */
class Receipt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'receipt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_method_id', 'customer_id', 'created_by', 'username', 'full_name', 'receipt_number', 'email', 'date_paid', 'timestamp'], 'required'],
            [['payment_method_id', 'customer_id', 'student_registration_id', 'created_by', 'modified_by', 'publish_count', 'auto_publish', 'is_active', 'is_deleted'], 'integer'],
            [['notes'], 'string'],
            [['date_paid', 'timestamp'], 'safe'],
            [['username', 'full_name', 'receipt_number', 'email', 'cheque_number'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payment_method_id' => 'Payment Method ID',
            'customer_id' => 'Customer ID',
            'student_registration_id' => 'Student Registration ID',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'username' => 'Username',
            'full_name' => 'Full Name',
            'receipt_number' => 'Receipt Number',
            'email' => 'Email',
            'notes' => 'Notes',
            'publish_count' => 'Publish Count',
            'auto_publish' => 'Auto Publish',
            'date_paid' => 'Date Paid',
            'timestamp' => 'Timestamp',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'cheque_number' => 'Cheque Number',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillings()
    {
        return $this->hasMany(Billing::class, ['receipt_id' => 'id']);
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
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['personid' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::class, ['paymentmethodid' => 'payment_method_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentRegistration()
    {
        return $this->hasOne(StudentRegistration::class, ['studentregistrationid' => 'student_registration_id']);
    }
}
