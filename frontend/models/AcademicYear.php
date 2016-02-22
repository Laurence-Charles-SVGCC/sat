<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "academic_year".
 *
 * @property string $academicyearid
 * @property string $title
 * @property boolean $iscurrent
 * @property string $startdate
 * @property string $enddate
 * @property boolean $isactive
 * @property boolean $isdeleted
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
            [['iscurrent', 'isactive', 'isdeleted'], 'boolean'],
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
    
}
