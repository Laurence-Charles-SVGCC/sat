<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "employee_batch".
 *
 * @property integer $personid
 * @property integer $batchid
 * @property integer $isleadlecturer
 *
 * @property User $person
 * @property Batch $batch
 */
class EmployeeBatch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee_batch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'batchid'], 'required'],
            [['personid', 'batchid', 'isleadlecturer'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'personid' => 'Personid',
            'batchid' => 'Batchid',
            'isleadlecturer' => 'Isleadlecturer',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::class, ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatch()
    {
        return $this->hasOne(Batch::class, ['batchid' => 'batchid']);
    }
}
