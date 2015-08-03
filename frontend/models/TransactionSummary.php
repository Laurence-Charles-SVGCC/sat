<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "transaction_summary".
 *
 * @property string $transactionsummaryid
 * @property string $balance
 * @property string $totalpaid
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Transaction[] $transactions
 */
class TransactionSummary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction_summary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balance', 'totalpaid'], 'required'],
            [['balance'], 'number'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['totalpaid'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transactionsummaryid' => 'Transactionsummaryid',
            'balance' => 'Balance',
            'totalpaid' => 'Totalpaid',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['transactionsummaryid' => 'transactionsummaryid']);
    }
}
