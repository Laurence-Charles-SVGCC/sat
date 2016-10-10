<?php

namespace frontend\models;

use Yii;
use frontend\models\Transaction;

/**
 * This is the model class for table "payment_method".
 *
 * @property string $paymentmethodid
 * @property string $name
 * @property string $createdby
 * @property string $lastmodifiedby
 * @property integer $isactive
 * @property integer $isdeleted
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
            [['name', 'createdby'], 'required'],
            [['isactive', 'isdeleted', 'createdby', 'lastmodifiedby'], 'integer'],
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
            'createdby' => 'Created By',
            'lastmodifiedby' =>  'Last Modified By',
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
    
    
    /**
     * Returns true if payment method has been utilized in any transaction record.
     * 
     * @param type $recordid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 04/10/2016
     * Date Last Modified: 04/10/2016
     */
    public static function paymentMethodRecorded($recordid)
    {
        $transactions = Transaction::find()
                ->where(['paymentmethodid' => $recordid, 'isdeleted' => 0])
                ->all();
        if ($transactions)
            return true;
        return false;
    }
}
