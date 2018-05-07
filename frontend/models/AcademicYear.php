<?php

namespace frontend\models;

use Yii;

use frontend\models\ApplicantIntent;

/**
 * This is the model class for table "academic_year".
 *
 * @property string $academicyearid
 * @property string $title
 * @property string applicantintentid
 * @property boolean $iscurrent
 * @property string $startdate
 * @property string $enddate
 * @property integer $isactive
 * @property integer $isdeleted
 */
class AcademicYear extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'academic_year';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'startdate'], 'required'],
            [['applicantintentid', 'iscurrent', 'isactive', 'isdeleted'], 'integer'],
            [['startdate', 'enddate'], 'safe'],
            [['title'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'academicyearid' => 'Academicyearid',
            'title' => 'Title',
            'applicantintentid' => 'Applicant Intent ID',
            'iscurrent' => 'Iscurrent',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
    
    
    /**
     * Returns the title of a particular academic year record
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 08/12/2015
     * Date Last Modified: 08/12/2015
     */
    public static function getYearTitle($id)
    {
        $year = AcademicYear::find()
                ->where(['academicyearid' => $id, 'isactive' => 1 , 'isdeleted' => 0])
                ->one();
        if ($year)
            return $year->title;
        return false;
    }
    
    
    /**
     * Returns a particular academic year record
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 09/12/2015
     * Date Last Modified: 09/12/2015
     */
    public static function getYear($id)
    {
        $year = AcademicYear::find()
                ->where(['academicyearid' => $id, 'isactive' => 1 , 'isdeleted' => 0])
                ->one();
        if ($year)
            return $year;
        return false;
    }
    
    
    /**
     * Returns an associative array of all academic years
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/02/2016
     * Date Last Modified: 09/02/2016
     */
    public static function getAcademicYears()
    {
        $years = AcademicYear::find()
                    ->where(['isactive' => 1, 'isdeleted' => 0])
                    ->all();
        if (count($years) > 0)
        {
            $keys = array();
            array_push($keys, '');

            $values = array();
            array_push($values, 'Select Year...');

            foreach($years as $year)
            {
                $key = strval($year->academicyearid);
                array_push($keys, $key);
                $value = strval($year->title);
                array_push($values, $value);
            }

            $combined = array_combine($keys, $values);
            return $combined;
        }
        return false;
    }
    
    
    
    /**
     * Returns an associative array of the current academic year
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 12/02/2016
     * Date Last Modified: 12/02/2016
     */
    public static function getCurrentAcademicYearPrepared()
    {
        $year = self::getCurrentYear();
                    
        if ($year)
        {
            $keys = array();
            $values = array();

            $key = strval($year->academicyearid);
            array_push($keys, $key);
            $value = strval($year->title);
            array_push($values, $value);

            $combined = array_combine($keys, $values);
            return $combined;
        }
        return false;
    }
    
    
    /**
     * Returns the current academic year
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 11/02/2016
     * Date Last Modified: 11/02/2016
     */
    public static function getCurrentYear()
    {
        $year = AcademicYear::find()
                ->where(['iscurrent' => 1, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        if ($year)
            return $year;
        return false;
    }
    
    
    /**
     * Returns the last created academic year
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 01/03/2016
     * Date Last Modified: 01/03/2016
     */
    public static function getMostRecentlyCreatedYear()
    {
        $years = AcademicYear::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
        $count = count($years);
        if ($count > 0)
        {
            return $years[($count-1)];
        }
        return false;
    }
    
    
    /**
     * Returns an associative array is ['academicyearid'=>'title']
     * that has students enrolled
     * 
     * @param type $employeetitle
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 25/06/2016
     * Date Last Modified: 25/06/2016
     */
    public static function getYearListing()
    {
         $years = AcademicYear::find()
                    ->innerJoin('academic_offering', '`academic_year`.`academicyearid` = `academic_offering`.`academicyearid`')
                    ->innerJoin('student_registration', '`academic_offering`.`academicofferingid` = `student_registration`.`academicofferingid`')
                    ->where(['academic_year.isactive' => 1, 'academic_year.isdeleted' => 0,
                                    'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                    'student_registration.isdeleted' => 0
                                   ])
                    ->all();
         
        if ($years)
        {
            $keys = array();
            array_push($keys, '');

            $values = array();
            array_push($values, 'Select Year...');

           foreach($years as $year)
            {
                $key = $year->academicyearid;
                array_push($keys, $key);
                $year_title = $year->title;
                $year_division = ApplicantIntent::find()
                        ->where(['applicantintentid' => $year->applicantintentid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one()->name;
                $value = $year_title . " (" . $year_division . ")";  
//                $value = $year->title;
                array_push($values, $value);
            }
         }
        $combined = array_combine($keys, $values);
        return $combined;
    }
    
    
    
    /**
     * Returns an associative array is ['academicyearid'=>'title']
     * that has students enrolled
     * 
     * @param type $employeetitle
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 29/01/2017
     * Date Last Modified: 29/01/2017
     */
    public static function getAllAcademicYears()
    {
         $years = AcademicYear::find()
                    ->where(['isactive' => 1, 'isdeleted' => 0])
                    ->all();
         
        $listing = array();
         
        foreach ($years as $year) 
        {
            $combined = array();
            $keys = array();
            $values = array();
            array_push($keys, "academicyearid");
            array_push($keys, "title");
            $year_id = strval($year->academicyearid);
            
            $year_title = $year->title;
            $year_division = ApplicantIntent::find()
                    ->where(['applicantintentid' => $year->applicantintentid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one()->name;
            $year_label = strval($year_title . " (" . $year_division . ")");
            
            array_push($values, $year_id);
            array_push($values, $year_label);
            $combined = array_combine($keys, $values);
            array_push($listing, $combined);
            $combined = NULL;
            $keys = NULL;
            $values = NULL;
        }
        return $listing;
    }
    
    
    
    
    
    /*******************************************************************************************/
    /**
     * Retrieve all active aplication period records
     * 
     * @return [AcademicYear] | []
     * 
     * Author: Laurence Charles
    *  Date Created: 2017_08_24
    *  Date Last Modified: 2017_08_24
     */
    public static function getAllActiveYears()
    {
        $academic_years = AcademicYear::find()
                    ->where(['isactive' => 1,  'isdeleted' => 0])
                    ->all();
        return $academic_years;
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
        if (strcmp($this->title, "default") == 0  &&  strcmp($this->startdate,"1990-01-01") == 0)
        {
            return false;
        }
        return true;
    }
    
}
