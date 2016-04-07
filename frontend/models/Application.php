<?php

namespace frontend\models;

use Yii;
use common\models\User;
use frontend\models\Applicant;
use frontend\models\AcademicYear;
use frontend\models\CsecQualification;
use frontend\models\PostSecondaryQualification;
use frontend\models\ExternalQualification;

/**
 * This is the model class for table "application".
 *
 * @property string $applicationid
 * @property string $personid
 * @property string $divisionid
 * @property string $academicofferingid
 * @property string $applicationstatusid
 * @property string $applicationtimestamp
 * @property string $submissiontimestamp
 * @property integer $ordering
 * @property string $ipaddress
 * @property string $browseragent
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property ApplicationStatus $applicationstatus
 * @property Person $person
 * @property AcademicOffering $academicoffering
 * @property Division $division
 * @property ApplicationCapesubject[] $applicationCapesubjects
 * @property ApplicationHistory[] $applicationHistories
 * @property Offer[] $offers
 */
class Application extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'divisionid', 'academicofferingid', 'applicationstatusid', 'applicationtimestamp', 'ordering'], 'required'],
            [['personid', 'divisionid', 'academicofferingid', 'applicationstatusid', 'ordering', 'isactive', 'isdeleted'], 'integer'],
            [['applicationtimestamp', 'submissiontimestamp'], 'safe'],
            [['ipaddress', 'browseragent'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicationid' => 'Applicationid',
            'personid' => 'Personid',
            'divisionid' => 'Divisionid',
            'academicofferingid' => 'Academicofferingid',
            'applicationstatusid' => 'Applicationstatusid',
            'applicationtimestamp' => 'Applicationtimestamp',
            'submissiontimestamp' => 'Submissiontimestamp',
            'ordering' => 'Ordering',
            'ipaddress' => 'Ipaddress',
            'browseragent' => 'Browseragent',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationstatus()
    {
        return $this->hasOne(ApplicationStatus::className(), ['applicationstatusid' => 'applicationstatusid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
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
    public function getDivision()
    {
        return $this->hasOne(Division::className(), ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationCapesubjects()
    {
        return $this->hasMany(ApplicationCapesubject::className(), ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationHistories()
    {
        return $this->hasMany(ApplicationHistory::className(), ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['applicationid' => 'applicationid']);
    }
    
    public static function isCapeApplication($academicofferingid)
    {
        $ao = AcademicOffering::findOne(['academicofferingid' => $academicofferingid]);
        $cape_prog = ProgrammeCatalog::findOne(['name' => 'cape']);
        return $cape_prog ? $ao->programmecatalogid == $cape_prog->programmecatalogid : False;
    }
    
    
    /**
     * Returns all applicant applications
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 23/12/2015
     * Date LAt Modified: 23/12/2015
     */
    public static function getApplications($id)
    {
        $applications = Application::find()
//                ->where(['personid' => $id, 'isactive' => 1, 'isdeleted'=> 0])
                ->where(['personid' => $id, 'isdeleted'=> 0])
                ->all();
        if (count($applications) > 0)
        {
            return $applications;
        }
        return false;
    }
    
    
    /**
     * Determines if application relates to a CAPE programme
     * 
     * @param type $academicofferingid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 23/12/2015
     * Date Last Modified: 23/12/2015
     */
    public static function isCape($academicofferingid)
    {
        $db = Yii::$app->db;
        $records = $db->createCommand(
                "SELECT academic_offering.academicofferingid AS 'academicofferingid',"
                . " programme_catalog.name AS 'name'"
                . " FROM academic_offering"
                . " JOIN programme_catalog"
                . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                . " WHERE academic_offering.academicofferingid = ". $academicofferingid
                . ";"
                )
                ->queryAll();
        
        $name = $records[0]["name"];
        if (strcmp($name, "CAPE") == 0)     //if application is for CAPE programme     
            return true;
        return false;
    }
    
    
    /**
     * Returns all applications that were suggested by Deans/Deputy Deans
     * 
     * @param type $id
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 23/12/2015
     * Date Last Modified: 23/12/2015
     */
    public static function getSpecialApplication($id)
    {
        $applications = Applincation::find()
                    ->where(['personid' => $id, 'isactive' =>1, 'isdeleted'=> 0])
                    ->andWhere(['>', 'ordering', 3])
                    ->all();
        if (count($applications)>0)
            return $applications;
        return false;
    }
    
    
    /**
     * Return the fully qualified name of an application
     * 
     * @param type $academicofferingid
     * @return boolean|string
     * 
     * Author: Laurence Charles
     * Date Created: 23/12/2015
     * Date Last Modified: 23/12/2015
     */
    public static function getApplicationDetails($academicofferingid)
    {
        $db = Yii::$app->db;
        $p = $db->createCommand(
            "SELECT academic_offering.academicofferingid AS 'academicofferingid',"
            . " programme_catalog.name AS 'name',"
            . " programme_catalog.specialisation AS 'specialisation',"
            . " qualification_type.abbreviation AS 'qualificationtype'"
            . " FROM  academic_offering "
            . " JOIN programme_catalog"
            . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
            . " JOIN qualification_type"
            . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
            . " WHERE academic_offering.academicofferingid = " . $academicofferingid . " ;"
            )
            ->queryAll();

        if (count($p)>0)
        {
            $specialization = $p[0]["specialisation"];
            $qualification = $p[0]["qualificationtype"];
            $programme = $p[0]["name"];
            $fullname = $qualification . " " . $programme . " " . $specialization;
            return $fullname;
        }
        else 
            return false;
    }
    
    
    /**
     * Returns the appropriate ordering for a new institution created application
     * 
     * @param type $personid
     * @return int
     * 
     * Author: Laurence Charles
     * Date Created: 09/01/2016
     * Date Last Modified: 11/01/2016
     */
    public static function getNextApplicationID($personid)
    {
        $custom_applications = Application::find()
                    ->where(['personid' => $personid])
                    ->andWhere(['>', 'ordering', 3])
                    ->all();
        $count = count($custom_applications);
        if($count > 0)
        {
            $last_id = $applications[($count-1)];
            $new_id = $last_id + 1;
        }
        else
        {
            $student_applications = Application::find()
                    ->where(['personid' => $personid])
                    ->andWhere(['<', 'ordering', 4])
                    ->all();
            $count2 = count($student_applications);
            if ($count2 == 1)
                $new_id = 2;
            elseif ($count2 == 2)
                $new_id = 3;
            elseif ($count2 == 3)
                $new_id = 4;
        }
        return $new_id;
    }
    
    
    /**
     * Gets the Applicants with CSEC Certificates who indicated they have "External" qualifications
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 18/02/2016
     * Date Last Modified: 18/02/2016 | 20/03/2016
     */
    public static function getExternal()
    {
        $data = array();
        $applications = Application::find()
                ->leftjoin('applicant', '`application`.`personid` = `applicant`.`personid`')
                ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where(['application_period.iscomplete' => 0, 'application_period.isactive' => 1, 
                        'application.isdeleted' => 0,
                        'applicant.isexternal' => 1, 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                        'academic_offering.isdeleted' => 0,
                        'application.applicationstatusid' => [2,3,4,5,6,7,8,9]])
                ->groupby('application.personid')
                ->all();
        foreach($applications as $application)
        {
            $data[] = Applicant::find()->where(['personid' => $application->personid])->one();
        }
        return $data;
        
    }
    
    
    /**
     * Gets the count of Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     * 
     * @param type $cseccentreid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 18/02/2016
     * Date Last Modified: 18/02/2016 | 20/03/2016 | 04/04/2016
     */
    public static function centreApplicantsReceivedCount($cseccentreid)
    {
        $count = Application::find()
                    ->leftjoin('applicant', '`application`.`personid` = `applicant`.`personid`')
                    ->innerJoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->innerJoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->innerJoin('academic_year', '`academic_year`.`academicyearid` = `application_period`.`academicyearid`')
                    ->where(['applicant.isexternal' => 0,
                            'csec_centre.cseccentreid' => $cseccentreid,
                            'application_period.iscomplete' => 0, 'application_period.isactive' => 1,
                            'csec_qualification.isdeleted' => 0,
                            'application.isdeleted' => 0, 'application.applicationstatusid' => [2,3,4,5,6,7,8,9],
                            'academic_offering.isdeleted' => 0
                            ])
                    ->groupby('application.personid')
                    ->count();
        return $count;
    }
    
    /**
     * Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     * 
     * @param type $cseccentreid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 18/02/2016
     * Date Last Modified: 18/02/2016 | 20/03/2016 | 04/04/2016
     */
    public static function centreApplicantsReceived($cseccentreid)
    {
        $applicants = Application::find()
                    ->innerJoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->innerJoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->innerJoin('academic_year', '`academic_year`.`academicyearid` = `application_period`.`academicyearid`')
                    ->where(['csec_centre.cseccentreid' => $cseccentreid,
                            'application_period.iscomplete' => 0, 'application_period.isactive' => 1,
                            'csec_qualification.isdeleted' => 0,
                            'application.isdeleted' => 0, 'application.applicationstatusid' => [2,3,4,5,6,7,8,9],
                            'academic_offering.isdeleted' => 0
                            ])
                    ->groupby('application.personid')
                    ->all();
        return $applicants;
    }
    
  
    /**
     * Gets a count of the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     * who have already been fully verified
     * 
     * @param type $cseccentreid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 18/02/2016
     * Date Last Modified: 18/02/2016 | 04/04/2016
     */
    public static function centreApplicantsVerifiedCount($cseccentreid, $external = false)
    {
        if ($external == true)
            return count(self::centreApplicantsVerified($cseccentreid, true));
        else
            return count(self::centreApplicantsVerified($cseccentreid));
    }
    
    
    /**
     * Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     * who have already been fully verified
     * 
     * @param type $cseccentreid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 18/02/2016
     * Date Last Modified: 18/02/2016 | 20/03/2016 | 04/04/2016
     */
    public static function centreApplicantsVerified($cseccentreid, $external = false)
    {
        if($external == true)
        {
            $applicants = Application::find()
                    ->innerJoin('applicant', '`applicant`.`personid` = `application`.`personid`')
                    ->innerJoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->innerJoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['applicant.isexternal' => 1, 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                            'csec_qualification.isverified' => 1, 'csec_qualification.isactive' => 1, 'csec_qualification.isdeleted' => 0,
                            'application_period.iscomplete' => 0, 'application_period.isactive' => 1,
                            'application.isdeleted' => 0, 'application.applicationstatusid' => [2,3,4,5,6,7,8,9],
                            'academic_offering.isdeleted' => 0])
                    ->groupBy('application.personid')
                    ->all();
        }
        else
        {
            $applicants = Application::find()
                        ->innerJoin('applicant', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                        ->innerJoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where(['applicant.isexternal' => 0, 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                                'csec_centre.cseccentreid' => $cseccentreid,
                                'csec_qualification.isverified' => 1, 'csec_qualification.isactive' => 1, 'csec_qualification.isdeleted' => 0,
                                'application_period.iscomplete' => 0, 'application_period.isactive' => 1, /*'application_period.applicationperiodstatusid' => 5,*/ 
                                'application.isdeleted' => 0, 'application.applicationstatusid' => [2,3,4,5,6,7,8,9],
                                'academic_offering.isdeleted' => 0])
                        ->groupBy('application.personid')
                        ->all();
        }
        
        $eligible = array();
        foreach ($applicants as $key => $applicant)
        {
            if($external == true)       // if attempting to retrieve external applicants
            {
                /*
                * if all of an applicant's certificates as well as post secondary qualification
                * are verified then they are removed from "pending"
                */
                $all_certs = CsecQualification::find()
                       ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0])
                       ->all();
                $cert_count = count($all_certs);
                $verified_count = 0;
                if ($cert_count > 0)
                {
                    foreach ($all_certs as $cert)
                    {   
                        if($cert->isverified == 1  && $cert->isqueried == 0)
                           $verified_count++;
                    }
                }

                $post_qualification = PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid);
                $external_qualification = ExternalQualification::getExternalQualifications($applicant->personid);

                $verified = false;
                if($post_qualification == true)
                {
                    $cert_count++;
                    if ($post_qualification->isverified==1 && $post_qualification->isqueried==0)
                        $verified_count++;
                }

                if($external_qualification == true)
                {
                    $cert_count++;
                    if ($external_qualification->isverified==1 && $external_qualification->isqueried==0)
                        $verified_count++;
                }

                if ($cert_count == $verified_count)
                    $eligible[] = $applicants[$key];
            }
            else        // if attempting to retrieve "non-external" applicants
            {
                /*
                * if all of an applicant's certificates as well as post secondary qualification
                * are verified then they are removed from "pending"
                */
                $all_certs = CsecQualification::find()
                       ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0])
                       ->all();
                $cert_count = count($all_certs);
                $verified_count = 0;
                if ($cert_count > 0)
                {
                    foreach ($all_certs as $cert)
                    {   
                        if($cert->isverified == 1  && $cert->isqueried == 0)
                           $verified_count++;
                    }
                }

                $post_qualification = PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid);
                $external_qualification = ExternalQualification::getExternalQualifications($applicant->personid);

                $verified = false;
                if($post_qualification == true)
                {
                    $cert_count++;
                    if ($post_qualification->isverified==1 && $post_qualification->isqueried==0)
                        $verified_count++;
                }

                if ($cert_count == $verified_count)
                    $eligible[] = $applicants[$key];
            }
        }
            
        return $eligible;
    }
    
    
    /**
     * Gets the count of Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     * who have a certificate flagged as to be queried
     * 
     * @param type $cseccentreid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 18/02/2016
     * Date Last Modified: 18/02/2016 | 04/04/2016
     */
    public static function centreApplicantsQueriedCount($cseccentreid, $external = false)
    {
        if ($external == true)
            return count(self::centreApplicantsQueried($cseccentreid, true));
        else
            return count(self::centreApplicantsQueried($cseccentreid));
    }
    
    
    /**
     * Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     * who have a certificate flagged as to be queried
     * 
     * @param type $cseccentreid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 18/02/2016
     * Date Last Modified: 18/02/2016 | 20/03/2016 | 04/04/2016
     */
    public static function centreApplicantsQueried($cseccentreid, $external = false)
    {
        if($external == true)
        {
            $applicants = Application::find()
                    ->innerJoin('applicant', '`applicant`.`personid` = `application`.`personid`')
                    ->innerJoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->innerJoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['applicant.isexternal' => 1, 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                            'csec_qualification.isdeleted' => 0,  'csec_qualification.isactive' => 1,
                            'application_period.iscomplete' => 0, 'application_period.isactive' => 1,
                            'application.isdeleted' => 0, 'application.applicationstatusid' => [2,3,4,5,6,7,8,9],
                            'academic_offering.isdeleted' => 0])
                    ->groupBy('application.personid')
                    ->all();
        }
        else
        {
            $applicants = Application::find()
                        ->innerJoin('applicant', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                        ->innerJoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where(['applicant.isexternal' => 0, 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                                'csec_centre.cseccentreid' => $cseccentreid,
                                'csec_qualification.isdeleted' => 0,  'csec_qualification.isactive' => 1,
                                'application_period.iscomplete' => 0, 'application_period.isactive' => 1,
                                'application.isdeleted' => 0, 'application.applicationstatusid' => [2,3,4,5,6,7,8,9],
                                'academic_offering.isdeleted' => 0])
                        ->groupBy('application.personid', 'csec_centre.cseccentreid')
                        ->all();
        }
        
        $eligible = array();
        foreach ($applicants as $key => $applicant)
        {
            if($external == true)       // if attempting to retrieve external applicants
            {
                // if applicant has a queried certificate they are added to 'eligible'
                if (CsecQualification::findOne(['personid' => $applicant->personid, 'isqueried' => 1, 'isdeleted' => 0, 'isactive' => 1]))
                   $eligible[] = $applicants[$key];
                else
                {
                    $post_qualification = PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid);
                    $external_qualification = ExternalQualification::getExternalQualifications($applicant->personid);
                    if($post_qualification == true  && $post_qualification->isqueried==1)
                        $eligible[] = $applicants[$key];
                    elseif($external_qualification == true  && $external_qualification->isqueried==1)
                        $eligible[] = $applicants[$key];
                }
            }
            else        // if attempting to retrieve "non-external" applicants
            {
                // if applicant has a queried certificate they are added to 'eligible
                if (CsecQualification::findOne(['personid' => $applicant->personid, 'isqueried' => 1, 'isdeleted' => 0, 'isactive' => 1]))
                   $eligible[] = $applicants[$key];
                else
                {
                    $post_qualification = PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid);
                    if($post_qualification == true  && $post_qualification->isqueried==1)
                        $eligible[] = $applicants[$key];
                }
            }
        }
        return $eligible;
    }
    
    
    
    
    /**
     * Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     * who have a certificate that are not flagged as yet
     * 
     * @param type $cseccentreid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 18/02/2016
     * Date Last Modified: 18/02/2016 | 20/03/2016 | 04/04/2016
     */
    public static function centreApplicantsPending($cseccentreid, $external = false)
    {
        if($external == true)
        {
            $applicants = Application::find()
                    ->innerJoin('applicant', '`applicant`.`personid` = `application`.`personid`')
                    ->innerJoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->innerJoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => [2,3,4,5,6,7,8,9],
                            'applicant.isexternal' => 1, 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                            'csec_qualification.isactive' => 1, 'csec_qualification.isdeleted' => 0,
                            'application_period.iscomplete' => 0, 'application_period.isactive' => 1, 
                            'academic_offering.isdeleted' => 0])
                    ->groupBy('application.personid')
                    ->all();
            
            /*
             * External Applicant with no certificates entered must also be captured
             */
            $applicants_without_csec_qualification = Application::find()
                                            ->leftjoin('applicant', '`application`.`personid` = `applicant`.`personid`')
                                            ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                                            ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                                            ->where(['application_period.iscomplete' => 0, 'application_period.isactive' => 1, 
                                                    'application.isdeleted' => 0,
                                                    'applicant.isexternal' => 1, 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                                                    'academic_offering.isdeleted' => 0,
                                                    'application.applicationstatusid' => [2,3,4,5,6,7,8,9]])
                                            ->groupby('application.personid')
                                            ->all();
            foreach ($applicants_without_csec_qualification as $index =>$record)
            {
                if( CsecQualification::getQualifications($record->personid) == true)
                    unset($applicants_without_csec_qualification[$index]);
            }
        }
        else
        {
            $applicants = Application::find()
                        ->innerJoin('applicant', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                        ->innerJoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where(['application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => [2,3,4,5,6,7,8,9],
                                'applicant.isexternal' => 0, 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                                'csec_centre.cseccentreid' => $cseccentreid,
                                'csec_qualification.isactive' => 1, 'csec_qualification.isdeleted' => 0,
                                'application_period.iscomplete' => 0, 'application_period.isactive' => 1,
                                'academic_offering.isdeleted' => 0])
                        ->groupBy('application.personid')
                        ->all();
        }
        
        $elegible = array();
        
        foreach ($applicants as $key => $applicant)
        {
            if($external == true)       // if attempting to retrieve external applicants
            {    
                $non_verified1 = CsecQualification::findOne(['personid' => $applicant->personid, 'isverified' => 0, 'isdeleted' => 0, 'isactive' => 1]);
                $queried1 = CsecQualification::findOne(['personid' => $applicant->personid, 'isqueried' => 1, 'isdeleted' => 0, 'isactive' => 1]);

                $post_qualification = PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid);
                $external_qualification = ExternalQualification::getExternalQualifications($applicant->personid);

                if($post_qualification == false && $external_qualification == false)
                {
                    if ($non_verified1 == true &&  $queried1 == false)
                        $elegible [] =$applicants[$key];
                }
                elseif($post_qualification == true  &&  $external_qualification==false)
                {
                    $non_verified2 = $post_qualification->isverified;
                    $queried2 = $post_qualification->isqueried;
                    if ($queried1 == false && $queried2 == 0 && ($non_verified1 == true || $non_verified2 == 0))
                       $elegible [] =$applicants[$key];
                }
                elseif($post_qualification == false  &&  $external_qualification==true)
                {
                    $external_qualification_isverified = $external_qualification->isverified;
                    $external_qualification_isqueried = $external_qualification->isqueried;
                    if ($queried1 == false && $external_qualification_isqueried == 0 && ($non_verified1 == true || $external_qualification_isverified == 0))
                       $elegible [] =$applicants[$key];
                }
                elseif($post_qualification == true  &&  $external_qualification==true)
                {
                    $non_verified2 = $post_qualification->isverified;
                    $queried2 = $post_qualification->isqueried;

                    $external_qualification_isverified = $external_qualification->isverified;
                    $external_qualification_isqueried = $external_qualification->isqueried;

                    if ($queried1 == false  && $queried2 == 0 && $external_qualification_isqueried == 0 && ($non_verified1 == true || $non_verified2 == 0 || $external_qualification_isverified == 0))
                       $elegible [] =$applicants[$key];
                }
            }
            else    // if attempting to retrieve "non-external" applicants
            {
                $non_verified1 = CsecQualification::findOne(['personid' => $applicant->personid, 'isverified' => 0, 'isdeleted' => 0, 'isactive' => 1]);
                $queried1 = CsecQualification::findOne(['personid' => $applicant->personid, 'isqueried' => 1, 'isdeleted' => 0, 'isactive' => 1]);
                $qualification = PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid);

                if($qualification == true)
                {
                    $non_verified2 = $qualification->isverified;
                    $queried2 = $qualification->isqueried;

                    if ($queried1 == false && $queried2 == 0 && ($non_verified1 == true || $non_verified2 == 0))
                       $elegible[] =$applicants[$key];
                }
                else
                {
                    if ($queried1 == false && $non_verified1 == true)
                       $elegible[] = $applicants[$key];
                }
                
            }
        }
        
        if($external == true)
        {
            //adds empty $applicants to array
            foreach ($applicants_without_csec_qualification as $record)
            {
                $elegible[] = $record;
            }
        }
        
        return $elegible;
    }
    
    
    /**
     * Returns count of pending applications for a testing centre
     * 
     * @param type $cseccentreid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 16/03/2016
     * Date Last Modified: 16/03/2016 | 04/04/2016
     */
    public static function centreApplicantsPendingCount($cseccentreid, $external = false)
    {
        if ($external == true)
            return count(self::centreApplicantsPending($cseccentreid, true));
        else
            return count(self::centreApplicantsPending($cseccentreid));
    }
    
    
    /**
     * Determines if and application is the application currently under consideration
     * 
     * @param type $applications
     * @param type $application_status
     * @param type $candidate
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 20/02/2016
     * Date Last Modified: 20/02/2016
     */
    public static function isTarget($applications, $application_status, $candidate)
    {
        $count = count($applications);
                
        if ($application_status == 6)       //if reject
        {
            $target_application = $applications[($count-1)];
        }
        
        
        elseif ($application_status == 2)   //if unverifed
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid==2)
                {
                    $target_application = $application;
                    break;
                }
            }
        }
        
            
        elseif ($application_status == 3)   //if pending
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid==3)
                {
                    $target_application = $application;
                    break;
                }
            }
        }

        elseif ($application_status == 4)    //if shortlist
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid == 4)
                {
                    $target_application = $application;
                    break;
                }
            }
        }

        elseif ($application_status == 7)   //if borderline
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid == 7)
                {
                    $target_application = $application;
                    break;
                }
            }
        }

        elseif ($application_status == 8)    //if conditional offer
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid == 8)
                {
                    $target_application = $application;
                    break;
                }
            }
        }

        elseif ($application_status == 9)   //if full-offer
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid == 9)
                {
                    $target_application = $application;
                    break;
                }
            }
        }

        elseif ($application_status == 10)  //if conditional-offer-reject
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid == 10)
                {
                    $target_application = $application;
                    break;
                }
            }
        }
        
        if ($candidate->ordering == $target_application->ordering)
            return true;
        return false;
    }
    
    
    /**
     * Retrieves application currently under consideration
     * 
     * @param type $applications
     * @param type $application_status
     * @param type $candidate
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 20/02/2016
     * Date Last Modified: 20/02/2016
     */
    public static function getTarget($applications, $application_status)
    {
        $count = count($applications);
                
        if ($application_status == 6)       //if reject
        {
            $target_application = $applications[($count-1)];
        }
         
        elseif ($application_status == 2)   //if unnverified
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid==2)
                {
                    $target_application = $application;
                    break;
                }
            }
        }
        
        elseif ($application_status == 3)   //if pending
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid==3)
                {
                    $target_application = $application;
                    break;
                }
            }
        }

        elseif ($application_status == 4)    //if shortlist
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid == 4)
                {
                    $target_application = $application;
                    break;
                }
            }
        }

        elseif ($application_status == 7)   //if borderline
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid == 7)
                {
                    $target_application = $application;
                    break;
                }
            }
        }

        elseif ($application_status == 8)    //if conditional offer
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid == 8)
                {
                    $target_application = $application;
                    break;
                }
            }
        }

        elseif ($application_status == 9)   //if full-offer
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid == 9)
                {
                    $target_application = $application;
                    break;
                }
            }
        }

        elseif ($application_status == 10)  //if conditional-offer-reject
        {
            foreach($applications as $application)
            {
                if ($application->applicationstatusid == 10)
                {
                    $target_application = $application;
                    break;
                }
            }
        }
        
        return $target_application;
    }
    
    
    /**
     * Returns the position of an application in the appliction array
     * 
     * @param type $applications
     * @param type $application
     * @return boolean|int
     * 
     * Author: Laurence Charles
     * Date Created: 20/02/2016
     * Date Last Modified: 20/02/2016
     */
    public static function getPosition($applications, $application)
    {
        $count = count($applications);
        
        for ($i = 0 ; $i < $count ; $i++)
        {
            if($applications[$i]->applicationid == $application->applicationid)
                return $i;
        }
        return false;
    }
    
    
    /**
     * Determines the existence of Nursing Assistant applications
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 04/02/2016
     * Date Last Modified: 04/02/2016
     */
    public static function hasMidwiferyApplication($id){
        $db = Yii::$app->db;
        $query = $db->createCommand(
                "SELECT *"
                . " FROM application"
                . " JOIN academic_offering"
                . " ON application.academicofferingid = academic_offering.academicofferingid"
                . " JOIN programme_catalog"
                . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
                . " WHERE programme_catalog.name = 'Midwifery'"
                . " AND academic_offering.isactive = 1"
                . " AND academic_offering.isdeleted = 0"
                . " AND application.isactive = 1"
                . " AND application.isdeleted = 0"
                . " AND application.personid=" . $id . ";"
                )
                ->queryAll();
        if (count($query) >0)
            return true;
        return false;
    }    
    
    
    /**
     * Returns a count of the applicants for current active application periods
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 05/03/2016
     * Date Last Modified: 05/03/2016
     */
    public static function countActiveApplications($divisionid = NULL)
    {
        if ($divisionid == NULL)
        {
            $applicants = Application::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                            ->where(['application_period.iscomplete' => 0, /*'application_period.applicationperiodstatusid' => 5,*/ 'application_period.isactive' => 1,
                                    'application.isdeleted' => 0, 'application.applicationstatusid' => [2,3,4,5,6,7,8,9],
                                    'academic_offering.isdeleted' => 0
                                    ])
                            ->groupby('application.personid')
                            ->count();
        }
        else
        {
            $applicants = Application::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                            ->where(['application_period.iscomplete' => 0, 'application_period.isactive' => 1,
                                    'application.isdeleted' => 0, 'application.applicationstatusid' => [2,3,4,5,6,7,8,9], 'application.divisionid' => $divisionid,
                                    'academic_offering.isdeleted' => 0
                                    ])
                            ->groupby('application.personid')
                            ->count();
        }
        return $applicants;
    }
    
    
    /**
     * Returns a count of the applicants that have been verified
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 06/03/2016
     * Date Last Modified: 06/03/2016
     */
    public static function countVerifiedApplications($divisionid = NULL)
    {
        if ($divisionid == NULL)
        {
            $applicants = Application::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                            ->where(['application_period.iscomplete' => 0, /*'application_period.applicationperiodstatusid' => 5,*/ 'application_period.isactive' => 1,
                                    'application.isdeleted' => 0, 'application.applicationstatusid' => [3,4,5,6,7,8,9],
                                    'academic_offering.isdeleted' => 0
                                    ])
                            ->groupby('application.personid')
                            ->count();
        }
        else
        {
            $applicants = Application::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                            ->where(['application_period.iscomplete' => 0, 'application_period.isactive' => 1,
                                    'application.isdeleted' => 0, 'application.applicationstatusid' => [3,4,5,6,7,8,9], 'application.divisionid' => $divisionid,
                                    'academic_offering.isdeleted' => 0
                                    ])
                            ->groupby('application.personid')
                            ->count();
        }
            return $applicants;
    }
    
    
    
}
