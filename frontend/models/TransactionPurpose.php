<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "transaction_purpose".
 *
 * @property string $transactionpurposeid
 * @property string $name
 * @property string $createdby
 * @property string $lastmodifiedby
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Transaction[] $transactions
 */
class TransactionPurpose extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction_purpose';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
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
            'transactionpurposeid' => 'Transactionpurposeid',
            'name' => 'Name',
            'description' => 'Description',
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
        return $this->hasMany(Transaction::className(), ['transactionpurposeid' => 'transactionpurposeid']);
    }
    
    /**
     * Returns true if TransactionPurpose model has been utilized in any transaction record.
     * 
     * @param type $recordid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 07/10/2016
     * Date Last Modified: 07/10/2016
     */
    public static function transactionPurposeRecorded($recordid)
    {
        $types = Transaction::find()
                ->where(['transactionpurposeid' => $recordid, 'isdeleted' => 0])
                ->all();
        if ($types)
            return true;
        return false;
    }
}
