<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "payment_method".
 *
 * @property string $paymentmethodid
 * @property string $name
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Transaction[] $transactions
 */
class PaymentMethod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_method';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'paymentmethodid' => 'Paymentmethodid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['paymentmethodid' => 'paymentmethodid']);
    }
}
