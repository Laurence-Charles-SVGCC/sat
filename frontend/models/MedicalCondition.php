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
    
    
    /**
     * Retrieves all the medical records associated with a personid
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 23/12/2015
     * Date Last Modified: 23/12/2015
     */
    public static function getMedicalConditions($id)
    {
        $conditions = MedicalCondition::find()
                 ->where(['personid'=> $id, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();
        if (count($conditions) > 0)
            return $conditions;
        return false;     
    }
}
