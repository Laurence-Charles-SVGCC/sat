<?php

namespace frontend\models;

use Yii;
use common\models\User;
use frontend\models\Application;

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
             * Application 2 -> Pending     | Shortlisted
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
             * Application 2 -> Pending     | Borderline
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
             * Application 2 -> Pending         | InterviewOffer
             */
            elseif($count == 2)
            {
                if(
                        ($applications[0]->applicationstatusid == 8  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 8)
                  )
                    return true;
            }
            
            /* If applicant has 3 applications; they are considered interview-offer if; 
             * Application 1 -> InterviewOffer  | Rejected         | Rejected
             * Application 2 -> Pending         | InterviewOffer   | Rejected
             * Application 3 -> Pending         | Pending          | InterviewOffer
             */
            elseif($count == 3)
            {
                if(
                        ($applications[0]->applicationstatusid == 8  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 8 && $applications[2]->applicationstatusid == 3)
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
        
        $apps = self::getApplicantsByYear(AcademicYear::getCurrentYear()->title, $division_id);
        
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
    
    
    
  
    
}
