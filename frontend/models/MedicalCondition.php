<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "medical_condition".
 *
 * @property string $medicalconditionid
 * @property string $personid
 * @property string $medicalcondition
 * @property string $description
 * @property string $emergencyaction
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 */
class MedicalCondition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medical_condition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'medicalcondition'], 'required'],
            [['personid', 'isactive', 'isdeleted'], 'integer'],
            [['description', 'emergencyaction'], 'string'],
            [['medicalcondition'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'medicalconditionid' => 'Medicalconditionid',
            'personid' => 'Personid',
            'medicalcondition' => 'Medicalcondition',
            'description' => 'Description',
            'emergencyaction' => 'Emergencyaction',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }
}
