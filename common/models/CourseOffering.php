<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "course_offering".
 *
 * @property integer $courseofferingid
 * @property integer $coursecatalogid
 * @property integer $academicofferingid
 * @property integer $coursetypeid
 * @property integer $passcriteriaid
 * @property integer $semesterid
 * @property integer $prerequisiteid
 * @property integer $passfailtypeid
 * @property string $courseworkweight
 * @property string $examweight
 * @property string $passmark
 * @property integer $credits
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $mdlgroupid
 * @property integer $createdby
 * @property integer $lastupdatedby
 *
 * @property Batch[] $batches
 * @property Cordinator[] $cordinators
 * @property CourseCatalog $coursecatalog
 * @property AcademicOffering $academicoffering
 * @property CourseType $coursetype
 * @property PassCriteria $passcriteria
 * @property Semester $semester
 * @property PassFailType $passfailtype
 * @property CourseOffering $prerequisite
 * @property CourseOffering[] $courseOfferings
 * @property User $createdby0
 * @property User $lastupdatedby0
 */
class CourseOffering extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_offering';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coursecatalogid', 'academicofferingid', 'coursetypeid', 'passcriteriaid', 'semesterid', 'courseworkweight', 'examweight', 'passmark'], 'required'],
            [['coursecatalogid', 'academicofferingid', 'coursetypeid', 'passcriteriaid', 'semesterid', 'prerequisiteid', 'passfailtypeid', 'credits', 'isactive', 'isdeleted', 'mdlgroupid', 'createdby', 'lastupdatedby'], 'integer'],
            [['courseworkweight', 'examweight', 'passmark'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'courseofferingid' => 'Courseofferingid',
            'coursecatalogid' => 'Coursecatalogid',
            'academicofferingid' => 'Academicofferingid',
            'coursetypeid' => 'Coursetypeid',
            'passcriteriaid' => 'Passcriteriaid',
            'semesterid' => 'Semesterid',
            'prerequisiteid' => 'Prerequisiteid',
            'passfailtypeid' => 'Passfailtypeid',
            'courseworkweight' => 'Courseworkweight',
            'examweight' => 'Examweight',
            'passmark' => 'Passmark',
            'credits' => 'Credits',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'mdlgroupid' => 'Mdlgroupid',
            'createdby' => 'Createdby',
            'lastupdatedby' => 'Lastupdatedby',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatches()
    {
        return $this->hasMany(Batch::class, ['courseofferingid' => 'courseofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCordinators()
    {
        return $this->hasMany(Cordinator::class, ['courseofferingid' => 'courseofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoursecatalog()
    {
        return $this->hasOne(CourseCatalog::class, ['coursecatalogid' => 'coursecatalogid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicoffering()
    {
        return $this->hasOne(AcademicOffering::class, ['academicofferingid' => 'academicofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoursetype()
    {
        return $this->hasOne(CourseType::class, ['coursetypeid' => 'coursetypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPasscriteria()
    {
        return $this->hasOne(PassCriteria::class, ['passcriteriaid' => 'passcriteriaid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemester()
    {
        return $this->hasOne(Semester::class, ['semesterid' => 'semesterid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPassfailtype()
    {
        return $this->hasOne(PassFailType::class, ['passfailtypeid' => 'passfailtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrerequisite()
    {
        return $this->hasOne(CourseOffering::class, ['courseofferingid' => 'prerequisiteid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseOfferings()
    {
        return $this->hasMany(CourseOffering::class, ['prerequisiteid' => 'courseofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedby0()
    {
        return $this->hasOne(User::class, ['personid' => 'createdby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastupdatedby0()
    {
        return $this->hasOne(User::class, ['personid' => 'lastupdatedby']);
    }
}
