<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment_method".
 *
 * @property int $paymentmethodid
 * @property string $name
 * @property int $createdby
 * @property int $lastmodifiedby
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property User $createdby0
 * @property User $lastmodifiedby0
 * @property Transaction[] $transactions
 */
class PaymentMethod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['createdby', 'lastmodifiedby', 'isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['createdby'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['createdby' => 'personid']],
            [['lastmodifiedby'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['lastmodifiedby' => 'personid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'paymentmethodid' => 'Paymentmethodid',
            'name' => 'Name',
            'createdby' => 'Createdby',
            'lastmodifiedby' => 'Lastmodifiedby',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedby0()
    {
        return $this->hasOne(User::class, ['personid' => 'createdby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastmodifiedby0()
    {
        return $this->hasOne(User::class, ['personid' => 'lastmodifiedby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['paymentmethodid' => 'paymentmethodid']);
    }
}
