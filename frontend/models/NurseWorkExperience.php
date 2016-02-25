<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "nurse_work_experience".
 *
 * @property integer $nurseworkexperienceid
 * @property integer $personid
 * @property string $location
 * @property string $natureoftraining
 * @property string $tenureperiod
 * @property string $departreason
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 */
class NurseWorkExperience extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nurse_work_experience';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'location', 'natureoftraining', 'tenureperiod'], 'required'],
            [['personid', 'isactive', 'isdeleted'], 'integer'],
            [['location', 'natureoftraining', 'departreason'], 'string'],
            [['tenureperiod'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nurseworkexperienceid' => 'Nurseworkexperienceid',
            'personid' => 'Personid',
            'location' => 'Location',
            'natureoftraining' => 'Natureoftraining',
            'tenureperiod' => 'Tenureperiod',
            'departreason' => 'Departreason',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
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
     * Returns instance of model 
     * 
     * @param type $id          An applicant's personid
     * @return boolean
     * 
     * Date Created: 01/10/2015
     * Last Date Modified: 01/10/2015
     */
    public static function getNurseWorkExperience($id)
    {
        $model = NurseWorkExperience::find()
                 ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                 ->one();
        if ($model)
        {
            return $model;
        }
        else
        {
            return false;
        }
    }
    
    
    /**
     * Retrieves all the nurse work experience records associated with a personid
     * 
     * @param type $id
     * @return type
     * 
     * Date Created: 30/09/2015
     * Last Date Modified: 30/09/2015
     */
    public static function getNurseWorkExperiences($id)
    {
        $model = NurseWorkExperience::find()
                 ->where(['personid'=> $id, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();
        return $model;
    }
    
    
    
    
    //Checks is applicant sponsor field is populated
    //For conditional appearance of sponsor radiolist
    public static function checkNurseWorkExperience($id){
        $model = self::getNurseWorkExperience($id);
        
        if ($model){
            if($model->natureoftraining != NULL && strcmp($model->natureoftraining,"") != 0
               && $model->location != NULL  && strcmp($model->location,"") != 0
               && $model->tenureperiod != NULL  && strcmp($model->tenureperiod,"") != 0
               && $model->departreason != NULL && strcmp($model->departreason,"") != 0)
            {
                return true;
            }
        }    
        return false;
    }
}
