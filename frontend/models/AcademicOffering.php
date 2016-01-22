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
 * @property integer $appliable
 * @property integer $isactive
 * @property integer $isdeleted
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
            [['programmecatalogid', 'academicyearid', 'applicationperiodid', 'spaces', 'interviewneeded', 'isactive', 'isdeleted'], 'integer']
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
       public function getacademic_year() 
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
       
       
       /**
        * Returns an array of cohorts for a particular programme
        * 
        * @param type $programmecatalogid
        * @return type
        * 
        * Author: Laurence Charles
        * Date Created: 06/12/2015
        * Date Last Modified: 09/12/2015
        */
       public static function getCohortCount($programmecatalogid)
       {
           $cohorts = AcademicOffering::find()
                   ->where(['programmecatalogid' => $programmecatalogid, 'isactive' => 1, 'isdeleted' => 0])
                   ->all();
           return count($cohorts);
       }
       
       
       /**
        * Returns an count of the cohorts for a particular programme
        * 
        * @param type $programmecatalogid
        * @return boolean
        * 
        * Author: Laurence Charles
        * Date Created: 06/12/2015
        * Date Last Modified: 09/12/2015
        */
       public static function getCohorts($programmecatalogid)
       {
            $cohorts = AcademicOffering::find()
                   ->where(['programmecatalogid' => $programmecatalogid, 'isactive' => 1, 'isdeleted' => 0])
                   ->all();
            if (count($cohorts) > 0)
                return $cohorts;
            else
                return false;
       }
          
       
       
       
}
