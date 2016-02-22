<?php

    namespace app\subcomponents\admissions\controllers;

    use Yii;
    use yii\helpers\Url;
    use yii\data\ArrayDataProvider;
    use frontend\models\Application;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\AcademicOffering;
    use frontend\models\Applicant;
    use frontend\models\CsecQualification;
    use frontend\models\Offer;
    use frontend\models\ApplicationStatus;
    use frontend\models\ApplicationCapesubject;
    use frontend\models\CapeSubjectGroup;
    use frontend\models\CapeGroup;
    use frontend\models\AcademicYear;
    use frontend\models\ExaminationBody;
    use frontend\models\Subject;
    use frontend\models\ExaminationGrade;
    use common\models\User;
    use frontend\models\CapeSubject;
    use frontend\models\Division;

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
            $division_id = Yii::$app->session->get('divisionid');
            
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
                
                $app_details['applicantid'] = $applicant->getPerson()->one()->username;
                $app_details['firstname'] = $applicant->firstname;
                $app_details['middlename'] = $applicant->middlename;
                $app_details['lastname'] = $applicant->lastname;
                
                
                $applications = Application::find()
                                ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->all();
                $count = count($applications);
                
                if ($application_status == 6)       //if reject
                {
                    $target_application = $applications[($count-1)];
                    $programme = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->where(['application.applicationid' => $target_application->applicationid])
                            ->one();
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
                    $programme = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->where(['application.applicationid' => $target_application->applicationid])
                            ->one();
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
                    $programme = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->where(['application.applicationid' => $target_application->applicationid])
                            ->one();
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
                    $programme = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->where(['application.applicationid' => $target_application->applicationid])
                            ->one();
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
                    $programme = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->where(['application.applicationid' => $target_application->applicationid])
                            ->one();
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
                    $programme = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->where(['application.applicationid' => $target_application->applicationid])
                            ->one();
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
                    $programme = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->where(['application.applicationid' => $target_application->applicationid])
                            ->one();
                }
                
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
                
                $app_details['applicantid'] = $target_application->applicationid;
                
                $cape_subjects_names = array();
                $cape_subjects = ApplicationCapesubject::find()
                            ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                            ->where(['application.applicationid' => $target_application->applicationid,
                                    'application.isactive' => 1,
                                    'application.isdeleted' => 0]
                                    );
                foreach ($cape_subjects as $cs) 
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $app_details['programme'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
               
                $app_details['subjects_no'] = $applicant->getSubjectsPassedCount($application->personid);
                $app_details['ones_no'] = $applicant->getSubjectGradesCount($application->personid, 1);
                $app_details['twos_no'] = $applicant->getSubjectGradesCount($application->personid, 2);
                $app_details['threes_no'] = $applicant->getSubjectGradesCount($application->personid, 3);

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
            foreach ($programmes as $programme)
            {
                $progs[$programme->programmecatalogid] = $programme->getFullName();
            }
            
            $status_name = ApplicationStatus::find()->where(['applicationstatusid' => $application_status])->one()->name;


            return $this->render('view_applications_by_status',
                [
                    'results' => $dataProvider,
                    'programmes' => $progs,
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
        public function actionViewApplicantCertificates($applicantid, $programme, $application_status, $applicationid)
        {
            $applicant = Applicant::find()
                        ->where(['applicantid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();

            $applications = Application::find()
                        ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where(['application_period.isactive' => 1, 'application_period.isdeleted' => 0])
                        ->all();
            
            $certificates = CsecQualification::getSubjects($applicant->personid);
            $subjects_passed = CsecQualification::getSubjectsPassedCount($applicant->personid);
            $has_english = CsecQualification::hasEnglish($certificates);
            $has_math = CsecQualification::hasMath($certificates);
            
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
                            ->where(['application.applicationid' => $target_application->applicationid,
                                    'application.isactive' => 1,
                                    'application.isdeleted' => 0]
                                    );
                foreach ($cape_subjects as $cs) 
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $programme_name = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
                array_push($values, $programme_name);
                
                $status = ApplicationStatus::find()
                        ->where(['applicationstatusid' => $application->applicationstatusid])
                        ->one()
                        ->name;
                array_push($values, $status);
                
                $combined = array_combine($keys, $values);
                array_push($application_container, $combined);
            }
            
            // Prepares error message if appropriate
            $error_mess = 'Applicant: ';

            if (!$has_math)
            {
                $error_mess = $error_mess . 'Did not pass CSEC Math!  ';
            }
            if (!$has_english)
            {
                $error_mess = $error_mess . 'Did not pass CSEC English Language!  ';
            } 
            if ( $subjects_passed < 5)
            {
                $error_mess = $error_mess . 'Passed less than 5 CSEC Subjects!  ';
            }
            if ( count($offers) == 1)
            {
                $error_mess = $error_mess . 'Has an offer.  ';
            }
            if ( count($offers) > 1)
            {
                $error_mess = $error_mess . 'Has multiple offers!  ';
            }

            if (!$has_english || !$has_math || $subjects_passed < 5 || $offers)
            {
                Yii::$app->session->setFlash('error', $error_mess);
            }
            
            /*Get possible duplicates. needs work to deal with multiple years of certificates, 
             * but should catch majority
             */
            if ($certificates)
            {
                $dups = self::getPossibleDuplicate($personid, $certificates[0]->candidatenumber, $certificates[0]->year);
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
                $reapp = self::getPossibleReapplicant($personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                if ($reapp)
                {
                    $message = $message . ' Applicant applied to College in a previous year.';
                }
                if ($dups || $reapp)
                {
                    Yii::$app->session->setFlash('warning', $message);
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
            $ao = $target_application ? AcademicOffering::findOne(['academicofferingid' => $application->academicofferingid]) : NULL;
            if ($ao)
            {
                $cape_prog = ProgrammeCatalog::findOne(['name' => 'cape']);
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
                            ->where(['academic_offering.academicofferingid' => $ao->academicofferingid])
                            ->all());

                    $spaces = $ao->spaces;
                }
            }
            
            
            return $this->render('view_applicant_certificates',
                    [
                        'applicant' => $applicant,
                        'applications' => $applications,
                        'application_container' => $application_container,
                        'dataProvider' => $dataProvider,
                        'application_status' => $application_status,
                        'applicationid' => $target_applicationid,
                        'programme' => $programme,
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
                            ->where(['personid' => $update_candidate->personid])
                            ->all();
            $count = count($applications);
            
            $pos = Application::getPosition($applications, $update_candidate);
            
            // if an application is rejected, all subsequent applications are set to pending
            if($new_status == 6)
            {
                for ($i = $pos ; $i < $count ; $i++)
                {
                   $applications[$i]->applicationstatusid = 3;
                   $applications[$i]->save();
                }
            }
            
            
                
                
                
            
            
            $update_candidate->applicationstatusid = $new_status;
            $update_candidate->save();
            
            return self::actionViewByStatus($division_id, $old_status);
        }

        
    }

