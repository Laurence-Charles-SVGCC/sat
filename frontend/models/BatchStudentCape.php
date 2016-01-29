<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "batch_student_cape".
 *
 * @property integer $studentregistrationid
 * @property integer $batchcapeid
 * @property string $courseworktotal
 * @property string $examtotal
 * @property string $final
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property StudentRegistration $studentregistration
 * @property BatchCape $batchcape
 */
class BatchStudentCape extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'batch_student_cape';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentregistrationid', 'batchcapeid'], 'required'],
            [['studentregistrationid', 'batchcapeid', 'isactive', 'isdeleted'], 'integer'],
            [['courseworktotal', 'examtotal', 'final'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studentregistrationid' => 'Studentregistrationid',
            'batchcapeid' => 'Batchcapeid',
            'courseworktotal' => 'Courseworktotal',
            'examtotal' => 'Examtotal',
            'final' => 'Final',
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
    public function getBatchcape()
    {
        return $this->hasOne(BatchCape::className(), ['batchcapeid' => 'batchcapeid']);
    }

    
    /**
     * Returns a count of the number of distinct academic years student has grades for.
     * 
     * @param type $studentregistrationid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 14/12/2015
     * Dte Last Modified: 14/12/2015
     */
    public static function getYears($studentregistrationid)
    {
        $db = Yii::$app->db;
        $count = $db->createCommand(
                "SELECT DISTINCT cape_course.semesterid"
                . " FROM batch_student_cape"
                . " JOIN batch_cape"
                . " ON batch_student_cape.batchcapeid = batch_cape.batchcapeid"
                . " JOIN cape_course"
                . " ON batch_cape.capecourseid =cape_course.capecourseid"
                . " JOIN semester"
                . " ON cape_course.semesterid = semester.semesterid"
                . " JOIN academic_year"
                . " ON semester.academicyearid = academic_year.academicyearid"
                . " WHERE batch_student_cape.studentregistrationid = " .  $studentregistrationid
                . ";"    
                )
                ->queryScalar();
        return $count;
    }
    

    /**
     * Returns a count of the number of distinct semesters a student has grades for.
     * 
     * @param type $studentregistrationid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 14/12/2015
     * Dte Last Modified: 14/12/2015
     */
    public static function getSemesterCount($studentregistrationid)
    {
        $db = Yii::$app->db;
        $count = $db->createCommand(
                "SELECT COUNT(DISTINCT cape_course.semesterid)"
                . " FROM batch_student_cape"
                . " JOIN batch_cape"
                . " ON batch_student_cape.batchcapeid = batch_cape.batchcapeid"
                . " JOIN cape_course"
                . " ON batch_cape.capecourseid =cape_course.capecourseid"
                . " JOIN semester"
                . " WHERE batch_student_cape.studentregistrationid = " .  $studentregistrationid
                . ";"    
                )
                ->queryScalar();
        return $count;
    }
    
    
    
    /**
     * Returns and array of associative arrays where each associative array holds a ['semesterid' => $semesterid, 'semester_title'=> $semester.title, 'academic_year_title' =>  $academic_year.title]
     * 
     * @param type $studentregistrationid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 14/12/2015
     * Dte Last Modified: 14/12/2015
     */
    public static function getSemesters($studentregistrationid)
    {
        $db = Yii::$app->db;
        $semesters = $db->createCommand(
                "SELECT DISTINCT cape_course.semesterid"
                . " FROM batch_student_cape"
                . " JOIN batch_cape"
                . " ON batch_student_cape.batchcapeid = batch_cape.batchcapeid"
                . " JOIN cape_course"
                . " ON cape_course.capecourseid = batch_cape.capecourseid"
                . " WHERE batch_student_cape.studentregistrationid = " .  $studentregistrationid
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
     * Date Created: 14/12/2015
     * Date Last Modified: 14/12/2015
     */
    private static function compareSemester($sem1, $sem2)
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
     * Date Created: 14/12/2015
     * Date Last Modified: 14/12/2015
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
     * Returns the number of courses taken in a particular semester
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
                "SELECT COUNT(batch_student_cape.studentregistrationid)"
                . " FROM batch_student_cape"
                . " JOIN batch_cape"
                . " ON batch_student_cape.batchcapeid = batch_cape.batchcapeid"
                . " JOIN cape_course"
                . " ON cape_course.capecourseid = batch_cape.capecourseid"
                . " WHERE cape_course.semesterid = " .  $semesterid
                . " AND batch_student_cape.studentregistrationid = " . $studentregistrationid
                . " AND batch_student_cape.isactive = 1;"      
                )
                ->queryScalar();
        return $count;
    }
    
    /**
     * Returns and array of associative arrays where each associative array hold one modified 'batch_student_cape' record
     * 
     * @param type $studentregistrationid
     * @param type $semesterid
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created: 14/12/2015
     * Date Last Modified: 29/01/2016
     */
    public static function getSemesterRecords($studentregistrationid, $semesterid)
    {
        $db = Yii::$app->db;
        $batch_student_records = $db->createCommand(
                "SELECT batch_cape.batchcapeid AS 'batchid',"
                . " cape_course.coursecode AS 'code',"
                . " cape_course.name AS 'name',"
                . " cape_unit.unitcode AS 'unit',"
                . " cape_subject.subjectname AS 'subject',"
                . " batch_student_cape.courseworktotal AS 'courseworktotal',"
                . " batch_student_cape.examtotal AS 'examtotal',"
                . " batch_student_cape.final AS 'final'"
                . " FROM batch_student_cape"
                . " JOIN batch_cape"
                . " ON batch_student_cape.batchcapeid = batch_cape.batchcapeid"
                . " JOIN cape_course"
                . " ON batch_cape.capecourseid = cape_course.capecourseid"
                . " JOIN cape_unit"
                . " ON cape_course.capeunitid = cape_unit.capeunitid"
                . " JOIN cape_subject"
                . " ON cape_unit.capesubjectid = cape_subject.capesubjectid"
                . " WHERE cape_course.semesterid = " .  $semesterid
                . " AND batch_student_cape.studentregistrationid = " . $studentregistrationid
                . " AND batch_student_cape.isactive = 1;"   
                )
                ->queryAll();
        
        $records_container = array();
        for ($i = 0 ; $i < count($batch_student_records) ; $i++)
        {
            $keys = array();
            array_push($keys, "batchid");
            array_push($keys, "code");
            array_push($keys, "name");
            array_push($keys, "unit");
            array_push($keys, "subject");         
            array_push($keys, "courseworktotal");
            array_push($keys, "examtotal");
            array_push($keys, "final");
            
            $values = array();
            array_push($values, $batch_student_records[$i]["batchid"]);
            array_push($values, $batch_student_records[$i]["code"]);
            array_push($values, $batch_student_records[$i]["name"]);
            array_push($values, $batch_student_records[$i]["unit"]);
            array_push($values, $batch_student_records[$i]["subject"]);
            array_push($values, $batch_student_records[$i]["courseworktotal"]);
            array_push($values, $batch_student_records[$i]["examtotal"]);
            array_push($values, $batch_student_records[$i]["final"]);
            
            $combined = array_combine($keys, $values);
            array_push($records_container, $combined);
            $keys = NULL;
            $values = NULL;
            $combined = NULL;
        }
        return $records_container;
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
                "SELECT batch_student_cape.studentregistrationid AS 'studentregistrationid',"
                . " batch_cape.batchcapeid AS 'batchid',"
                . " cape_course.courseworkweight AS 'courseworkweight',"
                . " cape_course.examweight AS 'examweight',"
                . " cape_course.coursecode AS 'code',"
                . " cape_course.name AS 'name',"
                . " cape_unit.unitcode AS 'unit',"
                . " cape_subject.subjectname AS 'subject',"
                . " batch_student_cape.courseworktotal AS 'courseworktotal',"
                . " batch_student_cape.examtotal AS 'examtotal',"
                . " batch_student_cape.final AS 'final'"
                . " FROM batch_student_cape"
                . " JOIN batch_cape"
                . " ON batch_student_cape.batchcapeid = batch_cape.batchcapeid"
                . " JOIN cape_course"
                . " ON batch_cape.capecourseid = cape_course.capecourseid"
                . " JOIN cape_unit"
                . " ON cape_course.capeunitid = cape_unit.capeunitid"
                . " JOIN cape_subject"
                . " ON cape_unit.capesubjectid = cape_subject.capesubjectid"
                . " WHERE batch_student_cape.batchcapeid = " .  $batchid
                . " AND batch_student_cape.studentregistrationid = " . $studentregistrationid
                . " AND batch_student_cape.isactive = 1;"     
                )
                ->queryOne();
        if ($course_record)
            return $course_record;
        return false;
    }
    
}
