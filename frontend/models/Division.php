<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "division".
 *
 * @property string $divisionid
 * @property string $name
 * @property string $abbreviation
 * @property string $phone
 * @property string $website
 * @property string $email
 * @property string $country
 * @property string $constituency
 * @property string $town
 * @property string $addressline
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Application[] $applications
 * @property ApplicationPeriod[] $applicationPeriods
 * @property ClubDivision[] $clubDivisions
 * @property Club[] $clubs
 * @property DeanDivision[] $deanDivisions
 * @property Department[] $departments
 */
class Division extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'division';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'abbreviation', 'phone'], 'required'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name'], 'string', 'max' => 100],
            [['abbreviation', 'phone'], 'string', 'max' => 15],
            [['website', 'email', 'country', 'constituency', 'town', 'addressline'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'divisionid' => 'Divisionid',
            'name' => 'Name',
            'abbreviation' => 'Abbreviation',
            'phone' => 'Phone',
            'website' => 'Website',
            'email' => 'Email',
            'country' => 'Country',
            'constituency' => 'Constituency',
            'town' => 'Town',
            'addressline' => 'Addressline',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::className(), ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationPeriods()
    {
        return $this->hasMany(ApplicationPeriod::className(), ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubDivisions()
    {
        return $this->hasMany(ClubDivision::className(), ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubs()
    {
        return $this->hasMany(Club::className(), ['clubid' => 'clubid'])->viaTable('club_division', ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeanDivisions()
    {
        return $this->hasMany(DeanDivision::className(), ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments()
    {
        return $this->hasMany(Department::className(), ['divisionid' => 'divisionid']);
    }
    
    
    /********************* Author Defined Functions ***********************************/
    
    /**
     * Returns an associative array of all divisions
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 05/12/2015
     * Date Last Modified: 05/12/2015
     */
    public static function getAllDivisions()
    {
        $divisions = Division::find()
                    ->where(['isactive' => 1, 'isdeleted' => 0])
                    ->andWhere(['not', ['abbreviation' => 'SVGCC']])
                    ->andWhere(['not', ['abbreviation' => 'PC']])
                    ->all();
        
        $keys = array();
        array_push($keys, '0');
        
        $values = array();
        array_push($values, 'Select Division...');
        
        foreach($divisions as $division)
        {
            $key = strval($division->divisionid);
            array_push($keys, $key);
            $value = strval($division->abbreviation);
            array_push($values, $value);
        }
        
        $combined = array_combine($keys, $values);
        return $combined;
    }
    
    
    /**
     * Returns an associative array of the division that the user is assigned to.
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 06/12/2015
     * Date Last Modified: 06/12/2015
     */
    public static function getDivisionsAssignedTo($personid)
    {
        $db = Yii::$app->db;
        $records = $db->createCommand(
                    "SELECT  employee_department.personid AS 'personid',"
                    . " employee_department.departmentid AS 'departmentid',"
                    . " department.divisionid AS 'divisionid'"
                    . " FROM employee_department"
                    . " JOIN department"
                    . " ON employee_department.departmentid = department.departmentid"
                    . " WHERE employee_department.personid = ". $personid
                    . " AND employee_department.departmentid <> 20"
                    . " AND employee_department.isactive = 1"
                    . " AND employee_department.isdeleted = 0;"
                    )
                    ->queryAll();

        $keys = array();
        array_push($keys, '0');
        
        $values = array();
        array_push($values, 'Select Division...');
        
        foreach($records as $record)
        {
            $division = NULL;
            $id = NULL;
            $id = $record["divisionid"];
            $division = Division::find()
                    ->where(['divisionid' => $id,  'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            
            $key = strval($division->divisionid);
            array_push($keys, $key);
            $value = strval($division->abbreviation);
            array_push($values, $value);           
        }
        
        $combined = array_combine($keys, $values);
        return $combined;
//    public static function getDivisionsAssignedTo($personid)
//    {
//        $db = Yii::$app->db;
//        $records = $db->createCommand(
//                    "SELECT * "
//                    . " FROM employee_division"
//                    . " WHERE employeeid = ". $personid
//                    . " AND isactive = 1"
//                    . " AND isdeleted = 0;"
//                    )
//                    ->queryAll();
//
//        $keys = array();
//        array_push($keys, '0');
//        
//        $values = array();
//        array_push($values, 'Select Division...');
//        
//        foreach($records as $record)
//        {
//            $division = NULL;
//            $id = NULL;
//            $id = $record["divisionid"];
//            $division = Division::find()
//                    ->where(['divisionid' => $id,  'isactive' => 1, 'isdeleted' => 0])
//                    ->one();
//            
//            $key = strval($division->divisionid);
//            array_push($keys, $key);
//            $value = strval($division->abbreviation);
//            array_push($values, $value);           
//        }
//        
//        $combined = array_combine($keys, $values);
//        return $combined;
    }
    
    
    /**
     * Returns the name of a particular division
     * 
     * @param type $divisionid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 06/12/2015
     * Date Last Modified: 07/12/2015
     */
    public static function getDivisionName($divisionid)
    {
        $division = Division::find()
                    ->where(['divisionid' => $divisionid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($division)
        {
            return $division->name;
        }
        else
            return false;
    }
    
    
    /**
     * Returns the abbreviation of a particular division
     * 
     * @param type $divisionid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 08/01/2016
     * Date Last Modified: 08/01/2016
     */
    public static function getDivisionAbbreviation($divisionid)
    {
        $division = Division::find()
                    ->where(['divisionid' => $divisionid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($division)
        {
            return $division->abbreviation;
        }
        else
            return false;
    }
    
    
    /**
     * Returns a key=>value array containing alist of divisions
     * 
     * @param type $applicantintent
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/01/2016
     * Date Last Modified: 09/01/2016
     */
    public static function getDivisions($applicantintent)
    {
        if ($applicantintent == 1)      //if user is applying for DASGS/DTVE full time
        {
            $db = Yii::$app->db;
            $records = $db->createCommand(
                                    "SELECT application_period.divisionid, division.name" 
                                    ." FROM application_period" 
                                    ." JOIN division"
                                    ." ON application_period.divisionid = division.divisionid"
                                    ." WHERE application_period.isactive=1"
                                    ." AND application_period.isdeleted=0"
                                    ." AND application_period.divisionid IN (4,5)" 
                                    ." GROUP BY divisionid;"
                                )
                                ->queryAll();

            $keys = array();
            array_push($keys, '0');
            $values = array();
            array_push($values, 'Select...');
            $combined = array();
            
            foreach($records as $record)
            {
                $k = strval($record["divisionid"]);
                array_push($keys, $k);
                $v = strval($record["name"]);
                array_push($values, $v);

            }
            $combined = array_combine($keys, $values);
            return $combined;
        }
        
        
        else if ($applicantintent == 4)      //if user is applying for DTE full time
        {
            $db = Yii::$app->db;
            $records = $db->createCommand(
                                    "SELECT application_period.divisionid, division.name" 
                                    ." FROM application_period" 
                                    ." JOIN division"
                                    ." ON application_period.divisionid = division.divisionid"
                                    ." WHERE application_period.isactive=1"
                                    ." AND application_period.isdeleted=0"
                                    ." AND application_period.divisionid=6" 
                                    ." GROUP by divisionid;"
                                )
                                ->queryAll();

            $keys = array();
            array_push($keys, '0');
            $values = array();
            array_push($values, 'Select...');
            $combined = array();
            
            foreach($records as $record)
            {
                $k = strval($record["divisionid"]);
                array_push($keys, $k);
                $v = strval($record["name"]);
                array_push($values, $v);

            }
            $combined = array_combine($keys, $values);
            return $combined;
        }
        
        
        else if ($applicantintent == 6)      //if user is applying for DNE full time
        {
            $db = Yii::$app->db;
            $records = $db->createCommand(
                                    "SELECT application_period.divisionid, division.name" 
                                    ." FROM application_period" 
                                    ." JOIN division"
                                    ." ON application_period.divisionid = division.divisionid"
                                    ." WHERE application_period.isactive=1"
                                    ." AND application_period.isdeleted=0"
                                    ." AND application_period.divisionid=7" 
                                    ." GROUP BY divisionid;"
                                )
                                ->queryAll();

            $keys = array();
            array_push($keys, '0');
            $values = array();
            array_push($values, 'Select...');
            $combined = array();
            
            foreach($records as $record)
            {
                $k = strval($record["divisionid"]);
                array_push($keys, $k);
                $v = strval($record["name"]);
                array_push($values, $v);
            }
            $combined = array_combine($keys, $values);
            return $combined;
        }
        
        
        
    }
    
    
    
}
