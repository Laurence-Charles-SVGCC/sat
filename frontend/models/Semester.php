<?php

namespace frontend\models;

use Yii;

use frontend\models\AcademicYear;

/**
 * This is the model class for table "semester".
 *
 * @property string $semesterid
 * @property string $academicyearid
 * @property string $title
 * @property string $period
 * @property string $startdate
 * @property string $enddate
 * @property integer $iscurrent
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $publishgradesdate
 *
 * @property CapeCourse[] $capeCourses
 * @property CourseOffering[] $courseOfferings
 * @property AcademicYear $academicyear
 * @property Transaction[] $transactions
 */
class Semester extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'semester';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['academicyearid', 'title', 'startdate', 'enddate', 'period'], 'required'],
            [['academicyearid', 'iscurrent', 'isactive', 'isdeleted'], 'integer'],
            [['startdate', 'enddate'], 'safe'],
            [['title'], 'string', 'max' => 15],
            [['period'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'semesterid' => 'Semesterid',
            'academicyearid' => 'Academicyearid',
            'title' => 'Title',
            'period' => 'Period',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'iscurrent' => 'Iscurrent',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapeCourses()
    {
        return $this->hasMany(CapeCourse::className(), ['semesterid' => 'semesterid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseOfferings()
    {
        return $this->hasMany(CourseOffering::className(), ['semesterid' => 'semesterid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicyear()
    {
        return $this->hasOne(AcademicYear::className(), ['academicyearid' => 'academicyearid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['semesterid' => 'semesterid']);
    }
    
    
    /**
     * Returns an associate array listing semeters [Division|AcademicYear|Title]
     * 
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created: 18/01/2017
     * Date LAst Modified: 18/01/2017
     */
    public static function associativeSemesterListing()
    {
        $semesters = Semester::find()
                ->where(['isdeleted' => 0])
                ->orderBy('semesterid DESC')
                ->all();
        
        $semester_listing = array();
        foreach ($semesters as $semester) 
        {
            $combined = array();
            $keys = array();
            $values = array();
            array_push($keys, "semesterid");
            array_push($keys, "title");
            $k1 = strval($semester->semesterid);
            $division = ApplicantIntent::find()
                     ->innerJoin('academic_year' , '`applicant_intent`.`applicantintentid` = `academic_year`.`applicantintentid`')
                     ->innerJoin('semester' , '`academic_year`.`academicyearid` = `semester`.`academicyearid`')
                    ->where(['applicant_intent.isdeleted' => 0,
                                    'academic_year.isdeleted' => 0,
                                     'semester.semesterid' => $semester->semesterid, 'semester.isdeleted' => 0
                                ])
                    ->one()
                    ->name;
            
            $year = AcademicYear::find()
                     ->innerJoin('semester' , '`academic_year`.`academicyearid` = `semester`.`academicyearid`')
                    ->where(['academic_year.isdeleted' => 0,
                                     'semester.semesterid' => $semester->semesterid, 'semester.isdeleted' => 0
                                ])
                    ->one()
                    ->title;
            $name = $division .  "(" . $year . ")" . " - Sem. " . $semester->title;
            $k2 = strval($name);
            array_push($values, $k1);
            array_push($values, $k2);
            $combined = array_combine($keys, $values);
            array_push($semester_listing, $combined);
            $combined = NULL;
            $keys = NULL;
            $values = NULL;
        }
        return $semester_listing;
    }
    
    
    /**
     * Adds new semester record to array of semesters
     * 
     * @param type $collection
     * @param type $semester
     * @return type Array
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2018_05_07
     * Modified: 2018_05_07
     */
    public static function addNewSemester($collection)
    {
        array_push($collection, new Semester());
        return $collection;
    }
    
    
    /**
     * Determines if record is valid to save
     * 
     * @return boolean
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2018_05_07
     * Modified: 2018_05_07
     */
    public function isValid()
    {
        if (strcmp($this->title, "default") == 0  
                &&  strcmp($this->period, "default") == 0
                &&  strcmp($this->startdate,"1990-01-01") == 0  
                && strcmp($this->enddate,"1990-01-01") ==0)
        {
            return false;
        }
        return true;
    }
}
