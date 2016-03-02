<?php

namespace frontend\models;

use Yii;
use common\models\User;
use frontend\models\Application;
use frontend\models\Offer;
use frontend\models\ProgrammeCatalog;
use frontend\models\CsecQualification;

/**
 * This is the model class for table "applicant".
 *
 * @property string $applicantid
 * @property string $applicanttypeid
 * @property string $personid
 * @property string $potentialstudentid
 * @property string $title
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $gender
 * @property string $dateofbirth
 * @property string $photopath
 * @property integer $bursarystatus
 * @property string $sponsorname
 * @property string $clubs
 * @property string $otherinterests
 * @property integer $isactive
 * @property integer $isdeleted
 * @property string $maritalstatus
 * @property string $nationality
 * @property string $religion
 * @property string $placeofbirth
 * @property string $nationalsports 
* @property string $othersports 
* @property string $otheracademics 
* @property string $isexternal 
 *
 * @property Person $person
 */
class Applicant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'applicant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'potentialstudentid', 'applicantintentid', 'bursarystatus', 'isactive', 'isdeleted', 'isexternal'], 'integer'],
            [['dateofbirth'], 'safe'],
            [['clubs', 'otherinterests', 'nationalsports', 'othersports', 'otheracademics'], 'string'],
            [['title'], 'string', 'max' => 3],
            [['firstname', 'middlename', 'lastname', 'nationality', 'religion', 'placeofbirth'], 'string', 'max' => 45],
            [['gender'], 'string', 'max' => 6],
            [['photopath', 'sponsorname'], 'string', 'max' => 100],
            [['maritalstatus'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicantid' => 'Applicantid',
            'personid' => 'Personid',
            'potentialstudentid' => 'Potentialstudentid',
            'applicantintentid' => 'Applicantintentid',
            'title' => 'Title',
            'firstname' => 'First Name',
            'middlename' => 'Middle Name',
            'lastname' => 'Last Name',
            'gender' => 'Gender',
            'dateofbirth' => 'Date of Birth',
            'photopath' => 'Photo Path',
            'bursarystatus' => 'Bursary Status',
            'sponsorname' => 'Sponsor',
            'clubs' => 'Clubs',
            'otherinterests' => 'Other Interests',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'maritalstatus' => 'Marital Status',
            'nationality' => 'Nationality',
            'religion' => 'Religion',
            'placeofbirth' => 'Place of Birth',
            'nationalsports' => 'National Sports',
            'othersports' => 'Other Sports',
            'otheracademics' => 'Other Academics',
            'isexternal' => 'External',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }
    
    
    /**
    * Finds applicant by personid
    *
    * @param string $username
    * @return static|null
    * 
    * Author: Laurence Charles
    * Date Created: 21/12/2015
    * Date Last Modified: 21/12/2015 
    */
    public static function findByPersonID($id)
    {
        return static::findOne(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0]);
    }
    
    
    /**
     * Load applicant model with data from 'General' view
     * 
     * @param type $student_profile
     * 
     * Author: Laurence Charles
     * Date Created: 25/12/2015
     * Date Last Modified: 28/12/2015
     */
    public function loadGeneral($student_profile)
    {
        $this->title = $student_profile->title;
        $this->firstname = $student_profile->firstname;       
        $this->middlename = $student_profile->middlename;   
        $this->lastname = $student_profile->lastname;
        $this->dateofbirth = $student_profile->dateofbirth;
        $this->gender = $student_profile->gender;
        $this->nationality = $student_profile->nationality;
        $this->placeofbirth = $student_profile->placeofbirth;
        $this->religion = $student_profile->religion;     
        $this->maritalstatus = $student_profile->maritalstatus;
        $this->sponsorname = $student_profile->sponsorname;
    }
    
    
    /**
     * Used to determine the intended division of an applicant
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 09/01/2016
     * Date Last Modified: 09/01/2016 | 09/02/2016
     */
    public static function getApplicantIntent($id)
    {
        $applicants = Applicant::find()
                    ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
        $count = count($applicants);
        if($count > 0)
        {
            $target = $count-1;
            $applicant = $applicants[$target];
            return $applicant->applicantintentid;
        }
        return false;    
    }
    
    
    /**
     * Returns 'true' if applicant indicated that that they have external qualifications
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 18/02/2016
     * Date Last Modified: 18/02/2016
     */
    public static function hasExternal($personid)
    {
        $applicant = Applicant::find()
                    ->where(['personid' => $personid])
                    ->one();
        if ($applicant == true)
        {
            if($applicant->isexternal == 1)
                return true;
        }
        return false;
    }
    
    
    /**
     * Returns a list of applicant for a particular academic year
     * 
     * @param type $academic_year
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016 | 23/02/2016
     */
    public static function getActiveApplicants($division_id)
    {
        // retrieves all applicant records
        if ($division_id == 1)
        {
            $applicants = Applicant::find()
                    ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                    ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                    ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->where(['applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                            'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                            'application_period.isactive' => 1, 'application_period.applicationperiodstatusid' => 5,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => [3,4,5,6,7,8,9,10]
                            ])
                    ->groupBy('applicant.personid')
                    ->all();
        }
        
        // retrieves applicants based on division they applied to
        else
        {
            $applicants = Applicant::find()
                    ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                    ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                     ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->where(['applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                            'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                            'application_period.isactive' => 1, 'application_period.applicationperiodstatusid' => 5,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.divisionid' => $division_id,  'application.applicationstatusid' => [3,4,5,6,7,8,9,10]
                            ])
                    ->groupBy('applicant.personid')
                    ->all();
        }
        if (count($applicants) > 0)
            return $applicants;
        return false;
    }
    
    
    /**
     * Returns a list of applicant for a particular academic year
     * 
     * @param type $academic_year
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016
     */
    public static function getApplicantsByYear($academic_year, $division_id)
    {
        // retrieves all applicant records
        if ($division_id == 1)
        {
            $applicants = Applicant::find()
                    ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                    ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                    ->innerJoin('academic_year', '`academic_offering`.`academicyearid` = `academic_year`.`academicyearid`')
                    ->where(['applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                            'application.isactive' => 1, 'application.isdeleted' => 0,
                            'academic_year.title' => $academic_year
                            ])
                    ->groupBy('application.personid')
                    ->all();
        }
        
        // retrieves applicants based on division they applied to
        else
        {
            $applicants = Applicant::find()
                    ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                    ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                    ->innerJoin('academic_year', '`academic_offering`.`academicyearid` = `academic_year`.`academicyearid`')
                    ->where(['applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.divisionid' => $division_id,
                            'academic_year.title' => $academic_year
                            ])
                    ->groupBy('application.persionid')
                    ->all();
        }
        if (count($applicants) > 0)
            return $applicants;
        return false;
    }
    
    
    /**
     * Gets the programme and applicantion status of an applicant
     * 
     * @param type $personid
     * 
     * Author: Laurence Charles
     * Date Created: 24/02/2016
     * Date Last Modified: 24/02/2016
     */
    public static function getApplicantInformation($personid)
    {
        $combined = array();
        $keys = array();
        $values = array();
        array_push($keys, "prog");
        array_push($keys, "status");
        $applications = Application::find()
                    ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                    ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->where(['application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $personid,
                            'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                            'application_period.isactive' => 1, 'application_period.applicationperiodstatusid' => 5,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => [3,4,5,6,7,8,9,10]
                            ])
                    ->all();
        $count = count($applications);
        
        if ($count == 1)
            $application_status = $applications[0]->applicationstatusid;
        
        elseif ($count == 2)
        {
            //if rejected
            if($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6)
                $application_status = 6;
            
            //if pending
            elseif(
                        ($applications[0]->applicationstatusid == 3  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 3)
                  )
                $application_status = 3;
            
            //if shortlisted
            elseif(
                        ($applications[0]->applicationstatusid == 4  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 4)
                  )
                    $application_status = 4;
            
            //if borderlined
            elseif(
                        ($applications[0]->applicationstatusid == 7  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 7)
                  )
                    $application_status = 7;
            
            //if interview-offer
            elseif(
                        ($applications[0]->applicationstatusid == 8  && $applications[1]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 8)
                  )
                    $application_status = 8;
            
            //if offer
            elseif(
                        ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 9)
                  )
                    $application_status = 9;
            
            //is reject of conditional offer
            elseif(
                        ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10)
                  )
                    $application_status =10;
        }
        
        elseif ($count == 3)
        {
            //if rejected
            if($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                $application_status = 6;
            
            //if pending
            elseif(
                        ($applications[0]->applicationstatusid == 3  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 3)
                  )
                    $application_status = 3;
            
            //if shortlisted
            elseif(
                        ($applications[0]->applicationstatusid == 4  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 4 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 4)
                  )
                    $application_status = 4;
            
            //if borderlined
            elseif(
                        ($applications[0]->applicationstatusid == 7  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 7 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 7)
                  )
                    $application_status = 7;
            
            //if interview-offer
            if(
                        ($applications[0]->applicationstatusid == 8  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 8 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 8)
                  )
                    $application_status = 8;
            
            //if offer
            elseif(
                        ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 9 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 9)
                  )
            $application_status = 9;
            
            //is reject of conditional offer
            if(
                        ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 10)
                  )
                $application_status =10;
        }
        
        $target = Application::getTarget($applications, $application_status);
        $programme_record = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                            ->where(['academic_offering.academicofferingid' => $target->academicofferingid])
                            ->one();
        $name = $programme_record->getFullName();
        
        array_push($values, $name);
        array_push($values, $application_status);
        
        $combined = array_combine($keys, $values);
        return $combined;
    }
    
    
    /**
     * Determines if application is currently considered a "Rejected"
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016
     */
    public static function isRejected($personid)
    {
        $applications = Application::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        $count = count($applications);
        if ($count > 0)
        {   
            /* If applicant has 1 application; they are considered rejected if; 
             * application 1 -> Rejected
             */
            if($count == 1)     
            {
                if ($applications[0]->applicationstatusid == 6)
                    return true;
            }
            
            /* If applicant has 2 applications; they are considered rejected if; 
             * Application 1 -> Rejected
             * Application 2 -> Rejected
             */
            elseif($count == 2)
            {
                if($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6)
                    return true;
            }
            
            /* If applicant has 3 applications; they are considered rejected if; 
             * Application 1 -> Rejected
             * Application 2 -> Rejected
             * Application 3 -> Rejected
             */
            elseif($count == 3)
            {
                if($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                    return true;
            }
        }
        return false;
        
    }
    
    
    /**
     * Determines if application is currently considered a "Pending"
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016
     */
    public static function isPending($personid)
    {
        $applications = Application::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
        $count = count($applications);
        if ($count > 0)
        {   
            /* If applicant has 1 application; they are considered pending if; 
             * application 1 -> Pending
             */
            if($count == 1)     
            {
                if ($applications[0]->applicationstatusid == 3)
                    return true;
            }
            
            /* If applicant has 2 applications; they are considered pending if; 
             * Application 1 -> Pending | Rejected
             * Application 2 -> Pending | Pending
             */
            elseif($count == 2)
            {
                if(
                        ($applications[0]->applicationstatusid == 3  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 3)
                  )
                    return true;
            }
            
            /* If applicant has 3 applications; they are considered pending if; 
             * Application 1 -> Pending | Rejected | Rejected
             * Application 2 -> Pending | Pending  | Rejected
             * Application 3 -> Pending | Pending  | Pending
             */
            elseif($count == 3)
            {
                if(
                        ($applications[0]->applicationstatusid == 3  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 3)
                  )
                    return true;
            }
        }
        return false;
        
    }
    
    
    /**
     * Determines if application is currently considered a "Shortlisted"
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016
     */
    public static function isShortlisted($personid)
    {
        $applications = Application::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
        $count = count($applications);
        if ($count > 0)
        {   
            /* If applicant has 1 application; they are considered Shortlisted if; 
             * application 1 -> Shortlisted
             */
            if($count == 1)     
            {
                if ($applications[0]->applicationstatusid == 4)
                    return true;
            }
            
            /* If applicant has 2 applications; they are considered shortlisted if; 
             * Application 1 -> Shortlisted | Rejected
             * Application 2 -> Pending    | Shortlisted
             */
            elseif($count == 2)
            {
                if(
                        ($applications[0]->applicationstatusid == 4  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 4)
                  )
                    return true;
            }
            
            /* If applicant has 3 applications; they are considered shortlisted if; 
             * Application 1 -> Shortlisted | Rejected     | Rejected
             * Application 2 -> Pending     | Shortlisted  | Rejected
             * Application 3 -> Pending     | Pending      | Shortlisted
             */
            elseif($count == 3)
            {
                if(
                        ($applications[0]->applicationstatusid == 4  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 4 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 4)
                  )
                    return true;
            }
        }
        return false;
        
    }
    
    
    /**
     * Determines if application is currently considered a "Borderline"
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016
     */
    public static function isBorderline($personid)
    {
        $applications = Application::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
        $count = count($applications);
        if ($count > 0)
        {   
            /* If applicant has 1 application; they are considered Borderline if; 
             * application 1 -> Borderline
             */
            if($count == 1)     
            {
                if ($applications[0]->applicationstatusid == 7)
                    return true;
            }
            
            /* If applicant has 2 applications; they are considered borderline if; 
             * Application 1 -> Borderline  | Rejected
             * Application 2 -> Pending   | Borderline
             */
            elseif($count == 2)
            {
                if(
                        ($applications[0]->applicationstatusid == 7  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 7)
                  )
                    return true;
            }
            
            /* If applicant has 3 applications; they are considered borderline if; 
             * Application 1 -> Borderline  | Rejected     | Rejected
             * Application 2 -> Pending     | Borderline   | Rejected
             * Application 3 -> Pending     | Pending      | Borderline
             */
            elseif($count == 3)
            {
                if(
                        ($applications[0]->applicationstatusid == 7  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 7 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 7)
                  )
                    return true;
            }
        }
        return false;
        
    }
    
    
    /**
     * Determines if application is currently considered a "InterviewOffer"
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016
     */
    public static function isInterviewOffer($personid)
    {
        $applications = Application::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
        $count = count($applications);
        if ($count > 0)
        {   
            /* If applicant has 1 application; they are considered InterviewOffer if; 
             * application 1 -> InterviewOffer
             */
            if($count == 1)     
            {
                if ($applications[0]->applicationstatusid == 8)
                    return true;
            }
            
            /* If applicant has 2 applications; they are considered interview-offer if; 
             * Application 1 -> InterviewOffer  | Rejected
             * Application 2 -> Rejected        | InterviewOffer
             */
            elseif($count == 2)
            {
                if(
                        ($applications[0]->applicationstatusid == 8  && $applications[1]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 8)
                  )
                    return true;
            }
            
            /* If applicant has 3 applications; they are considered interview-offer if; 
             * Application 1 -> InterviewOffer  | Rejected         | Rejected
             * Application 2 -> Rejected        | InterviewOffer   | Rejected
             * Application 3 -> Rejected        | Rejected         | InterviewOffer
             */
            elseif($count == 3)
            {
                if(
                        ($applications[0]->applicationstatusid == 8  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 8 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 8)
                  )
                    return true;
            }
        }
        return false;
        
    }
    
    
    /**
     * Determines if application is currently considered a "Offer"
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016
     */
    public static function isOffer($personid)
    {
        $applications = Application::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
        $count = count($applications);
        if ($count > 0)
        {   
            /* If applicant has 1 application; they are considered Offer if; 
             * application 1 -> Offer
             */
            if($count == 1)     
            {
                if ($applications[0]->applicationstatusid == 9)
                    return true;
            }
            
            /* If applicant has 2 applications; they are considered offer if; 
             * Application 1 -> Offer   | Rejected
             * Application 2 -> Rejected| Offer
             */
            elseif($count == 2)
            {
                if(
                        ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 9)
                  )
                    return true;
            }
            
            /* If applicant has 3 applications; they are considered offer if; 
             * Application 1 -> Offer           | Rejected         | Rejected
             * Application 2 -> Rejected        | Offer            | Rejected
             * Application 3 -> Rejected        | Rejected         | Offer
             */
            elseif($count == 3)
            {
                if(
                        ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 9 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 9)
                  )
                    return true;
            }
        }
        return false;
        
    }
    
    
    /**
     * Determines if application is currently considered a "RejectedConditionalOffer"
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016
     */
    public static function isRejectedConditionalOffer($personid)
    {
        $applications = Application::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
        $count = count($applications);
        if ($count > 0)
        {   
            /* If applicant has 1 application; they are considered RejectedConditionalOffer if; 
             * application 1 -> RejectedConditionalOffer
             */
            if($count == 1)     
            {
                if ($applications[0]->applicationstatusid == 10)
                    return true;
            }
            
            /* If applicant has 2 applications; they are considered Rejected-conditional-offer if; 
             * Application 1 -> RejectedConditionalOffer | Rejected
             * Application 2 -> Rejected                 | RejectedConditionalOffer
             */
            elseif($count == 2)
            {
                if(
                        ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10)
                  )
                    return true;
            }
            
            /* If applicant has 3 applications; they are considered Rejected-conditional-offer if; 
             * Application 1 -> RejectedConditionalOffer | Rejected                | Rejected
             * Application 2 -> Rejected                 | RejectedConditionalOffer| Rejected
             * Application 3 -> Rejected                 | Rejected                | RejectedConditionalOffer
             */
            elseif($count == 3)
            {
                if(
                        ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 10)
                  )
                    return true;
            }
        }
        return false;
        
    }
    
    
    /**
     * Retrieves applicants based on the current application status 
     * 
     * @param type $status_id
     * @param type $division_id
     * 
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016
     */
    public static function getByStatus($status_id, $division_id)
    {
        $applicants = array();
        
//        $apps = self::getApplicantsByYear(AcademicYear::getCurrentYear()->title, $division_id);
        $apps = self::getActiveApplicants($division_id);
        
        if ($apps)
        {
            foreach($apps as $key => $app)
            {
                // If seeking 'Rejected'
                if ($status_id == 6)        
                {
                    if(self::isRejected($app->personid) == false)
                        unset($apps[$key]);
                }

                // If seeking 'Pending'
                elseif ($status_id == 3)        
                {
                    if(self::isPending($app->personid) == false)
                        unset($apps[$key]);
                }

                // If seeking 'Shortlisted'
                elseif ($status_id == 4)        
                {
                    if(self::isShortlisted($app->personid) == false)
                        unset($apps[$key]);
                }


                // If seeking 'Borderline'
                elseif ($status_id == 7)        
                {
                    if(self::isBorderline($app->personid) == false)
                        unset($apps[$key]);
                }

                // If seeking 'InterviewOffer'
                elseif ($status_id == 8)        
                {
                    if(self::isInterviewOffer($app->personid) == false)
                        unset($apps[$key]);
                }

                // If seeking 'Offer'
                elseif ($status_id == 9)        
                {
                    if(self::isOffer($app->personid) == false)
                        unset($apps[$key]);
                }

                // If seeking 'RejectedConditionalOffer'
                elseif ($status_id == 10)        
                {
                    if(self::isRejectedConditionalOffer($app->personid) == false)
                        unset($apps[$key]);
                }
            }
            $applicants = $apps;
        }
        return $applicants;
    }
    
    
    /**
     * Returns an array indicating the status [empty or populated] of the variabe fields[sponsorname, nationalsports, otherssports, clubs, otherinterests
     * 
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created: ??
     * Date Last Modified: 10/11/2015
     */
    public function variableDetails()
    {
        $details = array();
        if (is_null($this->sponsorname) == false  && strcmp($this->sponsorname,"") !=0)
        {
            array_push($details, 1);
        }
        else
        {
            array_push($details, 0);
        }
        
        if (is_null($this->nationalsports) == false  && strcmp($this->nationalsports,"") !=0){
            array_push($details, 1);
        }
        else{
            array_push($details, 0);
        }
        
        if (is_null($this->othersports) == false  && strcmp($this->othersports,"") !=0)
        {
            array_push($details, 1);
        }
        else
        {
            array_push($details, 0);
        }
        
        if (is_null($this->clubs) == false  && strcmp($this->clubs,"") !=0)
        {
            array_push($details, 1);
        }
        else
        {
            array_push($details, 0);
        }
        
        if (is_null($this->otherinterests) == false  && strcmp($this->otherinterests,"") !=0)
        {
            array_push($details, 1);
        }
        else
        {
            array_push($details, 0);
        }
        
        return $details;
    }
    
    
    /**
     * Gets the programme and applicantion status of an applicant
     * 
     * @param type $personid
     * 
     * Author: Laurence Charles
     * Date Created: 28/02/2016
     * Date Last Modified: 28/02/2016
     */
    public static function getSuccessfulApplicantInformation($personid)
    {
        $offer = Offer::getActiveOffer($personid);
        
        $programme = ProgrammeCatalog::getApplicantProgramme($offer->applicationid);
        
        
        
        
        $combined = array();
        $keys = array();
        $values = array();
        array_push($keys, "prog");
        array_push($keys, "status");
        $applications = Application::find()
                    ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                    ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->where(['application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $personid,
                            'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                            'application_period.isactive' => 1, 'application_period.applicationperiodstatusid' => 5,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => [3,4,5,6,7,8,9,10]
                            ])
                    ->all();
        $count = count($applications);
        
        if ($count == 1)
            $application_status = $applications[0]->applicationstatusid;
        
        elseif ($count == 2)
        {
            //if rejected
            if($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6)
                $application_status = 6;
            
            //if pending
            elseif(
                        ($applications[0]->applicationstatusid == 3  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 3)
                  )
                $application_status = 3;
            
            //if shortlisted
            elseif(
                        ($applications[0]->applicationstatusid == 4  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 4)
                  )
                    $application_status = 4;
            
            //if borderlined
            elseif(
                        ($applications[0]->applicationstatusid == 7  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 7)
                  )
                    $application_status = 7;
            
            //if interview-offer
            elseif(
                        ($applications[0]->applicationstatusid == 8  && $applications[1]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 8)
                  )
                    $application_status = 8;
            
            //if offer
            elseif(
                        ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 9)
                  )
                    $application_status = 9;
            
            //is reject of conditional offer
            elseif(
                        ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10)
                  )
                    $application_status =10;
        }
        
        elseif ($count == 3)
        {
            //if rejected
            if($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                $application_status = 6;
            
            //if pending
            elseif(
                        ($applications[0]->applicationstatusid == 3  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 3)
                  )
                    $application_status = 3;
            
            //if shortlisted
            elseif(
                        ($applications[0]->applicationstatusid == 4  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 4 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 4)
                  )
                    $application_status = 4;
            
            //if borderlined
            elseif(
                        ($applications[0]->applicationstatusid == 7  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 7 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 7)
                  )
                    $application_status = 7;
            
            //if interview-offer
            if(
                        ($applications[0]->applicationstatusid == 8  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 8 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 8)
                  )
                    $application_status = 8;
            
            //if offer
            elseif(
                        ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 9 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 9)
                  )
            $application_status = 9;
            
            //is reject of conditional offer
            if(
                        ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 10)
                  )
                $application_status =10;
        }
        
        $target = Application::getTarget($applications, $application_status);
        $programme_record = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                            ->where(['academic_offering.academicofferingid' => $target->academicofferingid])
                            ->one();
        $name = $programme_record->getFullName();
        
        array_push($values, $name);
        array_push($values, $application_status);
        
        $combined = array_combine($keys, $values);
        return $combined;
    }
    
    
    /**
     * Returns 'true' if an applicant is verified
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 01/03/2016
     * Date Last Modified: 01/03/2016
     */
    public static function isVerified($personid)
    {
        $qualifications = CsecQualification::find()
                    ->innerJoin('application', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                    ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->where(['csec_qualification.personid' => $personid,
                            'application.isactive' => 1, 'application.isdeleted' => 0,
                            'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                            'application_period.isactive' => 1, 'application_period.applicationperiodstatusid' => 5,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => 2
                            ])
                    ->all();
        $all_verified = true;
        foreach($qualifications as $qualification)
        {
            if ($qualification->isverified == 0)
                $all_verified = false;
        }
        
        return $all_verified;
    }
  
    
}
