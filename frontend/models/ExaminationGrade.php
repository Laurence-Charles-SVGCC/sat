<?php

namespace frontend\models;

use Yii;
use frontend\models\CsecQualification;

/**
 * This is the model class for table "examination_grade".
 *
 * @property string $examinationgradeid
 * @property string $examinationbodyid
 * @property string $name
 * @property integer $ordering
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property CsecQualification[] $csecQualifications
 * @property ExaminationBody $examinationbody
 */
class ExaminationGrade extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'examination_grade';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['examinationbodyid', 'name', 'ordering'], 'required'],
            [['examinationbodyid', 'ordering'], 'integer'],
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
            'examinationgradeid' => 'Examinationgradeid',
            'examinationbodyid' => 'Examinationbodyid',
            'name' => 'Name',
            'ordering' => 'Ordering',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCsecQualifications()
    {
        return $this->hasMany(CsecQualification::className(), ['examinationgradeid' => 'examinationgradeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExaminationbody()
    {
        return $this->hasOne(ExaminationBody::className(), ['examinationbodyid' => 'examinationbodyid']);
    }
    
    
    /**
     * Initializes array for the grades
     * 
     * @param type $id
     * @param type $index
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2016
     * Date Last Modified: 04/01/2016
     */
    public static function initializeGradesDropdown($qualificationid)
    {
        $qualification = CsecQualification::find()
                    ->where(['csecqualificationid' => $qualificationid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        
        $keys = array();
        array_push($keys, '');
        array_push($keys, '25');        //valid examinationgradeid
        $values = array();
        array_push($values, 'Select...');
        array_push($values, 'Pending');
        $combined = array();
        
        if($qualification == false){
            $combined = array_combine($keys, $values);
            return $combined;   
        }
        else        //if qualificationrecords exist
        {                   
            $examination_body_id = $qualification->examinationbodyid;

            $grades = ExaminationGrade::find()
                    ->where(['examinationbodyid' => $examination_body_id])
                    ->all();

            if (count($grades)>0)       //if associated subject exist
            {          
                foreach($grades as $record)
                {
                    $k = strval($record->examinationgradeid);
                    array_push($keys, $k);
                    $v = strval($record->name);
                    array_push($values, $v);
                }  
                
                $combined = array_combine($keys, $values);
                return $combined;   
            }
            return false;
        }
    }
    

    /**
     * Returns array of 'examination_grade' records assoicated with a particular examination body
     * 
     * @param type $examination_body_id
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2016
     * Date Last Modified: 04/01/2016
     */
    public static function getExaminationGradeList($examination_body_id)
    {  
        $records = ExaminationGrade::find()
                ->where(['examinationbodyid' => $examination_body_id, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
      
        $arr = array();
        foreach ($records as $record)
        {
            $combined = array();
            $keys = array();
            $values = array();
            array_push($keys, "id");
            array_push($keys, "name");
            $k1 = strval($record->examinationgradeid);
            $k2 = strval($record->name);
            array_push($values, $k1);
            array_push($values, $k2);
            $combined = array_combine($keys, $values);
            array_push($arr, $combined);
            $combined = NULL;
            $keys = NULL;
            $values = NULL;        
        }
        return $arr;  
    }
    
    
}
