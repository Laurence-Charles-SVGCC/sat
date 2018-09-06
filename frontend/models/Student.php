<?php

namespace frontend\models;

use Yii;

use common\models\User;
use frontend\models\StudentRegistration;


/**
 * This is the model class for table "student".
 *
 * @property string $studentid
 * @property string $personid
 * @property string $applicantname
 * @property string $admissiondate
 * @property string $title
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $gender
 * @property string $dateofbirth
 * @property integer $isactive
 * @property integer $isdeleted
 * @property string $email
 *
 * @property Person $person
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['personid', 'applicantname', 'admissiondate', 'firstname', 'lastname', 'gender', 'dateofbirth'], 'required'],
            [['personid'], 'integer'],
            [['admissiondate', 'dateofbirth'], 'safe'],
            [['applicantname', 'firstname', 'middlename', 'lastname', 'email'], 'string', 'max' => 45],
            [['title'], 'string', 'max' => 4],
            [['gender'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studentid' => 'Studentid',
            'personid' => 'Personid',
            'applicantname' => 'Applicant Name',
            'admissiondate' => 'Admissiondate',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'middlename' => 'Middlename',
            'lastname' => 'Lastname',
            'gender' => 'Gender',
            'dateofbirth' => 'Dateofbirth',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'email' => 'Email',
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
     * Returns a 'Student' record based on $personid
     *
     * @param type $personid
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 09/12/2015
     * Date Last Modified: 09/12/2015
     */
    public static function getStudent($personid)
    {
        $student = Student::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        if ($student)
            return $student;
        return false;
    }


    /**
     * Load Student model with data from 'General' view
     *
     * @param type $student_profile
     *
     * Author: Laurence Charles
     * Date Created: 28/12/2015
     * Date Last Modified: 28/12/2015
     */
    public function loadGeneral($student_profile)
    {
        $this->title = $student_profile->title;
        $this->firstname = $student_profile->firstname;
        $this->middlename = $student_profile->middlename;
        $this->lastname = $student_profile->lastname;
        $this->dateofbirth = $student_profile->dateofbirth;
        $this->gender = $student_profile->gender;
    }

   /**
    * Given an array of Student records, function returns true if any of those student
    * are enrolled at the requested division
    *
    * @param type $students
    * @param type $divisionid
    * @return boolean
    */
   public static function checkStudentsDivision($students, $divisionid)
   {
        $count = 0;
        foreach ($students as $student)
        {
            $registration_records = StudentRegistration::getStudentsByDivision($divisionid, $student->personid);
            if (count($registration_records) >0)
                $count++;
        }
        if ($count == 0)
            return false;
        return true;
   }


}
