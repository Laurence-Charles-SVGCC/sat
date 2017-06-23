<?php

namespace frontend\models;

use Yii;
use frontend\models\ProgrammeCatalog;
use frontend\models\StudentRegistration;
use frontend\models\ApplicationPeriod;

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
        * Returns an array of uniqe academicoffering records associated with a cordinator
        * 
        * @return type
        * 
        * Author: Laurence Charles
        * Date Created: 29/06/2016
        * Date LAst Modified: 29/.6/2016
        */
       public static function getCordinatedAcademicofferings()
       {
           $employeeid = Yii::$app->user->identity->personid;
           $cordinated_programme_academicofferingids = array();
           $cordinated_programme_academicofferings = array();

            $programme_head_cordinator_records = Cordinator::find()
                    ->innerJoin('cordinator_type' , '`cordinator`.`cordinatortypeid` = `cordinator_type`.`cordinatortypeid`')
                    ->where(['cordinator.personid' => $employeeid, 'cordinator.isserving' => 1,  'cordinator.isactive' => 1, 'cordinator.isdeleted' => 0,
                                    'cordinator_type.isactive' => 1, 'cordinator_type.isdeleted' => 0, 'cordinator_type.name' => 'Programme Head'
                                    ])
                    ->all();
            if( $programme_head_cordinator_records)
            {
                foreach ($programme_head_cordinator_records as $programme_head_cordinator)
                {
                    if(in_array( $programme_head_cordinator->academicofferingid, $cordinated_programme_academicofferingids) == false)
                    {
                        $cordinated_programme_academicofferingids[] = $programme_head_cordinator->academicofferingid;
                        $offering = AcademicOffering::find()
                                ->where(['academicofferingid' => $programme_head_cordinator->academicofferingid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();
                        $cordinated_programme_academicofferings[] = $offering;
                    }
                }
            }
//            $offering = NULL;
                    
            $department_head_cordinator_records = Cordinator::find()
                    ->innerJoin('cordinator_type' , '`cordinator`.`cordinatortypeid` = `cordinator_type`.`cordinatortypeid`')
                    ->where(['cordinator.personid' => $employeeid, 'cordinator.isserving' => 1,  'cordinator.isactive' => 1, 'cordinator.isdeleted' => 0,
                                    'cordinator_type.isactive' => 1, 'cordinator_type.isdeleted' => 0, 'cordinator_type.name' => 'Head of Department'
                                    ])
                    ->all();
            if($department_head_cordinator_records)
            {
                foreach ($department_head_cordinator_records as $department_head_cordinator)
                {
                    $offerings  = AcademicOffering::find()
                            ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->where(['academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,  'academic_offering.academicyearid' => $department_head_cordinator->academicyearid,
                                            'programme_catalog.departmentid' => $department_head_cordinator->departmentid, 'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0
                                            ])
                            ->all();
                    
                    foreach($offerings as $offering)
                    {
                        if(in_array($offering->academicofferingid, $cordinated_programme_academicofferingids) == false)
                        {
                            $cordinated_programme_academicofferingids[] = $offering->academicofferingid;
                            $cordinated_programme_academicofferings[] = $offering;
                        }
                    }
                } 
            }
            return $cordinated_programme_academicofferings;
       }
       
       
       /**
        * Returns an array of uniqe academicofferingids associated with a cordinator
        * 
        * @return type
        * 
        * Author: Laurence Charles
        * Date Created: 29/06/2016
        * Date LAst Modified: 29/06/2016
        */
       public static function getCordinatedAcademicofferingids()
       {
           $employeeid = Yii::$app->user->identity->personid;
        
            $cordinated_programme_academicofferingids = array();

            $programme_head_cordinator_records = Cordinator::find()
                    ->innerJoin('cordinator_type' , '`cordinator`.`cordinatortypeid` = `cordinator_type`.`cordinatortypeid`')
                    ->where(['cordinator.personid' => $employeeid, 'cordinator.isserving' => 1,  'cordinator.isactive' => 1, 'cordinator.isdeleted' => 0,
                                    'cordinator_type.isactive' => 1, 'cordinator_type.isdeleted' => 0, 'cordinator_type.name' => 'Programme Head'
                                    ])
                    ->all();
            if($programme_head_cordinator_records)
            {
                foreach ($programme_head_cordinator_records as $programme_head_cordinator)
                {
                    if(in_array( $programme_head_cordinator->academicofferingid, $cordinated_programme_academicofferingids) == false)
                            $cordinated_programme_academicofferingids[] = $programme_head_cordinator->academicofferingid;
                }
            }

            $department_head_cordinator_records = Cordinator::find()
                    ->innerJoin('cordinator_type' , '`cordinator`.`cordinatortypeid` = `cordinator_type`.`cordinatortypeid`')
                    ->where(['cordinator.personid' => $employeeid, 'cordinator.isserving' => 1,  'cordinator.isactive' => 1, 'cordinator.isdeleted' => 0,
                                    'cordinator_type.isactive' => 1, 'cordinator_type.isdeleted' => 0, 'cordinator_type.name' => 'Head of Department'
                                    ])
                    ->all();
            if($department_head_cordinator_records)
            {
                foreach ($department_head_cordinator_records as $department_head_cordinator)
                {
                    $offerings  = AcademicOffering::find()
                            ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.programmecatalogid')
                            ->where(['academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'academic_offering.academicyearid' => $department_head_cordinator->academicyearid,
                                            'programme_catalog.departmentid' => $department_head_cordinator->departmentid, 'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0
                                            ])
                            ->all();
                    
                    foreach($offerings as $offering)
                    {
                        if(in_array($offering->academicofferingid, $cordinated_programme_academicofferingids) == false)
                        {
                            $cordinated_programme_academicofferingids[] = $offering->academicofferingid;
                        }
                    }
                } 
            }
            return $cordinated_programme_academicofferingids;
       }
       
       
       /**
        * Determines if a CAPE offering has been created for a particular application period
        * 
        * @param type $applicationperiodid
        * @return boolean
        * 
        * Author: Laurence Charles
        * Date Created: 14/02/2016
        * Date Last Modified: 13/08/2016
        */
       public static function hasCapeOffering($applicationperiodid)
       {
//            $db = Yii::$app->db;
//            $records = $db->createCommand(
//                    "SELECT *"
//                    . " FROM academic_offering" 
//                    . " JOIN programme_catalog"
//                    . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
//                    . " WHERE academic_offering.applicationperiodid =" . $applicationperiodid
//                    . " AND programme_catalog.name = 'CAPE'"
//                    . " AND academic_offering.isactive = 1"
//                    . " AND academic_offering.isdeleted = 0"
//                    . ";"
//                )
//                ->queryAll();
            $records = AcademicOffering::find()
                    ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->where(['academic_offering.applicationperiodid' => $applicationperiodid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                   'programme_catalog.name' => 'CAPE', 'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0
                                ])
                    ->all();
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
        * Date Last Modified: 13/08/2016
        */
       public static function getCapeOffering($applicationperiodid)
       {
           $record = AcademicOffering::find()
                   ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                   ->where(['academic_offering.applicationperiodid' => $applicationperiodid,'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0])
                   ->andWhere([ 'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0, 'programme_catalog.name' => 'CAPE'])
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
        * Date Last Modified: 13/08/2016
        */
       public static function hasNoneCapeOffering($applicationperiodid)
       {
//            $db = Yii::$app->db;
//            $records = $db->createCommand(
//                    "SELECT *"
//                    . " FROM academic_offering" 
//                    . " JOIN programme_catalog"
//                    . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
//                    . " WHERE academic_offering.applicationperiodid =" . $applicationperiodid
//                    . " AND academic_offering.isactive = 1"
//                    . " AND academic_offering.isdeleted = 0"
//                    . " AND programme_catalog.isactive = 1"
//                    . " AND programme_catalog.isdeleted = 0"
//                    . " AND programme_catalog.name <> 'CAPE'"
//                    . ";"
//                )
//                ->queryAll();
            $records = AcademicOffering::find()
                    ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->where(['academic_offering.applicationperiodid' => $applicationperiodid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                    'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0
                                ])
                    ->andWhere(['not', ['programme_catalog.name' => 'CAPE']])
                    ->all();
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
        * Date Last Modified: 13/08/2016
        */
       public static function getNoneCapeOffering($applicationperiodid)
       {
            $records = AcademicOffering::find()
                    ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->where(['academic_offering.applicationperiodid' => $applicationperiodid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                    'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0
                                ])
                    ->andWhere(['not', ['programme_catalog.name' => 'CAPE']])
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
    
    
    /**
     * Returns true is academicoffering correspondes to a CAPE programme
     * 
     * @param type $academicofferingid
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 27/042016 [L.Charles]
     */
    public static function isCape($academicofferingid)
    {
        $ao = AcademicOffering::findOne(['academicofferingid' => $academicofferingid]);
        $cape_prog = ProgrammeCatalog::findOne(['name' => 'CAPE']);
        return $cape_prog ? $ao->programmecatalogid == $cape_prog->programmecatalogid : false;
    }
    
    
    /**
     * Returns the academicofferingid of current CAPE offfering
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 25/02/2016
     * Date Last Modified: 25/02/2016 | 24/08/2016
     */
    public static function getCurrentCapeID()
    {
        $offering = AcademicOffering::find()
                    ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->where(['academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                    'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0, 'programme_catalog.name' => "CAPE",
                                    'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.iscomplete' => 0
                                ])
                    ->one();
        if ($offering)
            return $offering->academicofferingid;
        return false;
    }
    
    
    /**
     * Returns tha academicoffering of the most recent DASGS application period
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 17/09/2016
     * Date Last Modified: 17/09/2016
     */
    public static function getMostRecentCapeAcademicOfferingID()
    {
        $recent_dasgs_period = ApplicationPeriod::find()
                ->innerJoin('academic_year', '`application_period`.`academicyearid` = `academic_year`.`academicyearid`')
                ->where(['application_period.isdeleted' => 0, 'application_period.divisionid' => 4,
                                'academic_year.isdeleted' => 0, 'academic_year.iscurrent' => 1])
                ->one();
        if ($recent_dasgs_period)
        {
            $offering = AcademicOffering::find()
                    ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->where([ 'academic_offering.isdeleted' => 0,
                                    'programme_catalog.isdeleted' => 0, 'programme_catalog.name' => "CAPE",
                                    'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $recent_dasgs_period->applicationperiodid
                                ])
                    ->one();
        if ($offering)
            return $offering->academicofferingid;
        }
        return false;
    }
    
    
    
    /**
     * Returns the CAPE academic offering for the specified application period
     * 
     * @param type $periodid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 14/05/2016
     * Date Last Modified: 14/05/2016
     */
    public static function getCapeID($periodid)
    {
        $offering = AcademicOffering::find()
                    ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->where(['programme_catalog.name' => "CAPE",
                            'application_period.applicationperiodid' => $periodid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                            ])
                    ->one();
        if ($offering)
            return $offering->academicofferingid;
        return false;
    }
    
    
    /**
     * Returns an associative array of ['academicofferingid' => 'programme_name']
     * 
     * @param type $academicyearid
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created: 24/06/2016
     * Date Last Modified: 24/06/2016
     */
    public static function prepareAcademicOfferingListing($academicyearid)
    {
         $records = AcademicOffering::find()
                    ->where(['academicyearid' => $academicyearid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();

        $listing = array();
        foreach ($records as $record) 
        {
            $combined = array();
            $keys = array();
            $values = array();
            array_push($keys, "id");
            array_push($keys, "name");
            $k1 = strval($record->academicofferingid);
            $name = ProgrammeCatalog::getProgrammeName($record->academicofferingid);
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
    
    
    
    public static function getHighestGPA($academicofferingid)
    {
        $enrolled_students = StudentRegistration::find()
                            ->where(['academicofferingid' => $academicofferingid, 'isdeleted' => 0])
                            ->all();
        $top_performers = array();
        $highest_gpa = 0;
        foreach ($enrolled_students as $student)
        {
            if ($student->studentstatusid == 1 || $student->studentstatusid == 11)
            {
                $gpa = StudentRegistration::calculateCumulativeGPA($student->studentregistrationid);
                if($gpa > $highest_gpa)
                {
                    $highest_gpa = $gpa;
                }
            }
        }
        
        foreach ($enrolled_students as $student)
        {
            if ($student->studentstatusid == 1 || $student->studentstatusid == 11)
            {
                $gpa = StudentRegistration::calculateCumulativeGPA($student->studentregistrationid);
                if($gpa == $highest_gpa)
                {
                    $top_performers[] = $student->studentregistrationid;
                }
            }
        }
        return $top_performers;
    }
       
       
}
