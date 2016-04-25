<?php

namespace frontend\models;

use Yii;

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
}
