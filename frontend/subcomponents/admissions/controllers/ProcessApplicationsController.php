<?php

    namespace app\subcomponents\admissions\controllers;

    use Yii;
    use yii\helpers\Url;
    use yii\data\ArrayDataProvider;
    
    use common\models\User;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\AcademicOffering;
    use frontend\models\Offer;
    use frontend\models\ApplicationStatus;
    use frontend\models\CapeSubjectGroup;
    use frontend\models\AcademicYear;
    use frontend\models\CapeSubject;
    use frontend\models\EmployeeDepartment;
    
    use frontend\models\applicantregistration\ApplicantRegistration;
    use frontend\models\ApplicantSearchModel;
    use frontend\models\Applicant;
    use frontend\models\Address;
    use frontend\models\Phone;
    use frontend\models\Relation;
    use frontend\models\CompulsoryRelation;
    use frontend\models\MedicalCondition;
    use frontend\models\Institution;
    use frontend\models\PersonInstitution;
    use frontend\models\UnverifiedInstitution;
    use frontend\models\CsecQualification;
    use frontend\models\CsecQualificationModel;
    use frontend\models\Application;
    use frontend\models\ApplicationHistory;
    use frontend\models\ApplicationCapesubject;
    use frontend\models\CapeGroup;
    use frontend\models\CsecCentre;
    use frontend\models\ExaminationBody;
    use frontend\models\Subject;
    use frontend\models\ExaminationProficiencyType;
    use frontend\models\ExaminationGrade;
    use frontend\models\Division;  
    use frontend\models\NursingAdditionalInfo;
    use frontend\models\GeneralWorkExperience;
    use frontend\models\Reference;
    use frontend\models\CriminalRecord;
    use frontend\models\NurseWorkExperience;
    use frontend\models\TeachingExperience;
    use frontend\models\TeachingAdditionalInfo;
    use frontend\models\NursePriorCertification;
    use frontend\models\PostSecondaryQualification;
    use frontend\models\Rejection;
    use frontend\models\RejectionApplications;

    class ProcessApplicationsController extends \yii\web\Controller
    { 
        
        /**
         * Renders the Application Dashboard
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 19/02/2016
         * Date Last Modified: 19/02/2016
         */
        public function actionIndex()
        {
            //Determine user's division_id
            $division_id = EmployeeDepartment::getUserDivision();
            
            $pending_count = count(Applicant::getByStatus(3, $division_id));
            $shortlist_count = count(Applicant::getByStatus(4, $division_id));
            $borderline_count = count(Applicant::getByStatus(7, $division_id));
            $interviewoffer_count = count(Applicant::getByStatus(8, $division_id));
            $offer_count = count(Applicant::getByStatus(9, $division_id));
            $rejected_count = count(Applicant::getByStatus(6, $division_id));
            $conditional_reject_count = count(Applicant::getByStatus(10, $division_id));
        
            
            return $this->render('index', 
                        [
                            'division_id' => $division_id,
                            'pending' => $pending_count,
                            'shortlist' => $shortlist_count,
                            'borderline' => $borderline_count,
                            'interviewoffer' => $interviewoffer_count,
                            'offer' => $offer_count,
                            'rejected' => $rejected_count,
                            'conditionalofferreject' => $conditional_reject_count
                        ]);
        }
    
       
        
        /**
         * Reneders the aplicant list
         * 
         * @param type $division_id
         * @param type $application_status
         * @param type $programme
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 20/02/2016
         * Date Last Modified: 20/02/2016
         */
        public function actionViewByStatus($division_id, $application_status, $programme = 0)
        {
            $applicants = Applicant::getByStatus($application_status, $division_id);
            
            $data = array();
            foreach($applicants as $applicant)
            {
                $app_details = array();
                
                $app_details['username'] = $applicant->getPerson()->one()->username;
                $app_details['firstname'] = $applicant->firstname;
                $app_details['middlename'] = $applicant->middlename;
                $app_details['lastname'] = $applicant->lastname;
                
                
                $applications = Application::find()
                                ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->all();
                $count = count($applications);
                
                $target_application = Application::getTarget($applications, $application_status);
                $programme_record = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->where(['application.applicationid' => $target_application->applicationid])
                            ->one();
               
                
                /* Used to facilitate filtering of result set by 'application_status AND 'programme'
                 * Results are not constrained on inital view load and when a criteria of "None" is selected
                 */
                if ($programme != 0) 
                {
                    $offering = AcademicOffering::find()
                                ->where(['academicofferingid' => $target_application->academicofferingid])
                                ->one();
                    if ($offering->programmecatalogid != $programme)
                        continue;
                }
                
                $app_details['personid'] = $applicant->personid;
                
                $cape_subjects_names = array();
                $cape_subjects = ApplicationCapesubject::find()
                            ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                            ->where(['application.applicationid' => $target_application->applicationid,
                                    'application.isactive' => 1,
                                    'application.isdeleted' => 0]
                                    )
                            ->all();
                
                foreach ($cape_subjects as $cs) 
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                
                $app_details['programme'] = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                
                $app_details['subjects_no'] = CsecQualification::getSubjectsPassedCount($applicant->personid);
                $app_details['ones_no'] = CsecQualification::getSubjectGradesCount($applicant->personid, 1);
                $app_details['twos_no'] = CsecQualification::getSubjectGradesCount($applicant->personid, 2);
                $app_details['threes_no'] = CsecQualification::getSubjectGradesCount($applicant->personid, 3);

                $data[] = $app_details;
            }
            
            $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 25,
                ],
                'sort' => [
                    'defaultOrder' => ['subjects_no' => SORT_DESC, 'ones_no' => SORT_DESC, 'twos_no' => SORT_DESC, 'threes_no' => SORT_DESC],
                    'attributes' => ['subjects_no', 'ones_no', 'twos_no', 'threes_no'],
                    ]
            ]);
            
            //Retrieve programmes for current application period
            $programmes = ProgrammeCatalog::getCurrentProgrammes($division_id);
            
            $progs = array(0 => 'None');
            foreach ($programmes as $prog)
            {
                $progs[$prog->programmecatalogid] = $prog->getFullName();
            }
            
            
            $status_name = ApplicationStatus::find()->where(['applicationstatusid' => $application_status])->one()->name;


            return $this->render('view_applications_by_status',
                [
                    'dataProvider' => $dataProvider,
                    'programmes' => $progs,
                    'status_name' => $status_name,
                    'application_status' => $application_status,
                    'division_id' => $division_id,
                    'status' => $status_name,
                ]);
        }
        
        
        /*
        * Purpose: Updates view of applications by selected criteria
        * Created: 27/07/2015 by Gamal Crichton
        * Last Modified: 27/07/2015 by Gamal Crichton
        */
        /**
         * Updates view of applications by selected criteria (application_status + programme)
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 19/02/2016
         * Date Last Modified: 19/02/2016
         */
        public function actionUpdateView()
        {
            if (Yii::$app->request->post())
            {
                $request = Yii::$app->request;
                $application_status = $request->post('application_status');
                $division_id = $request->post('division_id');
                $programme = $request->post('programme');
            }
            
            return self::actionViewByStatus($division_id, $application_status, $programme);
        }
        
        
        /*
        * Purpose: Prepares Applications and applicants info for displaying 
        * Created: 27/07/2015 by Gamal Crichton
        * Last Modified: 27/07/2015 by Gamal Crichton | Laurence Charles (20/02/2016)
        */
        public function actionViewApplicantCertificates($personid, $programme, $application_status)
        {
            $duplicate_message = false;
            
            $applicant = Applicant::find()
                        ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            
            $username = $applicant->getPerson()->one()->username;

            $applications = Application::find()
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where(['application_period.iscomplete' => 0, /*'application_period.applicationperiodstatusid' => 5,*/  'application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                                'application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $applicant->personid])
                        ->all();
            
            $certificates = CsecQualification::getSubjects($applicant->personid);
            
            $application_container = array();
            
            $target_application = null;
            
            foreach($applications as $application)
            {
                
                $combined = array();
                $keys = array();
                $values = array();
                
                array_push($keys, "application");
                array_push($keys, "istarget");
                array_push($keys, "division");
                array_push($keys, "programme");
                array_push($keys, "status");
                
                array_push($values, $application);
                
                $istarget = Application::isTarget($applications, $application_status, $application);
                if ($istarget == true)
                    $target_application = $application;
                array_push($values, $istarget);
                
                $division = Division::find()
                            ->where(['divisionid' => $application->divisionid])
                            ->one()
                            ->abbreviation;
                array_push($values, $division);
                
                $cape_subjects_names = array();
                $cape_subjects = ApplicationCapesubject::find()
                            ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                            ->where(['application.applicationid' => $application->applicationid,
                                    'application.isactive' => 1,
                                    'application.isdeleted' => 0]
                                    )
                            ->all();
                
                $programme_record = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->where(['application.applicationid' => $application->applicationid])
                            ->one();
                
                foreach ($cape_subjects as $cs) 
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                
                $programme_name = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                array_push($values, $programme_name);
                
                $status = ApplicationStatus::find()
                        ->where(['applicationstatusid' => $application->applicationstatusid])
                        ->one()
                        ->name;
                array_push($values, $status);
                
                $combined = array_combine($keys, $values);
                array_push($application_container, $combined);
            }
           

            /*Get possible duplicates. needs work to deal with multiple years of certificates, 
             * but should catch majority
             */
            if ($certificates)
            {
                $dups = CsecQualification::getPossibleDuplicate($applicant->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                $message = '';
                if ($dups)
                {
                    $dupes = '';
                    foreach($dups as $dup)
                    {
                        $user = User::findOne(['personid' => $dup, 'isdeleted' => 0]);
                        $dupes = $user ? $dupes . ' ' . $user->username : $dupes;
                    }
                    $message = 'Possible Duplicate of applicant(s) ' . $dupes;
                    
                }
                $reapp = CsecQualification::getPossibleReapplicant($applicant->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                if ($reapp)
                {
                    $message = $message . ' Applicant applied to College in academic year prior to 2015/2016.';
                }
                if ($dups || $reapp)
                {
                    //Yii::$app->session->setFlash('warning', $message);
                    $duplicate_message = $message;
                }
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Applicant certificates not yet verified OR Applicant has external Certificates.');
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $certificates,
                'pagination' => [
                    'pageSize' => 50,
                ],
            ]);
            
            
            $offers_made = 0;
            $spaces = 0;
            $cape_info = array();
            $cape = false;
            $ao = $target_application ? AcademicOffering::findOne(['academicofferingid' => $target_application->academicofferingid]) : NULL;
            if ($ao)
            {
                $cape_prog = ProgrammeCatalog::findOne(['name' => 'CAPE']);
                $cape = $cape_prog ? $ao->programmecatalogid == $cape_prog->programmecatalogid : False;

                if ($cape)
                {
                    $cape_subjects = CapeSubject::find()
                            ->innerJoin('application_capesubject', '`application_capesubject`.`capesubjectid` = `cape_subject`.`capesubjectid`')
                            ->where(['application_capesubject.applicationid' => $application->applicationid])
                            ->all();

                    foreach ($cape_subjects as $cape)
                    {
                        $cape_info[$cape->subjectname]['offers_made'] = count(Offer::find()
                            ->joinWith('application')
                            ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                            ->innerJoin('`application_capesubject`', '`application`.`applicationid` = `application_capesubject`.`applicationid`')    
                            ->where(['application_capesubject.capesubjectid' => $cape->capesubjectid, 'application_period.isactive' => 1, 
                                    'offer.isdeleted' => 0])
                            ->all());
                        $cape_info[$cape->subjectname]['capacity'] = $cape->capacity;
                    }
                }
                else
                {
                    $offers_made = count(Offer::find()
                            ->innerJoin('application', '`application`.`applicationid` = `offer`.`applicationid`')
                            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->where(['offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.offertypeid' => 1,
                                    'application.isactive' => 1, 'application.isdeleted' => 0,
                                    'academic_offering.academicofferingid' => $ao->academicofferingid])
                            ->all());
                    
                    $conditional_offers_made = count(Offer::find()
                            ->innerJoin('application', '`application`.`applicationid` = `offer`.`applicationid`')
                            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->where(['offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.offertypeid' => 2,
                                    'application.isactive' => 1, 'application.isdeleted' => 0,
                                    'academic_offering.academicofferingid' => $ao->academicofferingid])
                            ->all());

                    $spaces = $ao->spaces;
                }
            }
            
            
            return $this->render('view_applicant_certificates',
                    [
                        'duplicate_message' => $duplicate_message,
                        'username' => $username,
                        'applicant' => $applicant,
                        'applications' => $applications,
                        'application_container' => $application_container,
                        'dataProvider' => $dataProvider,
                        'application_status' => $application_status,
                        'applicationid' => $target_application->applicationid,
                        'target_application' => $target_application,
                        'programme' => $programme,
                        'conditional_offers_made' => $conditional_offers_made,
                        'offers_made' => $offers_made,
                        'spaces' => $spaces,
                        'cape' => $cape,
                        'cape_info' => $cape_info,
                    ]);
        }
        
        
        /**
         * Updates an applicants appropriately
         * 
         * @param type $applicationid
         * @param type $new_status
         * @param type $old_status
         * @param type $divisionid
         * 
         * Author: Laurence Charles
         * Date Created: 19/02/2016
         * Date Last Modified: 19/02/2016
         */
        public function actionUpdateApplicationStatus($applicationid, $new_status, $old_status, $divisionid)
        {
            $update_candidate = Application::find()
                            ->where(['applicationid' => $applicationid])
                            ->one();
            
            $applications = Application::find()
                            ->where(['personid' => $update_candidate->personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->all();
            $count = count($applications);
            
            $position = Application::getPosition($applications, $update_candidate);
            
            $update_candidate_save_flag = false;
            $applications_save_flag = false;
            $offer_save_flag = false;
            $rejection_save_flag = false;
            $miscellaneous_save_flag = false;
            
            $transaction = \Yii::$app->db->beginTransaction();
            try 
            { 
                /*
                 * If user is a member of "DTE" of "DNE", many condiseration can be negated such as application spanning multiple divsions
                 */
                if (EmployeeDepartment::getUserDivision() == 6  || EmployeeDepartment::getUserDivision() == 7  || EmployeeDepartment::getUserDivision() == 1)
                {
                    /*
                     * If an application is pending all subsequent applications
                     * are set to pending
                     */
                    if($new_status == 3)
                    {
                        if($count - $position > 1)
                        {
                            for ($i = $position+1 ; $i < $count ; $i++)
                            {
                                $applications[$i]->applicationstatusid = 3;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when savingapplication');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                                }
                            }
                        }

                        /*
                        * If previous status was "pre or post interview rejection"
                        * then that rejection is rescinded
                        */
                        if($old_status == 6)
                        {
                            $result = Rejection::rescindRejection($update_candidate->personid);
                            if ($result == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                        }

                        /*
                         * If previous status was"conditional offer",
                         * then that offer is revoked
                         */
                        elseif($old_status == 8)
                        {
                            $result = Offer::rescindOffer($update_candidate->applicationid, 2);
                            if ($result == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                        }
                        /*
                         * If previous status was "offer",
                         * then that offer is revoked
                         */
                        elseif($old_status == 9)
                        {
                            $result = Offer::rescindOffer($update_candidate->applicationid, 1);
                            if ($result == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                            else
                            {
                                $applicant = Applicant::find()
                                            ->where(['personid' => $update_candidate->personid])
                                            ->one();
                                $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                $applicant->potentialstudentid = $generated_id;
                                $applicant->save();
                            }
                        }
                    }


                    /*
                     * If an application is shortlist, borderlined all preceeding applications
                     * to reject and subsequent applications are set to pending
                     */
                    elseif($new_status == 4  || $new_status == 7)
                    {
                        //updates subsequent applications
                        if($count - $position > 1)
                        {
                            for ($i = $position+1 ; $i < $count ; $i++)
                            {
                                $applications[$i]->applicationstatusid = 3;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when savingapplication');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                                }
                            }
                        }

                        //updates preceeding applications
                        if($position > 0)
                        {
                            for ($i = $position-1 ; $i >= 0 ; $i--)
                            {
                                $applications[$i]->applicationstatusid = 6;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when savingapplication');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                                }
                            }
                        }

                        /*
                        * If previous status was "pre or post interview rejection"
                        * then that rejection is rescinded
                        */
                        if($old_status == 6)
                        {
                            $result = Rejection::rescindRejection($update_candidate->personid);
                            if ($result == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                        }

                        /*
                         * If previous status was"conditional offer",
                         * then that offer is revoked
                         */
                        elseif($old_status == 8)
                        {
                            $result = Offer::rescindOffer($update_candidate->applicationid, 2);
                            if ($result == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                        }
                        /*
                         * If previous status was "offer",
                         * then that offer is revoked
                         */
                        elseif($old_status == 9)
                        {
                            $result = Offer::rescindOffer($update_candidate->applicationid, 1);
                            if ($result == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                            else
                            {
                                $applicant = Applicant::find()
                                            ->where(['personid' => $update_candidate->personid])
                                            ->one();
                                $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                $applicant->potentialstudentid = $generated_id;
                                $applicant->save();
                            }
                        }
                    }
                    

                    /*
                     * If an application is interviewoffer;
                     * -> all preceeding  applications are set to reject
                     * -> all subsequent applications are set to reject
                     * -> new conditional offer is created
                     */
                    elseif($new_status == 8)
                    {
                        //updates subsequent applications
                        if($count - $position > 1)
                        {
                            for ($i = $position+1 ; $i < $count ; $i++)
                            {
                               $applications[$i]->applicationstatusid = 6;
                               $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when savingapplication');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                                }
                            }
                        }

                        //updates preceeding applications
                        if($position > 0)
                        {
                            for ($i = $position-1 ; $i >= 0 ; $i--)
                            {
                                $applications[$i]->applicationstatusid = 6;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                                }
                            }
                        }
                        
                        /**
                         * this should prevent the creation of multiple offers,
                         * which is suspected to occur when internet timeout 
                         * during request submission
                         */
                        $existing_current_offer = Offer::find()
                                        ->where(['applicationid' => $applicationid, 'offertypeid' => 2, 'isactive' => 1, 'isdeleted' => 0])
                                        ->all();
                        if($existing_current_offer == false)
                        {
                            $offer = new Offer();
                            $offer->applicationid = $applicationid;
                            $offer->offertypeid = 2;
                            $offer->issuedby = Yii::$app->user->getID();
                            $offer->issuedate = date('Y-m-d');
                            $offer_save_flag = $offer->save();
                            if($offer_save_flag == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                        }

                        /*
                        * If previous status was "pre-interview rejection"
                        * then that rejection is rescinded
                        */
                        if($old_status == 6)
                        {
                            $result = Rejection::rescindRejection($update_candidate->personid);
                            if ($result == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                        }
                    }

                    /*
                     * If an application is given pre-interview rejection,
                     * -> all precceding applications are rejected
                     * -> all subsequent applications are set to pending
                     */
                    elseif($new_status == 6)
                    {
                        //updates preceeding applications
                        if($position > 0)
                        {
                            for ($i = $position-1 ; $i >= 0 ; $i--)
                            {
                                $applications[$i]->applicationstatusid = 6;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                                }
                            }
                        }

                        //if  not last application -> updates subsequent applications
                        if($count - $position > 1)
                        {
                            for ($i = $position+1 ; $i < $count ; $i++)
                            {
                                $applications[$i]->applicationstatusid = 3;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                                }
                            }
                        }
                        /*
                         * If current application being updated is the last application,
                         * then a rejection must be issued
                         */
                        else
                        {
                            //create Rejection record
                            $rejection = new Rejection();
                            $rejection->personid = $update_candidate->personid;
                            $rejection->rejectiontypeid = 1;
                            $rejection->issuedby = Yii::$app->user->getID();
                            $rejection->issuedate = date('Y-m-d');
                            $rejection_save_flag = $rejection->save();
                            if($rejection_save_flag == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when creating rejection');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }

                            //crete associate RejectionApplications records
                            foreach($applications as $application)
                            {
                                $temp = new RejectionApplications();
                                $temp->rejectionid = $rejection->rejectionid;
                                $temp->applicationid = $application->applicationid;
                                $miscellaneous_save_flag = $temp->save();
                                if ($miscellaneous_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when saving record');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                                }
                            }
                        }

                        /*
                         * If previous status was"conditional offer",
                         * then that offer is revoked
                         */
                        if($old_status == 8)
                        {
                            $result = Offer::rescindOffer($update_candidate->applicationid, 2);
                            if ($result == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                        }
                        /*
                         * If previous status was "offer",
                         * then that offer is revoked
                         */
                        elseif($old_status == 9)
                        {
                            $result = Offer::rescindOffer($update_candidate->applicationid, 1);
                            if ($result == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                            else
                            {
                                $applicant = Applicant::find()
                                            ->where(['personid' => $update_candidate->personid])
                                            ->one();
                                $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                $applicant->potentialstudentid = $generated_id;
                                $applicant->save();
                            }
                        }
                    }

                    /*
                     * If an application is given an 'offer';
                     * -> nothing is done to preceeding applications
                     * -> nothing is done to subsequent applications
                     */
                    elseif($new_status == 9  && (Yii::$app->user->can('Dean') || Yii::$app->user->can('Deputy Dean')))
                    {
                        if($old_status == 8)
                        {
                            $old_offer = Offer::find()
                                       ->where(['applicationid' => $update_candidate->applicationid, 'offertypeid' => 2, 'isactive' => 1, 'isdeleted' => 0])
                                       ->one();
                            if($old_offer == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Applicant corresponding conditional offer was not found');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                            else
                            {
                                /**
                                * this should prevent the creation of multiple offers,
                                * which is suspected to occur when internet timeout 
                                * during request submission
                                */
                               $existing_current_offer = Offer::find()
                                               ->where(['applicationid' => $applicationid, 'offertypeid' => 1, 'isactive' => 1, 'isdeleted' => 0])
                                               ->all();
                               
                                /*
                                * If conditional exists;
                                * it must be published before applicant can be given a full offer
                                */
                                if($old_offer->ispublished == 1  && $existing_current_offer == false)
                                {
                                    // create offer
                                    $offer = new Offer();
                                    $offer->applicationid = $applicationid;
                                    $offer->offertypeid = 1;
                                    $offer->issuedby = Yii::$app->user->getId();
                                    $offer->issuedate = date("Y-m-d");
                                    $offer_save_flag = $offer->save();
                                    if($offer_save_flag == false)
                                    {
                                        $transaction->rollBack();
                                        Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                                        return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                                    }
                                    // Generate potentialstudentid
                                    else
                                    {
                                        $applicant = Applicant::find()
                                                    ->where(['personid' => $update_candidate->personid])
                                                    ->one();
                                        $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                        $applicant->potentialstudentid = $generated_id;
                                        $applicant->save();
                                    }
                                }
                                else
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Applicant conditional offer must be published before full offer can be made');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                                }
                            }
                        }
                        /*
                        * If previous status was "post interview rejection",
                        * -> that rejection is rescinded
                        * -> new offer is created
                        */
                        elseif($old_status == 10)
                        {
                            $result = Rejection::rescindRejection($update_candidate->personid);
                            if ($result == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                            else
                            {
                                // create offer
                                $offer = new Offer();
                                $offer->applicationid = $applicationid;
                                $offer->offertypeid = 1;
                                $offer->issuedby = Yii::$app->user->getId();
                                $offer->issuedate = date("Y-m-d");
                                $offer_save_flag = $offer->save();
                                if($offer_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                                }
                            }
                       }
                    }


                    /*
                     * If an application is interview-rejected;
                     * -> all precceding applications are rejected
                     * ->all subsequent applications are set to rejected
                     */
                    elseif($new_status == 10  && (Yii::$app->user->can('Dean') || Yii::$app->user->can('Deputy Dean')))
                    {
                        //updates subsequent applications
                        if($count - $position > 1)
                        {
                            for ($i = $position+1 ; $i < $count ; $i++)
                            {
                                $applications[$i]->applicationstatusid = 6;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                                }
                            }
                        }

                        //updates preceeding applications
                        if($position > 0)
                        {
                            for ($i = $position-1 ; $i >= 0 ; $i--)
                            {
                                $applications[$i]->applicationstatusid = 6;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                                }
                            }
                        }

                        /*
                         * If previous status was"conditional offer",
                         * then that offer is revoked
                         */
                        if($old_status == 8)
                        {
                            $result = Offer::rescindOffer($update_candidate->applicationid, 2);
                            if ($result == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                        }
                        /*
                         * If previous status was "offer",
                         * then that offer is revoked
                         */
                        elseif($old_status == 9)
                        {
                            $result = Offer::rescindOffer($update_candidate->applicationid, 1);
                            if ($result == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                            else
                            {
                                $applicant = Applicant::find()
                                            ->where(['personid' => $update_candidate->personid])
                                            ->one();
                                $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                $applicant->potentialstudentid = $generated_id;
                                $applicant->save();
                            }
                        }

                        //create Rejection record
                        $rejection = new Rejection();
                        $rejection->personid = $update_candidate->personid;
                        $rejection->rejectiontypeid = 2;
                        $rejection->issuedby = Yii::$app->user->getID();
                        $rejection->issuedate = date('Y-m-d');
                        $rejection_save_flag = $rejection->save();
                        if ($rejection_save_flag == false)
                        {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when creating rejection');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                        }

                        //create associate RejectionApplications records
                        foreach($applications as $application)
                        {
                            $temp = new RejectionApplications();
                            $temp->rejectionid = $rejection->rejectionid;
                            $temp->applicationid = $application->applicationid;
                            $miscellaneous_save_flag = $temp->save();
                            if ($miscellaneous_save_flag == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when saving record');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                            }
                        }
                    }
                    
                    $update_candidate->applicationstatusid = $new_status;
                    $update_candidate_save_flag = $update_candidate->save();
                    if($update_candidate_save_flag == false)
                    {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Error occured when saving target application');
                        return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
                    }
                    else
                        $transaction->commit();
                    
                }
                /*
                 * If user is a member of "DTE" of "DNE" many additional considerations have to be  accounted for such as application spanning multiple divisions
                 */
                elseif (EmployeeDepartment::getUserDivision() == 4  || EmployeeDepartment::getUserDivision() == 5)
                {

                }
                
            }catch (Exception $e) 
            {
                $transaction->rollBack();
            }

            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status);
        }
        
        
        /**
         * Prepares data that is to be displayed on the "View Applicant Details" view
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date created: 23/02/2016
         * Date Last Modified: 23/02/2016
         */
        public function actionViewApplicantDetails($personid, $programme, $application_status)
        {  
            $id = $personid;
            $applicant= Applicant::findByPersonID($id); 

            $permanentaddress = Address::getAddress($id, 1);            
            $residentaladdress = Address::getAddress($id, 2);
            $postaladdress = Address::getAddress($id, 3);
            $addresses = [$permanentaddress, $residentaladdress, $postaladdress];

            $phone = Phone::findPhone($id);

            //Relations
            $beneficiary = false;
            $spouse = false;
            $mother = false;
            $father = false;
            $nextofkin = false;
            $emergencycontact = false;
            $guardian = false;

            $beneficiary = CompulsoryRelation::getRelationRecord($id, 6);
            $emergencycontact = CompulsoryRelation::getRelationRecord($id, 4);

            $spouse = Relation::getRelationRecord($id, 7);
            $mother = Relation::getRelationRecord($id, 1);
            $father = Relation::getRelationRecord($id, 2);
            $nextofkin = Relation::getRelationRecord($id, 3);
            $guardian = Relation::getRelationRecord($id, 5);

            $medicalConditions = MedicalCondition::getMedicalConditions($id);

            $applicantDetails = $applicant->variableDetails();

            $applications = Application::getApplications($id);
            $first = array();
            $firstDetails = array();
            $second = array();
            $secondDetails = array();
            $third = array();
            $thirdDetails = array();

            $db = Yii::$app->db;
            foreach($applications as $application)
            {
                $capeSubjects = NULL;
                $isCape = NULL;
                $division = NULL;
                $programme = NULL;
                $d = NULL;
                $p = NULL;
                if ($application->ordering == 1)
                {
                    array_push($first, $application);
                    $isCape = Application::isCapeApplication($application->academicofferingid);
                    if ($isCape == true)
                    {
                      $capeSubjects = ApplicationCapesubject::getRecords($application->applicationid);
                      array_push($first, $capeSubjects);
                    }
                    $d = Division::find()
                            ->where(['divisionid' => $application->divisionid])
                            ->one();
                    $division = $d->name;
                    array_push($firstDetails, $division);

                    $p = $db->createCommand(
                        "SELECT academic_offering.academicofferingid, programme_catalog.name, programme_catalog.specialisation, qualification_type.abbreviation"
                        . " FROM  academic_offering "
                        . " JOIN programme_catalog"
                        . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
                        . " JOIN qualification_type"
                        . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                        . " WHERE academic_offering.academicofferingid = " . $application->academicofferingid . " ;"
                        )
                        ->queryAll();

                    $specialization = $p[0]["specialisation"];
                    $qualification = $p[0]["abbreviation"];
                    $programme = $p[0]["name"];
                    $fullname = $qualification . " " . $programme . " " . $specialization;
                    array_push($firstDetails, $fullname);
                }

                else if ($application->ordering == 2)
                {
                    array_push($second, $application);
                    $isCape = Application::isCapeApplication($application->academicofferingid);
                    if ($isCape == true)
                    {
                        $capeSubjects = ApplicationCapesubject::getRecords($application->applicationid);
                        array_push($second, $capeSubjects);
                    }
                    $d = Division::find()
                        ->where(['divisionid' => $application->divisionid])
                        ->one();
                    $division = $d->name;
                    array_push($secondDetails, $division);

                    $p = $db->createCommand(
                        "SELECT academic_offering.academicofferingid, programme_catalog.name, programme_catalog.specialisation, qualification_type.abbreviation"
                        . " FROM  academic_offering "
                        . " JOIN programme_catalog"
                        . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
                        . " JOIN qualification_type"
                        . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                        . " WHERE academic_offering.academicofferingid = " . $application->academicofferingid . " ;"
                        )
                        ->queryAll();

                    $specialization = $p[0]["specialisation"];
                    $qualification = $p[0]["abbreviation"];
                    $programme = $p[0]["name"];
                    $fullname = $qualification . " " . $programme . " " . $specialization;
                    array_push($secondDetails, $fullname);
                }
                else if ($application->ordering == 3)
                {
                    array_push($third, $application);
                    $isCape = Application::isCapeApplication($application->academicofferingid);
                    if ($isCape == true)
                    {
                        $capeSubjects = ApplicationCapesubject::getRecords($application->applicationid);
                        array_push($third, $capeSubjects);
                    }
                    $d = Division::find()
                        ->where(['divisionid' => $application->divisionid])
                        ->one();
                    $division = $d->name;
                    array_push($thirdDetails, $division);

                    $p = $db->createCommand(
                        "SELECT academic_offering.academicofferingid, programme_catalog.name, programme_catalog.specialisation, qualification_type.abbreviation"
                        . " FROM  academic_offering "
                        . " JOIN programme_catalog"
                        . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
                        . " JOIN qualification_type"
                        . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                        . " WHERE academic_offering.academicofferingid = " . $application->academicofferingid . " ;"
                        )
                        ->queryAll();

                    $specialization = $p[0]["specialisation"];
                    $qualification = $p[0]["abbreviation"];
                    $programme = $p[0]["name"];
                    $fullname = $qualification . " " . $programme . " " . $specialization;
                    array_push($thirdDetails, $fullname);
                }
            }

            $preschools = PersonInstitution::getPersonInsitutionRecords($id, 1);
            $preschoolNames = array();
            if ($preschools!=false)
            {
                foreach ($preschools as $preschool)
                {
                    $name = NULL;
                    $record = NULL;
                    $record = Institution::find()
                            ->where(['institutionid' => $preschool->institutionid])
                            ->one();     
                    $name = $record->name;
                    array_push($preschoolNames, $name);          
                }
            }

            $primaryschools = PersonInstitution::getPersonInsitutionRecords($id, 2);
            $primaryschoolNames = array();
            if ($primaryschools!=false)
            {
                foreach ($primaryschools as $primaryschool)
                {
                    $name = NULL;
                    $record = NULL;
                    $record = Institution::find()
                            ->where(['institutionid' => $primaryschool->institutionid])
                            ->one();     
                    $name = $record->name;
                    array_push($primaryschoolNames, $name); 
                }
            }

            $secondaryschools = PersonInstitution::getPersonInsitutionRecords($id, 3);
            $secondaryschoolNames = array();
            if ($secondaryschools!=false)
            {
                foreach ($secondaryschools as $secondaryschool)
                {
                    $name = NULL;
                    $record = NULL;
                    $record = Institution::find()
                            ->where(['institutionid' => $secondaryschool->institutionid])
                            ->one();       
                    $name = $record->name;
                    array_push($secondaryschoolNames, $name); 
                }
            }

            $tertieryschools = PersonInstitution::getPersonInsitutionRecords($id, 4);
            $tertieryschoolNames = array();
            if ($tertieryschools!=false)
            {
                foreach ($tertieryschools as $tertieryschool)
                {
                    $name = NULL;
                    $record = NULL;
                    $record = Institution::find()
                            ->where(['institutionid' => $tertieryschool->institutionid])
                            ->one();  
                    $name = $record->name;
                    array_push($tertieryschoolNames, $name); 
                }
            }

            $qualifications = CsecQualification::getQualifications($id);
            $qualificationDetails = array();

            if ($qualifications != false)
            {
                $keys = ['centrename', 'examinationbody', 'subject', 'proficiency', 'grade'];
                foreach ($qualifications as $qualification)
                {
                    $values = array();
                    $combined = array();
                    $centre = CsecCentre::find()
                            ->where(['cseccentreid' => $qualification->cseccentreid])
                            ->one();
                    array_push($values, $centre->name);
                    $examinationbody = ExaminationBody::find()
                            ->where(['examinationbodyid' => $qualification->examinationbodyid])
                            ->one();
                    array_push($values, $examinationbody->abbreviation);
                    $subject = Subject::find()
                            ->where(['subjectid' => $qualification->subjectid])
                            ->one();
                    array_push($values, $subject->name);
                    $proficiency = ExaminationProficiencyType::find()
                            ->where(['examinationproficiencytypeid' => $qualification->examinationproficiencytypeid])
                            ->one();
                    array_push($values, $proficiency->name);
                    $grade = ExaminationGrade::find()
                            ->where(['examinationgradeid' => $qualification->examinationgradeid])
                            ->one();
                    array_push($values, $grade->name);
                    $combined = array_combine($keys,$values);
                    array_push($qualificationDetails, $combined);
                    $values = NULL;
                    $combined = NULL;
                }
            }

            $certificates = NursePriorCertification::getCertifications($id);
            $nursinginfo = NursingAdditionalInfo::getNursingInfo($id);
            $teaching_info = TeachingAdditionalInfo::getTeachingInfo($id); 
            $generalExperiences = GeneralWorkExperience::getGeneralWorkExperiences($id);
            $references = Reference::getReferences($id);
            $criminalrecord = CriminalRecord::getCriminalRecord($id);
            $nurseExperience = NurseWorkExperience::getNurseWorkExperience($id);
            $teachingExperiences = TeachingExperience::getTeachingExperiences($id);
            
            $qualification = PostSecondaryQualification::find()
                        ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();

            return $this->render('view_applicant_details', [
                'applicant' => $applicant,
                'addresses' => $addresses,
                'phone'=> $phone,
                'beneficiary' => $beneficiary,
                'mother' => $mother,
                'father' => $father,
                'nextofkin' => $nextofkin,
                'emergencycontact' => $emergencycontact,
                'guardian' =>  $guardian,                   
                'spouse' => $spouse,
                'medicalConditions' => $medicalConditions,
                'applicantDetails' => $applicantDetails,
                'qualifications' => $qualifications,
                'qualificationDetails' => $qualificationDetails,
                'first' => $first,
                'firstDetails' =>$firstDetails,
                'second' => $second,
                'secondDetails' =>$secondDetails,
                'third' => $third,
                'thirdDetails' =>$thirdDetails,
                'preschools' => $preschools,
                'preschoolNames' => $preschoolNames,
                'primaryschools' => $primaryschools,
                'primaryschoolNames' => $primaryschoolNames,
                'secondaryschools' => $secondaryschools,
                'secondaryschoolNames' => $secondaryschoolNames,
                'tertieryschools' => $tertieryschools,
                'tertieryschoolNames' => $tertieryschoolNames,
                'teaching_info' => $teaching_info,
                'nursinginfo' => $nursinginfo,
                'generalExperiences' => $generalExperiences,
                'references' => $references,
                'criminalrecord' =>$criminalrecord,
                'nurseExperience' => $nurseExperience,
                'teachingExperiences' => $teachingExperiences,
                'certificates' => $certificates,
                'qualification' => $qualification,
                
                'programme' => $programme,
                'application_status' => $application_status,
            ]);
        }
        
        
        
        public function actionGenerateConditionalOfferList()
        {
            
        }
        
        

        
    }

