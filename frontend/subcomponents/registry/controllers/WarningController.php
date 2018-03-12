<?php

    namespace app\subcomponents\registry\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\base\Model;
    use yii\data\ArrayDataProvider;

    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\StudentRegistration;
    use frontend\models\Student;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\Application;
    use frontend\models\StudentStatus;
    use frontend\models\AcademicStatus;
    use frontend\models\EmployeeDepartment;
    use frontend\models\ApplicationCapesubject;
     use frontend\models\Offer;
    use frontend\models\Email;
    use frontend\models\Employee;
    use frontend\models\AcademicOffering;
    use frontend\models\BatchStudent;
    use frontend\models\BatchStudentCape;
    use frontend\models\AcademicYear;
      

    class WarningController extends Controller 
    {
        /**
         * Renders academic warning candidates search view
         * 
         * @return type
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2018_03_09
         * Modified: 2018_03_09
         */
         public function actionIndex()
         {
            $periods = ApplicationPeriod::prepareWarningReportPeriods();
             return $this->render('select_candidate_criteria', [ 'periods' => $periods]);
         }
        
        
        /**
         * Generate academic warning candidates
         * 
         * @return type
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2018_03_09
         * Modified: 2018_03_09 | 2018_03_12
         */
        public function actionGenerateWarningCandidates() 
        {
            $dataProvider = array();
            $data = array();
            $filename = "";
            $title = "";
            
            $academic_warning = 0;
            $academic_probation = 0;
            $poor_performers = 0;
            
            $periods = ApplicationPeriod::prepareWarningReportPeriods();
            
             if (Yii::$app->request->post())
            {
                //Everytime a new search is initiated session variable must be removed
                 if (Yii::$app->session->get('period-id'))
                    Yii::$app->session->remove('period-id');

                $request = Yii::$app->request;
                $application_periodid = $request->post('period-id');

                 if(Yii::$app->session->get('period-id') == null  && $application_periodid == true)
                    Yii::$app->session->set('period-id', $application_periodid);
            }
            else    
            {
                $application_periodid = Yii::$app->session->get('period-id');
            }
            
            if ($application_periodid == false)
            {
                Yii::$app->getSession()->setFlash('error', 'No application period was selected.');
                return $this->redirect(\Yii::$app->request->getReferrer());
            }
            else
            {
                $registrations = StudentRegistration::find()
                        ->innerJoin('offer', '`student_registration`.`offerid` = `offer`.`offerid`')
                        ->innerJoin('application', '`offer`.`applicationid` = `application`.`applicationid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where(['student_registration.isactive' => 1, 'student_registration.isdeleted' => 0,
                                        'offer.isactive' => 1, 'offer.isdeleted' => 0,
                                        'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                        'application_period.applicationperiodid' => $application_periodid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                                    ])
                        ->all();

                foreach ($registrations as $registration)
                {
                    $info = array();
                    $offer = Offer::find()
                             ->where(['offerid' => $registration->offerid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($offer == false)
                        continue;

                    $target_application = Application::find()
                            ->where(['applicationid' => $offer->applicationid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($target_application == false) 
                        continue;
                    
                    /******************  Checks if applicant is eligible for contract ********************/
                    $is_cape = AcademicOffering::isCape($target_application->academicofferingid);
                    $fails = 0;
                    if ($is_cape == true)       // CAPE students are excluded
                    {
                        continue;
                    }
                    else
                    {
                        $courses = BatchStudent::find()
                                ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                                ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                                ->innerJoin('academic_offering', '`course_offering`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                                ->innerJoin('semester', '`course_offering`.`semesterid` = `semester`.`semesterid`')
                                ->where(['batch_students.studentregistrationid' => $registration->studentregistrationid, 'batch_students.isactive' => 1, 'batch_students.isdeleted'=> 0,
                                                'batch.batchtypeid' =>[1], 'batch.isactive' => 1, 'batch.isdeleted'=> 0,
                                                'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0,
                                                 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                                'application_period.applicationperiodid' => $application_periodid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                                                'semester.title' => 1, 'semester.isactive' => 1, 'semester.isdeleted' => 0,
                                    ])       
                               ->andWhere(['<>','course_offering.credits', 0])
                                ->all();
                    }
                    
                    if ($courses == false)
                        continue;
                    
                    foreach ($courses as $course)
                    {
                        if ($course->was_failed() == true)
                        {
                            $fails++;
                        }
                    }
                    
                    if ($fails < 2)
                        continue;

                    $total_courses = count($courses);
                    $percentage_failed = round( (($fails/$total_courses)*100) , 1);
                   
//                    if ($percentage_failed < 33)
//                        continue;
                    
                    /*************************************************************************/
                    $poor_performers ++;
                    
                    if ($fails == 2)
                    {
                       $academic_warning++;
                        $info['proposed_status'] = "Warning";
                    }
                    elseif ($fails > 2)
                    {
                      $academic_probation++;
                       $info['proposed_status'] = "Probation";
                    }
                    else
                    {
                       $info['proposed_status'] = "Other";
                    }
                          
                   
                    $info['student_registrationid'] = $registration->studentregistrationid;
                    $info['offerid'] = $registration->offerid;
                    $info['personid'] = $registration->personid;

                    $user = User::find()
                            ->where(['personid' => $registration->personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($user == false)
                        continue;
                    $info['username'] = $user->username;

                    $student = Student::find()
                            ->where(['personid' => $registration->personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($student == false)
                        continue;

                    $info['title'] = $student->title;
                    $info['first_name'] = $student->firstname;
                    $info['middle_name'] = $student->middlename;
                    $info['last_name'] = $student->lastname;
                    $info['fails'] = $fails;
                    $info['total_courses'] = $total_courses;
                    $info['percentage_failed'] = $percentage_failed;

                    $cape_subjects = array();
                    $cape_subjects_names = array();
                    $programme_record = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->where(['academicofferingid' => $target_application->academicofferingid])
                            ->one();
                    $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $target_application->applicationid]);
                    foreach ($cape_subjects as $cs) 
                    {
                        $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname;
                    }
                    $programme = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                    $info['programme'] = $programme;

                    $student_status =AcademicStatus::find()
                            ->where(['academicstatusid' => $registration->academicstatusid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($student_status == false)
                    {
                        $info['student_status'] = "Unknown";
                    }
                    else
                    {
                       $info['student_status'] = $student_status->name;
                    }
                    
                   
                    $email = $user->email;
                    if ($email == false)
                    {
                        $email_record = Email::find()
                            ->where(['personid' => $registration->personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                        if ($email == false)
                            continue;
                        $email = $email_record->email;
                    }
                    $info['email'] = $email;

                    $data[] = $info;
                }

                $dataProvider = new ArrayDataProvider([
                        'allModels' => $data,
                        'pagination' => [
                            'pageSize' => 50,
                        ],
                        'sort' => [
                                'defaultOrder' => ['last_name' => SORT_ASC, 'first_name' => SORT_ASC],
                                'attributes' => ['username', 'last_name', 'first_name', 'programme'],
                            ],
                    ]);

                $periodname = ApplicationPeriod::find()
                    ->where(['applicationperiodid' => $application_periodid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                    ->name;
                $title = "Title: Warning Candidates for " . $periodname;
            }
            
            
           
             return $this->render('warning_candidate_result',
                [
                    'periods' => $periods,
                    'dataProvider' => $dataProvider,
                    'title' => $title,
                    'application_periodid' => $application_periodid, 
                    'academic_warning' => $academic_warning,
                    'academic_probation' => $academic_probation,
                    'poor_performers' => $poor_performers
                ]);
        }
        
        
        /**
        * Generates exportable withdrawal listing
        * 
        * @return type
        * 
        * Author: charles.laurence1@gmail.com
        * Date Created: 15/1/2016
        * Date Last Modified: 04/08/2017 | 2018_03_12
        */
       public function actionExportWarningListing($application_periodid)
       {
           $registrations = StudentRegistration::find()
                        ->innerJoin('offer', '`student_registration`.`offerid` = `offer`.`offerid`')
                        ->innerJoin('application', '`offer`.`applicationid` = `application`.`applicationid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where(['student_registration.isactive' => 1, 'student_registration.isdeleted' => 0,
                                        'offer.isactive' => 1, 'offer.isdeleted' => 0,
                                        'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                        'application_period.applicationperiodid' => $application_periodid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                                    ])
                        ->all();

            foreach ($registrations as $registration)
            {
                $info = array();
                $offer = Offer::find()
                         ->where(['offerid' => $registration->offerid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                if ($offer == false)
                    continue;

                $target_application = Application::find()
                        ->where(['applicationid' => $offer->applicationid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                if ($target_application == false) 
                    continue;

                /******************  Checks if applicant is eligible for contract ********************/
                $is_cape = AcademicOffering::isCape($target_application->academicofferingid);
                $fails = 0;
                if ($is_cape == true)
                {
                    continue;
                }
                else
                {
                    $courses = BatchStudent::find()
                                ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                                ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                                ->innerJoin('academic_offering', '`course_offering`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                                ->innerJoin('semester', '`course_offering`.`semesterid` = `semester`.`semesterid`')
                                ->where(['batch_students.studentregistrationid' => $registration->studentregistrationid, 'batch_students.isactive' => 1, 'batch_students.isdeleted'=> 0,
                                                'batch.batchtypeid' =>[1], 'batch.isactive' => 1, 'batch.isdeleted'=> 0,
                                                'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0,
                                                 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                                'application_period.applicationperiodid' => $application_periodid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                                                'semester.title' => 1, 'semester.isactive' => 1, 'semester.isdeleted' => 0
                                    ])       
                               ->andWhere(['<>','course_offering.credits', 0])
                                ->all();
                }

                if ($courses == false)
                    continue;
                
                foreach ($courses as $course)
                {
                    if ($course->was_failed() == true)
                    {
                        $fails++;
                    }
                }
                
                if ($fails < 2)
                        continue;

                $total_courses = count($courses);
                $percentage_failed = round( (($fails/$total_courses)*100) , 1);
                        
//                if ($fails < 4)
//                    continue;
//                 if ($percentage_failed < 33)
//                        continue;
                /*************************************************************************/
                if ($fails == 2)
                {
                    $info['proposed_status'] = "Warning";
                }
                elseif ($fails > 2)
                {
                   $info['proposed_status'] = "Probation";
                }
                else
                {
                   $info['proposed_status'] = "---";
                }
                    
                $info['student_registrationid'] = $registration->studentregistrationid;
                $info['offerid'] = $registration->offerid;
                $info['personid'] = $registration->personid;

                $user = User::find()
                        ->where(['personid' => $registration->personid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                if ($user == false)
                    continue;
                $info['username'] = $user->username;

                $student = Student::find()
                        ->where(['personid' => $registration->personid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                if ($student == false)
                    continue;

                $info['title'] = $student->title;
                $info['first_name'] = $student->firstname;
                $info['middle_name'] = $student->middlename;
                $info['last_name'] = $student->lastname;
                $info['fails'] = $fails;
                $info['total_courses'] = $total_courses;
                $info['percentage_failed'] = $percentage_failed;

                $cape_subjects = array();
                $cape_subjects_names = array();
                $programme_record = ProgrammeCatalog::find()
                        ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                        ->where(['academicofferingid' => $target_application->academicofferingid])
                        ->one();
                $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $target_application->applicationid]);
                foreach ($cape_subjects as $cs) 
                {
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname;
                }
                $programme = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                $info['programme'] = $programme;

                $info['current_level'] = $registration->currentlevel;

                $student_status = AcademicStatus::find()
                        ->where(['academicstatusid' => $registration->academicstatusid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                if ($student_status == false)
                    $info['student_status'] = "Unknown";
                else
                   $info['student_status'] = $student_status->name;

                $email = $user->email;
                if ($email == false)
                {
                    $email_record = Email::find()
                        ->where(['personid' => $registration->personid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                    if ($email == false)
                        continue;
                    $email = $email_record->email;
                }
                $info['email'] = $email;

                $data[] = $info;
            }

            $dataProvider = new ArrayDataProvider([
                    'allModels' => $data,
                    'pagination' => [
                        'pageSize' => 1000,
                    ],
                     'sort' => [
                                'defaultOrder' => ['last_name' => SORT_ASC, 'first_name' => SORT_ASC],
                                'attributes' => ['username', 'last_name', 'first_name', 'programme'],
                            ],
                ]);

            $periodname = ApplicationPeriod::find()
                ->where(['applicationperiodid' => $application_periodid, 'isactive' => 1, 'isdeleted' => 0])
                ->one()
                ->name;
            $title = "Title: Warning Candidates for " . $periodname;
            $date = "   Date : " . date('Y-m-d') . "   ";
            $employeeid = Yii::$app->user->identity->personid;
            $generating_officer = "   Generator: " . Employee::getEmployeeName($employeeid);
            $filename = $title . $date . $generating_officer;
           
           
           return $this->renderPartial('warning_export', [
                'dataProvider' => $dataProvider,
                'filename' => $filename,
            ]);
       }
        
         
        
    }
