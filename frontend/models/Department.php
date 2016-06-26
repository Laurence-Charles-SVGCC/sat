<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property string $departmentid
 * @property string $divisionid
 * @property string $name
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Division $division
 * @property EmployeeDepartment[] $employeeDepartments
 * @property ProgrammeCatalog[] $programmeCatalogs
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['divisionid', 'name'], 'required'],
            [['divisionid'], 'integer'],
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
            'departmentid' => 'Departmentid',
            'divisionid' => 'Divisionid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::className(), ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeDepartments()
    {
        return $this->hasMany(EmployeeDepartment::className(), ['departmentid' => 'departmentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammeCatalogs()
    {
        return $this->hasMany(ProgrammeCatalog::className(), ['departmentid' => 'departmentid']);
    }
    
    
    /**
     * Returns an array containing the education departments of a particular division
     * 
     * @param type $divisionid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 07/12/2015
     * Date Last Modified: 07/12/2015
     */
    public static function getDepartments($divisionid)
    {
        if ($divisionid == 4)      //if DASGS
        {
            $departments = Department::find()
                        ->where(['divisionid' => $divisionid, 'isactive' => 1, 'isdeleted' => 0])
                        ->andWhere(['not', ['name' => 'DASGS Senior']])
                        ->andWhere(['not', ['name' => 'Administrative (DASGS)']])
                        ->andWhere(['not', ['name' => 'Library (DASGS)']])
                        ->all();             
        }
        elseif ($divisionid == 5)      //if DTVE
        {
            $departments = Department::find()
                         ->where(['divisionid' => $divisionid, 'isactive' => 1, 'isdeleted' => 0])
                        ->andWhere(['not', ['name' => 'DTVE Senior']])
                        ->andWhere(['not', ['name' => 'Administrative (DTVE)']])
                        ->andWhere(['not', ['name' => 'Library (DTVE)']])
                        ->all();
        }
        elseif ($divisionid == 6)      //if DTE
        {
            $departments = Department::find()
                         ->where(['divisionid' => $divisionid, 'isactive' => 1, 'isdeleted' => 0])
                        ->andWhere(['not', ['name' => 'DTE Senior']])
                        ->andWhere(['not', ['name' => 'Administrative (DTE)']])
                        ->andWhere(['not', ['name' => 'Library (DTE)']])
                        ->all();  
        }
        elseif ($divisionid == 7)      //if DNE
        {
            $departments = Department::find()
                        ->where(['divisionid' => $divisionid, 'isactive' => 1, 'isdeleted' => 0])
                        ->andWhere(['not', ['name' => 'DNE Senior']])
                        ->andWhere(['not', ['name' => 'Administrative (DNE)']])
                        ->andWhere(['not', ['name' => 'Library (DNE)']])
                        ->all(); 
        }
        
        if (count($departments) > 0)
            return $departments;
        else
            return false;
    }
    
    
    /**
     * Returns an array of departmentIDs for a particular division
     * 
     * @param type $divisionid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 27/04/2016
     * Date Last Modified: 27/04/2016
     */
    public static function getDepartmentIDs($divisionid)
    {
        $departments = Department::find()
                    ->where(['divisionid' => $divisionid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
        $ids = array();
        if ($departments)
        {
            foreach ($departments as $department)
            {
                $ids[]= $department->departmentid;
            }
            return $ids;
        }
        return false;
    }
    
    
    /**
     * Returns an array of department that have at least one assigned course
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 27/04/2016
     * Date Last Modified: 27/04/2016
     */
    public static function getDepartmentsWithCourses()
    {
        $non_cape_departments = Department::find()
                ->innerJoin('programme_catalog', '`department`.`departmentid` = `programme_catalog`.`departmentid`')
                ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                ->innerJoin('course_offering', '`academic_offering`.`academicofferingid` = `course_offering`.`academicofferingid`')
                ->where(['course_offering.isactive' => 1, 'course_offering.isdeleted' => 0])
                ->all();
        $cape_departments = Department::find()
                ->innerJoin('programme_catalog', '`department`.`departmentid` = `programme_catalog`.`departmentid`')
                ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                ->innerJoin('cape_subject', '`academic_offering`.`academicofferingid` = `cape_subject`.`academicofferingid`')
                ->where(['cape_subject.isactive' => 1, 'cape_subject.isdeleted' => 0])
                ->all();
        
        $departments = array();
                
        $keys = array();
        array_push($keys, '0');
        
        $values = array();
        array_push($values, 'Select Department...');
        
        foreach($non_cape_departments as $non_cape_department)
        {
            if (!in_array($non_cape_department, $departments))
            {
                $key = strval($non_cape_department->departmentid);
                array_push($keys, $key);
                $value = strval($non_cape_department->name);
                array_push($values, $value);
            }
        }
        
        foreach($cape_departments as $cape_department)
        {
            if (!in_array($cape_department, $departments))
            {
                $key = strval($cape_department->departmentid);
                array_push($keys, $key);
                $value = strval($cape_department->name);
                array_push($values, $value);
            }
        }
        
        $combined = array_combine($keys, $values);
        return $combined;        
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
    public static function getDeparmentName($departmentid)
    {
        $department = Department::find()
                    ->where(['departmentid' => $departmentid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($department)
        {
            return $department->name;
        }
        else
            return false;
    }
    
    
    /**
     * Returns the divisionid of a particular departmnent
     * 
     * @param type $departmentid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 10/06/2016
     * Date Last Modified: 10/06/2016
     */
     public static function getDivisionID($departmentid)
    {
        $department = Department::find()
                ->where(['departmentid' => $departmentid])
                ->one();
        if($department)
            return $department->divisionid;
        return false;       
    }
    
    
    /**
     * Returns an associative array is ['depatmentid'=>'name'
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 24/06/2016
     * Date Last Modified: 24/06/2016
     */
    public static function getAcademicDepartmentListing()
    {
         $departments = Department::find()
                 ->where(['<', 'departmentid', 10])
                 ->all();
         
        if ($departments)
        {
            $keys = array();
            array_push($keys, '');

            $values = array();
            array_push($values, 'Select...');

           foreach($departments as $department)
            {
                $key = $department->departmentid;
                array_push($keys, $key);
                $value = $department->name;
                array_push($values, $value);
            }
         }
        $combined = array_combine($keys, $values);
        return $combined;
    }
    
    
}
