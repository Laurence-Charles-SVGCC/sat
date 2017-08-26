<?php
    namespace frontend\models\provider_builders;

    use Yii;
    use yii\data\ArrayDataProvider;
    use yii\custom\ModelNotFoundException;
     
    use frontend\models\ApplicationPeriod;
    use frontend\models\ApplicationperiodStatus;
    use frontend\models\ApplicationPeriodType;
    use frontend\models\Division;
    use frontend\models\Employee;
    use frontend\models\AcademicYear;
    use frontend\models\ApplicantIntent;
    use frontend\models\Applicant;
     
    class ApplicationPeriodBuilder extends \yii\base\Model
    { 

        /**
         * Created collection of application period details
         * 
         * @return ArrayDataProvider
         * 
         * Author: Laurence Charles
         * Date Created: 2017_08_24
         * Date Last Modified: 2017_08_24
         */   
        public static function generateApplicaitonPeriodListing()
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
                            'pageSize' => 25,
                        ],
                        'sort' => [
                            'defaultOrder' => ['id' =>SORT_ASC],
                            'attributes' => ['id', 'division'],
                        ]
                ]); 
            
            return $period_details_data_provider;
        }


        
        /**
         * Created collection of application period ap-plicant partipation statistics
         * 
         * @return ArrayDataProvider
         * 
         * Author: Laurence Charles
         * Date Created: 2017_08_24
         * Date Last Modified: 2017_08_24
         */   
        public static function generateApplicaitonPeriodStatistics()
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

                $total_number_of_applications_started = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where(['applicant.applicantintentid' =>  $intent->applicantintentid , 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                                        'application.applicationstatusid' => [1,2,3,4,5,6,7,8,9,10,11], 'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                         'application_period.academicyearid' => $academic_year->academicyearid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                                    ])
                        ->groupBy('applicant.personid')
                        ->count();
                $stats_data['total_number_of_applications_started'] = $total_number_of_applications_started;

                $total_number_of_applications_completed = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where(['applicant.applicantintentid' =>  $intent->applicantintentid , 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                                        'application.applicationstatusid' => [2,3,4,5,6,7,8,9,10,11], 'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                         'application_period.academicyearid' => $academic_year->academicyearid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                                    ])
                        ->groupBy('applicant.personid')
                        ->count();
                $stats_data['total_number_of_applications_completed'] = $total_number_of_applications_completed;


                $total_number_of_applications_removed = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where(['applicant.applicantintentid' =>  $intent->applicantintentid , 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                                        'application.applicationstatusid' => 11, 'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                         'application_period.academicyearid' => $academic_year->academicyearid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                                    ])
                        ->groupBy('applicant.personid')
                        ->count();
                $stats_data['total_number_of_applications_removed'] = $total_number_of_applications_removed;

                $total_number_of_applications_incomplete =Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where(['applicant.applicantintentid' =>  $intent->applicantintentid , 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                                        'application.applicationstatusid' => 1, 'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                         'application_period.academicyearid' => $academic_year->academicyearid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                                    ])
                        ->groupBy('applicant.personid')
                        ->count();
                $stats_data['total_number_of_applications_incomplete'] = $total_number_of_applications_incomplete;

                $total_number_of_applications_verified = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where(['applicant.applicantintentid' =>  $intent->applicantintentid , 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                                        'application.applicationstatusid' => [3,4,5,6,7,8,9,10], 'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                         'application_period.academicyearid' => $academic_year->academicyearid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                                    ])
                        ->groupBy('applicant.personid')
                        ->count();
                $stats_data['total_number_of_applications_verified'] = $total_number_of_applications_verified;

                $total_number_of_applications_unverified = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where(['applicant.applicantintentid' =>  $intent->applicantintentid , 'applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                                        'application.applicationstatusid' => [2, 11], 'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                         'application_period.academicyearid' => $academic_year->academicyearid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                                    ])
                        ->groupBy('applicant.personid')
                        ->count();
                 $stats_data['total_number_of_applications_unverified'] = $total_number_of_applications_unverified;
                $stats_records[] = $stats_data;
            }

            $period_stats_data_provider = new ArrayDataProvider([
                    'allModels' => $stats_records,
                    'pagination' => [
                        'pageSize' => 25,
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
         * 
         * @return csvfile
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_08_25
         */
        public static function generateCommencedApplicationsReport($academicyearid)
        {
            $data_provider = array();
            $records = array();
            
            $registrations = ApplicantRegistration::getApplicantRegistrationsByYear($acadmeicyearid);
            
            if (empty($registrations) == true)
            {
                $error_message = "No student user accounts found for AcademicYear ->ID= " . $acadmeicyearid;
                throw new ModelNotFoundException($error_message);
            }
            
            foreach ($registrations as $registrations)
            {
                $data = array();
                
                
                $records[] = $data;
            }
                
            $data_provider = new ArrayDataProvider([
                    'allModels' => $records,
                    'pagination' => [
                        'pageSize' => 2000,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' =>SORT_ASC, 'firstname' =>SORT_ASC],
                        'attributes' => ['username', 'firstname', 'lastname'],
                    ]
                ]); 
            return $data_provider;
        }
        
        
        /**
         * Generates report listing applicants that completed the submission of their applications
         * 
         * @return csvfile
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_08_25
         */
        public static function generateCompletedApplicationsReport($academicyearid)
        {
            $data_provider = array();
            $records = array();
            
            $data_provider = new ArrayDataProvider([
                    'allModels' => $records,
                    'pagination' => [
                        'pageSize' => 2000,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' =>SORT_ASC, 'firstname' =>SORT_ASC],
                        'attributes' => ['username', 'firstname', 'lastname'],
                    ]
                ]); 
            return $data_provider;
        }

        
        /**
         * Generates report listing who started but did not submit their applications
         * 
         * @return csvfile
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_08_25
         */
        public static function generateIncompleteApplicationsReport($academicyearid)
        {
            $data_provider = array();
            $records = array();
            
            $data_provider = new ArrayDataProvider([
                    'allModels' => $records,
                    'pagination' => [
                        'pageSize' => 2000,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' =>SORT_ASC, 'firstname' =>SORT_ASC],
                        'attributes' => ['username', 'firstname', 'lastname'],
                    ]
                ]); 
            return $data_provider;
        }
        
        
        /**
         * Generates report listing applicants whose certificates have been verified
         * 
         * @return csvfile
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_08_25
         */
        public static function generateVerifiedApplicationsReport($academicyearid)
        {
            $data_provider = array();
            $records = array();
            
            $data_provider = new ArrayDataProvider([
                    'allModels' => $records,
                    'pagination' => [
                        'pageSize' => 2000,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' =>SORT_ASC, 'firstname' =>SORT_ASC],
                        'attributes' => ['username', 'firstname', 'lastname'],
                    ]
                ]); 
            return $data_provider;
        }
        
        
        /**
         * Generates report listing applicants whose certificates have not  been verified
         * 
         * @return csvfile
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_08_25
         */
        public static function generateUnverifiedApplicationsReport($academicyearid)
        {
            $data_provider = array();
            $records = array();
            
            $data_provider = new ArrayDataProvider([
                    'allModels' => $records,
                    'pagination' => [
                        'pageSize' => 2000,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' =>SORT_ASC, 'firstname' =>SORT_ASC],
                        'attributes' => ['username', 'firstname', 'lastname'],
                    ]
                ]); 
            return $data_provider;
        }
    }
    
