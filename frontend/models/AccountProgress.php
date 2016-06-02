<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "account_progress".
 *
 * @property integer $accountprogressid
 * @property string $name
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 */
class AccountProgress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account_progress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'accountprogressid' => 'Accountprogressid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
