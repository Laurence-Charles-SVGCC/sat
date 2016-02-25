<?php

namespace frontend\models;

use Yii;
use frontend\models\NursingAdditionalInfo;

/**
 * This is the model class for table "criminal_record".
 *
 * @property integer $criminalrecordid
 * @property integer $personid
 * @property string $natureofcharge
 * @property string $outcome
 * @property string $dateofconviction
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 */
class CriminalRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'criminal_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'natureofcharge', 'outcome'], 'required'],
            [['personid', 'isactive', 'isdeleted'], 'integer'],
            [['natureofcharge', 'outcome'], 'string'],
            [['dateofconviction'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'criminalrecordid' => 'Criminalrecordid',
            'personid' => 'Personid',
            'natureofcharge' => 'Natureofcharge',
            'outcome' => 'Outcome',
            'dateofconviction' => 'Dateofconviction',
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
     * Date Created: 30/09/2015
     * Last Date Modified: 30/09/2015
     */
    public static function getCriminalRecord($id)
    {
        $model = CriminalRecord::find()
                 ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                 ->one();
        if ($model)
        {
            return $model;
        }
        return false;
    }
    
    
    /**
     * Determines if all manadatory field of the model have data.
     * 
     * @param type $id
     * @return boolean
     * 
     * Date Created: 30/09/2015
     * Date Last Modified: 30/0/2015
     */
    public static function checkCriminalRecord($id)
    {
        $model= self::getCriminalRecord($id);

        if ($model){
            if($model->natureofcharge != NULL && strcmp($model->natureofcharge,"") != 0
               && $model->outcome != NULL  && strcmp($model->outcome,"") != 0 
               && $model->dateofconviction != NULL && strcmp($model->dateofconviction,"") != 0)
            {
                return true;
            }
        }    
        return false;
    }
    
}
