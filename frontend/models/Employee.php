<?php

namespace frontend\models;

use Yii;
use common\models\User;

use frontend\models\EmployeeDivision;
use backend\models\AuthAssignment;

/**
 * This is the model class for table "employee".
 *
 * @property string $employeeid
 * @property string $personid
 * @property string $employeetitleid
 * @property string $title
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $gender
 * @property string $dateofbirth
 * @property string $maritalstatus
 * @property string $nationality
 * @property string $religion
 * @property string $placeofbirth
 * @property string $photopath
 * @property string $nationalidnumber
 * @property string $nationalinsurancenumber
 * @property string $inlandrevenuenumber
 * @property string $signaturepath
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property EmployeeTitle $employeetitle
 * @property Person $person
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'firstname', 'lastname'], 'required'],
            [['personid', 'employeetitleid'], 'integer'],
            [['dateofbirth'], 'safe'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['title'], 'string', 'max' => 3],
            [['firstname', 'middlename', 'lastname', 'maritalstatus', 'nationality', 'religion', 'placeofbirth', 'nationalidnumber', 'nationalinsurancenumber', 'inlandrevenuenumber'], 'string', 'max' => 45],
            [['gender'], 'string', 'max' => 6],
            [['photopath', 'signaturepath'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employeeid' => 'Employeeid',
            'personid' => 'Personid',
            'employeetitleid' => 'Job Title',
            'title' => 'Title',
            'firstname' => 'First Name',
            'middlename' => 'Middle Name(s)',
            'lastname' => 'Last Name',
            'gender' => 'Gender',
            'dateofbirth' => 'Date of Birth',
            'maritalstatus' => 'Marital Status',
            'nationality' => 'Nationality',
            'religion' => 'Religion',
            'placeofbirth' => 'Place of Birth',
            'photopath' => 'Photo Path',
            'nationalidnumber' => 'National ID Number',
            'nationalinsurancenumber' => 'National Insurance Number',
            'inlandrevenuenumber' => 'Inland Revenue Number',
            'signaturepath' => 'Signature Path',
            'isactive' => 'Active',
            'isdeleted' => 'Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeetitle()
    {
        return $this->hasOne(EmployeeTitle::className(), ['employeetitleid' => 'employeetitleid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }
    
    
    /**
     * Returns the full name of an employee
     * 
     * @param type $personid
     * @return boolean|string
     * 
     * Author: Laurence Charles
     * Date Created : 23/12/2015
     * Date Last Modified: 23/12/2015
     */
    public static function getEmployeeName($personid)
    {
        $employee = Employee::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($employee)
        {
            $full_name = $employee->title . ". " . $employee->firstname . " " . $employee->lastname;
            return $full_name;
        }
        return false;
    }
    
    
    /**
     * Returns the divisionid of an employee
     * 
     * @param type $personid
     * @return boolean|string
     * 
     * Author: Laurence Charles
     * Date Created : 16/01/2016
     * Date Last Modified: 16/01/2016
     */
    public static function getEmployeeDivisionID($personid)
    {
        $employee = EmployeeDivision::find()
                    ->where(['employeeid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($employee)
        {
            return $employee->divisionid;
        }
        return false;
    }
    
    
    /**
     * Returns an associative array is ['personid'=>'fullname']
     * 
     * @param type $employeetitle
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 23/06/2016
     * Date Last Modified: 23/06/2016
     */
    public static function getEmployeeListing($employeetitle)
    {
         $employees = Employee::find()
                 ->innerJoin('employee_title', '`employee`.`employeetitleid` = `employee_title`.`employeetitleid`')
                 ->where(['employee.isactive' => 1, 'employee.isdeleted' => 0,
                                'employee_title.isactive' => 1, 'employee_title.isdeleted' => 0, 'employee_title.name' => $employeetitle
                                ])
                 ->orderBy('lastname')
                 ->all();
         
        if ($employees)
        {
            $keys = array();
            array_push($keys, '');

            $values = array();
            array_push($values, 'Select Emploee...');

           foreach($employees as $employee)
            {
                $key = $employee->personid;
                array_push($keys, $key);
                $value = self::getEmployeeName($employee->personid);
                array_push($values, $value);
            }
         }
        $combined = array_combine($keys, $values);
        return $combined;
    }
    
    
    
    /**
     * Returns an associative array is ['personid'=>'fullname'] of all employees
     * 
     * @param type $employeetitle
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 02/11/2016
     * Date Last Modified: 02/11/2016
     */
    public static function getAllEmployees()
    {
         $employees = Employee::find()
                 ->where(['isactive' => 1, 'isdeleted' => 0])
                 ->orderBy('lastname')
                 ->all();
         
        if ($employees)
        {
            $keys = array();
            array_push($keys, '');

            $values = array();
            array_push($values, 'Select Emploee...');

           foreach($employees as $employee)
           {
               $role = AuthAssignment::find()
                       ->where(['user_id' => $employee->personid ])
                       ->one();
               if ($role == false ||  ($role == true && $role->item_name!="System Administrator"))
               {
                    $key = $employee->personid;
                    array_push($keys, $key);
                    $value = self::getEmployeeName($employee->personid);
                    array_push($values, $value);
               }
            }
        }
       $combined = array_combine($keys, $values);
       return $combined;
    }
    
    
    
}
