<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "course_offering".
 *
 * @property string $courseofferingid
 * @property string $coursecatalogid
 * @property string $academicofferingid
 * @property string $coursetypeid
 * @property string $passcriteriaid
 * @property string $semesterid
 * @property string $prerequisiteid
 * @property string $passfailtypeid
 * @property string $courseworkweight
 * @property string $examweight
 * @property string $passmark
 * @property integer $credits
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $mdlgroupid
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
 * @property CourseofferingResitstatus[] $courseofferingResitstatuses
 * @property ResitStatus[] $resitstatuses
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
            [['coursecatalogid', 'academicofferingid', 'coursetypeid', 'passcriteriaid', 'semesterid', 'prerequisiteid', 'passfailtypeid', 'credits', 'isactive', 'isdeleted', 'mdlgroupid'], 'integer'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatches()
    {
        return $this->hasMany(Batch::className(), ['courseofferingid' => 'courseofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCordinators()
    {
        return $this->hasMany(Cordinator::className(), ['courseofferingid' => 'courseofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoursecatalog()
    {
        return $this->hasOne(CourseCatalog::className(), ['coursecatalogid' => 'coursecatalogid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicoffering()
    {
        return $this->hasOne(AcademicOffering::className(), ['academicofferingid' => 'academicofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoursetype()
    {
        return $this->hasOne(CourseType::className(), ['coursetypeid' => 'coursetypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPasscriteria()
    {
        return $this->hasOne(PassCriteria::className(), ['passcriteriaid' => 'passcriteriaid']);
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
    public function getPassfailtype()
    {
        return $this->hasOne(PassFailType::className(), ['passfailtypeid' => 'passfailtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrerequisite()
    {
        return $this->hasOne(CourseOffering::className(), ['courseofferingid' => 'prerequisiteid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseOfferings()
    {
        return $this->hasMany(CourseOffering::className(), ['prerequisiteid' => 'courseofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseofferingResitstatuses()
    {
        return $this->hasMany(CourseofferingResitstatus::className(), ['courseofferingid' => 'courseofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResitstatuses()
    {
        return $this->hasMany(ResitStatus::className(), ['resitstatusid' => 'resitstatusid'])->viaTable('courseoffering_resitstatus', ['courseofferingid' => 'courseofferingid']);
    }
}
