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
       
       
       /**
        * Determines of a CAPE offering has been created for a particular appolication period
        * 
        * @param type $applicationperiodid
        * @return boolean
        * 
        * Author: Laurence Charles
        * Date Created: 14/02/2016
        * Date Last Modified: 14/02/2016
        */
       public static function hasCapeOffering($applicationperiodid)
       {
            $db = Yii::$app->db;
            $records = $db->createCommand(
                    "SELECT *"
                    . " FROM academic_offering" 
                    . " JOIN programme_catalog"
                    . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                    . " WHERE academic_offering.applicationperiodid =" . $applicationperiodid
                    . " AND programme_catalog.name = 'CAPE'"
                    . " AND academic_offering.isactive = 1"
                    . " AND academic_offering.isdeleted = 0"
                    . ";"
                )
                ->queryAll();
            if (count($records) > 0)
                return true;
            return false;
        }
        
        
        /**
        * Retrieves the CAPE offering has been created for a particular appolication period
        * 
        * @param type $applicationperiodid
        * @return boolean
        * 
        * Author: Laurence Charles
        * Date Created: 15/02/2016
        * Date Last Modified: 15/02/2016
        */
       public static function getCapeOffering($applicationperiodid)
       {
           $record = AcademicOffering::find()
                   ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                   ->where(['academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0])
                   ->andWhere(['programme_catalog.name' => 'CAPE'])
                   ->andWhere(['academic_offering.applicationperiodid' => $applicationperiodid])
                   ->one();

            if ($record)
                return $record;
            return false;
        }
        
        
        /**
        * Determines of any none CAPE offering has been created for a particular appolication period
        * 
        * @param type $applicationperiodid
        * @return boolean
        * 
        * Author: Laurence Charles
        * Date Created: 14/02/2016
        * Date Last Modified: 14/02/2016
        */
       public static function hasNoneCapeOffering($applicationperiodid)
       {
            $db = Yii::$app->db;
            $records = $db->createCommand(
                    "SELECT *"
                    . " FROM academic_offering" 
                    . " JOIN programme_catalog"
                    . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                    . " WHERE academic_offering.applicationperiodid =" . $applicationperiodid
                    . " AND academic_offering.isactive = 1"
                    . " AND academic_offering.isdeleted = 0"
                    . " AND programme_catalog.name <> 'CAPE'"
                    . ";"
                )
                ->queryAll();
            if (count($records) > 0)
                return true;
            return false;
        }
        
        
        
        
        /**
        * Retrieves any none CAPE offering has been created for a particular appolication period
        * 
        * @param type $applicationperiodid
        * @return boolean
        * 
        * Author: Laurence Charles
        * Date Created: 15/02/2016
        * Date Last Modified: 15/02/2016
        */
       public static function getNoneCapeOffering($applicationperiodid)
       {
           $records = AcademicOffering::find()
                   ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                   ->where(['academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0])
                   ->andWhere(['not', ['programme_catalog.name' => 'CAPE']])
                   ->andWhere(['academic_offering.applicationperiodid' => $applicationperiodid])
                   ->all();
            if (count($records) > 0)
                return $records;
            return false;
        }
        
        
     /**
     * Creates backup of a collection of AcademicOffering records
     * 
     * @param type $experinences
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created: 15/02/2016
     * Date Last Modified: 15/02/2016
     */
    public static function backUp($offerings)
    {
        $saved = array();
         
        foreach ($offerings as $offering)
        {
            $temp = NULL;
            $temp = new AcademicOffering();
            $temp->programmecatalogid = $offering->programmecatalogid;
            $temp->academicyearid = $offering->academicyearid;
            $temp->applicationperiodid = $offering->applicationperiodid;
            $temp->spaces = $offering->interviewneeded;
            $temp->isactive = $offering->isactive;
            $temp->isdeleted = $offering->isdeleted;
            array_push($saved, $temp);      
        }
        return $saved;
    }
        
        
    /**
     * Saves the backed up AcademicOffering to the databases
     * 
     * @param type $experiences
     * 
     * Author: Laurence Charles
     * Date Created: 15/02/2016
     * Date Last Modified: 15/02/2016
     */
    public static function restore($offerings)
    {
        foreach ($offerings as $offering)
        {
            $offering->save();     
        }
    }
    
    
    /**
     * Creates backup of a single AcademicOffering record
     * 
     * @param type $experinences
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created: 15/02/2016
     * Date Last Modified: 15/02/2016
     */
    public static function backUpSingle($offering)
    {
        $temp = NULL;
        $temp = new AcademicOffering();
        $temp->programmecatalogid = $offering->programmecatalogid;
        $temp->academicyearid = $offering->academicyearid;
        $temp->applicationperiodid = $offering->applicationperiodid;
        $temp->spaces = $offering->interviewneeded;
        $temp->isactive = $offering->isactive;
        $temp->isdeleted = $offering->isdeleted;
        
        return $temp;
    }
        
        
    /**
     * Saves the backed up AcademicOffering to the databases
     * 
     * @param type $experiences
     * 
     * Author: Laurence Charles
     * Date Created: 15/02/2016
     * Date Last Modified: 15/02/2016
     */
    public static function restoreSingle($offering)
    {
        $offering->save();     
    }
    
    
    /**
     * Returns true is programme requires interview
     * 
     * @param type $applicationid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 05/03/2016
     * Date Last Modified: 05/03/2016
     */
    public static function requiresInterview($applicationid)
    {
        $record = AcademicOffering::find()
                ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->where(['academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'academic_offering.interviewneeded' => 1,
                        'application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationid' => $applicationid, 
                        ])
                ->one();
        if ($record)
            return true;
        return false;
    }
       
       
       
}
