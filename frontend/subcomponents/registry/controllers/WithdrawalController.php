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
      

    class WithdrawalController extends Controller {

        /**
         * Renders withdrawl candidates
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 10/09/2016
         * Date Last Modified: 10/09/2016
         */
        public function actionIndex($new = 0) {

            $dataProvider = array();
            $data = array();
            $filename = "";
            $title = "";
            
            $periods = ApplicationPeriod::preparePastPeriods();
            
            if ($new ==1)
            {
                $application_periodid = NULL;
            }
            else
            {
                if (Yii::$app->request->post()) 
                {
                    $request = Yii::$app->request;

                    $application_periodid = $request->post('period-id');

                    if (!$application_periodid)
                    {
                        $application_periodid = Yii::$app->session->get('application_periodid');
                        $application_periodid = $request->post('application_periodid');
                    }
                    Yii::$app->session->set('application_periodid', $application_periodid);
                } 
                else
                {
                    $application_periodid = Yii::$app->session->get('application_periodid');
                    Yii::$app->session->set('application_periodid', $application_periodid);
                }
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
                                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                                'attributes' => ['username', 'lastname', 'firstname', 'programme'],
                            ],
                    ]);

                $periodname = ApplicationPeriod::find()
                    ->where(['applicationperiodid' => $application_periodid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                    ->name;
                $title = "Title: Withdrawal Candidates for " . $periodname;
                $date = " Date : " . date('Y-m-d') . "   ";
                $employeeid = Yii::$app->user->identity->personid;
                $generating_officer = " Generator: " . Employee::getEmployeeName($employeeid);
                $filename = $title . $date . $generating_officer;
            }
           
            return $this->render('select_candidate_criteria',
                [
                    'periods' => $periods,
                    'dataProvider' => $dataProvider,
                    'filename' => $filename,
                    'title' => $title,
                    'application_periodid' => $application_periodid,
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
         * Date Last Modified: 12/09/2016
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
                            return self::actionIndex(1);
                        }
                    }
                    elseif (StudentRegistration::getStudentDivision($registration->studentregistrationid) == 6 && $registration->currentlevel == 2 && ($registration->studentstatusid == 1 || $registration->studentstatusid == 11))
                    {
                        $registration->currentlevel = 3;
                        $save_flag = $registration->save();
                        if ($save_flag == false)
                        {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error occurred saving registration record.');
                            return self::actionIndex(1);
                        }
                    }
                }
                
                $transaction->commit();
                return self::actionIndex(1);
                
            } catch (Exception $ex) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', 'Error occurred when processing request.');
                 return self::actionIndex(1);
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
         * Date Last Modified: 12/09/2016
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
                    if ($registration->currentlevel != 0)
                    {
                        $registration->currentlevel = $registration->currentlevel-1;
                        $save_flag = $registration->save();
                        if ($save_flag == false)
                        {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error occurred saving registration record.');
                            return self::actionIndex(1);
                        }
                    }
                }
                
                $transaction->commit();
                return self::actionIndex(1);
                
            } catch (Exception $ex) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', 'Error occurred when processing request.');
            }
        }
        
    }
