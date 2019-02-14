<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "batch_students".
 *
 * @property integer $studentregistrationid
 * @property integer $batchid
 * @property integer $coursestatusid
 * @property string $courseworktotal
 * @property string $examtotal
 * @property string $final
 * @property string $grade
 * @property string $gradepoints
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property StudentRegistration $studentregistration
 * @property Batch $batch
 * @property CourseStatus $coursestatus
 */
class BatchStudent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'batch_students';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentregistrationid', 'batchid', 'coursestatusid'], 'required'],
            [['studentregistrationid', 'batchid', 'coursestatusid', 'isactive', 'isdeleted'], 'integer'],
            [['courseworktotal', 'examtotal', 'final', 'gradepoints'], 'number'],
            [['grade'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studentregistrationid' => 'Studentregistrationid',
            'batchid' => 'Batchid',
            'coursestatusid' => 'Coursestatusid',
            'courseworktotal' => 'Courseworktotal',
            'examtotal' => 'Examtotal',
            'final' => 'Final',
            'grade' => 'Grade',
            'gradepoints' => 'Gradepoints',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::className(), ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatch()
    {
        return $this->hasOne(Batch::className(), ['batchid' => 'batchid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoursestatus()
    {
        return $this->hasOne(CourseStatus::className(), ['coursestatusid' => 'coursestatusid']);
    }


    /**
     * Returns a count of the number of distinct semesters a student has grades for.
     *
     * @param type $studentregistrationid
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 10/12/2015
     * Dte Last Modified: 10/12/2015
     */
    public static function getSemesterCount($studentregistrationid)
    {
        $db = Yii::$app->db;
        $count = $db->createCommand(
                "SELECT COUNT(DISTINCT course_offering.semesterid)"
                . " FROM batch_students"
                . " JOIN batch"
                . " ON batch_students.batchid = batch.batchid"
                . " JOIN course_offering"
                . " ON batch.courseofferingid = course_offering.courseofferingid"
                . " WHERE batch_students.studentregistrationid = " .  $studentregistrationid
                . ";"
                )
                ->queryScalar();
        return $count;
    }


    /**
     * Returns and array of associative arrays where each associative array holds a ['semesterid' => $semesterid, 'emester_title'=> $semester.title, 'academic_year_title' =>  $academic_year.title]
     *
     * @param type $studentregistrationid
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 11/12/2015
     * Dte Last Modified: 11/12/2015
     */
    public static function getSemesters($studentregistrationid)
    {
        $db = Yii::$app->db;
        $semesters = $db->createCommand(
                "SELECT DISTINCT course_offering.semesterid"
                . " FROM batch_students"
                . " JOIN batch"
                . " ON batch_students.batchid = batch.batchid"
                . " JOIN course_offering"
                . " ON batch.courseofferingid = course_offering.courseofferingid"
                . " WHERE batch_students.studentregistrationid = " .  $studentregistrationid
                . ";"
                )
                ->queryAll();
        if (count ($semesters > 0))
        {
            $semester_info = array();
            for ($i = 0 ; $i < count($semesters) ; $i++)
            {
                $keys = array();
                $values = array();
                $combined = array();

                $year = $db->createCommand(
                    "SELECT semester.semesterid AS 'sem_id', semester.title AS 'sem_title', academic_year.title AS 'year_title'"
                    . " FROM semester"
                    . " JOIN academic_year"
                    . " ON semester.academicyearid = academic_year.academicyearid"
                    . " WHERE semester.semesterid = " .  $semesters[$i]["semesterid"]
                    . ";"
                    )
                    ->queryOne();
                if ($year["sem_id"])
                {
                    array_push($keys, "semester_id");
                    array_push($keys, "semester_title");
                    array_push($keys, "academic_year_title");
                    array_push($values, $year["sem_id"]);
                    array_push($values, $year["sem_title"]);
                    array_push($values, $year["year_title"]);
                    $combined = array_combine($keys, $values);
                    array_push($semester_info,$combined);

                    $keys = NULL;
                    $values = NULL;
                    $combined = NULL;
                }
                else
                {
                    break;
                }
            }
            return $semester_info;
        }
        return false;
    }


    /**
     * Determines if $sem1 < $sem2
     *
     * @param type $sem1
     * @param type $sem2
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 11/12/2015
     * Date Last Modified: 11/12/2015
     */
   public static function compareSemester($sem1, $sem2)
    {
        if (strcmp($sem1["academic_year_title"], $sem2["academic_year_title"]) < 0)
                return true;
        if (strcmp($sem1["academic_year_title"], $sem2["academic_year_title"]) == 0)
        {
            $s1_title = $sem1["semester_title"];
            $s2_title = $sem2["semester_title"];
            if ($s1_title < $s2_title)
            {
                return true;
            }
        }
        return false;
    }


    /**
     * Returns a sorted list of semesters
     *
     * @param type $semester_info
     *
     * Author: Laurence Charles
     * Date Created: 11/12/2015
     * Date Last Modified: 11/12/2015
     */
    public static function sortSemesters($semester_info)
    {
        $min = NULL;
        $array_size = count($semester_info);
        for ($i = 0 ; $i < $array_size-1 ; $i++)
        {
            $min = $i;
            for ($j = 0 ; $j < $array_size ; $j++)
            {
                if (self::compareSemester($semester_info[$j], $semester_info[$j]) == true)
                        $min = $j;
            }
            $temp = $semester_info[$i];
            $semester_info[$i] = $semester_info[$min];
            $semester_info[$min] = $temp;
        }
        return $semester_info;
    }


    /**
     * Returns the number of courses taken in a particular semester by a particular student
     *
     * @param type $studentregistrationid
     * @param type $semesterid
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 10/12/2015
     * Date Last Modified: 29/01/2016
     */
    public static function getCourseCount($studentregistrationid, $semesterid)
    {
        $db = Yii::$app->db;
        $count = $db->createCommand(
//                "SELECT COUNT(course_offering.semesterid)"
                "SELECT COUNT(batch_students.studentregistrationid)"
                . " FROM batch_students"
                . " JOIN batch"
                . " ON batch_students.batchid = batch.batchid"
                . " JOIN course_offering"
                . " ON batch.courseofferingid = course_offering.courseofferingid"
                . " WHERE course_offering.semesterid = " .  $semesterid
                . " AND batch_students.studentregistrationid = " . $studentregistrationid
                . " AND batch_students.isactive = 1;"
                )
                ->queryScalar();
        return $count;
    }


    /**
     * Returns the number of courses that should be considered for GPA calculation
     *
     * @param type $studentregistrationid
     * @param type $semesterid
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 13/01/2016
     * Date Last Modified: 29/01/2016
     */
    public static function getValidCourseCount($studentregistrationid, $semesterid)
    {
        $db = Yii::$app->db;
        $count = $db->createCommand(
//                "SELECT COUNT(course_offering.semesterid)"
                "SELECT COUNT(batch_students.studentregistrationid)"
                . " FROM batch_students"
                . " JOIN batch"
                . " ON batch_students.batchid = batch.batchid"
                . " JOIN course_offering"
                . " ON batch.courseofferingid = course_offering.courseofferingid"
                . " WHERE course_offering.semesterid = " .  $semesterid
                . " AND batch_students.studentregistrationid = " . $studentregistrationid
                . " AND batch_students.examtotal IS NOT NULL"
                . " AND batch_students.courseworktotal IS NOT NULL"
                . " AND batch_students.isactive = 1;"
                )
                ->queryScalar();
        return $count;
    }


    /**
     * Returns and array of associative arrays where each associative array hold one modified 'batch_student' record
     *
     * @param type $studentregistrationid
     * @param type $semesterid
     * @return array
     *
     * Author: Laurence Charles
     * Date Created: 11/12/2015
     * Date Last Modified: 29/01/2016
     */
    public static function getSemesterRecords($studentregistrationid, $semesterid)
    {
        $db = Yii::$app->db;
        $batch_student_records = $db->createCommand(
                "SELECT batch.batchid AS 'batchid',"
                . " batch.batchtypeid AS 'batchtypeid',"
                . " batch.isactive AS 'batch_is_active',"
                . " batch.isdeleted AS 'batch_is_deleted',"
                . " course_type.name AS 'course_type',"
                . " course_offering.courseworkweight AS 'courseworkweight',"
                . " course_offering.examweight AS 'examweight', "
                . " course_offering.passfailtypeid AS 'passfailtypeid',"
                . " course_catalog.coursecode AS 'code',"
                . " course_catalog.name AS 'name',"
                . " course_offering.credits AS 'credits_attempted',"
                . " course_offering.credits AS 'credits_awarded', "
                . " batch_students.courseworktotal AS 'courseworktotal',"
                . " batch_students.examtotal AS 'examtotal',"
                . " batch_students.final AS 'final',"
                . " course_status.abbreviation AS 'course_status', "
                . " batch_students.grade AS 'grade',"
                . " batch_students.qualitypoints AS 'qualitypoints'"
                . " FROM batch_students"
                . " JOIN batch"
                . " ON batch_students.batchid = batch.batchid"
                . " JOIN course_offering"
                . " ON batch.courseofferingid = course_offering.courseofferingid"
                . " JOIN course_status"
                . " ON batch_students.coursestatusid = course_status.coursestatusid"
                . " JOIN course_catalog"
                . " ON course_offering.coursecatalogid = course_catalog.coursecatalogid"
                . " JOIN course_type"
                . " ON course_offering.coursetypeid = course_type.coursetypeid"
                . " WHERE course_offering.semesterid = " .  $semesterid
                . " AND batch_students.studentregistrationid = " . $studentregistrationid
                . " AND batch_students.isactive = 1;"
                )
                ->queryAll();

        $records_container = array();
        for ($i = 0 ; $i < count($batch_student_records) ; $i++)
        {
            $keys = array();
            array_push($keys, "batchid");
            array_push($keys, "batchtypeid");
            array_push($keys, "batch_is_active");
            array_push($keys, "batch_is_deleted");
            array_push($keys, "course_type");
            array_push($keys, "courseworkweight");
            array_push($keys, "examweight");
            array_push($keys, "passfailtypeid");
            array_push($keys, "code");
            array_push($keys, "name");
            array_push($keys, "credits_attempted");
            array_push($keys, "credits_awarded");
            array_push($keys, "courseworktotal");
            array_push($keys, "examtotal");
            array_push($keys, "final");
            array_push($keys, "course_status");
            array_push($keys, "grade");
            array_push($keys, "qualitypoints");

            $values = array();
            array_push($values, $batch_student_records[$i]["batchid"]);
            array_push($values, $batch_student_records[$i]["batchtypeid"]);
            array_push($values, $batch_student_records[$i]["batch_is_active"]);
            array_push($values, $batch_student_records[$i]["batch_is_deleted"]);
            array_push($values, $batch_student_records[$i]["course_type"]);
            array_push($values, $batch_student_records[$i]["courseworkweight"]);
            array_push($values, $batch_student_records[$i]["examweight"]);
            array_push($values, $batch_student_records[$i]["passfailtypeid"]);
            array_push($values, $batch_student_records[$i]["code"]);

            if ($batch_student_records[$i]["batchtypeid"] == 1)
            {
                $batch_name = $batch_student_records[$i]["name"];
            }
            elseif ($batch_student_records[$i]["batchtypeid"] == 2)
            {
                $batch_name = $batch_student_records[$i]["name"] . " - RESIT";
            }
            elseif ($batch_student_records[$i]["batchtypeid"] == 3)
            {
                $batch_name = $batch_student_records[$i]["name"] . " - SUPPLEMENTAL";
            }
            elseif ($batch_student_records[$i]["batchtypeid"]  == 4)
            {
                $batch_name = $batch_student_records[$i]["name"] . " - EXEMPTION";
            }
            elseif ($batch_student_records[$i]["batchtypeid"]  == 5)
            {
                $batch_name = $batch_student_records[$i]["name"] . " - ABSENT";
            }
            array_push($values, $batch_name);

            array_push($values, $batch_student_records[$i]["credits_attempted"]);
            array_push($values, $batch_student_records[$i]["credits_awarded"]);
            array_push($values, $batch_student_records[$i]["courseworktotal"]);
            array_push($values, $batch_student_records[$i]["examtotal"]);
            array_push($values, $batch_student_records[$i]["final"]);
            array_push($values, $batch_student_records[$i]["course_status"]);
            array_push($values, $batch_student_records[$i]["grade"]);
            array_push($values, $batch_student_records[$i]["qualitypoints"]);

            $combined = array_combine($keys, $values);
            array_push($records_container, $combined);
            $keys = NULL;
            $values = NULL;
            $combined = NULL;
        }
        return $records_container;
    }


    /**
     * Returns the GPA of a student for a particular semester
     *
     * @param type $studentregistrationid
     * @param type $semesterid
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 28/01/2016
     * Date Last Modified: 28/01/2016
     */
    public static function getSemesterGPA($studentregistrationid, $semesterid)
    {
        $semester_results = self::getSemesterRecords($studentregistrationid, $semesterid);
        $gradepoints_sum = 0;
        $credits_sum = 0;
        $semester_gpa = "Pending";

        $count = count($semester_results);

        for ($i=0 ; $i<$count ; $i++)
        {
            $grade_points = $semester_results[$i]['credits_attempted'] * $semester_results[$i]['qualitypoints'];
//            $gradepoints_sum += $grade_points;
            if (strcmp($semester_results[$i]['course_status'],'INC') != 0  && strcmp($semester_results[$i]['course_status'],'UN') != 0
                && ($semester_results[$i]['passfailtypeid'] == 1 || $semester_results[$i]['passfailtypeid'] == 3))
            {
                $gradepoints_sum += $grade_points;
                $credits_sum += $semester_results[$i]["credits_awarded"];
            }
        }

        if($gradepoints_sum!=0  && $credits_sum!=0)
            $semester_gpa = round(($gradepoints_sum/$credits_sum), 2);

        return $semester_gpa;
    }


    /**
     * Returns a summary of a course information
     *
     * @param type $studentregistrationid
     * @param type $batchid
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 16/12/2015
     * Date Last Modified: 16/12/2015
     */
    public static function getCourseRecord($studentregistrationid, $batchid)
    {
        $db = Yii::$app->db;
        $course_record = $db->createCommand(
                "SELECT batch_students.studentregistrationid AS 'studentregistrationid',"
                . " batch.batchid AS 'batchid',"
                . " course_offering.coursetypeid AS 'coursetypeid',"
                . " course_offering.passcriteriaid AS 'passcriteriaid',"
                . " course_offering.passfailtypeid AS 'passfailtypeid',"
                . " course_offering.passmark AS 'passmark',"
                . " course_offering.courseworkweight AS 'courseworkweight',"
                . " course_offering.examweight AS 'examweight', "
                . " course_catalog.coursecode AS 'code',"
                . " course_catalog.name AS 'name',"
                . " course_offering.credits AS 'credits_attempted',"
                . " course_offering.credits AS 'credits_awarded', "
                . " batch_students.courseworktotal AS 'courseworktotal',"
                . " batch_students.examtotal AS 'examtotal',"
                . " batch_students.final AS 'final',"
                . " course_status.abbreviation AS 'course_status', "
                . " batch_students.grade AS 'grade',"
                . " batch_students.qualitypoints AS 'qualitypoints'"
                . " FROM batch_students"
                . " JOIN batch"
                . " ON batch_students.batchid = batch.batchid"
                . " JOIN course_offering"
                . " ON batch.courseofferingid = course_offering.courseofferingid"
                . " JOIN course_status"
                . " ON batch_students.coursestatusid = course_status.coursestatusid"
                . " JOIN course_catalog"
                . " ON course_offering.coursecatalogid = course_catalog.coursecatalogid"
                . " WHERE batch_students.batchid = " .  $batchid
                . " AND batch_students.studentregistrationid = " . $studentregistrationid
                . " AND batch_students.isactive = 1;"
                )
                ->queryOne();
        if ($course_record)
            return $course_record;
        return false;
    }


    /**
     * Return true if student has failed course
     *
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 03/08/2017
     * Date Last Modified: 03/08/2017
     */
    public function was_failed()
    {
        if ($this->grade == "F" || $this->grade == "INC")
        {
            return true;
        }
        return false;
    }



}
