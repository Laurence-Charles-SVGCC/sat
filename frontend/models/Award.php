<?php

namespace frontend\models;

use Yii;
use frontend\models\PersonAward;

/**
 * This is the model class for table "award".
 *
 * @property integer $awardid
 * @property integer $awardcategoryid
 * @property integer $awardtypeid
 * @property integer $awardscopeid
 * @property string $name
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $academicyearid
 * @property integer $semesterid
 * @property integer $divisionid
 * @property integer $departmentid
 * @property integer $programmecatalogid
 * @property integer $subject
 *
 * @property Semester $semester
 * @property AwardCategory $awardcategory
 * @property AwardType $awardtype
 * @property Division $division
 * @property Department $department
 * @property ProgrammeCatalog $programmecatalog
 * @property AcademicYear $academicyear
 * @property PersonAward[] $personAwards
 */
class Award extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'award';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['awardcategoryid', 'awardtypeid', 'awardscopeid', 'name', 'description'], 'required'],
            [['awardcategoryid', 'awardtypeid', 'awardscopeid', 'isactive', 'isdeleted', 'academicyearid', 'semesterid', 'divisionid', 'departmentid', 'programmecatalogid'], 'integer'],
            [['description', 'subject'], 'string'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'awardid' => 'Awardid',
            'awardcategoryid' => 'Awardcategoryid',
            'awardtypeid' => 'Awardtypeid',
            'awardscopeid' => 'Awardscopeid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'academicyearid' => 'Academicyearid',
            'semesterid' => 'Semesterid',
            'divisionid' => 'Divisionid',
            'departmentid' => 'Departmentid',
            'programmecatalogid' => 'Programmecatalogid',
            'subject' => 'Subject',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemester()
    {
        return $this->hasOne(Semester::className(), ['semesterid' => 'semesterid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAwardcategory()
    {
        return $this->hasOne(AwardCategory::className(), ['awardcategoryid' => 'awardcategoryid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAwardtype()
    {
        return $this->hasOne(AwardType::className(), ['awardtypeid' => 'awardtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::className(), ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['departmentid' => 'departmentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammecatalog()
    {
        return $this->hasOne(ProgrammeCatalog::className(), ['programmecatalogid' => 'programmecatalogid']);
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
    public function getPersonAwards()
    {
        return $this->hasMany(PersonAward::className(), ['awardid' => 'awardid']);
    }
    
    
    /**
     * Returns a 'person_award' record if it exists
     * 
     * @param type $awardid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 25/04/2016
     * Date Last Modified: 25/04/2016
     */
    public static function isAssigned($awardid)
    {
        $assignment = PersonAward::find()
                    ->where(['awardid' => $awardid])
                    ->one();
        if($assignment)
            return $assignment;
        return false;
    }
    
    /**
     * Returns array of 'person_award' records if it exists
     * 
     * @param type $awardid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 25/04/2016
     * Date Last Modified: 25/04/2016
     */
    public static function getAwardees($awardid)
    {
        $assignments = PersonAward::find()
                    ->where(['awardid' => $awardid])
                    ->all();
        if($assignments)
            return $assignments;
        return false;
    }
}
