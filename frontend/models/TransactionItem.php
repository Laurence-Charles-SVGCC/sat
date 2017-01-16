<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "transaction_item".
 *
 * @property string $transactionitemid
 * @property string $transactionpurposeid
 * @property string $name
 * @property string $createdby
 * @property string $lastmodifiedby
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property TransactionPurpose $transactionpurpose
 * @property Person $createdby
 * @property Person $lastmodifiedby
 */
class TransactionItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transactionpurposeid', 'name', 'createdby'], 'required'],
            [['transactionitemid', 'transactionpurposeid', 'createdby', 'lastmodifiedby', 'isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transactionitemid' => 'Transactionitemid',
            'transactionpurposeid' => 'Transactionpurposeid',
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
    public function getTransactionpurpose()
    {
        return $this->hasOne(TransactionPurpose::className(), ['transactionpurposeid' => 'transactionpurposeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedby()
    {
        return $this->hasOne(Person::className(), ['personid' => 'createdby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastmodifiedby()
    {
        return $this->hasOne(Person::className(), ['personid' => 'lastmodifiedby']);
    }
    
    
    /**
     * Returns true if TransactionItem model has been utilized in any transaction record.
     * 
     * @param type $recordid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 11/01/2017
     * Date Last Modified: 11/01/2017
     */
    public static function transactionItemRecorded($recordid)
    {
        $types = Transaction::find()
                ->where(['transactionitemid' => $recordid, 'isdeleted' => 0])
                ->all();
        if ($types)
            return true;
        return false;
    }
}
