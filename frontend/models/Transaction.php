<?php

namespace frontend\models;

use Yii;

use common\models\User;

/**
 * This is the model class for table "transaction".
 *
 * @property string $transactionid
 * @property string $transactiontypeid
 * @property string $personid
 * @property string $transactionpurposeid
 * @property string $recepientid
 * @property string $semesterid
 * @property string $paymentmethodid
 * @property string $transactionsummaryid
 * @property string $verifyingofficerid
 * @property string $paydate
 * @property string $paymentamount
 * @property string $totaldue
 * @property boolean $isverified
 * @property string $comments
 * @property string $receiptnumber
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property TransactionType $transactiontype
 * @property Person $person
 * @property TransactionPurpose $transactionpurpose
 * @property Person $recepient
 * @property Semester $semester
 * @property PaymentMethod $paymentmethod
 * @property TransactionSummary $transactionsummary
 * @property Person $verifyingofficer
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['transactiontypeid', 'personid', 'transactionpurposeid', 'recepientid', 'semesterid', 'paymentmethodid', 'transactionsummaryid', 'paydate', 'paymentamount', 'totaldue', 'receiptnumber'], 'required'],
            [['transactionpurposeid'], 'required'],
            [['transactiontypeid', 'personid', 'transactionpurposeid', 'recepientid', 'semesterid', 'paymentmethodid', 'transactionsummaryid', 'verifyingofficerid'], 'integer'],
            [['paydate'], 'safe'],
            [['paymentamount', 'totaldue'], 'number'],
            [['isverified', 'isactive', 'isdeleted'], 'boolean'],
            [['comments'], 'string'],
            [['receiptnumber'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transactionid' => 'Transactionid',
            'transactiontypeid' => 'Transaction Type',
            'personid' => 'Personid',
            'transactionpurposeid' => 'Transaction Purpose',
            'recepientid' => 'Recepientid',
            'semesterid' => 'Semester',
            'paymentmethodid' => 'Payment Method',
            'transactionsummaryid' => 'Transaction Summary',
            'verifyingofficerid' => 'Verifyingofficerid',
            'paydate' => 'Date of Payment',
            'paymentamount' => 'Amount Paid',
            'totaldue' => 'Total Due',
            'isverified' => 'Isverified',
            'comments' => 'Comments',
            'receiptnumber' => 'Receiptnumber',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactiontype()
    {
        return $this->hasOne(TransactionType::className(), ['transactiontypeid' => 'transactiontypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionpurpose()
    {
        return $this->hasOne(TransactionPurpose::className(), ['transactionpurposeid' => 'transactionpurposeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecepient()
    {
        return $this->hasOne(User::className(), ['personid' => 'recepientid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemester()
    {
        return $this->hasOne(Semester::className(), ['semesterid' => 'semesterid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentmethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['paymentmethodid' => 'paymentmethodid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionsummary()
    {
        return $this->hasOne(TransactionSummary::className(), ['transactionsummaryid' => 'transactionsummaryid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVerifyingofficer()
    {
        return $this->hasOne(User::className(), ['personid' => 'verifyingofficerid']);
    }
    
}
