<?php

namespace frontend\models;

use Yii;
use frontend\models\LegacyYear;
use frontend\models\LegacyFaculty;

/**
 * This is the model class for table "legacy_student".
 *
 * @property string $legacystudentid
 * @property string $title
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
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
            [['firstname', 'lastname', 'gender', 'middlename'], 'string', 'max' => 100]
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
}
