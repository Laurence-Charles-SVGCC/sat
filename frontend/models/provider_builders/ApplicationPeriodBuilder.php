<?php
    namespace frontend\models\provider_builders;

    use Yii;
    use yii\data\ArrayDataProvider;
    use yii\custom\ModelNotFoundException;
     
    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\ApplicationperiodStatus;
    use frontend\models\ApplicationPeriodType;
    use frontend\models\Division;
    use frontend\models\Employee;
    use frontend\models\AcademicYear;
    use frontend\models\ApplicantIntent;
    use frontend\models\Applicant;
    use frontend\models\ApplicantRegistration;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\ApplicationCapeSubject;
    use frontend\models\data_formatter\ArrayFormatter;
    use frontend\models\Phone;
    use frontend\models\Email;
    use frontend\models\CsecQualification;
    use frontend\models\ExaminationBody;
    use frontend\models\ExaminationGrade;
    use frontend\models\ExaminationProficiencyType;
    use frontend\models\Subject;
    use frontend\models\CsecCentre;
    use frontend\models\Offer;
    use frontend\models\Rejection;
     
    
    class ApplicationPeriodBuilder extends \yii\base\Model
    { 
        /**
         * Created collection of application period details
         * 
         * @param Integer $page_size
         * @return ArrayDataProvider
         * @throws ModelNotFoundException
         * 
         * Author: charles.laurence1@gmail.com
         * Date Created: 2017_08_24
         * Date Last Modified: 2017_10_09
         */   
        public static function generateApplicationPeriodListing($page_size)
        {
            $period_details_data_provider = array();
            $period_stats_data_provider = array();
            $details_records = array();
            $stats_records = array();

            $application_periods = ApplicationPeriod::getAllActivePeriods();
            
            if (empty($application_periods) == true)
            {
                throw new ModelNotFoundException('No active application periods found');
            }
            
            foreach ($application_periods  as $application_period)
            {
                $id = $application_period->applicationperiodid;
                $details_data = array();
                $details_data['id'] = $application_period->applicationperiodid;
                $status = ApplicationperiodStatus::find()
                        ->where(['applicationperiodstatusid' =>$application_period->applicationperiodstatusid, 'isdeleted' => 0])
                        ->one();
                if ( $status == NULL)
                {
                    $error_message = "ApplicationPeriodStatus for ApplicationPeriod -> ID= " .  $id .  " not found.";
                    throw new ModelNotFoundException($error_message);
                }
                $details_data['status'] = $status->name;
                
                $period_type = ApplicationPeriodType::find()
                        ->where(['applicationperiodtypeid' =>$application_period->applicationperiodtypeid, 'isdeleted' => 0])
                        ->one();
                if ( $period_type == NULL)
                {
                    $error_message = "ApplicationPeriodStatusType for ApplicationPeriod -> ID= " .  $id .  " not found.";
                    throw new ModelNotFoundException($error_message);
                }
                $details_data['type'] = $period_type->name;
                
                $division = Division::find()
                        ->where(['divisionid' =>  $application_period->divisionid])
                        ->one();
                if ( $division == NULL)
                {
                    $error_message = "Division for ApplicationPeriod -> ID= " .  $id  .  " not found.";
                    throw new ModelNotFoundException($error_message);
                }
                $details_data['division'] = $division->abbreviation;
                
                $details_data['created_by'] = Employee::getEmployeeName($application_period->personid);
                
                $year = AcademicYear::find()
                        ->where(['academicyearid' => $application_period->academicyearid])
                        ->one();
                 if ( $year == NULL)
                {
                    $error_message = "AcademicYear for ApplicationPeriod -> ID= " .  $id  .  " not found.";
                    throw new ModelNotFoundException($error_message);
                }        
                $details_data['year'] = $year->title;
                
                $details_data['name'] =  $application_period->name;
                $details_data['onsitestartdate'] =  $application_period->onsitestartdate;
                $details_data['onsiteenddate'] =  $application_period->onsiteenddate;
                $details_data['offsitestartdate'] =  $application_period->offsitestartdate;
                $details_data['offsiteenddate'] =  $application_period->offsiteenddate;
                $details_data['iscomplete'] =  $application_period->iscomplete == 1 ? "Excluded" : "Selectable";
                $details_records[] = $details_data;
            }

            $period_details_data_provider = new ArrayDataProvider([
                        'allModels' => $details_records,
                        'pagination' => [
                            'pageSize' => $page_size,
                        ],
                        'sort' => [
                            'defaultOrder' => ['id' => SORT_ASC],
                            'attributes' => ['id', 'division', 'year'],
                        ]
                ]); 
            
            return $period_details_data_provider;
        }


        /**
         * Created collection of application period ap-plicant partipation statistics
         * 
         * @param  Integer $page_size
         * @return ArrayDataProvider
         * @throws ModelNotFoundException
         * 
         * Author: charles.laurence1@gmail.com
         * Date Created: 2017_08_24
         * Date Last Modified: 2017_10_10
         */   
        public static function generateApplicationPeriodStatistics($page_size)
        {
            $period_stats_data_provider = array();
            $stats_records = array();

            $academic_years = AcademicYear::getAllActiveYears();

           if ( empty($academic_years) == true)
           {
               throw new ModelNotFoundException('No active academic years found');
           }
                    
           foreach ( $academic_years  as  $academic_year)
           {
                $active_application_period = ApplicationPeriod::find()->where(['academicyearid' => $academic_year->academicyearid, 'isactive' =>1, 'isdeleted' => 0])->one();
                if ($active_application_period == false)
                {
                    continue;
                }
                $id = $academic_year->academicyearid;
                $stats_data = array();
                $stats_data['academicyearid'] = $academic_year->academicyearid;
                $stats_data['title'] = $academic_year->title;

                $intent = ApplicantIntent::find()
                         ->where(['applicantintentid' => $academic_year->applicantintentid,  'isactive' => 1,  'isdeleted' => 0])
                        ->one();
                if ( $intent == NULL)
                {
                    $error_message = "ApplicantIntent for AcademicYear -> ID= " .  $id  .  " not found.";
                    throw new ModelNotFoundException($error_message);
                }
                $stats_data['applicantintent_name'] = $intent->name;

                $total_number_of_applications_started = count(Applicant::getCommencedApplicants($academic_year->academicyearid));
                $stats_data['total_number_of_applications_started'] = $total_number_of_applications_started;

                $total_number_of_applications_completed = count(Applicant::getCompletedApplicants($academic_year->academicyearid));
                $stats_data['total_number_of_applications_completed'] = $total_number_of_applications_completed;

                $total_number_of_applications_removed  = count(Applicant::getRemovedApplicants($academic_year->academicyearid));
                $stats_data['total_number_of_applications_removed'] = $total_number_of_applications_removed;

                $total_number_of_applications_incomplete  = count(Applicant::getIncompleteApplicants($academic_year->academicyearid));
                $stats_data['total_number_of_applications_incomplete'] = $total_number_of_applications_incomplete;

                $total_number_of_applications_verified = count(Applicant::getVerifiedApplicants($academic_year->academicyearid));
                $stats_data['total_number_of_applications_verified'] = $total_number_of_applications_verified;
                
                $total_number_of_applications_unverified = count(Applicant::getUnverifiedApplicants($academic_year->academicyearid));
                $stats_data['total_number_of_applications_unverified'] = $total_number_of_applications_unverified;
                
                $total_number_of_applications_offers = count(Applicant::getSuccessfulApplicants($academic_year->academicyearid));
                $stats_data['total_number_of_applications_offers'] = $total_number_of_applications_offers;
                
                $total_number_of_applications_rejections = count(Applicant::getUnsuccessfulApplicants($academic_year->academicyearid));
                $stats_data['total_number_of_applications_rejections'] = $total_number_of_applications_rejections;
                
                $stats_records[] = $stats_data;
            }

            $period_stats_data_provider = new ArrayDataProvider([
                    'allModels' => $stats_records,
                    'pagination' => [
                        'pageSize' => $page_size,
                    ],
                    'sort' => [
                        'defaultOrder' => ['title' =>SORT_ASC, 'applicantintent_name' =>SORT_ASC],
                        'attributes' => ['title', 'applicantintent_name'],
                    ]
                ]); 
            return $period_stats_data_provider;
        }


        /**
         * Generates report listing applicants that begin the application unverified applicants
         * Generates report listing applicants that completed email verification and had applicant accounts initialized
         * 
         * @param  Integer $academicyearid
         * @param type $page_size
         * @return ArrayDataProvider
         * @throws ModelNotFoundException
         * 
         * Author: charles.laurence1@gmail.com
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_10_09
         */
        public static function generateCommencedApplicationsReport($academicyearid, $page_size)
        {
            $data_provider = array();
            $records = array();
            
            $applicants = Applicant::getCommencedApplicants($academicyearid);
            
            if (empty($applicants) == false)
            {
                foreach ($applicants as $applicant)
                {
                    $data = array();
                    $data['username'] = User::find()->where(['personid' => $applicant->personid])->one()->username;
                    $data['title'] = $applicant->title;
                    $data['firstname'] = trim($applicant->firstname);
                    $data['lastname'] = trim($applicant->lastname);
                    $data['email'] = Email::find()->where(['personid' => $applicant->personid])->one()->email;
                    $records[] = $data;
                }

                $data_provider = new ArrayDataProvider([
                        'allModels' => $records,
                        'pagination' => [
                            'pageSize' => $page_size,
                        ],
                        'sort' => [
                            'defaultOrder' => ['lastname' =>SORT_ASC, 'firstname' =>SORT_ASC],
                            'attributes' => ['username', 'firstname', 'lastname'],
                        ]
                    ]); 
            }
            return $data_provider;
        }
        
        
        /**
         * Generates report listing applicants that completed the submission of their applications
         * 
         * @param Integer $academicyearid
         * @param Integer $page_size
         * @return ArrayDataProvider
         * @throws ModelNotFoundException
         * 
         * Author: charles.laurence1@gmail.com
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_10_10
         */
        public static function generateCompletedApplicationsReport($academicyearid, $page_size)
        {
            $provider_collection = array();
            $data_provider = array();
            $records = array();
            
            $applicants = Applicant::getCompletedApplicants($academicyearid);
            
            if (empty($applicants) == false)
            {
                $i = 0;
                foreach ($applicants as $applicant)
                {
                   
                    $id = $applicant->personid;
                    $data = array();

                    $user = User::getUser($applicant->personid);
                    if ($user == NULL)
                    {
                        $error_message = "No user record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    $data['username'] = $user->username;

                    $data['title'] = $applicant->title;
                    $data['firstname'] = trim($applicant->firstname);
                    $data['middlename'] = $applicant->middlename == true ? trim($applicant->middlename) : "";
                    $data['lastname'] = trim($applicant->lastname);

                    $email = Email::find()
                            ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->one();
                    if ($email == NULL)
                    {
                        $error_message = "No email record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    $data['email'] = $email->email;

                    $phone = Phone::find()
                            ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->one();
                    if ($phone == NULL)
                    {
                        $error_message = "No phone record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    $phone_contacts = "";
                    if ($phone->homephone == true)
                        $phone_contacts .= $phone->homephone . ", ";
                    if ($phone->workphone == true)
                        $phone_contacts .= $phone->workphone . ", ";
                    if ($phone->cellphone == true)
                        $phone_contacts .= $phone->cellphone;
                    $data['phone'] =  $phone_contacts;

                    $programme_listing = $applicant->getProgrammeChoices();
                    $data['programmes'] = $programme_listing;

                    $institution_listing =  $applicant->getInstitutions();
                    $data['institutions'] = $institution_listing;

                    $qualifications = $applicant->getQualifications(3);
                    $data['total_csec_qualifications'] = count($qualifications);

                    $data['csec_ones'] = CsecQualification::getAllSubjectGradesCount($applicant->personid, 3, 1);
                    $data['csec_twos'] = CsecQualification::getAllSubjectGradesCount($applicant->personid, 3, 2);
                    $data['csec_threes'] = CsecQualification::getAllSubjectGradesCount($applicant->personid, 3, 3);
                    $data['application_duration (mins)'] = $applicant->calculateApplicantSubmissionDurationFromAccountCreation();

                    $records[] = $data;
                    $i++;
                }

                $data_provider = new ArrayDataProvider([
                        'allModels' => $records,
                        'pagination' => [
                            'pageSize' => $page_size,
                        ],
                        'sort' => [
                            'defaultOrder' => ['lastname' =>SORT_ASC, 'firstname' =>SORT_ASC],
                            'attributes' => ['username', 'firstname', 'lastname'],
                        ]
                    ]);
            }
            return $data_provider;
        }

        
        /**
         * Generates report listing who started but did not submit their applications
         * 
         * @param Integer $academicyearid
         * @param Integere $page_size
         * @return ArrayDataProvider
         * @throws ModelNotFoundException
         * 
         * Author: charles.laurence1@gmail.com
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_10_09
         */
        public static function generateIncompleteApplicationsReport($academicyearid, $page_size)
        {
            $data_provider = array();
            $records = array();
            
            $applicants = Applicant::getIncompleteApplicants($academicyearid);
            
            if (empty($applicants) == false)
            {
                foreach ($applicants as $applicant)
                {
                    $id = $applicant->personid;
                    $data = array();

                    $user = User::getUser($applicant->personid);
                    if ($user == NULL)
                    {
                        $error_message = "No user record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    $data['username'] = $user->username;

                    $data['title'] = $applicant->title;
                    $data['firstname'] = trim($applicant->firstname);
                    $data['middlename'] = $applicant->middlename == true ? trim($applicant->middlename) : "";
                    $data['lastname'] = trim($applicant->lastname);

                    $email = Email::find()
                            ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->one();
                    if ($email == NULL)
                    {
                        $error_message = "No email record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    $data['email'] = $email->email;

                    $phone = Phone::find()
                            ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->one();
                    if ($phone == NULL)
                    {
                        $error_message = "No phone record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    $phone_contacts = "";
                    if ($phone->homephone == true)
                        $phone_contacts .= $phone->homephone . ", ";
                    if ($phone->workphone == true)
                        $phone_contacts .= $phone->workphone . ", ";
                    if ($phone->cellphone == true)
                        $phone_contacts .= $phone->cellphone;
                    $data['phone'] =  $phone_contacts;

                    $records[] = $data;
                }

                $data_provider = new ArrayDataProvider([
                        'allModels' => $records,
                        'pagination' => [
                            'pageSize' => $page_size,
                        ],
                        'sort' => [
                            'defaultOrder' => ['lastname' =>SORT_ASC, 'firstname' =>SORT_ASC],
                            'attributes' => ['username', 'firstname', 'lastname'],
                        ]
                    ]); 
            }
            return $data_provider;
        }
        
        
        /**
         * Generates report listing applicants whose certificates have been verified
         * 
         * @param Integer $academicyearid
         * @param Integer $page_size
         * @return ArrayDataProvider
         * @throws ModelNotFoundException
         * 
         * Author: charles.laurence1@gmail.com
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_10_09
         */
        public static function generateVerifiedApplicationsReport($academicyearid, $page_size)
        {
            $data_provider = array();
            $records = array();
            
            $applicants = Applicant::getVerifiedApplicants($academicyearid);
            
            if (empty($applicants) == false)
            {
                foreach ($applicants as $applicant)
                {
                    $id = $applicant->personid;

                    $data = array();

                    $user = User::getUser($applicant->personid);
                    if ($user == NULL)
                    {
                        $error_message = "No user record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    $data['username'] = $user->username;

                    $data['title'] = $applicant->title;
                    $data['firstname'] = trim($applicant->firstname);
                    $data['middlename'] = $applicant->middlename == true ? trim($applicant->middlename) : "";
    //                $data['lastname'] = htmlspecialchars_decode(trim($applicant->lastname), ENT_QUOTES);
    //                $data['lastname'] = htmlspecialchars_decode($applicant->lastname, ENT_QUOTES);

    //                 $data['lastname'] = substr_replace($applicant->lastname, "'", "&#39;");
    //                 $data['lastname'] = html_entity_decode($applicant->lastname, ENT_QUOTES);
                    $data['lastname'] = $applicant->lastname;

                    $email = Email::find()
                            ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->one();
                    if ($email == NULL)
                    {
                        $error_message = "No email record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    $data['email'] = $email->email;

                    $phone = Phone::find()
                            ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->one();
                    if ($phone == NULL)
                    {
                        $error_message = "No phone record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    $phone_contacts = "";
                    if ($phone->homephone == true)
                        $phone_contacts .= $phone->homephone . ", ";
                    if ($phone->workphone == true)
                        $phone_contacts .= $phone->workphone . ", ";
                    if ($phone->cellphone == true)
                        $phone_contacts .= $phone->cellphone;
                    $data['phone'] =  $phone_contacts;

                    $programme_listing = $applicant->getProgrammeChoices();
                    $data['programmes'] = $programme_listing;

                    $data['verifying_officer'] = Employee::getEmployeeName($applicant->verifier);

                    $records[] = $data;
                }

                $data_provider = new ArrayDataProvider([
                        'allModels' => $records,
                        'pagination' => [
                            'pageSize' => $page_size,
                        ],
                        'sort' => [
                            'defaultOrder' => ['lastname' =>SORT_ASC, 'firstname' =>SORT_ASC],
                            'attributes' => ['username', 'firstname', 'lastname'],
                        ]
                    ]); 
            }
            return $data_provider;
        }
        
        
        /**
         * Generates report listing applicants whose certificates have not  been verified
         * 
         * @param Integer $academicyearid
         * @param Integer $page_size
         * @return ArrayDataProvider
         * @throws ModelNotFoundException
         * 
         * Author: charles.laurence1@gmail.com
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_10_09
         */
        public static function generateUnverifiedApplicationsReport($academicyearid, $page_size)
        {
            $data_provider = array();
            $records = array();
            
            $applicants = Applicant::getUnverifiedApplicants($academicyearid);
            
            if (empty($applicants) == false)
            {
                foreach ($applicants as $applicant)
                {
                    $id = $applicant->personid;

                    $data = array();

                    $user = User::getUser($applicant->personid);
                    if ($user == NULL)
                    {
                        $error_message = "No user record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    $data['username'] = $user->username;

                    $data['title'] = $applicant->title;
                    $data['firstname'] = trim($applicant->firstname);
                    $data['middlename'] = $applicant->middlename == true ? trim($applicant->middlename) : "";
                    $data['lastname'] = trim($applicant->lastname);

                    $email = Email::find()
                            ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->one();
                    if ($email == NULL)
                    {
                        $error_message = "No email record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    $data['email'] = $email->email;

                    $phone = Phone::find()
                            ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->one();
                    if ($phone == NULL)
                    {
                        $error_message = "No phone record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    $phone_contacts = "";
                    if ($phone->homephone == true)
                        $phone_contacts .= $phone->homephone . ", ";
                    if ($phone->workphone == true)
                        $phone_contacts .= $phone->workphone . ", ";
                    if ($phone->cellphone == true)
                        $phone_contacts .= $phone->cellphone;
                    $data['phone'] =  $phone_contacts;

                    $programme_listing = $applicant->getProgrammeChoices();
                    $data['programmes'] = $programme_listing;

                    $records[] = $data;
                }

                $data_provider = new ArrayDataProvider([
                        'allModels' => $records,
                        'pagination' => [
                            'pageSize' => $page_size,
                        ],
                        'sort' => [
                            'defaultOrder' => ['lastname' =>SORT_ASC, 'firstname' =>SORT_ASC],
                            'attributes' => ['username', 'firstname', 'lastname'],
                        ]
                    ]); 
            }
            return $data_provider;
        }
        
        
        /**
         * Generates report listing successful applicants
         * 
         * @param Integer $academicyearid
         * @param Integer $page_size
         * @return ArrayDataProvider
         * @throws ModelNotFoundException
         * 
         * Author: charles.laurence1@gmail.com
         * Date Created: 2017_10_12
         * Date Last Modified: 2017_10_12
         */
        public static function generateSuccessfulApplicationsReport($academicyearid, $page_size)
        {
            $data_provider = array();
            $records = array();
            
            $applicants = Applicant::getSuccessfulApplicants($academicyearid);
            
            if (empty($applicants) == false)
            {
                foreach ($applicants as $applicant)
                {
                    $id = $applicant->personid;

                    $data = array();

                    $user = User::getUser($applicant->personid);
                    if ($user == NULL)
                    {
                        $error_message = "No user record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    
                    
                    // applicant may have multiple offers
                    $cond = array();
                    $cond['offer.offertypeid'] = 1;
                    $cond['offer.ispublished'] = 1;
                    $cond['offer.isactive'] = 1;
                    $cond['offer.isdeleted'] = 0;
                    $cond['academic_offering.isactive'] = 1;
                    $cond['academic_offering.isdeleted'] = 0;
                    $cond['academic_offering.academicyearid'] = $academicyearid;
                    $cond['application.personid'] = $applicant->personid;
                    $cond['application.applicationstatusid'] = 9;
                    $cond['application.isdeleted'] = 0;
                    
                    $offers =  Offer::find()
                            ->innerJoin('application', '`offer`.`applicationid` = `application`.`applicationid`')
                            ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                            ->where($cond)
                            ->groupBy('application.personid')
                            ->all();
                    
                    foreach ($offers as $offer)
                    {
                        $data['username'] = $user->username;

                        $data['title'] = $applicant->title;
                        $data['firstname'] = trim($applicant->firstname);
                        $data['middlename'] = $applicant->middlename == true ? trim($applicant->middlename) : "";
                        $data['lastname'] = trim($applicant->lastname);

                        $email = Email::find()
                                ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' =>0])
                                ->one();
                        if ($email == NULL)
                        {
                            $error_message = "No email record found for Applicant->PersonID= " . $id;
                            throw new ModelNotFoundException($error_message);
                        }
                        $data['email'] = $email->email;

                        $phone = Phone::find()
                                ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' =>0])
                                ->one();
                        if ($phone == NULL)
                        {
                            $error_message = "No phone record found for Applicant->PersonID= " . $id;
                            throw new ModelNotFoundException($error_message);
                        }
                        $phone_contacts = "";
                        if ($phone->homephone == true)
                            $phone_contacts .= $phone->homephone . ", ";
                        if ($phone->workphone == true)
                            $phone_contacts .= $phone->workphone . ", ";
                        if ($phone->cellphone == true)
                            $phone_contacts .= $phone->cellphone;
                        $data['phone'] =  $phone_contacts;

                        $application = $offer->getApplication()->one();
                        $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
                        $issuer = Employee::findOne(['personid' => $offer->issuedby]);
                        $issuername = $issuer ? $issuer->firstname . ' ' . $issuer->lastname : 'Undefined Issuer';
                        $cape_subjects_names = array();
                        $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
                        foreach ($cape_subjects as $cs) 
                        {
                            $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                        }
                        $data['programme'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
                        $data['issuedby'] = $issuername;
                        $data['issuedate'] = $offer->issuedate;
                        $records[] = $data;
                    }
                }

                $data_provider = new ArrayDataProvider([
                        'allModels' => $records,
                        'pagination' => [
                            'pageSize' => $page_size,
                        ],
                        'sort' => [
                            'defaultOrder' => ['lastname' =>SORT_ASC, 'firstname' =>SORT_ASC],
                            'attributes' => ['username', 'firstname', 'lastname'],
                        ]
                    ]);
             }
            return $data_provider;
        }
        
        
        /**
         * Generates report listing successful applicants
         * 
         * @param Integer $academicyearid
         * @param Integer $page_size
         * @return ArrayDataProvider
         * @throws ModelNotFoundException
         * 
         * Author: charles.laurence1@gmail.com
         * Date Created: 2017_10_12
         * Date Last Modified: 2017_10_12
         */
        public static function generateUnsuccessfulApplicationsReport($academicyearid, $page_size)
        {
            $data_provider = array();
            $records = array();
            
            $applicants = Applicant::getUnsuccessfulApplicants($academicyearid);
            
            if (empty($applicants) == false)
            {
                foreach ($applicants as $applicant)
                {
                    $id = $applicant->personid;

                    $data = array();

                    $user = User::getUser($applicant->personid);
                    if ($user == NULL)
                    {
                        $error_message = "No user record found for Applicant->PersonID= " . $id;
                        throw new ModelNotFoundException($error_message);
                    }
                    
                    // applicant may have multiple offers
                    $cond = array();
                    $cond['rejection.isactive'] = 1;
                    $cond['rejection.isdeleted'] = 0;
                    $cond['rejection_applications.isactive'] = 1;
                    $cond['rejection_applications.isdeleted'] = 0;
                    $cond['application.personid'] = $applicant->personid;
                    $cond['application.applicationstatusid'] = [6,10];
                    $cond['application.isdeleted'] = 0;
                    $cond['academic_offering.isactive'] = 1;
                    $cond['academic_offering.isdeleted'] = 0;
                    $cond['academic_offering.academicyearid'] = $academicyearid;
                   
                    $rejections = Rejection::find()
                            ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                            ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                            ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->where($cond)
                            ->groupby('rejection.rejectionid')
                            ->all();
                    
                    foreach ($rejections as $rejection)
                    {
                        $data['username'] = $user->username;
                        $data['title'] = $applicant->title;
                        $data['firstname'] = trim($applicant->firstname);
                        $data['middlename'] = $applicant->middlename == true ? trim($applicant->middlename) : "";
                        $data['lastname'] = trim($applicant->lastname);

                        $email = Email::find()
                                ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' =>0])
                                ->one();
                        if ($email == NULL)
                        {
                            $error_message = "No email record found for Applicant->PersonID= " . $id;
                            throw new ModelNotFoundException($error_message);
                        }
                        $data['email'] = $email->email;

                        $phone = Phone::find()
                                ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' =>0])
                                ->one();
                        if ($phone == NULL)
                        {
                            $error_message = "No phone record found for Applicant->PersonID= " . $id;
                            throw new ModelNotFoundException($error_message);
                        }
                        $phone_contacts = "";
                        if ($phone->homephone == true)
                            $phone_contacts .= $phone->homephone . ", ";
                        if ($phone->workphone == true)
                            $phone_contacts .= $phone->workphone . ", ";
                        if ($phone->cellphone == true)
                            $phone_contacts .= $phone->cellphone;
                        $data['phone'] =  $phone_contacts;

                        //generate array of all programmes applicant applied for
                        $applications = $rejection->getApplications()->all();
                        $programme_listing = array();
                        foreach($applications as $application)
                        {
                            $programme_record = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);

                            $cape_subjects = array();
                            $cape_subjects_names = array();
                            $cape_subjects = ApplicationCapesubject::find()
                                        ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                                        ->where(['application.applicationid' => $application->applicationid,
                                                'application.isactive' => 1,
                                                'application.isdeleted' => 0]
                                                )
                                        ->all();
                            foreach ($cape_subjects as $cs) 
                            { 
                                $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                            }
                            $programme_name = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                            array_push($programme_listing, $programme_name);
                        }
                        
                        $rejected_programme_listing = " ";
                        foreach ($programme_listing as $key=>$entry)
                        {
                            if((count($programme_listing)-1) == $key)
                            {
                                $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry;
                            }
                            else
                            {
                                $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry . ",";
                            }
                        }
                        $data['programme'] = $rejected_programme_listing;
            
                        $issuer = Employee::findOne(['personid' => $rejection->issuedby]);
                        $issuername = $issuer ? $issuer->title . '. ' . $issuer->lastname : 'Undefined Issuer';
                        $data['issuedby'] = $issuername;
                        $data['issuedate'] = $rejection->issuedate;
                        
                        $records[] = $data;
                    }
                }

                $data_provider = new ArrayDataProvider([
                        'allModels' => $records,
                        'pagination' => [
                            'pageSize' => $page_size,
                        ],
                        'sort' => [
                            'defaultOrder' => ['lastname' =>SORT_ASC, 'firstname' =>SORT_ASC],
                            'attributes' => ['username', 'firstname', 'lastname'],
                        ]
                    ]);
             }
            return $data_provider;
        }
        
        
        
        
        
        
        
    }
    
