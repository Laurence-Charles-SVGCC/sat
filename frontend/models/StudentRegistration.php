<?php

namespace frontend\models;

use Yii;
use common\models\User;
use frontend\models\BatchStudent;
use frontend\models\BatchStudentCape;
use frontend\models\Hold;

/**
 * This is the model class for table "student_registration".
 *
 * @property string $studentregistrationid
 * @property string $offerid
 * @property string $personid
 * @property string $academicofferingid
 * @property string $registrationtypeid
 * @property string $studentstatusid
 * @property string $academicstatusid
 * @property string $currentlevel
 * @property string $registrationdate
 * @property integer $receivedpicture
 * @property integer $cardready
 * @property integer $cardcollected
 * @property integer $iscurrent
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 * @property AcademicOffering $academicoffering
 * @property RegistrationType $registrationtype
 * @property StudentStatus $studentstatus
 * @property AcademicStatus $academicstatus
 */
class StudentRegistration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_registration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['offerid', 'personid', 'academicofferingid', 'registrationtypeid', 'currentlevel', 'registrationdate'], 'required'],
            [['offerid', 'personid', 'academicofferingid', 'registrationtypeid', 'studentstatusid', 'academicstatusid', 'currentlevel', 'receivedpicture', 'cardready', 'cardcollected', 'iscurrent', 'isactive', 'isdeleted'], 'integer'],
            [['registrationdate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studentregistrationid' => 'Studentregistrationid',
            'offerid' => 'Offerid',
            'personid' => 'Personid',
            'academicofferingid' => 'Academicofferingid',
            'registrationtypeid' => 'Registrationtypeid',
            'studentstatusid' => 'Studentstatusid',
            'academicstatusid' => 'Academicstatusid',
            'currentlevel' => 'Currentlevel',
            'registrationdate' => 'Registrationdate',
            'receivedpicture' => 'Receivedpicture',
            'cardready' => 'Cardready',
            'cardcollected' => 'Cardcollected',
            'iscurrent' => 'Is Current',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
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
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicoffering()
    {
        return $this->hasOne(AcademicOffering::className(), ['academicofferingid' => 'academicofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrationtype()
    {
        return $this->hasOne(RegistrationType::className(), ['registrationtypeid' => 'registrationtypeid']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentstatus()
    {
        return $this->hasOne(StudentStatus::className(), ['studentstatusid' => 'studentstatusid']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicstatus()
    {
        return $this->hasOne(AcademicStatus::className(), ['academicstatusid' => 'academicstatusid']);
    }
    
    /**
     * Returns all student registration records for a particular academicoffering
     * 
     * @param type $academicofferingid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 09/12/2015
     * Date Last Modifed: 09/12/2015
     */
    public static function getStudentRegistration($academicofferingid)
    {
        $registrations = StudentRegistration::find()
                    ->where(['academicofferingid' => $academicofferingid, 'isdeleted' => 0])
                    ->all();
        if (count($registrations) > 0)
            return $registrations;
        else
            return false;
    }

    
    /**
     * Determines if a 'student_registration' record is associated with a CAPE programme
     * 
     * @param type $studentregistrationid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 10/12/2015
     * Date Last Modified: 10/12/2015
     */
    public static function isCape($studentregistrationid)
    {
        $db = Yii::$app->db;
        $records = $db->createCommand(
                    "SELECT * "
                    . " FROM student_registration"
                    . " JOIN academic_offering"
                    . " ON student_registration.academicofferingid = academic_offering.academicofferingid"
                    . " JOIN programme_catalog"
                    . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                    . " WHERE student_registration.studentregistrationid = ". $studentregistrationid
                    . " AND programme_catalog.name = 'CAPE'"
                    . " AND student_registration.isdeleted = 0;"
                    )
                    ->queryAll();
        if (count($records) > 0)
            return true;
        return false;
    }
    
    
    /**
     * Returns a students cumulative GPA
     * 
     * @param type $studentregistrationid
     * @return int
     * 
     * Author: Laurence Charles
     * Date Created:17/12/215
     * Date Last Modified: 14/01/2016 | 03/02/2016
     */
    public static function calculateCumulativeGPA($studentregistrationid)
    {
        $cumulative_gpa = "Pending";
        $gradepoints_sum = 0;
        $credits_sum = 0;
        
        $db = Yii::$app->db;
        $records = $db->createCommand(
                "SELECT course_offering.passfailtypeid AS 'passfailtypeid',"
                . " course_type.name AS 'course_type',"
                . " course_offering.credits AS 'credits',"
                . " batch_students.coursestatusid AS 'course_status',"
                . " batch_students.qualitypoints AS 'qualitypoints'"
                . " FROM student_registration"
                . " JOIN batch_students"
                . " ON student_registration.studentregistrationid = batch_students.studentregistrationid"
                . " JOIN batch"
                . " ON batch_students.batchid = batch.batchid"
                . " JOIN course_offering"
                . " ON batch.courseofferingid = course_offering.courseofferingid"
                . " JOIN course_type"
                . " ON course_offering.coursetypeid = course_type.coursetypeid"
                . " WHERE batch_students.studentregistrationid = ". $studentregistrationid
                . ";"
                )
                ->queryAll();
        
        $records_count = count($records);
        if ($records_count > 0)
        {
            for ($i = 0 ; $i < $records_count ; $i++)
            {
                $grade_points = $records[$i]['credits'] * $records[$i]['qualitypoints'];
//                $gradepoints_sum += $grade_points;
                if (strcmp($records[$i]['course_status'],'INC') != 0  && strcmp($records[$i]['course_status'],'UN') != 0
                    &&  ($records[$i]['passfailtypeid'] == 1 || $records[$i]['passfailtypeid'] == 3))
                {
                   $gradepoints_sum += $grade_points;
                   $credits_sum += $records[$i]["credits"]; 
                }
            }
            if($gradepoints_sum!=0  && $credits_sum!=0)
                $cumulative_gpa = round(($gradepoints_sum/$credits_sum), 2);
        } 
        return $cumulative_gpa;
    }
    
    
    /**
     * 
     * @param type $studentregistrationid
     * @return string
     * 
     * Author: Laurence Charles
     * Date Created: 
     */
    public static function getAcademicStatusName($studentregistrationid)
    {
        $record = StudentRegistration::find()
                ->where(['studentregistrationid' => $studentregistrationid, 'isdeleted' => 0])
                ->one();
        if($record)
        {
            $status = AcademicStatus::find()
                    ->where(['academicstatusid' => $record->academicstatusid])
                    ->one();
           if ($status)
               return $status->name;
           else 
               return "Pending";
        }
    }
    
    
    public static function getUpdatedAcademicStatus($studentregistrationid)
    {
        $status = "Good";
        
        $warning_hold = Hold::find()
                ->where(['studentregistrationid' => $studentregistrationid, 'holdtypeid' => 6,  'isactive' => 1, 'isdeleted' => 0])
                ->all();
        
        $probation_hold = Hold::find()
                ->where(['studentregistrationid' => $studentregistrationid, 'holdtypeid' => 7,  'isactive' => 1, 'isdeleted' => 0])
                ->all();
        
        if ($probation_hold)
        {
            $status = "Academic Probation";
        }
        elseif ($warning_hold)
        {
             $status = "Academic Warning";
        }
        
        return $status;
    }
    
    
    /**
     * Returns an associative array of active academic hold records
     * 
     * @param type $divisionid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created:07/01/2016
     * Date Last Modified: 07/01/2016 | 11/03/2016
     */
    public static function getAcademicActiveHolds($divisionid)
    {
        $db = Yii::$app->db;
        $records = array();
        
        if ($divisionid == 1)           //if all divisions
        {
            $records = $db->createCommand(
                    "SELECT student_hold.studentholdid AS 'studentholdid',"
                    . " student_registration.studentregistrationid AS 'studentregistrationid',"
                    . " student.personid AS 'personid',"
                    . " person.username AS 'studentid',"
                    . " student.firstname AS 'firstname',"
                    . " student.lastname AS 'lastname',"
                    . " CONCAT(qualification_type.abbreviation, ' ', programme_catalog.name, ' ', programme_catalog.specialisation)AS 'programme',"
                    . " hold_type.name AS 'holdtype',"
                    . " student_hold.wasnotified AS 'wasnotified'"
                    . " FROM student_registration"
                    . " JOIN student"
                    . " ON student_registration.personid = student.personid"
                    . " JOIN person"
                    . " ON student_registration.personid = person.personid"
                    . " JOIN academic_offering"
                    . " ON student_registration.academicofferingid = academic_offering.academicofferingid"
                    . " JOIN programme_catalog"
                    . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                    . " JOIN student_hold"
                    . " ON student_registration.studentregistrationid = student_hold.studentregistrationid"
                    . " JOIN hold_type"
                    . " ON student_hold.holdtypeid = hold_type.holdtypeid"
                    . " JOIN qualification_type"
                    . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                    . " WHERE student_registration.isdeleted = 0"
                    . " AND student_hold.holdstatus = 1"
                    . " AND student_hold.isactive = 1"
                    . " AND student_hold.isdeleted = 0"
                    . " AND hold_type.holdtypeid IN (6,7);"
                    )
                    ->queryAll();
        }
        else
        {
            $records = $db->createCommand(
                    "SELECT student_hold.studentholdid AS 'studentholdid',"
                    . " student_registration.studentregistrationid AS 'studentregistrationid',"
                    . " student.personid AS 'personid',"
                    . " person.username AS 'studentid',"
                    . " student.firstname AS 'firstname',"
                    . " student.lastname AS 'lastname',"
                    . " CONCAT(qualification_type.abbreviation, ' ', programme_catalog.name, ' ', programme_catalog.specialisation)AS 'programme',"
                    . " hold_type.name AS 'holdtype',"
                    . " student_hold.wasnotified AS 'wasnotified'"
                    . " FROM student_registration"
                    . " JOIN student"
                    . " ON student_registration.personid = student.personid"
                    . " JOIN person"
                    . " ON student_registration.personid = person.personid"
                    . " JOIN academic_offering"
                    . " ON student_registration.academicofferingid = academic_offering.academicofferingid"
                    . " JOIN programme_catalog"
                    . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                    . " JOIN student_hold"
                    . " ON student_registration.studentregistrationid = student_hold.studentregistrationid"
                    . " JOIN hold_type"
                    . " ON student_hold.holdtypeid = hold_type.holdtypeid"
                    . " JOIN qualification_type"
                    . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                    . " JOIN department"
                    . " ON programme_catalog.departmentid = department.departmentid"
                    . " WHERE department.divisionid = ". $divisionid
                    . " AND student_registration.isdeleted = 0"
                    . " AND student_hold.holdstatus = 1"
                    . " AND student_hold.isactive = 1"
                    . " AND student_hold.isdeleted = 0"
                    . " AND hold_type.holdtypeid IN (6,7);"
                    )
                    ->queryAll();
        }
        
        return $records;     
    }
    
    
    /**
     * Determines if student has grade records 
     * 
     * @param type $studentregistrationid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created:09/01/2016
     * Date Last Modified: 09/01/2016
     */
    public static function hasGradeRecords($studentregistrationid)
    {
       $is_cape = self::isCape($studentregistrationid);
       if ($is_cape == true)
       {
           $records = BatchStudentCape::find()
                   ->where(['studentregistrationid' => $studentregistrationid, 'isactive'=>1, 'isdeleted' => 0])
                   ->all();
       }
       else
       {
           $records = BatchStudent::find()
                   ->where(['studentregistrationid' => $studentregistrationid, 'isactive'=>1, 'isdeleted' => 0])
                   ->all();
       }
       
       if (count($records)>0)
           return true;
       return false;
   }
   
   
   /**
    * Returns programme details
    * 
    * @param type $studentregistrationid
    * @return string
    * 
    * Author: Laurence Charles
    * Date Created:10/01/2016
    * Date Last Modified: 10/01/2016
    */
   public static function getProgrammeDetails($studentregistrationid)
   {
        $db = Yii::$app->db;
        $record = $db->createCommand(
                    "SELECT student_registration.studentregistrationid AS 'studentregistrationid',"
                    . " qualification_type.name AS 'qualification',"
                    . " programme_catalog.name AS 'programmename',"
                    . " programme_catalog.specialisation AS 'specialisation'"
                    . " FROM student_registration"
                    . " JOIN academic_offering"
                    . " ON student_registration.academicofferingid = academic_offering.academicofferingid"
                    . " JOIN programme_catalog"
                    . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                    . " JOIN qualification_type"
                    . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                    . " WHERE student_registration.studentregistrationid = ". $studentregistrationid
                    . " AND student_registration.isdeleted = 0;"
                    )
                    ->queryOne();
        if ($record)
            return $record;
        return false;
   }
   
   
   /**
    * Returns an array of student registration records based on division of enrollment
    * 
    * @param type $divisionid
    * @return type
    * 
    * Author: Laurence Charles
    * Date Created:16/01/2016
    * Date Last Modified: 16/01/2016 | 03/02/2016
    */
    public static function getStudentsByDivision($divisionid, $personid)
    {
        $registrations = array();
        
        //returns student_registration records with undeleted offers
        $registrations = StudentRegistration::find()
                    ->innerJoin('offer', '`student_registration`.`offerid` = `offer`.`offerid`')
                    ->innerJoin('application', '`offer`.`applicationid` = `application`.`applicationid`')
                    ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                    ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->innerJoin('department', '`programme_catalog`.`departmentid` = `department`.`departmentid`')
                    ->where(['student_registration.personid' => $personid, 'student_registration.isdeleted' => 0, 'student_registration.isactive' => 1,
                                    'offer.isdeleted' => 0, 'department.divisionid' => $divisionid
                                ])
                    ->all();
        
        return $registrations;
   }
   
   
   /**
    * Returns the division associated with a 'Student Registration' record
    * 
    * @param type $studentregistrationid
    * @return type
    * 
    * Author: Laurence Charles
    * Date Created: 14/09/2016
    * Date Last Modifieid: 14/09/2016
    */
   public static function getStudentDivision($studentregistrationid)
   {
       $application = Application::find()
               ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
               ->innerJoin('student_registration', '`offer`.`offerid` = `student_registration`.`offerid`')
               ->where(['application.isactive' => 1, 'application.isdeleted' => 0,
                                'offer.isactive' => 1, 'offer.isdeleted' => 0,
                                'student_registration.studentregistrationid' => $studentregistrationid, 'student_registration.isdeleted' => 0])
               ->one();
       if ($application)
       {
           return $application->divisionid;
       }
       return false;
    }
    
}
