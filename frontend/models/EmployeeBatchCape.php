<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "employee_batch_cape".
 *
 * @property string $personid
 * @property string $batchcapeid
 * @property integer $isleadlecturer
 *
 * @property Person $person
 * @property BatchCape $batchcape
 */
class EmployeeBatchCape extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee_batch_cape';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'batchcapeid'], 'required'],
            [['personid', 'batchcapeid', 'isleadlecturer'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'personid' => 'Personid',
            'batchcapeid' => 'Batchcapeid',
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
    public function getBatchcape()
    {
        return $this->hasOne(BatchCape::className(), ['batchcapeid' => 'batchcapeid']);
    }
}
