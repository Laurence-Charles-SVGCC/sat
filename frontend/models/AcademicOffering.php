<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "academic_offering".
 *
 * @property string $academicofferingid
 * @property string $programmecatalogid
 * @property string $academicyearid
 * @property string $applicationperiodid
 * @property string $spaces
 * @property boolean $appliable
 * @property boolean $isactive
 * @property boolean $isdeleted
 * @property ProgrammeCatalog $programmecatalog 
  * @property AcademicYear $academicyear 
  * @property ApplicationPeriod $applicationperiod 
  * @property Application[] $applications 
  * @property CapeSubject[] $capeSubjects 
  * @property CourseOffering[] $courseOfferings 
  * @property StudentRegistration[] $studentRegistrations 
  */
class AcademicOffering extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'academic_offering';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['programmecatalogid', 'academicyearid', 'applicationperiodid'], 'required'],
            [['programmecatalogid', 'academicyearid', 'applicationperiodid', 'spaces'], 'integer'],
            [['interviewneeded', 'isactive', 'isdeleted'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'academicofferingid' => 'Academic Offering ID',
            'programmecatalogid' => 'Programme',
            'academicyearid' => 'Academic Year',
            'applicationperiodid' => 'Application Period',
            'spaces' => 'Spaces',
            'interviewneeded' => 'Interview Needed',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
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
       public function getApplicationperiod() 
       { 
           return $this->hasOne(ApplicationPeriod::className(), ['applicationperiodid' => 'applicationperiodid']); 
       } 

       /** 
        * @return \yii\db\ActiveQuery 
        */ 
       public function getApplications() 
       { 
           return $this->hasMany(Application::className(), ['academicofferingid' => 'academicofferingid']); 
       } 

       /** 
        * @return \yii\db\ActiveQuery 
        */ 
       public function getCapeSubjects() 
       { 
           return $this->hasMany(CapeSubject::className(), ['academicofferingid' => 'academicofferingid']); 
       } 

       /** 
        * @return \yii\db\ActiveQuery 
        */ 
       public function getCourseOfferings() 
       { 
           return $this->hasMany(CourseOffering::className(), ['academicofferingid' => 'academicofferingid']); 
       } 

       /** 
        * @return \yii\db\ActiveQuery 
        */ 
       public function getStudentRegistrations() 
       { 
           return $this->hasMany(StudentRegistration::className(), ['academicofferingid' => 'academicofferingid']); 
       } 
}
