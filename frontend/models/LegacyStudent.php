<?php

namespace frontend\models;

use Yii;
use frontend\models\LegacyYear;
use frontend\models\LegacyFaculty;
use frontend\models\LegacyBatch;

/**
 * This is the model class for table "legacy_student".
 *
 * @property string $legacystudentid
 * @property string $title
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $fullname
 * @property string $dateofbirth
 * @property string $address
 * @property string $gender
 * @property string $legacyyearid
 * @property string $legacyfacultyid
 * @property string $createdby
 * @property string $datecreated
 * @property string $lastmodifiedby
 * @property string $datemodified
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property LegacyMarksheet[] $legacyMarksheets
 * @property LegacyYear $legacyyear
 * @property LegacyFaculty $legacyfaculty
 * @property Person $createdby
 * @property Person $lastmodifiedby
 */
class LegacyStudent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'legacy_student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'firstname', 'lastname', 'gender', 'legacyyearid', 'legacyfacultyid', 'createdby', 'datecreated', 'lastmodifiedby', 'datemodified'], 'required'],
            [['legacyyearid', 'legacyfacultyid', 'createdby', 'lastmodifiedby', 'isactive', 'isdeleted'], 'integer'],
            [['datecreated', 'datemodified', 'dateofbirth'], 'safe'],
            [['address'], 'string'],
            [['title'], 'string', 'max' => 4],
            [['firstname', 'lastname', 'gender', 'middlename'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'legacystudentid' => 'Legacystudentid',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'middlename' => 'Middlename',
            'lastname' => 'Lastname',
            'fullname' => 'Fullname',
            'gender' => 'Gender',
            'legacyyearid' => 'Legacyyearid',
            'legacyfacultyid' => 'Legacyfacultyid',
            'createdby' => 'Createdby',
            'datecreated' => 'Datecreated',
            'lastmodifiedby' => 'Lastmodifiedby',
            'datemodified' => 'Datemodified',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacyMarksheets()
    {
        return $this->hasMany(LegacyMarksheet::className(), ['legacystudentid' => 'legacystudentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacyyear()
    {
        return $this->hasOne(LegacyYear::className(), ['legacyyearid' => 'legacyyearid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacyfaculty()
    {
        return $this->hasOne(LegacyFaculty::className(), ['legacyfacultyid' => 'legacyfacultyid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedby()
    {
        return $this->hasOne(Person::className(), ['personid' => 'createdby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastmodifiedby()
    {
        return $this->hasOne(Person::className(), ['personid' => 'lastmodifiedby']);
    }
    
    
    /**
     * Returns true is record is a dummy record,
     * the dummy records are generated in javascript to facilitate a successful validation check
     * when submitting data on the 'batch studend
     * 
     * @param type $legacy_student
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 09/07/2016
     * Date Created: 09/07/2016
     */
    public static function isDummyRecord($legacy_student)
    {
        $years = LegacyYear::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
        $first_year_record = $years[0]->name;
        $selected_year = LegacyYear::find()
                ->where(['legacyyearid' => $legacy_student->legacyyearid, 'isactive' => 1, 'isdeleted' => 0])
                ->one()
                ->name;
        
        $faculties = LegacyFaculty::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
        $first_faculty_record = $faculties[0]->name;
        $selected_faculty = LegacyFaculty::find()
                ->where(['legacyfacultyid' => $legacy_student->legacyfacultyid, 'isactive' => 1, 'isdeleted' => 0])
                ->one()
                ->name;
        
        if ($legacy_student->title=='Mr'  && $legacy_student->firstname=='default' && /*$legacy_student->middlename=='default'  &&*/ 
                $legacy_student->lastname=='default' && /*$legacy_student->dateofbirth=='2000-01-01' && $legacy_student->address=='default'  &&*/ 
                $legacy_student->gender=='Male' && $selected_year==$first_year_record && $selected_faculty==$first_faculty_record)
            return true;
        return false;
    }
    
    
    
    public static function prepareEligibleStudentsListing($batchid)
    {
        $batch = LegacyBatch::find()
                ->where(['legacybatchid' => $batchid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        
        $yearid = LegacyTerm::find()
                ->where(['legacytermid' => $batch->legacytermid, 'isactive' => 1, 'isdeleted' => 0])
                ->one()
                ->legacyyearid;
        
        $possible_years = array();
        $target_year = LegacyYear::find()
                ->where(['legacyyearid' => $yearid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();

        $possible_year = intval(substr($target_year->name, 0, 4));

        $minus_two = $possible_year - 2;
        $minus_two_year = $minus_two . "/" . $minus_two+1;
        $target_year_minus_two = LegacyYear::find()
                ->where(['name' =>$minus_two_year , 'isactive' => 1 , 'isdeleted' => 0])
                ->one();
         if ($target_year_minus_two)
             $possible_years[] = $target_year_minus_two->legacyyearid;
        
        $minus_one = ($possible_year - 1);
        $minus_one_year = $minus_one . "/" . $minus_one+1;
        $target_year_minus_one = LegacyYear::find()
                ->where(['name' => $minus_one_year , 'isactive' => 1 , 'isdeleted' => 0])
                ->one();
        if ($target_year_minus_one)
             $possible_years[] = $target_year_minus_one->legacyyearid; 
        
        $possible_years[] = $yearid;
        
        $plus_one = ($possible_year + 1);
        $plus_one_year = $plus_one . "/" . $plus_one+1;
        $target_year_plus_one = LegacyYear::find()
                ->where(['name' => $plus_one_year , 'isactive' => 1 , 'isdeleted' => 0])
                ->one();
        if ($target_year_plus_one)
             $possible_years[] = $target_year_plus_one->legacyyearid;

        $plus_two = $possible_year + 2;
        $plus_two_year = $plus_two . "/" . $plus_two+1;
        $target_year_plus_two = LegacyYear::find()
                ->where(['name' =>$plus_two_year , 'isactive' => 1 , 'isdeleted' => 0])
                ->one();
         if ($target_year_plus_two)
             $possible_years[] = $target_year_plus_two->legacyyearid;

        $students = LegacyStudent::find()
                ->where(['legacyyearid' => $possible_years , 'isactive' => 1 , 'isdeleted' => 0])
                ->orderBy('lastname ASC', 'firstname ASC')
                ->all();
        
        $listing = array();
        foreach ($students as $student) 
        {
            $marksheet = LegacyMarksheet::find()
                    ->where(['legacystudentid' => $student->legacystudentid, 'legacybatchid' => $batchid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            
            $lower_six_marksheets = LegacyMarksheet::find()
                    ->innerJoin(['legacy_batch', '`legacy_marksheet`.`legacybatchid` = `legacy_batch`.`legacybatchid`'])
                    ->where(['legacy_marksheet.legacystudentid' => $student->legacystudentid,  'legacy_marksheet.isactive' => 1, 'legacy_marksheet.isdeleted' => 0,
                                     'legacy_batch.legacylevelid' => 1, 'legacy_batch.isactive' => 1, 'legacy_batch.isdeleted' => 0])
                    ->count();
             $upper_six_marksheets = LegacyMarksheet::find()
                    ->innerJoin(['legacy_batch', '`legacy_marksheet`.`legacybatchid` = `legacy_batch`.`legacybatchid`'])
                    ->where(['legacy_marksheet.legacystudentid' => $student->legacystudentid,  'legacy_marksheet.isactive' => 1, 'legacy_marksheet.isdeleted' => 0,
                                     'legacy_batch.legacylevelid' => 1, 'legacy_batch.isactive' => 2, 'legacy_batch.isdeleted' => 0])
                    ->count();
            
            if ($marksheet == false && (($lower_six_marksheets == 0  && $upper_six_marksheets == 0)  ||   $lower_six_marksheets != $upper_six_marksheets))
            {
                $combined = array();
                $keys = array();
                $values = array();
                array_push($keys, "legacystudentid");
                array_push($keys, "fullname");
                $k1 = strval($student->legacystudentid);
                $k2 = strval($student->fullname);
                array_push($values, $k1);
                array_push($values, $k2);
                $combined = array_combine($keys, $values);
                array_push($listing, $combined);
                $combined = NULL;
                $keys = NULL;
                $values = NULL;
            }
        }
        return $listing;
    }
}
