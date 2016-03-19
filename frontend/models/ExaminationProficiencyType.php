<?php

namespace frontend\models;

use Yii;
use frontend\models\CsecQualification;

/**
 * This is the model class for table "examination_proficiency_type".
 *
 * @property string $examinationproficiencytypeid
 * @property string $examinationbodyid
 * @property string $name
 * @property integer $isactive
 * @property integer $isdeleted
 */
class ExaminationProficiencyType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'examination_proficiency_type';
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
            'examinationproficiencytypeid' => 'Examinationproficiencytypeid',
            'examinationbodyid' => 'Examinationbodyid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
    
    
    /**
     * Initializes array for the proficiencies
     * 
     * @param type $id
     * @param type $index
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2016
     * Date Last Modified: 04/01/2016
     */
    public static function initializeProficiencyDropdown($qualificationid)
    {
        $qualification = CsecQualification::find()
                    ->where(['csecqualificationid' => $qualificationid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        
        $keys = array();
        array_push($keys, '');
        $values = array();
        array_push($values, 'Select Proficiency');
        $combined = array();
        
        if($qualification == false)
        {
            $combined = array_combine($keys, $values);
            return $combined;   
        }
        else        //if qualificationrecords exist
        {                   
            $examination_body_id = $qualification->examinationbodyid;

            $proficiencies = ExaminationProficiencyType::find()
                    ->where(['examinationbodyid' => $examination_body_id])
                    ->all();

            if (count($proficiencies)>0)        //if associated subject exist
            {         
                foreach($proficiencies as $record)
                {
                    $k = strval($record->examinationproficiencytypeid);
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
     * Returns array of 'examination_proficiency' records assoicated with a particular examination body
     * 
     * @param type $examination_body_id
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2016
     * Date Last Modified: 04/01/2016
     */
    public static function getExaminationProficiencyList($examination_body_id)
    {  
        $records = ExaminationProficiencyType::find()
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
            $k1 = strval($record->examinationproficiencytypeid);
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
