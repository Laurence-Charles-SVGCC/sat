<?php

namespace frontend\models;

use Yii;
use frontend\models\CsecQualification;

/**
 * This is the model class for table "subject".
 *
 * @property string $subjectid
 * @property string $examinationbodyid
 * @property string $name
 * @property boolean $isactive
 * @property boolean $isdeleted
 */
class Subject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['examinationbodyid', 'name'], 'required'],
            [['examinationbodyid'], 'integer'],
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
            'subjectid' => 'Subjectid',
            'examinationbodyid' => 'Examinationbodyid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
    
    
    /**
     * Initializes array for the subject dropdownlist
     * 
     * @param type $id
     * @param type $index
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2016
     * Date Last Modified: 04/01/2016
     */
    public static function initializeSubjectDropdown($qualificationid)
    {
        $qualification = CsecQualification::find()
                    ->where(['csecqualificationid' => $qualificationid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        
        $keys = array();
        array_push($keys, '');
        $values = array();
        array_push($values, 'Select...');
        $combined = array();
        
        if($qualification == false)
        {
            $combined = array_combine($keys, $values);
            return $combined;   
        }
        else        //if qualificationrecords exist
        {                   
            $examination_body_id = $qualification->examinationbodyid;

            $subjects = Subject::find()
                    ->where(['examinationbodyid' => $examination_body_id])
                    ->all();

            if (count($subjects)>0)     //if associated subject exist
            {
                foreach($subjects as $record)
                {
                    $k = strval($record->subjectid);
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
     * Returns array prepared for dropdownlist
     * 
     * @param type $examination_body_id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2016
     * Date Last Modified: 04/01/2016
     */
    public static function processSubjects($examination_body_id)
    {
        $records = Subject::find()
                ->where(['examinationbodyid' => $examination_body_id])
                ->all();

        $keys = array();
        array_push($keys, '');
        $values = array();
        array_push($values, 'Select...');
        $combined = array();

        if(count($records)==0)
        {
            return false;
        }
        else
        {   //if centre records found
            foreach($records as $record)
            {
                $k = strval($record->subjectid);
                array_push($keys, $k);
                $v = strval($record->name);
                array_push($values, $v);
            }
            $combined = array_combine($keys, $values);
            return $combined;
        }
    }
    
   
    /**
     * Returns array of subjects assoicated with a particular examination body
     * 
     * @param type $examination_body_id
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2016
     * Date Last Modified: 04/01/2016
     */
    public static function getSubjectList($examination_body_id)
    {  
        $records = Subject::find()
                ->where(['examinationbodyid' => $examination_body_id, 'isactive' =>1, 'isdeleted' => 0])
                ->all();
        
        $arr = array();
        foreach ($records as $record)
        {
            $combined = array();
            $keys = array();
            $values = array();
            array_push($keys, "id");
            array_push($keys, "name");
            $k1 = strval($record->subjectid);
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
