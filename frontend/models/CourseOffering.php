<?php

namespace frontend\models;

use Yii;

use frontend\models\CourseCatalog;

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
            [['coursecatalogid', 'academicofferingid', 'coursetypeid', 'passcriteriaid', 'semesterid', 'courseworkweight', 'examweight', 'passmark', 'passfailtypeid'], 'required'],
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
            'createdby' => 'Created By',
            'lastupdatedby' => 'Last Updated By',
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
    
    
    /**
     * Returns an associative array of ['courseofferingid' => 'course_name']
     * 
     * @param type $academicyearid
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created: 24/06/2016
     * Date Last Modified: 24/06/2016
     */
    public static function prepareCourseOfferingListing($academicyearid)
    {
         $records = CourseOffering::find()
                  ->innerJoin('academic_offering', '`course_offering`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                 ->where([ 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0,
                                'academic_offering.academicyearid' => $academicyearid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0
                                ])
                 ->all();

        $listing = array();
        
        foreach ($records as $record) 
        {
            $combined = array();
            $keys = array();
            $values = array();
            array_push($keys, "id");
            array_push($keys, "name");
            $k1 = strval($record->courseofferingid);
            $catalog = CourseCatalog::find()
                    ->where(['coursecatalogid' => $record->coursecatalogid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $name = $catalog->coursecode . " - " . $catalog->name;
            $k2 = strval($name);
            array_push($values, $k1);
            array_push($values, $k2);
            $combined = array_combine($keys, $values);
            array_push($listing, $combined);
            $combined = NULL;
            $keys = NULL;
            $values = NULL;
        }
        return $listing;
    }
}
