<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "transaction_type".
 *
 * @property string $transactiontypeid
 * @property string $name
 * @property string $createdby
 * @property string $lastmodifiedby
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Transaction[] $transactions
 */
class TransactionType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
            'transactiontypeid' => 'Transactiontypeid',
            'name' => 'Transaction Type Name',
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
        return $this->hasMany(Transaction::className(), ['transactiontypeid' => 'transactiontypeid']);
    }
    
    
    /**
     * Returns true if TransactionType model has been utilized in any transaction record.
     * 
     * @param type $recordid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 06/10/2016
     * Date Last Modified: 06/10/2016
     */
    public static function transactionTypeRecorded($recordid)
    {
        $types = Transaction::find()
                ->where(['transactiontypeid' => $recordid, 'isdeleted' => 0])
                ->all();
        if ($types)
            return true;
        return false;
    }
}
