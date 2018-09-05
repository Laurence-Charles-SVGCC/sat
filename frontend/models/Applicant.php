<?php

namespace frontend\models;

use Yii;
use common\models\User;
use frontend\models\Application;
use frontend\models\Applicant;
use frontend\models\ApplicantRegistration;
use frontend\models\Offer;
use frontend\models\ProgrammeCatalog;
use frontend\models\ApplicationCapeSubject;
use frontend\models\CsecQualification;
use frontend\models\Rejection;
use frontend\models\RejectionApplications;

use yii\custom\ModelNotFoundException;
use frontend\models\PersonInstitution;
use frontend\models\Institution;
use frontend\models\data_formatter\ArrayFormatter;

use frontend\models\CompulsoryRelation;
use frontend\models\Phone;
use frontend\models\CriminalRecord;
use frontend\models\Reference;
use frontend\models\TeachingAdditionalInfo;
use frontend\models\TeachingExperience;
use frontend\models\NursingAdditionalInfo;
use frontend\models\NursingExperience;

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
 * @property string $verifier
 * @property string $hasduplicate
 *  @property string $isprimary
 *  @property string $hasdeferred
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
            [['personid', 'potentialstudentid', 'applicantintentid', 'bursarystatus', 'isactive', 'isdeleted', 'isexternal', 'verifier', 'hasduplicate', 'isprimary', 'hasdeferred'], 'integer'],
            [['dateofbirth'], 'safe'],
            [['clubs', 'otherinterests', 'nationalsports', 'othersports', 'otheracademics'], 'string'],
            [['title'], 'string', 'max' => 4],
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
            'verifier' => 'Verifier',
            'hasduplicate' => 'Has Duplicate',
             'isprimary' => 'Is Primary',
             'hasdeferred' => 'Has Deferred'
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
     * Date Last Modified: 19/02/2016 | 23/02/2016 | 20/03/2016
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
                            'application_period.iscomplete' => 0, 'application_period.isactive' => 1,
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
                            'application_period.iscomplete' => 0, 'application_period.isactive' => 1,
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
     * Gets the programme and applicant status of an applicant
     *
     * @param type $personid
     *
     * Author: Laurence Charles
     * Date Created: 24/02/2016
     * Date Last Modified: 2017_08_28
     */
    public static function getApplicantInformation($personid, $unrestricted = false)
    {
        $combined = array();
        $keys = array();
        $values = array();
        array_push($keys, "prog");
        array_push($keys, "status");


        /**
         * applications from the 2015DASGS and 2015 DTVE application periods must be
         * processed differently as the application handling mechanism was subsequently
         * changed
         */
        $old_applications = Application::find()
                ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                ->where(['application.personid' => $personid, 'application.isactive' => 1, 'application.isdeleted' => 0,
                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                        'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.name' => ['DASGS2015', 'DTVE2015']
                    ])
                ->orderBy('application.ordering ASC')
                ->all();

        if ($old_applications)
        {
            $target_application = end($old_applications);
            $application_status = $target_application->applicationstatusid;

            $programme_record = ProgrammeCatalog::find()
                                ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                                ->where(['academic_offering.academicofferingid' => $target_application->academicofferingid])
                                ->one();
        }
        else
        {

            /*
             * if alternative application exist;
             * -> the last altenative application is the the target
             * else
             * -> the determination is a bit more complex
             */
            $alternatives = Application::getCustomApplications($personid);
            if($alternatives)
            {
                $target_application = end($alternatives);
                $application_status = $target_application->applicationstatusid;

                $programme_record = ProgrammeCatalog::find()
                                    ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                                    ->where(['academic_offering.academicofferingid' => $target_application->academicofferingid])
                                    ->one();
            }
            else
            {
                if ($unrestricted)     //if search not limited to current open application periods
                {
                    $applications = Application::find()
                            ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                            ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                            ->where(['application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $personid,
                                    'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                    'application_period.isactive' => 1,
                                    /*'application.isactive' => 1,*/ 'application.isdeleted' => 0, 'application.applicationstatusid' => [2,3,4,5,6,7,8,9,10,11]
                                    ])
                            ->orderBy('application.ordering ASC')
                            ->all();
                }
                else
                {
                    $applications = Application::find()
                                ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                                ->where(['application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $personid,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                        'application_period.iscomplete' => 0, 'application_period.isactive' => 1,
                                        /*'application.isactive' => 1,*/ 'application.isdeleted' => 0, 'application.applicationstatusid' => [2,3,4,5,6,7,8,9,10,11]
                                        ])
                                ->orderBy('application.ordering ASC')
                                ->all();
                }

                $count = count($applications);

                if ($count == 1)
                {
                    $application_status = $applications[0]->applicationstatusid;
                }

                elseif ($count == 2)
                {
                    //if unverified
                    if($applications[0]->applicationstatusid == 2  && $applications[1]->applicationstatusid == 2)
                        $application_status = 2;

                    //if rejected
                    elseif(($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6)
                          || ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6))
                        $application_status = 6;

                    //if pending
                     elseif(($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 3)
                                ||  ($applications[0]->applicationstatusid == 3  && $applications[1]->applicationstatusid == 3)
                                ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 3))
                        $application_status = 3;

                    //if shortlisted
                    elseif(($applications[0]->applicationstatusid == 4  && $applications[1]->applicationstatusid == 3)
                                ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 4)
                                ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 4) )
                            $application_status = 4;

                    //if borderlined
                    elseif(($applications[0]->applicationstatusid == 7  && $applications[1]->applicationstatusid == 3)
                                ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 7)
                                ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 7))
                            $application_status = 7;

                    //if interview-offer
                    elseif(($applications[0]->applicationstatusid == 8  && $applications[1]->applicationstatusid == 6)
                            ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 8)
                            ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 8) )
                            $application_status = 8;

                    //if offer
                    elseif(($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 6)
                            ||  ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 10)
                            ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 9)
                            ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 9) )
                            $application_status = 9;

                    //if reject of interview offer
                    elseif(($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10)
                            ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10))
                            $application_status =10;

                    //if abandoned
                    elseif($applications[0]->applicationstatusid == 11  && $applications[1]->applicationstatusid == 11)
                        $application_status =11;
                }

                elseif ($count == 3)
                {
                    //if unverified
                    if($applications[0]->applicationstatusid == 2  && $applications[1]->applicationstatusid == 2  && $applications[2]->applicationstatusid == 2)
                        $application_status = 2;

                    //if rejected
                    elseif(($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                                || ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                                || ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 6)
                                || ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 6))
                        $application_status = 6;

                    //if pending
                    elseif(($applications[0]->applicationstatusid == 3  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                                ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                                ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                                ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 3)
                                ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 3)
                                ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 3)
                                ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 3) )
                            $application_status = 3;

                    //if shortlisted
                    elseif(($applications[0]->applicationstatusid == 4  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                                ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 4 && $applications[2]->applicationstatusid == 3)
                                ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 4 && $applications[2]->applicationstatusid == 3)
                                ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 4)
                                ||  ($applications[0]->applicationstatusid == 10 && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 4)
                                ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 4)
                                ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 4))
                            $application_status = 4;

                    //if borderlined
                    elseif(($applications[0]->applicationstatusid == 7  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                            ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 7 && $applications[2]->applicationstatusid == 3)
                            ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 7 && $applications[2]->applicationstatusid == 3)
                            ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 7)
                            ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 7)
                            ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 7)
                            ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 7))
                            $application_status = 7;

                    //if interview-offer
                    elseif(($applications[0]->applicationstatusid == 8  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                            ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 8 && $applications[2]->applicationstatusid == 6)
                            ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 8 && $applications[2]->applicationstatusid == 6)
                            ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 8)
                            ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 8)
                            ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 8)
                            ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 8))
                            $application_status = 8;

                    //if offer
                    elseif( ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                        ||  ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 10)
                        ||  ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 6)
                        ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 9 && $applications[2]->applicationstatusid == 6)
                        ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 9 && $applications[2]->applicationstatusid == 6)
                        ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 9)
                        ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 9)
                        ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 9)
                        ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 9))
                    $application_status = 9;

                    //if reject of interview offer
                   elseif(($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 10)
                            ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 10))
                    $application_status =10;

                    //if abandoned
                    elseif($applications[0]->applicationstatusid == 11  && $applications[1]->applicationstatusid == 11 && $applications[2]->applicationstatusid == 11)
                        $application_status =11;
                }

                elseif ($count > 3)
                {
                    foreach ($applications as $app)
                    {
                        $active_offer = Offer::find()
                                ->where(['applicationid' => $app->applicationid, 'isactive' => 1, 'isdeleted' => 0, 'ispublished' => 1])
                                ->one();
                        if ($active_offer)
                        {
                            $application_status = 9;
                            break;
                        }
                    }
                }

                $target = Application::getTarget($applications, $application_status);

                $programme_record = ProgrammeCatalog::find()
                                    ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                                    ->where(['academic_offering.academicofferingid' => $target->academicofferingid])
                                    ->one();
            }
        }

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
        /*
         * There must be separate algorithms to determine if an applicant is classified as 'rejected'
         * based on where they possess application chosen soley by them or
         * their programme was chosen for them by the Dean/ Deputy Dean.
         *
         * If applicant has alternative application;
         * -> all applications must have rejected status
         * else
         * -> the applications must be assessed more thoroughly to make that determination
         */
        $custom_applications = Application::getCustomApplications($personid);
        if($custom_applications == true  && end($custom_applications)->applicationstatusid == 6)
        {
            return true;
        }
//        if(Application::getCustomApplications($personid) == true)
//        {
//            $all_applications = Application::find()
//                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
//                    ->all();
//            foreach($all_applications as $application)
//            {
//                if($application->applicationstatusid!=6)
//                    return false;
//            }
//        }
        else
        {
            $applications = Application::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->orderBy('ordering ASC')
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
                 * Application 1 -> Rejected | RejectedConditionalOffer
                 * Application 2 -> Rejected | Rejected
                 */
                elseif($count == 2)
                {
                    if(
                            ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6)
                              || ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6)
                       )
                        return true;
                }

                /* If applicant has 3 applications; they are considered rejected if;
                 * Application 1 -> Rejected | RejectedConditionalOffer | Rejected                            | RejectedConditionalOffer
                 * Application 2 -> Rejected | Rejected                           | RejectedConditionalOffer  | RejectedConditionalOffer
                 * Application 3 -> Rejected | Rejected                           | Rejected                            | Rejected
                 */
                elseif($count == 3)
                {
                    if(
                        ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                        || ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)      // Removed by L.Charles (21/06/2017)
                        || ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 6)     // Removed by L.Charles (21/06/2017)
                        || ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 6)
                        )
                        return true;
                }
            }
            return false;
        }
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
                    ->orderBy('ordering ASC')
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
             * Application 1 -> Rejected | Pending | RejectedConditionalOffer
             * Application 2 -> Pending  | Pending | Pending
             */
            elseif($count == 2)
            {
                if(
                        ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 3  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 3)
                  )
                    return true;
            }

            /* If applicant has 3 applications; they are considered pending if;
             * Application 1 -> Pending | Rejected | RejectedConditionalOffer | Rejected | Rejected                             | RejectedConditionalOffer | RejectedConditionalOffer
             * Application 2 -> Pending | Pending  | Pending                            | Rejected | RejectedConditionalOffer   | Rejected                           | RejectedConditionalOffer
             * Application 3 -> Pending | Pending  | Pending                            | Pending  | Pending                              | Pending                            | Pending
             */
            elseif($count == 3)
            {
                if(
                        ($applications[0]->applicationstatusid == 3  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 3)
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
                    ->orderBy('ordering ASC')
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
             * Application 1 -> Shortlisted | Rejected   | RejectedConditionalOffer
             * Application 2 -> Pending    | Shortlisted | Shortlisted
             */
            elseif($count == 2)
            {
                if(
                        ($applications[0]->applicationstatusid == 4  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 4)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 4)
                  )
                    return true;
            }

            /* If applicant has 3 applications; they are considered shortlisted if;
             * Application 1 -> Shortlisted | Rejected     | RejectedConditionalOffer  | Rejected     | RejectedConditionalOffer | RejectedConditionalOffer | Rejected
             * Application 2 -> Pending     | Shortlisted  | Shortlisted                        | Rejected     | RejectedConditionalOffer  | Rejected                           | RejectedConditionalOffer
             * Application 3 -> Pending     | Pending      | Pending                             | Shortlisted | Shortlisted                         | Shortlisted                       | Shortlisted
             */
            elseif($count == 3)
            {
                if(
                        ($applications[0]->applicationstatusid == 4  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 4 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 4 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 4)
                    ||  ($applications[0]->applicationstatusid == 10 && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 4)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 4)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 4)
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
                    ->orderBy('ordering ASC')
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
             * Application 1 -> Borderline  | Rejected    | RejectedConditionalOffer
             * Application 2 -> Pending      | Borderline | Borderline
             */
            elseif($count == 2)
            {
                if(
                        ($applications[0]->applicationstatusid == 7  && $applications[1]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 7)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 7)
                  )
                    return true;
            }

            /* If applicant has 3 applications; they are considered borderline if;
             * Application 1 -> Borderline  | Rejected     | RejectedConditionalOffer  | Rejected    | RejectedConditionalOffer  | RejectedConditionalOffer  | Rejected
             * Application 2 -> Pending     | Borderline   | Borderline                        | Rejected     | RejectedConditionalOffer  | Rejected                            | RejectedConditionalOffer
             * Application 3 -> Pending     | Pending       | Pending                            | Borderline  |  Borderline                        | Borderline                         | Borderline
             */
            elseif($count == 3)
            {
                if(
                        ($applications[0]->applicationstatusid == 7  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 7 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 7 && $applications[2]->applicationstatusid == 3)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 7)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 7)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 7)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 7)
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
                    ->orderBy('ordering ASC')
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
             * Application 1 -> InterviewOffer  | Rejected             | RejectedConditionalOffer
             * Application 2 -> Rejected            | InterviewOffer   | InterviewOffer
             */
            elseif($count == 2)
            {
                if(
                        ($applications[0]->applicationstatusid == 8  && $applications[1]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 8)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 8)
                  )
                    return true;
            }

            /* If applicant has 3 applications; they are considered interview-offer if;
             * Application 1 -> InterviewOffer  | Rejected            | RejectedConditionalOffer | Rejected             | RejectedConditionalOffer  | RejectedConditionalOffer  | Rejected
             * Application 2 -> Rejected            | InterviewOffer  | InterviewOffer                 | Rejected             | RejectedConditionalOffer  | Rejected                            | RejectedConditionalOffer
             * Application 3 -> Rejected            | Rejected            | Rejected                           | InterviewOffer   | InterviewOffer                  | InterviewOffer                  | InterviewOffer
             */
            elseif($count == 3)
            {
                if(
                        ($applications[0]->applicationstatusid == 8  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 8 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 8 && $applications[2]->applicationstatusid == 6)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 8)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 8)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 8)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 8)
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
        /*
         * There mus be separate algorithms to determine if an applicant is classified as 'rejected'
         * based on where they possess application chosen soley by them or
         * their programme was chosen for them by the Dean/ Deputy Dean.
         *
         * If applicant has aleternative application
         * -> they have an offer
         * else
         * -> the applications must be assessed more thoroughly to make that determination
         */
        $custom_applications = Application::getCustomApplications($personid);
        if($custom_applications == true  && end($custom_applications)->applicationstatusid == 9)
        {
            return true;
        }
        else
        {
            $applications = Application::find()
                        ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                        ->orderBy('ordering ASC')
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
                 * Application 1 -> Offer       | Offer                                  | Rejected  | RejectedConditionalOffer
                 * Application 2 -> Rejected  | RejectedConditionalOffer  | Offer       | Offer
                 */
                elseif($count == 2)
                {
                    if(
                            ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 6)
                        ||  ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 10)
                        ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 9)
                        ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 9)
                      )
                        return true;
                }

                /* If applicant has 3 applications; they are considered offer if;
                 * Application 1 -> Offer        |Offer                                  | Offer                                 | Rejected   | RejectedConditionalOffer  | Rejected | RejectedConditionalOffer | RejectedConditionalOffer  | Rejected
                 * Application 2 -> Rejected   |Rejected                             |RejectedConditionalOffer  | Offer        | Offer                                  | Rejected | RejectedConditionalOffer | Rejected                             | RejectedConditionalOffer
                 * Application 3 -> Rejected   |RejectedConditionalOffer  |Rejected                             | Rejected   | Rejected                             | Offer      | Offer                                 | Offer                                  | Offer
                 */
                elseif($count == 3)
                {
                    if(
                            ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 6)
                        ||  ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 10)
                        ||  ($applications[0]->applicationstatusid == 9  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 6)
                        ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 9 && $applications[2]->applicationstatusid == 6)
                        ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 9 && $applications[2]->applicationstatusid == 6)
                        ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 9)
                        ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 9)
                        ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 9)
                        ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 9)
                      )
                        return true;
                }
            }
            return false;
        }
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
                    ->orderBy('ordering ASC')
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
             * Application 1 -> RejectedConditionalOffer | Rejected                             |  RejectedConditionalOffer
             * Application 2 -> Rejected                           | RejectedConditionalOffer   |  RejectedConditionalOffer
             */
            elseif($count == 2)
            {
//                if(
//                        ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 6)
//                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10)
//                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10)
//                  )
                if(
                        ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10)
                  )
                    return true;
            }

            /* If applicant has 3 applications; they are considered Rejected-conditional-offer if;
             * Application 1 -> RejectedConditionalOffer | Rejected                                 | Rejected                              | RejectedConditionalOffer
             * Application 2 -> Pending                            | RejectedConditionalOffer       | Rejected                              | RejectedConditionalOffer
             * Application 3 -> Pending                            | Pending                                  | RejectedConditionalOffer    | RejectedConditionalOffer
             */
            elseif($count == 3)
            {
//                if(
//                        ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 3 && $applications[2]->applicationstatusid == 3)
//                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 3)
//                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 10)
//                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 10)
//                  )
                if(
                        ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 10)
                    ||  ($applications[0]->applicationstatusid == 6  && $applications[1]->applicationstatusid == 6 && $applications[2]->applicationstatusid == 10)
                    ||  ($applications[0]->applicationstatusid == 10  && $applications[1]->applicationstatusid == 10 && $applications[2]->applicationstatusid == 10)
                  )
                    return true;
            }
        }
        return false;
    }



    /**
     * Determines if application is currently considered a "Abandoned"
     *
     * @param type integer $personid
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_10_05
     * Modified: 2017_10_05
     */
    public static function isAbandoned($personid)
    {
        $applications = Application::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->orderBy('ordering ASC')
                    ->all();
        foreach($applications as $application)
        {
            if ($application->applicationstatusid == 11)
            {
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

                else
                {
                    if(self::isRejected($app->personid) == true ||  self::isPending($app->personid) == true
                            || self::isShortlisted($app->personid) == true  || self::isBorderline($app->personid) == true
                            || self::isInterviewOffer($app->personid) == true  || self::isOffer($app->personid) == true
                            || self::isRejectedConditionalOffer($app->personid) == true)
                         unset($apps[$key]);
                }

            }
            $applicants = $apps;
        }
        return $applicants;
    }


    /**
     * Returns collection of each application status type
     *
     * @param type $division_id
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 27/08/2016
     * Date Last Modified: 27/08/2016
     */
    public static function getAuhtorizedStatusCollection($division_id)
    {
        $container = array();

        $keys = array();
        array_push($keys, "pending");
        array_push($keys, "shortlist");
        array_push($keys, "borderline");
        array_push($keys, "pre_interview_rejects");
        array_push($keys, "interviewees");
        array_push($keys, "post_interview_rejects");
        array_push($keys, "offer");
        array_push($keys, "exceptions");

        $pending = array();
        $shortlist = array();
        $borderline = array();
        $pre_interview_rejects = array();
        $interviewees = array();
        $post_interview_rejects = array();
        $offer = array();
        $exceptions = array();

        $apps = self::getActiveApplicants($division_id);

        if ($apps)
        {
            foreach($apps as $key => $app)
            {
                if(self::isRejected($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $pre_interview_rejects[] = $apps[$key];
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 6)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $pre_interview_rejects[] = $apps[$key];
                    }
                }

                elseif(self::isPending($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $pending[] = $apps[$key];
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 3)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $pending[] = $apps[$key];
                    }
                }

                elseif(self::isShortlisted($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $shortlist[] = $apps[$key];
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 4 )
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $shortlist[] = $apps[$key];
                    }
                }

                elseif(self::isBorderline($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $borderline[] = $apps[$key];
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 7)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $borderline[] = $apps[$key];
                    }
                }

                elseif(self::isInterviewOffer($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $interviewees[] = $apps[$key];
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 8)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $interviewees[] = $apps[$key];
                    }
                }

                elseif(self::isOffer($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $offer[] = $apps[$key];
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 9)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $offer[] = $apps[$key];
                    }
                }

                elseif(self::isRejectedConditionalOffer($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $post_interview_rejects[] = $apps[$key];
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 10)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $post_interview_rejects[] = $apps[$key];
                    }
                }

                else
                {
                    $exceptions[] = $apps[$key];
                }
            }

            $values = array();
            array_push($values, $pending);
            array_push($values, $shortlist);
            array_push($values, $borderline);
            array_push($values, $pre_interview_rejects);
            array_push($values, $interviewees);
            array_push($values, $post_interview_rejects);
            array_push($values, $offer);
            array_push($values, $exceptions);

            $container = array_combine($keys, $values);
            return $container;
        }
    }


    /**
     * Returns count of each collection of each application status type
     *
     * @param type $division_id
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 27/08/2016
     * Date Last Modified: 27/08/2016
     */
    public static function getAuhtorizedStatusCollectionCounts($division_id)
    {
        $container = array();

        $keys = array();
        array_push($keys, "pending");
        array_push($keys, "shortlist");
        array_push($keys, "borderline");
        array_push($keys, "pre_interview_rejects");
        array_push($keys, "interviewees");
        array_push($keys, "post_interview_rejects");
        array_push($keys, "offer");
        array_push($keys, "exceptions");

        $pending = 0;
        $shortlist = 0;
        $borderline = 0;
        $pre_interview_rejects = 0;
        $interviewees = 0;
        $post_interview_rejects = 0;
        $offer = 0;
        $exceptions = 0;

        $apps = self::getActiveApplicants($division_id);

        if ($apps)
        {
            foreach($apps as $key => $app)
            {
                if(self::isRejected($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $pre_interview_rejects++;
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 6)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $pre_interview_rejects++;
                    }
                }

                elseif(self::isPending($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $pending++;
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 3)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $pending++;
                    }
                }

                elseif(self::isShortlisted($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $shortlist++;
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 4 )
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $shortlist++;
                    }
                }

                elseif(self::isBorderline($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $borderline++;
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 7)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $borderline++;
                    }
                }

                elseif(self::isInterviewOffer($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $interviewees++;
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 8)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $interviewees++;
                    }
                }

                elseif(self::isOffer($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $offer++;
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 9)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $offer++;
                    }
                }

                elseif(self::isRejectedConditionalOffer($app->personid) == true)
                {
                    if ($division_id == 1)
                    {
                        $post_interview_rejects++;
                    }
                    else
                    {
                        $target_applications = Application::find()
                                ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->orderBy('ordering ASC')
                                ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 10)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if($target_division == $division_id)
                            $post_interview_rejects++;
                    }
                }

                else
                {
//                    $exceptions[] = $apps[$key];
                    $exceptions++;
                }
            }

            $values = array();
            array_push($values, $pending);
            array_push($values, $shortlist);
            array_push($values, $borderline);
            array_push($values, $pre_interview_rejects);
            array_push($values, $interviewees);
            array_push($values, $post_interview_rejects);
            array_push($values, $offer);
            array_push($values, $exceptions);

            $container = array_combine($keys, $values);
            return $container;
        }
    }



    public static function getAuthorizedByStatus($status_id, $division_id)
    {
        $applicants = array();

        $apps = self::getActiveApplicants($division_id);

        if ($apps)
        {
            foreach($apps as $key => $app)
            {

                // If seeking 'Rejected'
                if ($status_id == 6)
                {
                    if ($division_id == 1)
                    {
                        if(self::isRejected($app->personid) == false)
                            unset($apps[$key]);
                    }
                    else
                    {
                        $target_applications = Application::find()
                                    ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->orderBy('ordering ASC')
                                    ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 6)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if(self::isRejected($app->personid) == false  || (self::isRejected($app->personid) == true  && $target_division != $division_id))
                            unset($apps[$key]);
                    }
                }


                // If seeking 'Pending'
                elseif ($status_id == 3)
                {
                    if ($division_id == 1)
                    {
                        if(self::isPending($app->personid) == false)
                            unset($apps[$key]);
                    }
                    else
                    {
                        $target_applications = Application::find()
                                    ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->orderBy('ordering ASC')
                                    ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 3)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if(self::isPending($app->personid) == false  || (self::isPending($app->personid) == true  && $target_division != $division_id))
                            unset($apps[$key]);
                    }
                }


                // If seeking 'Shortlisted'
                elseif ($status_id == 4)
                {
                    if ($division_id == 1)
                    {
                        if(self::isShortlisted($app->personid) == false)
                            unset($apps[$key]);
                    }
                    else
                    {
                        $target_applications = Application::find()
                                    ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->orderBy('ordering ASC')
                                    ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 4)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if(self::isShortlisted($app->personid) == false  || (self::isShortlisted($app->personid) == true  && $target_division != $division_id))
                            unset($apps[$key]);
                    }
                }


                // If seeking 'Borderline'
                elseif ($status_id == 7)
                {
                    if ($division_id == 1)
                    {
                        if(self::isBorderline($app->personid) == false)
                            unset($apps[$key]);
                    }
                    else
                    {
                        $target_applications = Application::find()
                                    ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->orderBy('ordering ASC')
                                    ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 7)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if(self::isBorderline($app->personid) == false  || (self::isBorderline($app->personid) == true  && $target_division != $division_id))
                            unset($apps[$key]);
                    }
                }

                // If seeking 'InterviewOffer'
                elseif ($status_id == 8)
                {
                    if ($division_id == 1)
                    {
                        if(self::isInterviewOffer($app->personid) == false)
                            unset($apps[$key]);
                    }
                    else
                    {
                        $target_applications = Application::find()
                                    ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->orderBy('ordering ASC')
                                    ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 8)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if(self::isInterviewOffer($app->personid) == false  || (self::isInterviewOffer($app->personid) == true  && $target_division != $division_id))
                            unset($apps[$key]);
                    }
                }

                // If seeking 'Offer'
                elseif ($status_id == 9)
                {
                    if ($division_id == 1)
                    {
                        if(self::isOffer($app->personid) == false)
                            unset($apps[$key]);
                    }
                    else
                    {
                        $target_applications = Application::find()
                                    ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->orderBy('ordering ASC')
                                    ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 9)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if(self::isOffer($app->personid) == false  || (self::isOffer($app->personid) == true  && $target_division != $division_id))
                            unset($apps[$key]);
                    }
                }

                // If seeking 'RejectedConditionalOffer'
                elseif ($status_id == 10)
                {
                    if ($division_id == 1)
                    {
                        if(self::isRejectedConditionalOffer($app->personid) == false)
                            unset($apps[$key]);
                    }
                    else
                    {
                        $target_applications = Application::find()
                                    ->where(['personid' => $app->personid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->orderBy('ordering ASC')
                                    ->all();
                        foreach($target_applications as $record)
                        {
                            if ($record->applicationstatusid == 10)
                            {
                                $target_division = $record->divisionid;
                                break;
                            }
                        }
                        if(self::isRejectedConditionalOffer($app->personid) == false  || (self::isRejectedConditionalOffer($app->personid) == true  && $target_division != $division_id))
                            unset($apps[$key]);
                    }
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
     * Date Last Modified: 28/02/2016 | 20/03/2016
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
                            'application_period.iscomplete' => 0, 'application_period.isactive' => 1, /*'application_period.applicationperiodstatusid' => 5,*/
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
     * Date Last Modified: 01/03/2016 | 20/03/2016
     */
    public static function isVerified($personid)
    {
        $applicant = Applicant::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();

        if ($applicant == true & $applicant->isexternal == 1 && $applicant->verifier == NULL)
            return false;

        $qualifications = CsecQualification::find()
                    ->innerJoin('application', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                    ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->where(['csec_qualification.personid' => $personid, 'csec_qualification.isactive' => 1, 'csec_qualification.isdeleted' => 0,
                                    'application.isactive' => 1, 'application.isdeleted' => 0,
                                    'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                    'application_period.iscomplete' => 0, 'application_period.isactive' => 1,
                                    'application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => 2
                                    ])
                    ->all();
        $all_verified = true;
        foreach($qualifications as $qualification)
        {
            if ($qualification->isverified == 0)
                $all_verified = false;
            if ($qualification->isqueried == 1)
                $all_verified = false;
        }

        $post_qualification = PostSecondaryQualification::getPostSecondaryQualifications($personid);
        if($post_qualification == true)
        {
            if ($post_qualification->isverified == 0 || $post_qualification->isqueried == 1)
                $all_verified = false;
        }

        return $all_verified;
    }


    /**
     * Returns an array of all applicant with multiple offers
     *
     * @param type $offers
     * @param type $details
     * @return boolean
     *
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 06/03/2016 (Laurence Charless)
     */
    public static function getMultipleOffers($offers, $details = false)
    {
        $offerids = array();
        $personids = array();
        $offenderids = array();

        if (empty($offers))
        {
            return false;
        }

        foreach($offers as $offer)
        {
            $applicant = Applicant::find()
              ->innerJoin('application', '`application`.`personid` = `applicant`.`personid`')
              ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
              ->where(['applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                        'application.isactive' => 1, 'application.isdeleted' => 0,
                        'offer.isactive' => 1,  'offer.isdeleted' => 0, 'offer.offerid' => $offer->offerid])
              ->one();

            /***** Identifies duplicates based on multiple offers having the same personid *********/
            if ($applicant == true)
            {
                if (in_array($applicant->personid, $personids) == true)
                {
                    if ($details)
                    {
                        $offenderids[] = $applicant->personid;
                    }
                    else
                    {
                        return true;
                    }
                }
                else
                {
                    $personids[] = $applicant->personid;
                }
            }
            else {
              continue;
            }
            /****************************************************************************/

            $certificates = CsecQualification::getSubjects($applicant->personid);
            if ($certificates)
            {
                $division_id = EmployeeDepartment::getUserDivision();
//                $dups = CsecQualification::getPossibleDuplicate($applicant->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                $dups = CsecQualification::getPossibleDuplicateOfferee($applicant->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                if ($dups)
                {
                    foreach($dups as $dup)
                    {
                        $user = User::findOne(['personid' => $dup, 'isdeleted' => 0]);
                        if ($user)
                        {
                            $offer_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1, 'offer.isdeleted' => 0,
                                'application.personid' => $user->personid);

                            if ($division_id && $division_id == 1)
                                $offer_cond = array('application_period.iscomplete' => 0, 'application_period.isactive' => 1, 'offer.isdeleted' => 0, 'application.personid' => $user->personid);

                            $offers = Offer::find()
                                    ->joinWith('application')
                                    ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                                    ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                                    ->where($offer_cond)
                                    ->all();
                            if ($details)
                                $offenderids[] = $user->personid;
                            else
                                return true;
                        }
                    }
                }
            }
        }

        foreach($offenderids as $offenderid)
        {
            $offs = Offer::find()
                    ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
                    ->where(['application.personid' => $offenderid, 'offer.isdeleted' => 0, 'application.isdeleted' => 0])
                    ->all();
            foreach($offs as $off)
            {
                $offerids[] = $off;
            }
        }
        return count($offerids) > 0 ? $offerids : false;
    }


    /**
     * Returns an array of all applicants that were given offers but don't have
     * CSEC English Language pass
     *
     * @param type $offers
     * @param type $details
     * @return boolean
     *
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 06/03/2016 (Laurence Charles)
     */
    public static function getAcceptedWithoutEnglish($offers, $details = false)
    {
        if (empty($offers))
            return false;

        $offerids = array();
        foreach($offers as $offer)
        {
            $applicant = Applicant::find()
                    ->innerJoin('application', '`application`.`personid` = `applicant`.`personid`')
                    ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                    ->where(['application.isdeleted' => 0, 'offer.isdeleted' => 0, 'offer.offerid' => $offer->offerid])
                    ->one();
            $has_english = CsecQualification::hasCsecEnglish($applicant->personid);
            if (!$has_english)
            {
                if ($details)
                    $offerids[] = $offer;
                else
                    return true;
            }
        }
        return count($offerids) > 0 ? $offerids : false;
    }


    /**
     * Returns an array of all applicants that were given rejections but have
     * CSEC English Language pass
     *
     * @param type $rejections
     * @param type $details
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 30/03/2016
     * Date Last Modified: 30/03/2016
     */
    public static function getRejectedWithEnglish($rejections, $details = false)
    {
        if (empty($rejections))
            return false;

        $rejectionids = array();
        foreach($rejections as $rejection)
        {
            $applicant = Applicant::find()
                    ->innerJoin('application', '`application`.`personid` = `applicant`.`personid`')
                    ->innerJoin('`rejection_applications`', '`rejection_applications`.`applicationid` = `application`.`applicationid`')
                    ->innerJoin('rejection', '`rejection`.`rejectionid` = `rejection_applications`.`rejectionid`')
                    ->where(['application.isdeleted' => 0, 'rejection.isdeleted' => 0, 'rejection.rejectionid' => $rejection->rejectionid])
                    ->one();
            $has_english = CsecQualification::hasCsecEnglish($applicant->personid);
            if ($has_english)
            {
                if ($details)
                    $rejectionids[] = $rejection;
                else
                    return true;
            }
        }
        return count($rejectionids) > 0 ? $rejectionids : false;
    }


    /**
     * Returns an array of all applicants that were given offers but don't have
     * CSEC Mathematics pass
     *
     * @param type $offers
     * @param type $details
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 06/03/2016
     * Date Last Modified: 06/03/2016
     */
    public static function getAcceptedWithoutMath($offers, $details = false)
    {
        if (empty($offers))
            return false;

        $offerids = array();
        foreach($offers as $offer)
        {
            $applicant = Applicant::find()
                    ->innerJoin('application', '`application`.`personid` = `applicant`.`personid`')
                    ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                    ->where(['application.isdeleted' => 0, 'offer.isdeleted' => 0, 'offer.offerid' => $offer->offerid])
                    ->one();
            $has_math = CsecQualification::hasCsecMathematics($applicant->personid);
            if (!$has_math)
            {
                if ($details)
                    $offerids[] = $offer;
                else
                    return true;
            }
        }
        return count($offerids) > 0 ? $offerids : false;
    }


    /**
     * Returns an array of all applicants that were given rejections but have
     * CSEC Mathematics pass
     *
     * @param type $rejections
     * @param type $details
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 30/03/2016
     * Date Last Modified: 30/03/2016
     */
    public static function getRejectedWithMath($rejections, $details = false)
    {
        if (empty($rejections))
            return false;

        $rejectionids = array();
        foreach($rejections as $rejection)
        {
            $applicant = Applicant::find()
                    ->innerJoin('application', '`application`.`personid` = `applicant`.`personid`')
                    ->innerJoin('`rejection_applications`', '`rejection_applications`.`applicationid` = `application`.`applicationid`')
                    ->innerJoin('rejection', '`rejection`.`rejectionid` = `rejection_applications`.`rejectionid`')
                    ->where(['application.isdeleted' => 0, 'rejection.isdeleted' => 0, 'rejection.rejectionid' => $rejection->rejectionid])
                    ->one();
            $has_math = CsecQualification::hasCsecMathematics($applicant->personid);
            if ($has_math)
            {
                if ($details)
                    $rejectionids[] = $rejection;
                else
                    return true;
            }
        }
        return count($rejectionids) > 0 ? $rejectionids : false;
    }


    /**
     * Returns an array of all applicants that were given offers but don't have
     * the required DTE Relevant science
     *
     * @param type $offers
     * @param type $details
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 06/03/2016
     * Date Last Modified: 06/03/2016
     */
    public static function getAcceptedWithoutDteScienceCriteria($offers, $details = false)
    {
        if (empty($offers))
            return false;

        $offerids = array();
        foreach($offers as $offer)
        {
            $application = Application::find()
                        ->where(['applicationid' => $offer->applicationid])
                        ->one();
            if ($application == false)
                continue;
            else
            {
                if($application->divisionid == 6)
                {
                    $applicant = Applicant::find()
                            ->innerJoin('application', '`application`.`personid` = `applicant`.`personid`')
                            ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                            ->where(['application.isdeleted' => 0, 'offer.isdeleted' => 0, 'offer.offerid' => $offer->offerid])
                            ->one();
                    $has_relevant_science = CsecQualification::hasDteRelevantSciences($applicant->personid);
                    if (!$has_relevant_science)
                    {
                        if ($details)
                            $offerids[] = $offer;
                        else
                            return true;
                    }
                }
            }
        }
        return count($offerids) > 0 ? $offerids : false;
    }


    /**
     * Returns an array of all applicants that were given rejections but have
     * the required DTE Relevant science
     *
     * @param type $rejections
     * @param type $details
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 30/03/2016
     * Date Last Modified: 30/03/2016
     */
    public static function getRejectedWithDteScienceCriteria($rejections, $details = false)
    {
        if (empty($rejections))
            return false;

        $rejectionids = array();
        foreach($rejections as $rejection)
        {
            $applications = Application::find()
                        ->innerJoin('`rejection_applications`', '`rejection_applications`.`applicationid` = `application`.`applicationid`')
                        ->where(['rejection_applications.rejectionid' => $rejection->rejectionid])
                        ->all();
            if ($applications == false)
                continue;
            else
            {
                foreach ($applications as $application)
                {
                    if($application->divisionid == 6)
                    {
                        $applicant = Applicant::find()
                                ->innerJoin('application', '`application`.`personid` = `applicant`.`personid`')
                                ->innerJoin('`rejection_applications`', '`rejection_applications`.`applicationid` = `application`.`applicationid`')
                                ->innerJoin('rejection', '`rejection`.`rejectionid` = `rejection_applications`.`rejectionid`')
                                ->where(['application.isdeleted' => 0, 'rejection.isdeleted' => 0, 'rejection.rejectionid' => $rejection->rejectionid])
                                ->one();
                        $has_relevant_science = CsecQualification::hasDteRelevantSciences($applicant->personid);
                        if ($has_relevant_science)
                        {
                            if ($details)
                            {
                                $rejectionids[] = $rejection;
                                break;
                            }
                            else
                                return true;
                        }
                    }
                }
            }
        }
        return count($rejectionids) > 0 ? $rejectionids : false;
    }


    /**
     * Returns an array of all applicants that were given offers but don't have
     * the required DNE Relevant science
     *
     * @param type $offers
     * @param type $details
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 06/03/2016
     * Date Last Modified: 06/03/2016
     */
    public static function getAcceptedWithoutDneScienceCriteria($offers, $details = false)
    {
        if (empty($offers))
            return false;

        $offerids = array();
        foreach($offers as $offer)
        {
            $application = Application::find()
                        ->where(['applicationid' => $offer->applicationid])
                        ->one();
            if ($application == false)
                continue;
            else
            {
                if($application->divisionid == 7)
                {
                    $applicant = Applicant::find()
                            ->innerJoin('application', '`application`.`personid` = `applicant`.`personid`')
                            ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                            ->where(['application.isdeleted' => 0, 'offer.isdeleted' => 0, 'offer.offerid' => $offer->offerid])
                            ->one();
                    $has_relevant_science = CsecQualification::hasDneRelevantSciences($applicant->personid);
                    if (!$has_relevant_science)
                    {
                        if ($details)
                            $offerids[] = $offer;
                        else
                            return true;
                    }
                }
            }
        }
        return count($offerids) > 0 ? $offerids : false;
    }


    /**
     * Returns an array of all applicants that were given rejections but have
     * the required DNE Relevant science
     *
     * @param type $rejections
     * @param type $details
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 30/03/2016
     * Date Last Modified: 30/03/2016
     */
    public static function getRejectedWithDneScienceCriteria($rejections, $details = false)
    {
        if (empty($rejections))
            return false;

        $rejectionids = array();
        foreach($rejections as $rejection)
        {
            $applications = Application::find()
                        ->innerJoin('`rejection_applications`', '`rejection_applications`.`applicationid` = `application`.`applicationid`')
                        ->where(['rejection_applications.rejectionid' => $rejection->rejectionid])
                        ->all();
            if ($applications == false)
                continue;
            else
            {
                foreach ($applications as $application)
                {
                    if($application->divisionid == 7)
                    {
                        $applicant = Applicant::find()
                                ->innerJoin('application', '`application`.`personid` = `applicant`.`personid`')
                                ->innerJoin('`rejection_applications`', '`rejection_applications`.`applicationid` = `application`.`applicationid`')
                                ->innerJoin('rejection', '`rejection`.`rejectionid` = `rejection_applications`.`rejectionid`')
                                ->where(['application.isdeleted' => 0, 'rejection.isdeleted' => 0, 'rejection.rejectionid' => $rejection->rejectionid])
                                ->one();
                        $has_relevant_science = CsecQualification::hasDneRelevantSciences($applicant->personid);
                        if ($has_relevant_science)
                        {
                            if ($details)
                            {
                                $rejectionids[] = $rejection;
                                break;
                            }
                            else
                                return true;
                        }
                    }
                }
            }
        }
        return count($rejectionids) > 0 ? $rejectionids : false;
    }


    /**
     * Returns an array of all applicants that were given offers but don't have
     * 5 CSEC Passes
     *
     * @param type $offers
     * @param type $details
     * @return boolean
     *
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 06/03/2016 (Laurence Charless)
     */
    public static function getAcceptedWithoutFivePasses($offers, $details = False)
    {
        if (empty($offers))
            return false;

        $offerids = array();
        foreach($offers as $offer)
        {
            $applicant = Applicant::find()
                    ->innerJoin('application', '`application`.`personid` = `applicant`.`personid`')
                    ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                    ->where(['application.isdeleted' => 0, 'offer.isdeleted' => 0, 'offer.offerid' => $offer->offerid])
                    ->one();
            $minimum_subjects_passed = CsecQualification::hasFiveCsecPasses($applicant->personid);
            if (!$minimum_subjects_passed)
            {
                if ($details)
                    $offerids[] = $offer;
                else
                    return true;
            }
        }
        return count($offerids) > 0 ? $offerids : false;
    }


    /**
     * Returns an array of all applicants that were given rejections but have
     * 5 CSEC Passes
     *
     * @param type $rejections
     * @param type $details
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 30/03/2016
     * Date Last Modified: 30/03/2016
     */
    public static function getRejectedWithFivePasses($rejections, $details = false)
    {
        if (empty($rejections))
            return false;

        $rejectionids = array();
        foreach($rejections as $rejection)
        {
            $applicant = Applicant::find()
                    ->innerJoin('application', '`application`.`personid` = `applicant`.`personid`')
                    ->innerJoin('`rejection_applications`', '`rejection_applications`.`applicationid` = `application`.`applicationid`')
                    ->innerJoin('rejection', '`rejection`.`rejectionid` = `rejection_applications`.`rejectionid`')
                    ->where(['application.isdeleted' => 0, 'rejection.isdeleted' => 0, 'rejection.rejectionid' => $rejection->rejectionid])
                    ->one();
            $minimum_subjects_passed = CsecQualification::hasFiveCsecPasses($applicant->personid);
            if ($minimum_subjects_passed)
            {
                if ($details)
                    $rejectionids[] = $rejection;
                else
                    return true;
            }
        }
        return count($rejectionids) > 0 ? $rejectionids : false;
    }


       /**
     * Returns an array of all applicants that were given rejections but have
     * 5 CSEC Passes
     *
     * @param type $rejections
     * @param type $details
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 30/03/2016
     * Date Last Modified: 30/03/2016
     */
    public static function getRejectedWithFivePassesAndEnglishPass($rejections, $details = false)
    {
        if (empty($rejections))
            return false;

        $rejectionids = array();
        foreach($rejections as $rejection)
        {
            $applicant = Applicant::find()
                    ->innerJoin('application', '`application`.`personid` = `applicant`.`personid`')
                    ->innerJoin('`rejection_applications`', '`rejection_applications`.`applicationid` = `application`.`applicationid`')
                    ->innerJoin('rejection', '`rejection`.`rejectionid` = `rejection_applications`.`rejectionid`')
                    ->where(['application.isdeleted' => 0, 'rejection.isdeleted' => 0, 'rejection.rejectionid' => $rejection->rejectionid])
                    ->one();
            $minimum_subjects_passed = CsecQualification::hasFiveCsecPasses($applicant->personid);
            $has_english = CsecQualification::hasCsecEnglish($applicant->personid);
            if ($minimum_subjects_passed == true  && $has_english == true)
            {
                if ($details)
                    $rejectionids[] = $rejection;
                else
                    return true;
            }
        }
        return count($rejectionids) > 0 ? $rejectionids : false;
    }


    /*
     * Generates/revokes a potentialstudentid
     *
     * @param type $divisionid
     * @param type $applicantid
     * @param type $action
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 30/03/2016
     * Date Last Modified: 30/03/2016
    */
    public static function preparePotentialStudentID($divisionid, $applicantid, $action)
    {
        if ($action == "generate")
        {
            $academic_year = AcademicYear::find()
                        ->innerJoin('academic_offering', '`academic_year`.`academicyearid` = `academic_offering`.`academicyearid`')
                        ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('applicant', '`application`.`personid` = `applicant`.`personid`')
                        ->where(['applicant.applicantid' => $applicantid,
                                'application.isactive' => 1, 'application.isdeleted' => 0
                                ])
                        ->groupBy('application.personid')
                        ->one();

            $startyear =  substr($academic_year->title, 2, 2);
            $div = str_pad(strval($divisionid), 2, '0', STR_PAD_LEFT);
            $num = str_pad(strval($applicantid), 4, '0', STR_PAD_LEFT);
            try
            {
                $potentialstudentid = intval($startyear . $div . $num);
            } catch (Exception $ex) {
                $potentialstudentid = NULL;
            }
        }
        elseif($action == "revoke")
        {
            $potentialstudentid = 0;
        }
        elseif($action == "transfer")
        {
            $applicant = Applicant::find()
                    ->where(['applicantid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $old_id = $applicant->potentialstudentid;
            $year =  substr($old_id, 0, 2);
            $div = str_pad(strval($divisionid), 2, '0', STR_PAD_LEFT);
            $num = substr($old_id, 4, 4);
            $potentialstudentid = intval($year . $div . $num);
        }
        return $potentialstudentid;
    }


    /**
     * Returns true is applicant has received an offer
     *
     * @param type $personid
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 30/08/2016
     * Date Last Modified: 30/08/2016
     */
    public static function hasBeenIssuedOffer($personid)
    {
        $applications =Application::find()
                ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                ->where(['application.personid' => $personid, 'application.isactive' => 1, 'application.isdeleted' => 0,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                 'application_period.iscomplete' => 0, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                            ])
                ->andWhere(['>', 'application.applicationstatusid', 2])
                ->orderBy('application.ordering ASC')
                ->all();

        $ids = array();
        foreach($applications as $application)
        {
            $ids[] = $application->applicationid;
        }

        $offers = Offer::find()
                ->where(['applicationid' => $ids,  'ispublished' => 1, 'offer.isdeleted' => 0, 'offertypeid' => 1])
                ->all();
        if ($offers == true)
            return true;

        return false;
    }


    /**
     * Returns true if applicant has
     *
     * @param type $personid
     * @return boolean
     *
     * Author: Laurence Charles
     * Date Created: 15/09/2016
     * Date Last Modified: 15/09/2016
     *
     */
    public static function hasBeenIssuedRejection($personid)
    {
        $applications =Application::find()
                ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                ->where(['application.personid' => $personid, 'application.isactive' => 1, 'application.isdeleted' => 0,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                 'application_period.iscomplete' => 0, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                            ])
                ->andWhere(['>', 'application.applicationstatusid', 2])
                ->orderBy('application.ordering ASC')
                ->all();

        $ids = array();
        foreach($applications as $application)
        {
            $ids[] = $application->applicationid;
        }

        $rejections = Rejection::find()
                ->innerJoin('rejection_applications', '`rejection`.`rejectionid` = `rejection_applications`.`rejectionid`')
                ->where(['rejection.ispublished' => 1,'rejection.isactive' => 1, 'rejection.isdeleted' => 0,
                                'rejection_applications.applicationid' => $ids, 'rejection_applications.isdeleted' => 0])
                ->all();
        if ($rejections == true)
            return true;

        return false;
    }


    /**
     * Return collection of applicants associated with academic year
     *
     * @return [Applicant] | []
     *
     * Author: Laurence Charles
     * Date Created: 2017_07_26
     * Date Last Modified: 2017_08_26
     */
    public static function getCommencedApplicants($academicyearid)
    {
        $cond = array();
        $cond['applicant.isactive'] = 1;
        $cond['applicant.isdeleted'] = 0;
        $cond['application.applicationstatusid'] = [1,2,3,4,5,6,7,8,9,10,11];
        $cond['application.isactive'] = 1;
        $cond['application.isdeleted'] = 0;
        $cond['academic_offering.isactive'] = 1;
        $cond['academic_offering.isdeleted'] = 0;
        $cond['academic_offering.academicyearid'] = $academicyearid;

        $applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->where($cond)
                        ->groupBy('applicant.personid')
                        ->all();
        return $applicants;
    }


     /**
     * Return collection of applicants that have submitted application(s) in an academic year
     *
     * @return [Applicant] | []
     *
     * Author: Laurence Charles
     * Date Created: 2017_07_26
     * Date Last Modified: 2017_08_26
     */
    public static function getCompletedApplicants($academicyearid)
    {
        $cond = array();
        $cond['applicant.isactive'] = 1;
        $cond['applicant.isdeleted'] = 0;
        $cond['application.applicationstatusid'] = [2,3,4,5,6,7,8,9,10,11];
        $cond['application.isactive'] = 1;
        $cond['application.isdeleted'] = 0;
        $cond['academic_offering.isactive'] = 1;
        $cond['academic_offering.isdeleted'] = 0;
        $cond['academic_offering.academicyearid'] = $academicyearid;

        $applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->where($cond)
                        ->groupBy('applicant.personid')
                        ->orderBy('applicant.lastname ASC')
                        ->all();
        return $applicants;
    }


    /**
     * Return collection of applicants that have unsubmitted application(s) in an academic year
     *
     * @return [Applicant] | []
     *
     * Author: Laurence Charles
     * Date Created: 2017_07_26
     * Date Last Modified: 2017_08_26
     */
    public static function getIncompleteApplicants($academicyearid)
    {
        $cond = array();
        $cond['applicant.isactive'] = 1;
        $cond['applicant.isdeleted'] = 0;
        $cond['application.applicationstatusid'] = 1;
        $cond['application.isdeleted'] = 0;
        $cond['academic_offering.isactive'] = 1;
        $cond['academic_offering.isdeleted'] = 0;
        $cond['academic_offering.academicyearid'] = $academicyearid;

        $applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->where($cond)
                        ->groupBy('applicant.personid')
                        ->all();
        return $applicants;
    }

    /**
     * Return collection of applicants that had their application(s) removed
     *
     * @return [Applicant] | []
     *
     * Author: Laurence Charles
     * Date Created: 2017_07_26
     * Date Last Modified: 2017_08_26
     */
    public static function getRemovedApplicants($academicyearid)
    {
        $cond = array();
        $cond['applicant.isactive'] = 1;
        $cond['applicant.isdeleted'] = 0;
        $cond['application.applicationstatusid'] = 11;
        $cond['application.isdeleted'] = 0;
        $cond['academic_offering.isactive'] = 1;
        $cond['academic_offering.isdeleted'] = 0;
        $cond['academic_offering.academicyearid'] = $academicyearid;

        $applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->where($cond)
                        ->groupBy('applicant.personid')
                        ->all();
        return $applicants;
    }


    /**
     * Return collection of applicants that have submitted applicants and been verified
     *
     * @return [Applicant] | []
     *
     * Author: Laurence Charles
     * Date Created: 2017_07_26
     * Date Last Modified: 2017_08_26
     */
    public static function getVerifiedApplicants($academicyearid)
    {
        $cond = array();
        $cond['applicant.isactive'] = 1;
        $cond['applicant.isdeleted'] = 0;
        $cond['application.applicationstatusid'] = [3,4,5,6,7,8,9,10];
        $cond['application.isdeleted'] = 0;
        $cond['academic_offering.isactive'] = 1;
        $cond['academic_offering.isdeleted'] = 0;
        $cond['academic_offering.academicyearid'] = $academicyearid;

        $applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->where($cond)
                        ->groupBy('applicant.personid')
                        ->all();
        return $applicants;
    }


    /**
     * Return collection of applicants that have submitted applications that have not been verified
     *
     * @return [Applicant] | []
     *
     * Author: Laurence Charles
     * Date Created: 2017_08_26
     * Date Last Modified: 2017_08_26
     */
    public static function getUnverifiedApplicants($academicyearid)
    {
        $cond = array();
        $cond['applicant.isactive'] = 1;
        $cond['applicant.isdeleted'] = 0;
        $cond['application.applicationstatusid'] = 2;
        $cond['application.isdeleted'] = 0;
        $cond['academic_offering.isactive'] = 1;
        $cond['academic_offering.isdeleted'] = 0;
        $cond['academic_offering.academicyearid'] = $academicyearid;

        $applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->where($cond)
                        ->groupBy('applicant.personid')
                        ->all();
        return $applicants;
    }


    /**
     * Return collection of applicants that have been issued full offers
     *
     * @return [Applicant] | []
     *
     * Author: Laurence Charles
     * Date Created: 2017_10_12
     * Date Last Modified: 2017_10_12
     */
    public static function getSuccessfulApplicants($academicyearid)
    {
        $cond = array();
        $cond['applicant.isactive'] = 1;
        $cond['applicant.isdeleted'] = 0;
        $cond['application.applicationstatusid'] = 9;
        $cond['application.isdeleted'] = 0;
        $cond['academic_offering.isactive'] = 1;
        $cond['academic_offering.isdeleted'] = 0;
        $cond['academic_offering.academicyearid'] = $academicyearid;
        $cond['offer.offertypeid'] = 1;
        $cond['offer.ispublished'] = 1;
        $cond['offer.isactive'] = 1;
        $cond['offer.isdeleted'] = 0;

        $applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->where($cond)
                        ->groupBy('applicant.personid')
                        ->all();
        return $applicants;
    }


    /**
     * Return collection of applicants that have been issued full offers
     *
     * @return [Applicant] | []
     *
     * Author: Laurence Charles
     * Date Created: 2017_10_12
     * Date Last Modified: 2017_10_12
     */
    public static function getUnsuccessfulApplicants($academicyearid)
    {
        $cond = array();
        $cond['applicant.isactive'] = 1;
        $cond['applicant.isdeleted'] = 0;
        $cond['application.applicationstatusid'] = [6,10];
        $cond['application.isdeleted'] = 0;
        $cond['academic_offering.isactive'] = 1;
        $cond['academic_offering.isdeleted'] = 0;
        $cond['academic_offering.academicyearid'] = $academicyearid;
        $cond['rejection.isactive'] = 1;
        $cond['rejection.isdeleted'] = 0;
        $cond['rejection_applications.isactive'] = 1;
        $cond['rejection_applications.isdeleted'] = 0;

        $applicants = Applicant::find()
                ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('`rejection_applications`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`rejection`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->where($cond)
                ->groupBy('applicant.personid')
                ->all();
        return $applicants;
    }


    /**
     * Returns array of applicant's institution attendances
     *
     * @return [String] |
     *
     * Author: Laurence Charles
     * Date Created: 2017_08_27
     * Date Last Modified: 2017_08_27
     */
    public function getInstitutions()
    {
        $listing = array();
        $institutions = Institution::find()
                    ->innerJoin('person_institution', '`person_institution`.`institutionid` = `institution`.`institutionid`')
                    ->where(['person_institution.personid' => $this->personid, 'person_institution.isactive' => 1, 'person_institution.isdeleted' =>0,
                                    'institution.levelid' => [3,4]
                                ])
                    ->orderBy('institution.levelid')
                    ->all();

        if (empty($institutions) == true)
        {
            return NULL;
        }

        foreach ($institutions as $institution)
        {
            array_push($listing, $institution->name);
        }
         return implode("|", $listing);
    }


    /**
     * Returns array of applicant's qualifications
     *
     * @param $examination_bodies
     * @return [CsecQualification] | []
     *
     * Author: Laurence Charles
     * Date Created: 2017_08_27
     * Date Last Modified: 2017_08_27
     */
    public function getQualifications($examination_bodies = NULL)
    {
        if ($examination_bodies == NULL)
        {
            $qualifications = CsecQualification::find()
                    ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' =>0])
                    ->all();
        }
        else
        {
            $qualifications = CsecQualification::find()
                    ->where(['personid' => $this->personid, 'examinationbodyid' => $examination_bodies,
                                    'isactive' => 1, 'isdeleted' =>0])
                    ->all();
        }
        return $qualifications;
    }


    /**
     * Returns array of applicant's institution attendance
     *
     * @param $levels
     * @return [ String ] | []
     * @throws ModelNotFoundException
     *
     * Author: Laurence Charles
     * Date Created: 2017_08_27
     * Date Last Modified: 2017_08_27
     */
    public function getAcademicPerformance($examination_bodies = NULL)
    {
        $qualification_listing = array();

        if ($level == NULL)
        {
            $qualifications = CsecQualification::find()
                    ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' =>0])
                    ->all();
        }
        else
        {
            $qualifications = CsecQualification::find()
                    ->where(['personid' => $this->personid, 'examinationbodyid' => $examination_bodies,
                                    'isactive' => 1, 'isdeleted' =>0])
                    ->all();
        }

        if (empty($qualifications) == true)
        {
           return $qualification_listing;
        }

        foreach ($qualifications as $qualification)
        {
            $qualification_name = CsecQualification::formatQualificationName($qualification->csecqualificationid);
            array_push($qualification_listing, $qualification_name);
        }

        return $qualification_listing;
    }


    /**
     * Returns array of applicant's applications
     *
     * @return [Application] | []
     *
     * Author: Laurence Charles
     * Date Created: 2017_08_27
     * Date Last Modified: 2017_08_27
     */
    public function getApplications()
    {
        $applications = Application::find()
                ->where(['applicationstatusid' => [2,3,4,5,6,7,8,9,10,11], 'personid' => $this->personid, 'isactive' => 1, 'isdeleted' =>0])
                ->orderBy('ordering')
                ->all();
        return $applications;
    }


     /**
     * Returns array of applicant's formatted programme choices
     *
     * @return String
     *
     * Author: Laurence Charles
     * Date Created: 2017_08_27
     * Date Last Modified: 2017_08_27
     */
    public function getProgrammeChoices()
    {
        $applications = $this->getApplications();
        return ArrayFormatter::FormatProgrammesChoices($applications);
    }



    /**
     * Returns time in minsutes thatapplicant took to complete application
     *
     * @return Float
     *
     * Author: Laurence Charles
     * Date Created: 2017_08_27
     * Date Last Modified: 2017_08_27
     */
    public function calculateApplicantSubmissionDurationFromEmailRegistration()
    {
        $user = User::getUser($this->personid);
        if ($user->persontypeid == 1)
        {
            $applicant_registration = ApplicantRegistration::find()
                    ->where(['applicantname' => $user->username])
                    ->one();
        }
        if ($user->persontypeid == 2)
        {
            $student = Student::find()
                    ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $applicant_registration = ApplicantRegistration::find()
                    ->where(['applicantname' => $student->applicantname])
                    ->one();
        }

        $application = Application::find()
                ->where(['personid' => $this->personid, 'applicationstatusid' => [2,3,4,5,6,7,8,9,10,11],
                                'isactive' => 1, 'isdeleted' => 0])
                ->one();

        $duration =  (strtotime($application->submissiontimestamp) - strtotime($applicant_registration->created_at))/60;
        return round($duration);
    }


    /**
     * Returns time in minutes that applicant took to complete application
     *
     * @return Float
     *
     * Author: Laurence Charles
     * Date Created: 2017_08_27
     * Date Last Modified: 2017_08_27
     */
    public function calculateApplicantSubmissionDurationFromAccountCreation()
    {
        $user = User::getUser($this->personid);

        $application = Application::find()
                ->where(['personid' => $this->personid, 'applicationstatusid' => [2,3,4,5,6,7,8,9,10,11],
                                'isactive' => 1, 'isdeleted' => 0])
                ->one();

        $duration =  (strtotime($application->submissiontimestamp) - strtotime($user->datecreated))/60;
        return round($duration);
    }


     /**
     * Send applicant submission email
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_03_01
     * Modified: 2017_03_01
     */
    public function processSendSubmissionConfirmationEmail($applicantregistrationid)
    {
        $applicant_registration = ApplicantRegistration::find()
                        ->where(['applicantregistrationid' => $applicantregistrationid])
                        ->one();

        $application_records = array();
        $applications = $this->getActiveApplications();
        foreach ($applications as $application)
        {
            $application_records[] = $application->formatApplicationInformation();
        }

        $email_file = "application_submission_successful";
        if ($this->applicantintentid == 1)
        {
            $email_file = "dasgs_dtve_full_application_submission_successful";
        }
        elseif ($this->applicantintentid == 4)
        {
            $email_file = "dte_full_application_submission_successful";
        }
        elseif ($this->applicantintentid == 6)
        {
            $email_file = "dne_full_application_submission_successful";
        }

        Yii::$app->mailer->compose(['html' => $email_file],
            ['applicant' => $this,
                'application_records' => $application_records,
                'username' => $applicant_registration->applicantname])
           ->setFrom(Yii::$app->params['applicationEmail'])
           ->setTo($applicant_registration->email)
           ->setSubject('Application Submission Successful')
           ->send();
    }


    /**
     * Return all active applicant's applications
     *
     * @return Application[] | []
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2018_03_01
     * Modified: 2018_03_01
     */
    public function getActiveApplications()
    {
        return Application::find()
                ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
                ->orderBy('ordering ASC')
                ->all();
    }


    /**
     * Returns applicant's fullname
     *
     * @return type
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2018_03_01
     * Modified: 2018_03_01
     */
    public function getFullName()
    {
        if ($this->middlename == false)
        {
            return  $this->title . " " . $this->firstname . " " . $this->lastname;
        }
        else
        {
            return  $this->title . " " . $this->firstname . " " . $this->middlename . " " . $this->lastname;
        }
    }



    /**
    * Returns qualification of applicant
    *
    * @return [CsecQualification] || []
    *
    * Author: charles.laurence1@gmail.com
     * Created: 2017_11_21
     * Modified: 2017_11_21
    */
   public function getAllQualifications()
   {
       return CsecQualification::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->all();
   }


   /**
    * Returns true if applicant has attended institution at a particular academic level
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_10_26
    * Modified: 2017_11_17
    */
   public function checkAttendance($levelid)
   {
       $attendances = PersonInstitution::find()
                    ->innerJoin('institution', '`person_institution`.`institutionid` = `institution`.`institutionid`')
                    ->where(['person_institution.personid'=> $this->personid,
                                    'person_institution.isdeleted'=> 0, 'institution.levelid'=> $levelid])
                    ->all();
      if (empty($attendances) == false)
      {
          return true;
      }
        return false;
    }

    /**
    * Returns true if the required fields of an address record has been completed
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_10_26
    * Modified: 2017_10_26
    */
   public function addressValid($addresstypeid)
   {
       $address = Address::find()
                    ->where(['personid' => $this->personid, 'addresstypeid'=> $addresstypeid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();

       if ($address == true)
       {
           // if country is local must then town field must be investigated
            if (strcmp($address->country,"st. vincent and the grenadines") == 0)
            {
                if (strcmp($address->town,"") == 0  || $address->town == NULL)
                {
                    return false;
                }
                else
                {
                    // if user select 'other' town; they must populate 'addressline' field
                    if (strcmp($address->town,"other") == 0
                            && (strcmp($address->addressline, "") == 0 || $address->addressline == NULL))
                    {
                        return false;
                    }
                }
            }
            else
            {
                if(strcmp($address->addressline, "") == 0  || $address->addressline == NULL)
                {
                    return false;
                }
            }
            return true;
       }
       return false;
   }

   /**
     * Determines the existence of Early Childhood Education applications
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_11_28
     * Modified: 2017_11_28
     */
    public function hasEarlyChildhoodApplication()
    {
        return Application::find()
                ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->where(['application.personid'=> $this->personid, 'application.isactive' => 1,  'application.isdeleted'=> 0,
                                'academic_offering.isactive' => 1,  'academic_offering.isdeleted'=> 0,
                                'programme_catalog.specialisation' => 'Early Childhood Education', 'programme_catalog.name' => 'Teacher Education',
                                'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0])
                ->all();
    }


    /**
     * Determines the existence of Primary School applications
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_11_28
     * Modified: 2017_11_28
     */
    public function hasPrimarySchoolApplication()
    {
        return Application::find()
                ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->where(['application.personid'=> $this->personid, 'application.isactive' => 1,  'application.isdeleted'=> 0,
                                'academic_offering.isactive' => 1,  'academic_offering.isdeleted'=> 0,
                                'programme_catalog.specialisation' => 'Primary', 'programme_catalog.name' => 'Teacher Education',
                                'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0])
                ->all();
    }


    /**
     * Determines the existence of Secondary applications
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_11_28
     * Modified: 2017_11_28
     */
    public function hasSecondarySchoolApplication()
    {
        return Application::find()
                ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->where(['application.personid'=> $this->personid, 'application.isactive' => 1,  'application.isdeleted'=> 0,
                                    'academic_offering.isactive' => 1,  'academic_offering.isdeleted'=> 0,
                                    'programme_catalog.specialisation' => 'Secondary', 'programme_catalog.name' => 'Teacher Education',
                                    'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0])
                ->all();
    }

    /**
     * Determines the existence of TVET applications
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2018_02_07
     * Modified: 2018_02_07
     */
    public function hasTVETApplication()
    {
        return Application::find()
                ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->where(['application.personid'=> $this->personid, 'application.isactive' => 1,  'application.isdeleted'=> 0,
                                    'academic_offering.isactive' => 1,  'academic_offering.isdeleted'=> 0,
                                    'programme_catalog.specialisation' => 'TVET - Home Economics & Industrial Arts', 'programme_catalog.name' => 'Teacher Education',
                                    'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0])
                ->all();
    }


    /**
     * Determines if applicant has >= 5 qualifications
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_11_29
     * Modified: 2017_11_29
     */
    public function hasEarlyChildhoodQualifications()
    {
        $records = CsecQualification::find()
                 ->where(['personid'=> $this->personid, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();

        if ($this->isexternal == 1  || count($records) >= 3)
        {
            return true;
        }
        return false;
    }


    /**
     * Determines if applicant has >= 5 qualifications
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_11_29
     * Modified: 2017_11_29
     */
    public function hasPrimarySchoolQualifications()
    {
        $records = CsecQualification::find()
                 ->where(['personid'=> $this->personid, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();

        if ($this->isexternal == 1  || count($records) >= 5)
        {
            return true;
        }
        return false;
    }


    /**
     * Determines if applicant has >= 5 CESE qualifications and unit 1 and unit 2 Cape qualification
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_11_29
     * Modified: 2017_11_29
     */
    public function hasSecondarySchoolQualifications()
    {
        $records = CsecQualification::find()
                 ->where(['personid'=> $this->personid, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();

        if ($this->isexternal == 1  || count($records) >= 5)
        {
            return true;
        }
        return false;
    }


    /**
     * Determines if applicant has >= 5 CESE qualifications
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2018_02_07
     * Modified: 2018_02_07
     */
    public function hasTVETQualifications()
    {
        $records = CsecQualification::find()
                 ->where(['personid'=> $this->personid, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();

        if ($this->isexternal == 1  || count($records) >= 5)
        {
            return true;
        }
        return false;
    }
    /*************************************       DasgsDtveFull     *********************************************/

    /**
    * Returns true if applicant has completed post secondary qualifications form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_10_26
    * Modified: 2017_11_23
    */
    public function isDasgsDtveAdditionalQualificationQueryEntryComplete()
    {
        if ($this->otheracademics != NULL)
        {
            return true;
        }
       return false;
    }

    /**
    * Returns true if applicant has completed academic qualifications form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_10_26
    * Modified: 2017_11_23
    */
   public function isDasgsDtveAcademicQualificationsEntryComplete()
   {
        if($this->isexternal == 1)
        {
            return true;
        }
        else
        {
             if(count($this->getAllQualifications()) >= 5)
            {
                 return true;
             }
        }
       return false;
   }

   /**
    * Returns true if applicant has completed tertiary institution form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_10_26
    * Modified: 2017_11_23
    */
    public function isDasgsDtveTertiaryInstitutionQueryEntryComplete()
    {
        return $this->checkAttendance(4);
    }

    /**
    * Returns true if applicant has completed secondary institution form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_10_26
    * Modified: 2017_11_23
    */
   public function isDasgsDtveSecondaryInstitutionEntryComplete()
   {
        return $this->checkAttendance(3);
   }

    /**
    * Returns true if applicant has completed primary institution form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_10_26
    * Modified: 2017_11_23
    */
   public function isDasgsDtvePrimaryInstitutionEntryComplete()
   {
        return $this->checkAttendance(2);
   }

   /**
    * Returns true if applicant has completed relatives information form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_10_26
    * Modified: 2017_11_11
    */
   public function isDasgsDtveFamilyContactsEntryComplete()
   {
        $relations = CompulsoryRelation::find()
                 ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();
         if (count($relations) == 2)
         {
             $beneficiery_found = false;
             $emergency_contact_found = false;
             foreach($relations as $relation)
             {
                 if ($relation->relationtypeid == 4)
                 {
                     $emergency_contact_found = true;
                 }
                 if ($relation->relationtypeid == 6)
                 {
                     $beneficiery_found = true;
                 }
             }
             if ($emergency_contact_found == true && $beneficiery_found == true)
             {
                 return true;
             }
         }
       return false;
   }

   /**
    * Returns true if applicant has completed addresses form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_10_26
    * Modified: 2017_10_26
    */
   public function isDasgsDtveAddressEntryComplete()
   {
        if ($this->addressValid(1) == true && $this->addressValid(2) == true  && $this->addressValid(3) == true)
        {
            return true;
        }
       return false;
   }

   /**
    * Returns true if applicant has completed contact information form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_10_26
    * Modified: 2017_11_08
    */
   public function isDasgsDtveContactEntryComplete()
   {
        $phone= Phone::find()
             ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
             ->one();

         if ($phone == true &&
                 (
                     ($phone->homephone != NULL && strcmp($phone->homephone,"") != 0)
                     ||  ($phone->cellphone != NULL && strcmp($phone->cellphone,"") != 0)
                     ||  ($phone->workphone != NULL && strcmp($phone->workphone,"") != 0)
                 )
             )
         {
             return true;
         }
       return false;
   }

   /**
    * Returns true if applicant has completed extracirrucular form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_10_26
    * Modified: 2017_11_08
    */
   public function isDasgsDtveExtracurricularEntryComplete()
   {
        /* Fields are set to NULL by default
         * if applicant indicates they have no extracirrcular activities
         *   -> fields should be set to N/A
         * else
         *   -> fields set to empty string
         */
        if ($this->nationalsports !== NULL && $this->othersports !== NULL
                && $this->otherinterests !== NULL && $this->clubs !== NULL)
        {
            return true;
        }
       return false;
   }

   /**
    * Returns true if applicant has completed secondary institution form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_10_26
    * Modified: 2017_11_08
    */
   public function isDasgsDtveProfileEntryComplete()
   {
        if ($this->title != NULL  && strcmp($this->title,"") !=0
       && $this->firstname != NULL && strcmp($this->firstname,"") != 0
       && $this->lastname != NULL && strcmp($this->lastname,"") != 0
       && $this->gender != NULL && strcmp($this->gender,"") != 0
       && $this->dateofbirth != NULL && strcmp($this->dateofbirth,"") != 0
       && $this->nationality != NULL && strcmp($this->nationality,"") != 0
       && $this->placeofbirth != NULL && strcmp($this->placeofbirth,"") != 0
       && $this->maritalstatus != NULL && strcmp($this->maritalstatus,"") != 0
       && $this->religion != NULL && strcmp($this->religion,"") != 0)
       {
            return true;
        }
       return false;
   }

   /**
    * Returns true if applicant has completed programme choice form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_10_26
    * Modified: 2017_10_26
    */
   public function isDasgsDtveProgrammeEntryComplete()
   {
       $applications = Application::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->all();
       if (empty($applications) == false)
       {
           return true;
       }
       return false;
   }

    /*************************************           DteFull           *********************************************/

   /**
    * Returns true if applicant has completed programme choice form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDteProgrammeEntryComplete()
   {
       $applications = Application::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->all();
       if (empty($applications) == false)
       {
           return true;
       }
       return false;
   }


    /**
    * Returns true if applicant has completed secondary institution form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDteProfileEntryComplete()
   {
        if ($this->title != NULL  && strcmp($this->title,"") !=0
       && $this->firstname != NULL && strcmp($this->firstname,"") != 0
       && $this->lastname != NULL && strcmp($this->lastname,"") != 0
       && $this->gender != NULL && strcmp($this->gender,"") != 0
       && $this->dateofbirth != NULL && strcmp($this->dateofbirth,"") != 0
       && $this->nationality != NULL && strcmp($this->nationality,"") != 0
       && $this->placeofbirth != NULL && strcmp($this->placeofbirth,"") != 0
       && $this->maritalstatus != NULL && strcmp($this->maritalstatus,"") != 0
       && $this->religion != NULL && strcmp($this->religion,"") != 0)
       {
            return true;
        }
       return false;
   }

    /**
    * Returns true if applicant has completed extracirrucular form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDteExtracurricularEntryComplete()
   {
        /* Fields are set to NULL by default
         * if applicant indicates they have no extracirrcular activities
         *   -> fields should be set to N/A
         * else
         *   -> fields set to empty string
         */
        if ($this->nationalsports !== NULL && $this->othersports !== NULL
                && $this->otherinterests !== NULL && $this->clubs !== NULL)
        {
            return true;
        }
       return false;
   }

    /**
    * Returns true if applicant has completed contact information form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDteContactEntryComplete()
   {
        $phone= Phone::find()
             ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
             ->one();

         if ($phone == true &&
                 (
                     ($phone->homephone != NULL && strcmp($phone->homephone,"") != 0)
                     ||  ($phone->cellphone != NULL && strcmp($phone->cellphone,"") != 0)
                     ||  ($phone->workphone != NULL && strcmp($phone->workphone,"") != 0)
                 )
             )
         {
             return true;
         }
       return false;
   }

    /**
    * Returns true if applicant has completed addresses form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDteAddressEntryComplete()
   {
        if ($this->addressValid(1) == true && $this->addressValid(2) == true  && $this->addressValid(3) == true)
        {
            return true;
        }
       return false;
   }

    /**
    * Returns true if applicant has completed relatives information form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDteFamilyContactsEntryComplete()
   {
        $relations = CompulsoryRelation::find()
                 ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();
         if (count($relations) == 2)
         {
             $beneficiery_found = false;
             $emergency_contact_found = false;
             foreach($relations as $relation)
             {
                 if ($relation->relationtypeid == 4)
                 {
                     $emergency_contact_found = true;
                 }
                 if ($relation->relationtypeid == 6)
                 {
                     $beneficiery_found = true;
                 }
             }
             if ($emergency_contact_found == true && $beneficiery_found == true)
             {
                 return true;
             }
         }
       return false;
   }

    /**
    * Returns true if applicant has completed primary institution form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDtePrimaryInstitutionEntryComplete()
   {
        return $this->checkAttendance(2);
   }

    /**
    * Returns true if applicant has completed secondary institution form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
    public function isDteSecondaryInstitutionEntryComplete()
    {
        return $this->checkAttendance(3);
    }

    /**
    * Returns true if applicant has completed tertiary institution form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDteTertiaryInstitutionQueryEntryComplete()
   {
        return $this->checkAttendance(4);
   }

    /**
    * Returns true if applicant has completed academic qualifications form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2018_02_07
    */
   public function isDteAcademicQualificationsEntryComplete()
   {
       if ($this->hasTVETApplication() == true  && $this->hasTVETQualifications() == true)
       {
           return true;
       }
       elseif ($this->hasSecondarySchoolApplication() == true  && $this->hasSecondarySchoolQualifications() == true)
       {
           return true;
       }
       elseif ($this->hasPrimarySchoolApplication() == true  && $this->hasPrimarySchoolQualifications() == true)
       {
           return true;
       }
       elseif ($this->hasEarlyChildhoodApplication() == true  && $this->hasEarlyChildhoodQualifications() == true)
       {
           return true;
       }
       return false;
   }

    /**
    * Returns true if applicant has completed post secondary qualifications form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDteAdditionalQualificationQueryEntryComplete()
   {
        if ($this->otheracademics != NULL)
        {
            return true;
        }
       return false;
   }

   /**
    * Returns true if applicant has completed additional teacher profile information
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDteTeachingAdditionalInfoEntryComplete()
   {
       $teaching_additional_info = TeachingAdditionalInfo::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->one();
        if ($teaching_additional_info == true)
        {
            return true;
        }
       return false;
   }

    /**
    * Returns true if applicant has completed teacher work experience information
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDteTeachingExperienceEntryComplete()
   {
       $teaching_additional_info = TeachingAdditionalInfo::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->one();
        if ($teaching_additional_info == true  && $teaching_additional_info->hasteachingexperience !== NULL)
        {
            return true;
        }
       return false;
   }

   /**
    * Returns true if applicant has completed general work experience information
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDteGeneralWorkExperienceEntryComplete()
   {
       $teaching_additional_info = TeachingAdditionalInfo::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->one();
        if ($teaching_additional_info == true  && $teaching_additional_info->hasworked !== NULL)
        {
            return true;
        }
       return false;
   }

   /**
    * Returns true if applicant has completed references
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDteReferencesEntryComplete()
   {
       $references = Reference::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->all();
        if (count($references) == 2)
        {
            return true;
        }
       return false;
   }

   /**
    * Returns true if applicant has completed criminal record information
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_12_01
    */
   public function isDteCriminalRecordEntryComplete()
   {
       $teaching_additional_info = TeachingAdditionalInfo::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->one();
        if ($teaching_additional_info == true  && $teaching_additional_info->hascriminalrecord !== NULL)
        {
            return true;
        }
       return false;
   }

    /*************************************           DneFull           *********************************************/

    /**
    * Returns true if applicant has completed programme choice form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDneProgrammeEntryComplete()
   {
       $applications = Application::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->all();
       if (empty($applications) == false)
       {
           return true;
       }
       return false;
   }


    /**
    * Returns true if applicant has completed secondary institution form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDneProfileEntryComplete()
   {
        if ($this->title != NULL  && strcmp($this->title,"") !=0
       && $this->firstname != NULL && strcmp($this->firstname,"") != 0
       && $this->lastname != NULL && strcmp($this->lastname,"") != 0
       && $this->gender != NULL && strcmp($this->gender,"") != 0
       && $this->dateofbirth != NULL && strcmp($this->dateofbirth,"") != 0
       && $this->nationality != NULL && strcmp($this->nationality,"") != 0
       && $this->placeofbirth != NULL && strcmp($this->placeofbirth,"") != 0
       && $this->maritalstatus != NULL && strcmp($this->maritalstatus,"") != 0
       && $this->religion != NULL && strcmp($this->religion,"") != 0)
       {
            return true;
        }
       return false;
   }



    /**
    * Returns true if applicant has completed extracirrucular form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDneExtracurricularEntryComplete()
   {
        /* Fields are set to NULL by default
         * if applicant indicates they have no extracirrcular activities
         *   -> fields should be set to N/A
         * else
         *   -> fields set to empty string
         */
        if ($this->nationalsports !== NULL && $this->othersports !== NULL
                && $this->otherinterests !== NULL && $this->clubs !== NULL)
        {
            return true;
        }
       return false;
   }


    /**
    * Returns true if applicant has completed contact information form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDneContactEntryComplete()
   {
        $phone= Phone::find()
             ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
             ->one();

         if ($phone == true &&
                 (
                     ($phone->homephone != NULL && strcmp($phone->homephone,"") != 0)
                     ||  ($phone->cellphone != NULL && strcmp($phone->cellphone,"") != 0)
                     ||  ($phone->workphone != NULL && strcmp($phone->workphone,"") != 0)
                 )
             )
         {
             return true;
         }
       return false;
   }


    /**
    * Returns true if applicant has completed addresses form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDneAddressEntryComplete()
   {
        if ($this->addressValid(1) == true && $this->addressValid(2) == true  && $this->addressValid(3) == true)
        {
            return true;
        }
       return false;
   }


    /**
    * Returns true if applicant has completed relatives information form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDneFamilyContactsEntryComplete()
   {
        $relations = CompulsoryRelation::find()
                 ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();
         if (count($relations) == 2)
         {
             $beneficiery_found = false;
             $emergency_contact_found = false;
             foreach($relations as $relation)
             {
                 if ($relation->relationtypeid == 4)
                 {
                     $emergency_contact_found = true;
                 }
                 if ($relation->relationtypeid == 6)
                 {
                     $beneficiery_found = true;
                 }
             }
             if ($emergency_contact_found == true && $beneficiery_found == true)
             {
                 return true;
             }
         }
       return false;
   }


    /**
    * Returns true if applicant has completed primary institution form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDnePrimaryInstitutionEntryComplete()
   {
        return $this->checkAttendance(2);
   }


    /**
    * Returns true if applicant has completed secondary institution form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
    public function isDneSecondaryInstitutionEntryComplete()
    {
        return $this->checkAttendance(3);
    }


    /**
    * Returns true if applicant has completed tertiary institution form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_11_27
    * Modified: 2017_11_27
    */
   public function isDneTertiaryInstitutionQueryEntryComplete()
   {
        return $this->checkAttendance(4);
   }


    /**
    * Returns true if applicant has completed academic qualifications form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDneAcademicQualificationsEntryComplete()
   {
       if ($this->hasMidwiferyApplication() == true  && $this->hasMidwiferyQualifications() == true)
       {
           return true;
       }
       elseif ($this->hasRegisteredNurseApplication() == true  && $this->hasRegisteredNursingQualifications() == true)
       {
           return true;
       }
       elseif ($this->hasNurseAssistantApplication() == true  && $this->hasNursingAssistantQualifications() == true)
       {
           return true;
       }
       return false;
   }

   /**
     * Determines the existence of Midwifery applications
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_12_03
     * Modified: 2017_12_03
     */
    public function hasMidwiferyApplication()
    {
        return Application::find()
                ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->where(['application.personid'=> $this->personid, 'application.isactive' => 1,  'application.isdeleted'=> 0,
                                'academic_offering.isactive' => 1,  'academic_offering.isdeleted'=> 0,
                                'programme_catalog.name' => 'Midwifery', 'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0])
                ->all();
    }


    /**
     * Determines the existence of Registered Nurse applications
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_12_03
     * Modified: 2017_12_03
     */
    public function hasRegisteredNurseApplication()
    {
        return Application::find()
                ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->where(['application.personid'=> $this->personid, 'application.isactive' => 1,  'application.isdeleted'=> 0,
                                'academic_offering.isactive' => 1,  'academic_offering.isdeleted'=> 0,
                                'programme_catalog.name' => 'Registered Nursing', 'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0])
                ->all();
    }


    /**
     * Determines the existence of Nursing Assistant applications
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_12_03
     * Modified: 2017_12_03
     */
    public function hasNurseAssistantApplication()
    {
        return Application::find()
                ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->where(['application.personid'=> $this->personid, 'application.isactive' => 1,  'application.isdeleted'=> 0,
                                'academic_offering.isactive' => 1,  'academic_offering.isdeleted'=> 0,
                                'programme_catalog.name' => 'Nursing Assistant', 'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0])
                ->all();
    }


    /**
     * Determines if applicant has >= 5 qualifications
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_12_03
     * Modified: 2017_12_03
     */
    public function hasMidwiferyQualifications()
    {
        $records = CsecQualification::find()
                 ->where(['personid'=> $this->personid, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();

        if (count($records) >= 5)
        {
            return true;
        }
        return false;
    }


    /**
     * Determines if applicant has >= 5 qualifications
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_12_03
     * Modified: 2017_12_03
     */
    public function hasRegisteredNursingQualifications()
    {
        $records = CsecQualification::find()
                 ->where(['personid'=> $this->personid, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();

        if ($this->isexternal == 1  || count($records) >= 5)
        {
            return true;
        }
        return false;
    }


    /**
     * Determines if applicant has >= 3 qualifications
     *
     * @return boolean
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2017_12_03
     * Modified: 2017_12_03
     */
    public function hasNursingAssistantQualifications()
    {
        $records = CsecQualification::find()
                 ->where(['personid'=> $this->personid, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();

        if ($this->isexternal == 1  || count($records) >= 3)
        {
            return true;
        }
        return false;
    }


    /**
    * Returns true if applicant has completed post secondary qualifications form
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDneAdditionalQualificationQueryEntryComplete()
   {
        if ($this->otheracademics != NULL)
        {
            return true;
        }
       return false;
   }


   /**
    * Returns true if applicant has completed additional nursing profile information
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDneNursingAdditionalInfoEntryComplete()
   {
       $nursing_additional_info = NursingAdditionalInfo::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->one();
        if ($nursing_additional_info == true)
        {
            return true;
        }
       return false;
   }


    /**
    * Returns true if applicant has completed nurse work experience information
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDneNursingExperienceEntryComplete()
   {
       $nursing_additional_info = NursingAdditionalInfo::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->one();
        if ($nursing_additional_info == true  && $nursing_additional_info->hasnursingexperience !== NULL)
        {
            return true;
        }
       return false;
   }


   /**
    * Returns true if applicant has completed general work experience information
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDneGeneralWorkExperienceEntryComplete()
   {
       $nursing_additional_info = NursingAdditionalInfo::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->one();
        if ($nursing_additional_info == true  && $nursing_additional_info->hasworked !== NULL)
        {
            return true;
        }
       return false;
   }


   /**
    * Returns true if applicant has completed references
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDneReferencesEntryComplete()
   {
       $references = Reference::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->all();
        if (count($references) == 2)
        {
            return true;
        }
       return false;
   }


   /**
    * Returns true if applicant has completed criminal record information
    *
    * @return boolean
    *
    * Author: charles.laurence1@gmail.com
    * Created: 2017_12_03
    * Modified: 2017_12_03
    */
   public function isDneCriminalRecordEntryComplete()
   {
       $nursing_additional_info = NursingAdditionalInfo::find()
               ->where(['personid' => $this->personid, 'isactive' => 1, 'isdeleted' => 0])
               ->one();
        if ($nursing_additional_info == true  && $nursing_additional_info->hascriminalrecord !== NULL)
        {
            return true;
        }
       return false;
   }


}
