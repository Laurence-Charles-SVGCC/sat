<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "receipt".
 *
 * @property int $id
 * @property int $payment_method_id
 * @property int $customer_id
 * @property int $student_registration_id
 * @property int $created_by
 * @property int $modified_by
 * @property string $username
 * @property string $full_name
 * @property string $receipt_number
 * @property string $email
 * @property string $notes
 * @property int $publish_count
 * @property int $auto_publish
 * @property string $date_paid
 * @property string $timestamp
 * @property int $is_active
 * @property int $is_deleted
 *
 * @property Billing[] $billings
 * @property Person $createdBy
 * @property Person $modifiedBy
 * @property Person $customer
 * @property PaymentMethod $paymentMethod
 */
class Receipt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_method_id', 'customer_id', 'created_by', 'username', 'full_name', 'receipt_number', 'email', 'date_paid', 'timestamp'], 'required'],
            [['payment_method_id', 'customer_id', 'student_registration_id', 'created_by', 'modified_by', 'publish_count', 'auto_publish', 'is_active', 'is_deleted'], 'integer'],
            [['notes'], 'string'],
            [['date_paid', 'timestamp'], 'safe'],
            [['username', 'full_name', 'receipt_number', 'email'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'personid']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['modified_by' => 'personid']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'personid']],
            [['student_registration_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentRegistration::class, 'targetAttribute' => ['student_registration_id' => 'studentregistrationid']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::class, 'targetAttribute' => ['payment_method_id' => 'paymentmethodid']],
        ];
    }

    /**
     * {@inheritdoc}
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
            'publish_count' => 'Publsh Count',
            'auto_publish' => 'Auto Publish',
            'date_paid' => 'Date Paid',
            'timestamp' => 'Timestamp',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
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
        return $this->hasOne(getStudentRegistration::class, ['studentregistrationid' => 'student_registration_id']);
    }
}
