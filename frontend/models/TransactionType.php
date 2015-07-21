<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "transaction_type".
 *
 * @property string $transactiontypeid
 * @property string $name
 * @property boolean $isactive
 * @property boolean $isdeleted
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
            'transactiontypeid' => 'Transactiontypeid',
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
        return $this->hasMany(Transaction::className(), ['transactiontypeid' => 'transactiontypeid']);
    }
}
