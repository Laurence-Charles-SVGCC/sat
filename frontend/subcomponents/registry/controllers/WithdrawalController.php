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
    use frontend\models\EmployeeDepartment;
    use frontend\models\ApplicationCapesubject;
     use frontend\models\Offer;
    use frontend\models\Email;
    use frontend\models\Employee;
    use frontend\models\AcademicOffering;
    use frontend\models\BatchStudent;
    use frontend\models\BatchStudentCape;
      

    class WithdrawalController extends Controller 
    {
        /**
         * Renders withdrawal candidates search view
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 10/09/2016
         * Date Last Modified: 10/09/2016 | 15/11/2016
         */
         public function actionIndex()
         {
             $periods = ApplicationPeriod::preparePastPeriods();
             
             return $this->render('select_candidate_criteria',
                [
                    'periods' => $periods,
                ]);
         }
        
        
        /**
         * Generate withdrawl candidates
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 15/11/2016
         * Date Last Modified: 15/11/2016
         */
        public function actionGenerateWithdrawalCandidates() 
        {
            $dataProvider = array();
            $data = array();
            $filename = "";
            $title = "";
            
            $prospective_withdrawals = 0;
            $probationary_retention = 0;
            $academic_withdrawal = 0;
            $current = 0;
            
            $periods = ApplicationPeriod::preparePastPeriods();
            
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
            
            if ($application_periodid != 0)
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
                        $courses = BatchStudentCape::find()
                                ->where(['studentregistrationid' => $registration->studentregistrationid, 'isactive' => 1, 'isdeleted'=> 0])
                                ->all();
                    }
                    else
                    {
                        $courses = BatchStudent::find()
                                ->where(['studentregistrationid' => $registration->studentregistrationid, 'isactive' => 1, 'isdeleted'=> 0])
                                ->all();
                    }
                    
                    if ($courses)
                    {
                        foreach ($courses as $course)
                        {
                            if ($course->final != NULL && $course->final < 40)
                            {
                                $fails++;
                            }
                        }
                    }
                    
                    if ($fails < 4)
                        continue;
                    /*************************************************************************/
                    $prospective_withdrawals ++;
                    
                    if ($registration->studentstatusid == 1)
                        $current++;
                    elseif ($registration->studentstatusid == 2)
                        $academic_withdrawal++;
                    elseif ($registration->studentstatusid == 11)
                        $probationary_retention++;
                   
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

                    $student_status = StudentStatus::find()
                            ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
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
                            'pageSize' => 25,
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
                $title = "Title: Withdrawal Candidates for " . $periodname;
                $date = "   Date : " . date('Y-m-d') . "   ";
                $employeeid = Yii::$app->user->identity->personid;
                $generating_officer = "   Generator: " . Employee::getEmployeeName($employeeid);
                $filename = $title . $date . $generating_officer;
            }
           
             return $this->render('withdrawal_candidate_result',
                [
                    'periods' => $periods,
                    'dataProvider' => $dataProvider,
                    'filename' => $filename,
                    'title' => $title,
                    'application_periodid' => $application_periodid, 
                    'prospective_withdrawals' => $prospective_withdrawals,
                    'probationary_retention' => $probationary_retention,
                    'academic_withdrawal' => $academic_withdrawal,
                    'current' => $current,
                ]);
        }
        
        
        /**
        * Generates exportable withdrawal listing
        * 
        * @return type
        * 
        * Author: Laurence Charles
        * Date Created: 15/1/2016
        * Date Last Modified: 15/1/2016
        */
       public function actionExportWithdrawalListing($application_periodid)
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
                    $courses = BatchStudentCape::find()
                            ->where(['studentregistrationid' => $registration->studentregistrationid, 'isactive' => 1, 'isdeleted'=> 0])
                            ->all();
                }
                else
                {
                    $courses = BatchStudent::find()
                            ->where(['studentregistrationid' => $registration->studentregistrationid, 'isactive' => 1, 'isdeleted'=> 0])
                            ->all();
                }

                if ($courses)
                {
                    foreach ($courses as $course)
                    {
                        if ($course->final != NULL && $course->final < 40)
                        {
                            $fails++;
                        }
                    }
                }

                if ($fails < 4)
                    continue;
                /*************************************************************************/
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

                $student_status = StudentStatus::find()
                        ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
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
                ]);

            $periodname = ApplicationPeriod::find()
                ->where(['applicationperiodid' => $application_periodid, 'isactive' => 1, 'isdeleted' => 0])
                ->one()
                ->name;
            $title = "Title: Withdrawal Candidates for " . $periodname;
            $date = "   Date : " . date('Y-m-d') . "   ";
            $employeeid = Yii::$app->user->identity->personid;
            $generating_officer = "   Generator: " . Employee::getEmployeeName($employeeid);
            $filename = $title . $date . $generating_officer;
           
           
           return $this->renderPartial('withdrawal_export', [
                'dataProvider' => $dataProvider,
                'filename' => $filename,
            ]);
       }
        
        
        /**
         * Promote students
         * 
         * @param type $applicationperiodid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 12/09/2016
         * Date Last Modified: 12/09/2016 | 17/11/2016
         */
        public function actionPromoteStudents($applicationperiodid)
        {
            $registrations = StudentRegistration::find()
                        ->innerJoin('offer', '`student_registration`.`offerid` = `offer`.`offerid`')
                        ->innerJoin('application', '`offer`.`applicationid` = `application`.`applicationid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->innerJoin('academic_year', '`application_period`.`academicyearid` = `application_period`.`academicyearid`')
                        ->where(['student_registration.isactive' => 1, 'student_registration.isdeleted' => 0,
                                        'offer.isactive' => 1, 'offer.isdeleted' => 0,
                                        'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                        'application_period.applicationperiodid' => $applicationperiodid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                                         'academic_year.iscurrent' => 0, 'academic_year.isactive' => 1, 'academic_year.isdeleted' => 0
                                    ])
                        ->all();
            
            $transaction = \Yii::$app->db->beginTransaction();
            try 
            {
                foreach ($registrations as $registration)
                {
                    $save_flag = false;
                    if ($registration->currentlevel == 1 && ($registration->studentstatusid == 1 || $registration->studentstatusid == 11))
                    {
                        $registration->currentlevel = 2;
                        $save_flag = $registration->save();
                        if ($save_flag == false)
                        {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error occurred saving registration record.');
                            return self::actionIndex();
                        }
                    }
                    elseif (StudentRegistration::getStudentDivision($registration->studentregistrationid) == 7 && $registration->currentlevel == 2 && ($registration->studentstatusid == 1 || $registration->studentstatusid == 11))
                    {
                        $registration->currentlevel = 3;
                        $save_flag = $registration->save();
                        if ($save_flag == false)
                        {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error occurred saving registration record.');
                            return self::actionIndex();
                        }
                    }
                }
                
                $transaction->commit();
                return self::actionIndex();
                
            } catch (Exception $ex) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', 'Error occurred when processing request.');
                 return self::actionIndex();
            }
        }
        
        
         /**
         * Undo student promotion
         * 
         * @param type $applicationperiodid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 12/09/2016
         * Date Last Modified: 12/09/2016 | 17/11/2016
         */
        public function actionUndoPromotions($applicationperiodid)
        {
            $registrations = StudentRegistration::find()
                        ->innerJoin('offer', '`student_registration`.`offerid` = `offer`.`offerid`')
                        ->innerJoin('application', '`offer`.`applicationid` = `application`.`applicationid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->innerJoin('academic_year', '`application_period`.`academicyearid` = `application_period`.`academicyearid`')
                        ->where(['student_registration.isactive' => 1, 'student_registration.isdeleted' => 0,
                                        'offer.isactive' => 1, 'offer.isdeleted' => 0,
                                        'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                        'application_period.applicationperiodid' => $applicationperiodid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                                        'academic_year.iscurrent' => 0, 'academic_year.isactive' => 1, 'academic_year.isdeleted' => 0
                                    ])
                        ->all();
            
            $transaction = \Yii::$app->db->beginTransaction();
            try 
            {
                foreach ($registrations as $registration)
                {
                    $save_flag = false;
                    if ($registration->currentlevel != 1)
                    {
                        $registration->currentlevel = $registration->currentlevel-1;
                        $save_flag = $registration->save();
                        if ($save_flag == false)
                        {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error occurred saving registration record.');
                            return self::actionIndex();
                        }
                    }
                }
                
                $transaction->commit();
                return self::actionIndex();
                
            } catch (Exception $ex) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', 'Error occurred when processing request.');
            }
        }
        
    }
