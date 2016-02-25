<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "general_work_experience".
 *
 * @property integer $generalworkexperienceid
 * @property integer $personid
 * @property string $role
 * @property string $natureofduties
 * @property string $employer
 * @property string $employeraddress
 * @property string $salary
 * @property string $startdate
 * @property string $enddate
 * @property integer $iscurrentjob
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 */
class GeneralWorkExperience extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'general_work_experience';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'role', 'employer', 'startdate', 'iscurrentjob', 'natureofduties', 'employeraddress'], 'required'],
            [['personid', 'iscurrentjob', 'isactive', 'isdeleted'], 'integer'],
            [['startdate', 'enddate'], 'safe'],
            [['role', 'employer'], 'string', 'max' => 100],
            [['salary'], 'string', 'max' => 15],
            [['natureofduties', 'employeraddress'], 'string'],
//            ['enddate', 'default', 'value' => null],
//            ['salary', 'default', 'value' => null],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'generalworkexperienceid' => 'Generalworkexperienceid',
            'personid' => 'Personid',
            'role' => 'Role',
            'natureofduties' => 'Nature of Duties',
            'employer' => 'Employer',
            'employeraddress' => 'Employer Address',
            'salary' => 'Salary',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'iscurrentjob' => 'Iscurrentjob',
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
     * Retrieves all the general work experience records associated with a personid
     * 
     * @param type $id
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 30/09/2015
     * Last Date Modified: 30/09/2015
     */
    public static function getGeneralWorkExperiences($id)
    {
        $model = GeneralWorkExperience::find()
                 ->where(['personid'=> $id, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();      
        return $model;
    }
  
    
    /**
     * Determines is eligible for saving
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 12/10/2015
     * Date Last Modified: 01/02/2016
     */
    public function isValid()
    {
        if ($this->role == NULL || strcmp($this->role,"")==0  || strcmp($this->role,"blank")==0
            || $this->employer == NULL || strcmp($this->employer,"")==0 || strcmp($this->employer,"")==0
            || $this->startdate == NULL || strcmp($this->startdate,"2015-01-01")==0  || strcmp($this->startdate,"2015-01-01")==0)
            return false;
        return true;
    }
    
    
    /**
     * Retrieves a particular record
     * 
     * @param type $primaryKey
     * @return boolean
     * 
     * Date Created: 14/10/2015
     * Date Last Modified: 14/10/2015
     */
    public function getGeneralWorkExperience($primaryKeys)
    {
        $count = count($primaryKeys);
        
        for ($i=0 ; $i<$count ; $i++)
        {
            if ($this->generalworkexperienceid == $primaryKeys[$i])
            {
                return true;
            }
        }
        return false;
    }
    
    
    /**
     * Creates backup of a collection of GeneralWorkExperiences
     * 
     * @param type $id
     * @param type $experiences
     * @return array
     * 
     * Date Created 15/10/2015
     * Date Last Modified: 15/10/2015
     */
    public static function backUp($id, $experiences)
    {
        $savedExperiences = array();
        
        foreach ($experiences as $experience)
        {
            $temp = NULL;
            $temp = new GeneralWorkExperience();
            $temp->role = $experience->role;
            $temp->natureofduties = $experience->natureofduties;
            $temp->employeraddress = $experience->employeraddress;
            $temp->employer = $experience->employer;
            $temp->salary = $experience->salary;
            $temp->startdate = $experience->startdate;
            $temp->enddate = $experience->enddate;
            $temp->iscurrentjob = $experience->iscurrentjob;
            array_push($savedExperiences, $temp);      
        }
        return $savedExperiences;
    }
    
    
    /**
     * Creates backup of a collection of GeneralWorkExperiences
     * 
     * @param type $id
     * @param type $experiences
     * @return array
     * 
     * Date Created 15/10/2015
     * Date Last Modified: 15/10/2015
     */
    public static function backUp2($experiences)
    {
        $savedExperiences = array();
        
        foreach ($experiences as $experience)
        {
            $temp = NULL;
            $temp = new GeneralWorkExperience();
            $temp->role = $experience->role;
            $temp->natureofduties = $experience->natureofduties;
            $temp->employeraddress = $experience->employeraddress;
            $temp->employer = $experience->employer;
            $temp->salary = $experience->salary;
            $temp->startdate = $experience->startdate;
            $temp->enddate = $experience->enddate;
            $temp->iscurrentjob = $experience->iscurrentjob;
            array_push($savedExperiences, $temp);      
        }
        return $savedExperiences;
    }
    
    
    /**
     * Saves the backed up GeneralWorkExperience to the databases
     * 
     * @param type $experiences
     * 
     * Date Created: 15/10/2015
     * Date Last Modified: 15/10/2015
     */
    public static function restore($experiences)
    {
        foreach ($experiences as $experience)
        {
            $experience->save();     
        }
    }
    
}
