<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "employee_batch".
 *
 * @property string $personid
 * @property string $batchid
 * @property integer $isleadlecturer
 *
 * @property Person $person
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
        return $this->hasOne(Person::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatch()
    {
        return $this->hasOne(Batch::className(), ['batchid' => 'batchid']);
    }
}
