<?php

/* 
 * Author: Laurence Charles
 * Date Created: 04/12/2015
 */

    namespace app\subcomponents\gradebook\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\data\ArrayDataProvider;
        
    use frontend\models\Division;
    use frontend\models\Employee;
    use frontend\models\EmployeeDepartment;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\AcademicOffering;
    use frontend\models\Department;
    use frontend\models\AcademicYear;
    use frontend\models\Cordinator;
    use frontend\models\StudentRegistration;
    use frontend\models\Student;
    use frontend\models\StudentStatus;
    use common\models\User;
    use frontend\models\Applicant;
    use frontend\models\Assessment;
    use frontend\models\AssessmentCape;
    use frontend\models\AssessmentStudent;
    use frontend\models\AssessmentStudentCape;
    use frontend\models\BatchStudent;
    use frontend\models\BatchStudentCape;
    use frontend\models\Batch;
    use frontend\models\ApplicationCapesubject;
    use frontend\models\QualificationType;
    use frontend\models\Offer;
    
    
    class GradebookController extends Controller
    {
        /**
         * Renders the Gradebook home view and process form submission
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 04/12/2015
         * Date Last Modified: 10/12/2015 | 04/11/2016
         */
        public function actionIndex($id = NULL)
        {
            $info_string = "";
            
            $all_student_data_container = array();
            $all_students_provider = array();
            $all_students_info = array();
            
            $a_f_provider = array();
            $a_f_info = array();
            
            $g_l_provider = array();
            $g_l_info = array();
            
            $m_r_provider = array();
            $m_r_info = array();
            
            $s_z_provider = array();
            $s_z_info = array();

            $divisionid = NULL;
            $studentid = NULL;
            $firstname = NULL;
            $lastname = NULL;
            
            
            //need to facilitate breadcrumb navigation from 'student_listing' to 'programme_listing' of source division
            if ($id)
            {
                $request = Yii::$app->request;
                $divisionid = $id;
                
                if ($divisionid != NULL  && $divisionid != 0 && strcmp($divisionid, "0") != 0)
                {
                    $division_name = Division::getDivisionAbbreviation($divisionid);
                    $department_count = count(Department::getDepartments($divisionid));
                    
                    $data_package = array();
                    $programme_collection = array();
                    /*
                     * Package Collection structure is as follows
                     * [department_count, [[programme, cohort_count, [cohorts,...]]]
                     */
                    array_push($data_package, $department_count);
                                       
                    $programmes = ProgrammeCatalog::getProgrammes($divisionid);
                    if ($programmes)
                    {
                        foreach ($programmes as $programme) 
                        {
                            $temp_array = array();
                            
                            $cohort_array = array();
                            
                            array_push($temp_array, $programme);

                            $cohort_count = AcademicOffering::getCohortCount($programme->programmecatalogid); //yet to be created
                            array_push($temp_array, $cohort_count);
                            
                            if ($cohort_count > 0)
                            {
                                $cohorts = AcademicOffering::getCohorts($programme->programmecatalogid); //yet to be created

                                for($i = 0 ; $i < $cohort_count ; $i++)
                                {
                                    array_push($cohort_array, $cohorts[$i]);
                                }
                                array_push($temp_array, $cohort_array);
                            }
                            
                            array_push($programme_collection, $temp_array);
                                               
                            $temp_array = NULL;
                            $cohort_array = NULL;
                            $name = NULL;
                            $cohort_count = NULL;
                            $cohorts = NULL;
                        }
                        array_push($data_package, $programme_collection);
                        
                        //if user is permitted to access Programme Listing view
                        if (Yii::$app->user->can('accessProgrammeListing') == true)
                        {
                            return $this->render('programme_listing', [
                                'division_id' => $divisionid,
                                'division_name' => $division_name,
                                'data' => $data_package,
                            ]);
                        }
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'No programmes found.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Please select a divsion.');                
            }
            

            if (Yii::$app->request->post())
            {
                //Everytime a new search is initiated session variable must be removed
                if (Yii::$app->session->get('studentid'))
                   Yii::$app->session->remove('studentid');
                
                if (Yii::$app->session->get('firstname'))
                   Yii::$app->session->remove('firstname');

               if (Yii::$app->session->get('lastname'))
                   Yii::$app->session->remove('lastname');

              
                $request = Yii::$app->request;
                $divisionid = $request->post('division_choice');
                $studentid = $request->post('studentid_field');
                $firstname = $request->post('firstname_field');
                $lastname = $request->post('lastname_field');
                
                 if(Yii::$app->session->get('studentid') == null  && $studentid == true)
                    Yii::$app->session->set('studentid', $studentid);

                if(Yii::$app->session->get('firstname') == null  && $firstname == true)
                    Yii::$app->session->set('firstname', $firstname);

                if(Yii::$app->session->get('lastname') == null  && $lastname == true)
                    Yii::$app->session->set('lastname', $lastname);
            }
            else    
            {
                $studentid = Yii::$app->session->get('studentid');
                $firstname = Yii::$app->session->get('firstname');
                $lastname = Yii::$app->session->get('lastname');
            }
            
            //if user initiates search based on programme
            if ($divisionid != NULL  && $divisionid != 0 && strcmp($divisionid, "0") != 0)
            {
                $division_name = Division::getDivisionAbbreviation($divisionid);
                $department_count = count(Department::getDepartments($divisionid));

                $data_package = array();
                $programme_collection = array();
                /*
                 * Package Collection structure is as follows
                 * [department_count, [[programme, cohort_count, [cohorts,...]]]
                 */
                array_push($data_package, $department_count);

                $programmes = ProgrammeCatalog::getProgrammes($divisionid);
                if ($programmes)
                {
                    foreach ($programmes as $programme) 
                    {
                        $temp_array = array();

                        $cohort_array = array();

                        array_push($temp_array, $programme);

                        $cohort_count = AcademicOffering::getCohortCount($programme->programmecatalogid); //yet to be created
                        array_push($temp_array, $cohort_count);

                        if ($cohort_count > 0)
                        {
                            $cohorts = AcademicOffering::getCohorts($programme->programmecatalogid); //yet to be created

                            for($i = 0 ; $i < $cohort_count ; $i++)
                            {
                                array_push($cohort_array, $cohorts[$i]);
                            }
                            array_push($temp_array, $cohort_array);
                        }



                        array_push($programme_collection, $temp_array);

                        $temp_array = NULL;
                        $cohort_array = NULL;
                        $name = NULL;
                        $cohort_count = NULL;
                        $cohorts = NULL;
                    }
                    array_push($data_package, $programme_collection);

                    //if user is permitted to access Programme Listing view
                    if (Yii::$app->user->can('accessProgrammeListing') == true)
                    {
                        return $this->render('programme_listing', [
                            'division_id' => $divisionid,
                            'division_name' => $division_name,
                            'data' => $data_package,
                        ]);
                    }
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'No programmes found.');
            }

            //if user initiates search based on studentID
            elseif ($studentid != NULL  && strcmp($studentid, "") != 0)
            {
                $info_string = $info_string .  " Student ID: " . $studentid;
                $user = User::findOne(['username' => $studentid, 'isactive' => 1, 'isdeleted' => 0]);
  
                if ($user)
                {    
                    //if system user is a Dean or Deputy Dean then their search is contrained by their division
                    if ((Yii::$app->user->can('Deputy Dean') || Yii::$app->user->can('Dean')  || Yii::$app->user->can('Divisional Staff'))  && !Yii::$app->user->can('System Administrator'))
                    {
//                            $divisionid = Employee::getEmployeeDivisionID(Yii::$app->user->identity->personid);
                        $divisionid = EmployeeDepartment::getUserDivision();
                        $registrations = StudentRegistration::getStudentsByDivision($divisionid, $user->personid);

                        if (empty($registrations))
                        {
                            Yii::$app->getSession()->setFlash('error', 'No students found matching this criteria within your division.');
                            return $this->render('index',[
                                'all_students_provider' => $all_students_provider,
                                'info_string' => $info_string,
                            ]);
                        }
                    }
                    //if system user is not a Dean or Deputy Dean then their search is not contrained
                    else
                    {
                        //must take into consideration users with multiple student registrations; can't just take the first one
                        $registrations = StudentRegistration::find()
                                    ->where(['personid' => $user->personid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->all();
                    }

                    if ($registrations) 
                    {          
                        for ($k = 0 ; $k < count($registrations) ; $k++)
                        {
                            //if user is 'Cordinator then they can only find students that re enrolled in a programme or department that they are cordinating
                            if(Yii::$app->user->can('Cordinator'))
                            {
                                $role_types = Cordinator::getCordinatorTypes();
                                //assess possible programmes if user is/was assigned Department Head role
                                if ($role_types == true && in_array(1, $role_types))
                                {
                                    $cordinated_items = Cordinator::getCordinationScope(1);
                                    if ($cordinated_items == true  && in_array($registrations[$k]->academicofferingid, $cordinated_items) == false)
                                    {
                                        continue;
                                    }
                                }
                                
                                //assess possible programmes if user is/was assigned Programme Head role
                                elseif ($role_types == true && in_array(2, $role_types))
                                {
                                    $cordinated_items = Cordinator::getCordinationScope(2);
                                    if ($cordinated_items == true  && in_array($registrations[$k]->academicofferingid, $cordinated_items) == false)
                                    {
                                        continue;
                                    }
                                }
                                
                            }
                                
                            $student = Student::getStudent($user->personid);
                            if ($student)
                            {
                                $all_students_info['personid'] = $user->personid;
                                $all_students_info['studentregistrationid'] = $registrations[$k]->studentregistrationid;
                                $all_students_info['studentno'] = $user->username;
                                $all_students_info['firstname'] = $student->firstname;
                                $all_students_info['middlename'] = $student->middlename;
                                $all_students_info['lastname'] = $student->lastname;
                                $all_students_info['gender'] = $student->gender;

                                $offer_from = Offer::find()
                                        ->where(['offerid' => $registrations[$k]->offerid, 'isdeleted' => 0])
                                        ->one();
                                if($offer_from == false)
                                    continue;
                                $current_cape_subjects_names = array();
                                $current_cape_subjects = array();
                                $current_application = $offer_from->getApplication()->one();
                                $current_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
                                $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
                                foreach ($current_cape_subjects as $cs)
                                { 
                                    $current_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                                }
                                $currentprogramme = empty($current_cape_subjects) ? $current_programme->getFullName() : $current_programme->name . ": " . implode(' ,', $current_cape_subjects_names);
                                $all_students_info['current_programme'] = $currentprogramme;

                                $enrollments = StudentRegistration::find()
                                        ->where(['personid' => $user->personid, 'isdeleted' => 0])
                                        ->count();
                                $all_students_info['enrollments'] = $enrollments;

                                $student_status = StudentStatus::find()
                                                ->where(['studentstatusid' => $registrations[$k]->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                                ->one();
                                $all_students_info['studentstatus'] = $student_status->name;
                                $all_student_data_container[] = $all_students_info;
                            }
                            else
                            {
                                Yii::$app->session->setFlash('error', 'No user found matching this criteria.');
                            }
                        }
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'No user found matching this criteria.');
                    }                    

                    $all_students_provider = new ArrayDataProvider([
                            'allModels' => $all_student_data_container,
                            'pagination' => [
                                'pageSize' => 25,
                            ],
                            'sort' => [
                                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                                'attributes' => ['firstname', 'lastname'],
                            ]
                    ]);    
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'No user found matching this criteria.');
                }                     
            }


            //if user initiates search based student name
            elseif( ($firstname != NULL && strcmp($firstname,"") != 0)  || ($lastname != NULL && strcmp($lastname,"") != 0) )
            {
//                    Yii::$app->getSession()->setFlash('error', 'Lets search using student name.');
                if ($firstname)
                {
                    $cond_arr['firstname'] = $firstname;
                    $info_string = $info_string .  " First Name: " . $firstname; 
                }
                if ($lastname)
                {
                    $cond_arr['lastname'] = $lastname;
                    $info_string = $info_string .  " Last Name: " . $lastname;
                }

                if (empty($cond_arr))
                {
                    Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
                }
                else
                {
                    $cond_arr['isactive'] = 1;
                    $cond_arr['isdeleted'] = 0;

                    $students = Student::find()
                            ->where($cond_arr)
                            ->all();
                    if (empty($students))
                    {
                        Yii::$app->getSession()->setFlash('error', 'No user found matching this criteria.');
                    }
                    else
                    {
                        //if system user is Dean or Deputy Dean then student_registration records are filtered by divisionid
                        $eligible_students_found = false; //students within correct division
                        if ((Yii::$app->user->can('Deputy Dean') || Yii::$app->user->can('Dean')  || Yii::$app->user->can('Divisional Staff')) &&  !Yii::$app->user->can('System Administrator'))
                        {
//                                $divisionid = Employee::getEmployeeDivisionID(Yii::$app->user->identity->personid);
                            $divisionid = EmployeeDepartment::getUserDivision();
                            foreach ($students as $student)
                            {
                                $registrations = StudentRegistration::getStudentsByDivision($divisionid, $student->personid);
                                if (!empty($registrations))
                                {
                                    foreach ($registrations as $registration)
                                    {
                                        $eligible_students_found = true;
                                        $user = User::findOne(['personid' => $student->personid, 'isactive' => 1, 'isdeleted' => 0]);
                                        if ($registration && $user)
                                        {
                                            $all_students_info['personid'] = $student->personid;
                                            $all_students_info['studentregistrationid'] = $registration->studentregistrationid;
                                            $all_students_info['studentno'] = $user->username;
                                            $all_students_info['firstname'] = $student->firstname;
                                            $all_students_info['middlename'] = $student->middlename;
                                            $all_students_info['lastname'] = $student->lastname;
                                            $all_students_info['gender'] = $student->gender;

                                            $offer_from = Offer::find()
                                                    ->where(['offerid' => $registration->offerid, 'isactive' => 1, 'isdeleted' => 0])
                                                    ->one();
                                            if($offer_from == false)
                                                continue;
                                            $current_cape_subjects_names = array();
                                            $current_cape_subjects = array();
                                            $current_application = $offer_from->getApplication()->one();
                                            $current_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
                                            $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
                                            foreach ($current_cape_subjects as $cs)
                                            { 
                                                $current_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                                            }
                                            $currentprogramme = empty($current_cape_subjects) ? $current_programme->getFullName() : $current_programme->name . ": " . implode(' ,', $current_cape_subjects_names);
                                            $all_students_info['current_programme'] = $currentprogramme;

                                            $enrollments = StudentRegistration::find()
                                                    ->where(['personid' => $user->personid,'isactive' =>1, 'isdeleted' => 0])
                                                    ->count();
                                            $all_students_info['enrollments'] = $enrollments;

                                            $student_status = StudentStatus::find()
                                                            ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                                            ->one();
                                            $all_students_info['studentstatus'] = $student_status->name;
                                            $all_student_data_container[] = $all_students_info;
                                        }
                                    }
                                }  
                            }

                            //if among the possible matching 'student' records there are no 'student_registration' records related to the user's division
                            if ($eligible_students_found == false)
                            {
                                Yii::$app->getSession()->setFlash('error', 'No students found matching this criteria within your division.');
                                return $this->render('index',[
                                    'all_students_provider' => $all_students_provider,
                                    'info_string' => $info_string,
                                ]);
                            }
                        }

                        //if system user is not a Dean or Deputy Dean then their search is not contrained
                        else
                        {
                            foreach ($students as $student)
                            {
                                $registration = StudentRegistration::find()
                                        ->where(['personid' => $student->personid, 'isactive' => 1, 'isdeleted' => 0])
                                        ->one();    
                                
                                //if user is 'Cordinator then they can only find students that re enrolled in a programme or department that they are cordinating
                                if(Yii::$app->user->can('Cordinator'))
                                {
                                    $role_types = Cordinator::getCordinatorTypes();
                                    //assess possible programmes if user is/was assigned Department Head role
                                    if ($role_types == true && in_array(1, $role_types))
                                    {
                                        $cordinated_items = Cordinator::getCordinationScope(1);
                                        if ($cordinated_items == true  && in_array($registrations[$k]->academicofferingid, $cordinated_items) == false)
                                        {
                                            continue;
                                        }
                                    }
                                    
                                    //assess possible programmes if user is/was assigned Programme Head role
                                    elseif ($role_types == true && in_array(2, $role_types))
                                    {
                                        $programmes_cordinated = Cordinator::getCordinationScope(2);
                                        if ($programmes_cordinated == true  && in_array($registration->academicofferingid, $programmes_cordinated) == false)
                                        {
                                            continue;
                                        }
                                    }
                                }
                                
                                
                                $user = User::findOne(['personid' => $student->personid, 'isactive' => 1, 'isdeleted' => 0]);
                                if ($registration && $user)
                                {
                                    $all_students_info['personid'] = $student->personid;
                                    $all_students_info['studentregistrationid'] = $registration->studentregistrationid;
                                    $all_students_info['studentno'] = $user->username;
                                    $all_students_info['firstname'] = $student->firstname;
                                    $all_students_info['middlename'] = $student->middlename;
                                    $all_students_info['lastname'] = $student->lastname;
                                    $all_students_info['gender'] = $student->gender;

                                    $offer_from = Offer::find()
                                            ->where(['offerid' => $registration->offerid, 'isdeleted' => 0])
                                            ->one();
                                    if($offer_from == false)
                                        continue;
                                    $current_cape_subjects_names = array();
                                    $current_cape_subjects = array();
                                    $current_application = $offer_from->getApplication()->one();
                                    $current_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
                                    $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
                                    foreach ($current_cape_subjects as $cs)
                                    { 
                                        $current_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                                    }
                                    $currentprogramme = empty($current_cape_subjects) ? $current_programme->getFullName() : $current_programme->name . ": " . implode(' ,', $current_cape_subjects_names);
                                    $all_students_info['current_programme'] = $currentprogramme;

                                    $enrollments = StudentRegistration::find()
                                            ->where(['personid' => $user->personid, 'isdeleted' => 0])
                                            ->count();
                                    $all_students_info['enrollments'] = $enrollments;

                                    $student_status = StudentStatus::find()
                                                    ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                                    ->one();
                                    $all_students_info['studentstatus'] = $student_status->name;
                                    $all_student_data_container[] = $all_students_info;
                                }
                                else
                                {
                                    Yii::$app->session->setFlash('error', 'No user found matching this criteria.');
                                }                  
                            }
                        }

                        $all_students_provider = new ArrayDataProvider([
                                'allModels' => $all_student_data_container,
                                'pagination' => [
                                    'pageSize' => 25,
                                ],
                                'sort' => [
                                    'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                                    'attributes' => ['firstname', 'lastname'],
                                ]
                        ]);      
                    }                 
                }
            }
            
            //if user is permitted to access GradeBook homepage
            if (Yii::$app->user->can('accessGradeBookHome') == true)
            {
                return $this->render('index',[
                    'all_students_provider' => $all_students_provider,
                    'info_string' => $info_string,
                ]);
            }
        }

        
        /**
         * Renders the 'Student Listing view and process form submission
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 09/12/2015
         * Date Last Modified: 09/12/2015
         */
        public function actionStudents($academicyearid, $academicofferingid, $programmename, $divisionid)
        {
            $all_students_provider = NULL;
            $all_students_info = array();
            
            $a_f_provider = NULL;
            $a_f_info = array();
            
            $g_l_provider = NULL;
            $g_l_info = array();
            
            $m_r_provider = NULL;
            $m_r_info = array();
            
            $s_z_provider = NULL;
            $s_z_info = array();
        
            $academicyear = AcademicYear::getYear($academicyearid);
            $cordinator_details = "";
            $cordinators = Cordinator::find()
                   ->where(['academicofferingid' => $academicofferingid , 'isserving' => 1, 'isactive' => 1, 'isdeleted' => 0])
                   ->orderBy('cordinatorid DESC')
                   -> all();
           if($cordinators)
           {
               foreach($cordinators as $key => $cordinator)
               {
                   $name = "";
                   $name = Employee::getEmployeeName($cordinators[$key]->personid);
                   if(count($cordinators) - 1 == 0)
                    $cordinator_details .= $name;
                    else 
                        $cordinator_details .= $name . ", ";
               }
           }
            
            $registrations = StudentRegistration::getStudentRegistration($academicofferingid);
            if ($registrations)
            {
                /************************** Prepares data for 'all_student' tab *******************************************/
                foreach ($registrations as $registration)
                {
                    $user = $registration->getPerson()->one();
                    if ($user)
                    {                 
                        $personid = $registration->personid;
                        $student = Student::getStudent($personid);
                        if ($student)
                        {
                            $all_students_info['personid'] = $user->personid;
                            $all_students_info['studentregistrationid'] = $registration->studentregistrationid;
                            $all_students_info['studentno'] = $user->username;
                            $all_students_info['firstname'] = $student->firstname;
                            $all_students_info['middlename'] = $student->middlename;
                            $all_students_info['lastname'] = $student->lastname;
                            $all_students_info['gender'] = $student->gender;

                            $student_status = StudentStatus::find()
                                            ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                            ->one();
                            $all_students_info['studentstatus'] = $student_status->name;
                            $all_student_data_container[] = $all_students_info;
                        }
                        else
                        {
                            Yii::$app->session->setFlash('error', 'Student not found');
                        }
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'User not found');
                    }                    
                }
                $all_students_provider = new ArrayDataProvider([
                        'allModels' => $all_student_data_container,
                        'pagination' => [
                            'pageSize' => 40,
                        ],
                        'sort' => [
                            'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                            'attributes' => ['firstname', 'lastname'],
                        ]
                ]);
                /***************************************************************************************************************/
                
                /*************************************Prepares data for 'a_f' tab **********************************************/
                foreach ($registrations as $registration)
                {
                    $user = $registration->getPerson()->one();
                    if ($user)
                    {                 
                        $personid = $registration->personid;
                        $student = Student::getStudent($personid);
                        
                        //inspects surname for filtering
                        $surname = $student->lastname;
                        $first_character = substr($surname,0,1);
                                
                        if ($student==true)
                        {
                            if (strcmp($first_character,"A")==0 || strcmp($first_character,"a")==0 || strcmp($first_character,"B")==0 || strcmp($first_character,"b")==0 || strcmp($first_character,"C")==0 || strcmp($first_character,"c")==0  || strcmp($first_character,"D")==0 || strcmp($first_character,"d")==0 || strcmp($first_character,"E")==0 || strcmp($first_character,"e")==0 || strcmp($first_character,"F")==0 || strcmp($first_character,"f")==0)
                            {
                                $a_f_info['personid'] = $user->personid;
                                $a_f_info['studentregistrationid'] = $registration->studentregistrationid;
                                $a_f_info['studentno'] = $user->username;
                                $a_f_info['firstname'] = $student->firstname;
                                $a_f_info['middlename'] = $student->middlename;
                                $a_f_info['lastname'] = $student->lastname;
                                $a_f_info['gender'] = $student->gender;

                                $student_status = StudentStatus::find()
                                                ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                                ->one();
                                $a_f_info['studentstatus'] = $student_status->name;
                                $a_f_data_container[] = $a_f_info;
                            }
                        }
                        else
                        {
                            Yii::$app->session->setFlash('error', 'Student not found');
                        }
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'User not found');
                    }                    
                } 
                $a_f_provider = new ArrayDataProvider([
                        'allModels' => $a_f_data_container,
                        'pagination' => [
                            'pageSize' => 40,
                        ],
                        'sort' => [
                            'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                            'attributes' => ['firstname', 'lastname'],
                        ]
                ]);
                /***************************************************************************************************************/

                /*************************************Prepares data for 'g_l' tab **********************************************/
                foreach ($registrations as $registration)
                {
                    $user = $registration->getPerson()->one();
                    if ($user)
                    {                 
                        $personid = $registration->personid;
                        $student = Student::getStudent($personid);
                        
                        //inspects surname for filtering
                        $surname = $student->lastname;
                        $first_character = substr($surname,0,1);
                                
                        if ($student==true)
                        {
                            if (strcmp($first_character,"G")==0 || strcmp($first_character,"g")==0 || strcmp($first_character,"H")==0 || strcmp($first_character,"h")==0 || strcmp($first_character,"I")==0 || strcmp($first_character,"i")==0  || strcmp($first_character,"J")==0 || strcmp($first_character,"j")==0 || strcmp($first_character,"K")==0 || strcmp($first_character,"k")==0 || strcmp($first_character,"L")==0 || strcmp($first_character,"l")==0)
                            {
                                $g_l_info['personid'] = $user->personid;
                                $g_l_info['studentregistrationid'] = $registration->studentregistrationid;
                                $g_l_info['studentno'] = $user->username;
                                $g_l_info['firstname'] = $student->firstname;
                                $g_l_info['middlename'] = $student->middlename;
                                $g_l_info['lastname'] = $student->lastname;
                                $g_l_info['gender'] = $student->gender;

                                $student_status = StudentStatus::find()
                                                ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                                ->one();
                                $g_l_info['studentstatus'] = $student_status->name;
                                $g_l_data_container[] = $g_l_info;
                            }
                        }
                        else
                        {
                            Yii::$app->session->setFlash('error', 'Student not found');
                        }
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'User not found');
                    }                    
                } 
                $g_l_provider = new ArrayDataProvider([
                        'allModels' => $g_l_data_container,
                        'pagination' => [
                            'pageSize' => 25,
                        ],
                        'sort' => [
                            'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                            'attributes' => ['firstname', 'lastname'],
                        ]
                ]);
                /***************************************************************************************************************/ 
                          
                /*************************************Prepares data for 'm_r' tab **********************************************/
                foreach ($registrations as $registration)
                {
                    $user = $registration->getPerson()->one();
                    if ($user)
                    {                 
                        $personid = $registration->personid;
                        $student = Student::getStudent($personid);
                        
                        //inspects surname for filtering
                        $surname = $student->lastname;
                        $first_character = substr($surname,0,1);
                                
                        if ($student==true)
                        {
                            if (strcmp($first_character,"M")==0 || strcmp($first_character,"m")==0 || strcmp($first_character,"N")==0 || strcmp($first_character,"n")==0 || strcmp($first_character,"O")==0 || strcmp($first_character,"o")==0  || strcmp($first_character,"P")==0 || strcmp($first_character,"p")==0 || strcmp($first_character,"Q")==0 || strcmp($first_character,"q")==0 || strcmp($first_character,"R")==0 || strcmp($first_character,"r")==0)
                            {
                                $m_r_info['personid'] = $user->personid;
                                $m_r_info['studentregistrationid'] = $registration->studentregistrationid;
                                $m_r_info['studentno'] = $user->username;
                                $m_r_info['firstname'] = $student->firstname;
                                $m_r_info['middlename'] = $student->middlename;
                                $m_r_info['lastname'] = $student->lastname;
                                $m_r_info['gender'] = $student->gender;

                                $student_status = StudentStatus::find()
                                                ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                                ->one();
                                $m_r_info['studentstatus'] = $student_status->name;
                                $m_r_data_container[] = $m_r_info;                       
                            }
                        }
                        else
                        {
                            Yii::$app->session->setFlash('error', 'Student not found');
                        }
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'User not found');
                    }                    
                } 
                $m_r_provider = new ArrayDataProvider([
                        'allModels' => $m_r_data_container,
                        'pagination' => [
                            'pageSize' => 25,
                        ],
                        'sort' => [
                            'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                            'attributes' => ['firstname', 'lastname'],
                        ]
                ]);
                /***************************************************************************************************************/
                  
                /*************************************Prepares data for 's_z' tab **********************************************/
                foreach ($registrations as $registration)
                {
                    $user = $registration->getPerson()->one();
                    if ($user)
                    {                 
                        $personid = $registration->personid;
                        $student = Student::getStudent($personid);
                        
                        //inspects surname for filtering
                        $surname = $student->lastname;
                        $first_character = substr($surname,0,1);
                                
                        if ($student==true)
                        {
                            if (strcmp($first_character,"S")==0 || strcmp($first_character,"s")==0 || strcmp($first_character,"T")==0 || strcmp($first_character,"t")==0  || strcmp($first_character,"U")==0 || strcmp($first_character,"u")==0 || strcmp($first_character,"V")==0 || strcmp($first_character,"v")==0 || strcmp($first_character,"W")==0 || strcmp($first_character,"w")==0 || strcmp($first_character,"X")==0 || strcmp($first_character,"x")==0 || strcmp($first_character,"Y")==0 || strcmp($first_character,"y")==0 || strcmp($first_character,"Z")==0 || strcmp($first_character,"z")==0)
                            {
                                $s_z_info['personid'] = $user->personid;
                                $s_z_info['studentregistrationid'] = $registration->studentregistrationid;
                                $s_z_info['studentno'] = $user->username;
                                $s_z_info['firstname'] = $student->firstname;
                                $s_z_info['middlename'] = $student->middlename;
                                $s_z_info['lastname'] = $student->lastname;
                                $s_z_info['gender'] = $student->gender;

                                $student_status = StudentStatus::find()
                                                ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                                ->one();
                                $s_z_info['studentstatus'] = $student_status->name;
                                $s_z_data_container[] = $s_z_info;
                            }
                        }
                        else
                        {
                            Yii::$app->session->setFlash('error', 'Student5 not found');
                        }
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'User not found');
                    }                    
                } 
                $s_z_provider = new ArrayDataProvider([
                        'allModels' => $s_z_data_container,
                        'pagination' => [
                            'pageSize' => 25,
                        ],
                        'sort' => [
                            'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                            'attributes' => ['firstname', 'lastname'],
                        ]
                ]); 
                /***************************************************************************************************************/
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Registration records not found');
                    
            }
            
            //if user is permitted to access student_listing view
            if (Yii::$app->user->can('accessStudentListing') == true)
            {
                return $this->render('student_listing', [
                    'division_id' => $divisionid,
                    'programmename' => $programmename,
                    'academicyear' => $academicyear,
                    'cordinator_details' => $cordinator_details,
                    'all_students_provider' => $all_students_provider,
                    'a_f_provider' => $a_f_provider,
                    'g_l_provider' => $g_l_provider,
                    'm_r_provider' => $m_r_provider,
                    's_z_provider' => $s_z_provider,      
                ]);
            }
        }
              
        
        /**
         * Renders the Gradebook 'student_transcript' view 
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 10/12/2015
         * Date Last Modified: 10/12/2015
         */
        public function actionTranscript($personid, $studentregistrationid)
        {
            $is_cape = StudentRegistration::isCape($studentregistrationid);
            $person = User::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $studentregistration = StudentRegistration::find()
                    ->where(['studentregistrationid' => $studentregistrationid,  'isdeleted' => 0])
                    ->one();
            $student = Student::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $applicant = Applicant::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            
            if ($person == true  &&  $studentregistration == true  &&  $applicant == true)
            {
                $academicofferingid = $studentregistration->academicofferingid;
                $academic_offering = AcademicOffering::find()
                                    ->where(['academicofferingid' => $academicofferingid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->one();
                $academicyearid = $academic_offering->academicyearid;
                $programme_catalog = ProgrammeCatalog::find()
                                    ->where(['programmecatalogid' => $academic_offering->programmecatalogid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->one();
                $programmename = $programme_catalog->name;
                
                //generates programme description
                if($is_cape == true)
                {
                    $programme_description = ApplicationCapesubject::getCapeSubjectListing($studentregistrationid);
                }
                else
                {
                    $qualification = QualificationType::find()
                            ->where(['qualificationtypeid' => $programme_catalog->qualificationtypeid])
                            ->one();
                    $programme_description =  $qualification->abbreviation . " " .  $programme_catalog->name . " " . $programme_catalog->specialisation;
                
                }
                
                $department = Department::find()
                            ->where(['departmentid' => $programme_catalog->departmentid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                $divisionid = $department->divisionid; 
                
                $enrollments = StudentRegistration::find()
                    ->where(['personid' => $personid, 'isdeleted' => 0])
                    ->all();
                
                //if CAPE registration programme and user has authorization to access transcript view
                if ($is_cape == true && Yii::$app->user->can('accessTranscript') == true)      
                {
                                  
                    
                    return $this->render('cape_transcript', [
                        'person' => $person,
                        'studentregistration' => $studentregistration,
                        'applicant' => $applicant, 
                        'student' => $student,
                        'academicyearid' =>$academicyearid, 
                        'academicofferingid' => $academicofferingid, 
                        'programmename' => $programmename, 
                        'divisionid' => $divisionid,
                        'programme_description' =>$programme_description,
                        'enrollments' => $enrollments,
                    ]);
                }
                
                //if assoiate programme registration and user has authorization to access transcript view
                elseif ($is_cape == false && Yii::$app->user->can('accessTranscript') == true)      
                {
                    $cumulative_gpa = StudentRegistration::calculateCumulativeGPA($studentregistrationid);
                    $academic_status = StudentRegistration::getUpdatedAcademicStatus($studentregistrationid);
                    
                    return $this->render('associate_transcript', [
                        'person' => $person,
                        'studentregistration' => $studentregistration,
                        'applicant' => $applicant,
                        'student' => $student,
                        'academicyearid' =>$academicyearid, 
                        'academicofferingid' => $academicofferingid, 
                        'programmename' => $programmename, 
                        'divisionid' => $divisionid,
                        'cumulative_gpa' => $cumulative_gpa,
                        'programme_description' =>$programme_description,
                        'academic_status' => $academic_status,
                        'enrollments' => $enrollments,
                    ]);
                }
            }      
        }
        
        
        /**
         * Renders the Gradebook 'course_detail' view 
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 12/12/2015
         * Date Last Modified: 12/12/2015
         */
        public function actionAssessments($iscape, $batchid, $studentregistrationid, $code, $name)
        {
            /********************* Needed to facilitate breadcrumb functionality ************************/
            $studentregistration = StudentRegistration::find()
                    ->where(['studentregistrationid' => $studentregistrationid, 'isdeleted' => 0])
                    ->one();
            $personid = $studentregistration->personid;
            $academicofferingid = $studentregistration->academicofferingid;
            $academic_offering = AcademicOffering::find()
                                ->where(['academicofferingid' => $academicofferingid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();
            $academicyearid = $academic_offering->academicyearid;
            $programme_catalog = ProgrammeCatalog::find()
                                ->where(['programmecatalogid' => $academic_offering->programmecatalogid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();
            $programmename = $programme_catalog->name;
            $department = Department::find()
                        ->where(['departmentid' => $programme_catalog->departmentid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            $divisionid = $department->divisionid; 
            
            /*********************** Needed to generate general student information ************************/
            $person = User::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $student = Student::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $applicant = Applicant::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            
            
            if (Yii::$app->user->can('accessCourseDetails') == true)      //if user has access to see course assessment summary
            {
                if ($iscape == 1)   //if student is doing CAPE programme
                {
                    $assessment_results = AssessmentCape::getAssessmentReport($batchid, $studentregistrationid);

                    return $this->render('course_detail', [
                        'iscape' => $iscape,

                        'code' => $code,
                        'name' => $name,

                        'person' => $person,
                        'student' => $student,
                        'applicant' => $applicant,
                        'studentregistration' => $studentregistration,

                        'personid' => $personid,
                        'studentregistrationid' => $studentregistrationid,

                        'academicyearid' => $academicyearid, 
                        'academicofferingid' => $academicofferingid,
                        'programmename' => $programmename, 
                        'divisionid' => $divisionid,

                        'assessments' => $assessment_results
                    ]);                   
                }
                elseif ($iscape == 0)   //if student is not doing CAPE programme
                {
                    $cumulative_gpa = 0.0;

                    $assessment_results = Assessment::getAssessmentReport($batchid, $studentregistrationid);

                    $course_details = Batch::getCourseDetails($batchid);

                    return $this->render('course_detail', [
                        'iscape' => $iscape,

                        'code' => $code,
                        'name' => $name,
                        'course_details' => $course_details,

                        'person' => $person,
                        'student' => $student,
                        'applicant' => $applicant,
                        'studentregistration' => $studentregistration,
    //                        'cumulative_gpa' => $cumulative_gpa,

                        'personid' => $personid,
                        'studentregistrationid' => $studentregistrationid, 

                        'academicyearid' => $academicyearid, 
                        'academicofferingid' => $academicofferingid,
                        'programmename' => $programmename, 
                        'divisionid' => $divisionid, 

                        'assessments' => $assessment_results
                    ]);
                }
            }
        }  
        
        
        /**
         * Renders the Gradebook 'assessment_edit' view 
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 12/12/2015
         * Date Last Modified: 15/12/2015
         */
        public function actionEditAssessments($studentregistrationid, $assessmentid, $code, $name)
        {

            $db = Yii::$app->db;
            $is_cape = StudentRegistration::isCape($studentregistrationid);
            $batch = NULL;
            $assessment_edittable = NULL;
            $assessment_non_edittable = NULL;

            //if assessment is related to a CAPE programme
            if ($is_cape == true)   
            { 
                $assessment = AssessmentCape::find()
                            ->where(['assessmentcapeid' => $assessmentid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                if ($assessment)
                {
                    $assessment_edittable = AssessmentStudentCape::find()
                                ->where(['studentregistrationid' => $studentregistrationid, 'assessmentcapeid' => $assessmentid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();

                    $assessment_non_edittable = AssessmentCape::getAssessmentDetails($assessmentid, $studentregistrationid);           
                }
            }


            //if assessment is related to an associate programme
            else                    
            {
                $assessment = Assessment::find()
                            ->where(['assessmentid' => $assessmentid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                if ($assessment)
                {
                    $assessment_edittable = AssessmentStudent::find()
                                ->where(['studentregistrationid' => $studentregistrationid, 'assessmentid' => $assessmentid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();
                    $assessment_non_edittable = Assessment::getAssessmentDetails($assessmentid, $studentregistrationid);
                }
            }

            
            
            if ($post_data = Yii::$app->request->post())
            {
                $mark_load_flag = false;
                $mark_validation_flag = false;
                $mark_save_flag = false;
                
                $mark_load_flag = $assessment_edittable->load($post_data); 
                if ($mark_load_flag == true)
                {
                    $mark_validation_flag = $assessment_edittable->validate();
                    if ($mark_validation_flag == true)
                    {   
                        //Ensures valid assessment mark is entered
                        if ($assessment_edittable->marksattained >= 0 && $assessment_edittable->marksattained <= $assessment_non_edittable["total_marks"])
                        {
                       
                            $mark_save_flag = $assessment_edittable->save();             
                            if ($mark_save_flag == true)        //if Phone model save operation succeeds 
                            {   
                                //facilitates the rendering of the 'assessment' view

                                if ($is_cape == true)
                                {
                                    $iscape = 1;
                                    $batchid = $assessment->batchcapeid;
                                }
                                else
                                {
                                    $iscape = 0;
                                    $batchid = $assessment->batchid;
                                }

                                return $this->redirect(['assessments',
                                                        'iscape' => $iscape, 
                                                        'batchid' => $batchid, 
                                                        'studentregistrationid' => $studentregistrationid,                     
                                                        'code' => $code, 
                                                        'name' => $name
                                                ]);
                            }
                        }
                        else
                            Yii::$app->getSession()->setFlash('error', 'Invalid assessment mark has been entered.');
                    }
                }
            }
            
            
            if (Yii::$app->user->can('editAssessment') == true)      //if user has access to edit an assessment
            {
                //if assessment record found
                if ($assessment_edittable && $assessment_non_edittable)     
                {
                    return $this->render('edit_assessment', [
                            'edittable' => $assessment_edittable,
                            'non_edittable' => $assessment_non_edittable,
                            'code' => $code, 
                            'name' => $name
                        ]);
                }
            }
            
        }
        
        
        /**
         * Redirects user back to 'assessment' view from the 'edit_assessment' view
         * 
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 18/12/2015
         * Date Last Modified: 18/12/2015
         */
        public function actionEditAssessmentsCancel($studentregistrationid, $code, $name, $batchid)
        {        
            $iscape = StudentRegistration::isCape($studentregistrationid);
  
            return $this->redirect(['assessments',
                                    'iscape' => $iscape,
                                    'batchid' => $batchid,
                                    'studentregistrationid' => $studentregistrationid,
                                    'code' => $code,
                                    'name' => $name,
                            ]);
            
        }
        
        
        /**
         * Redirects user back to 'transcript' view from the 'edit_transcript' view
         * 
         * @param type $batchid
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 18/12/2015
         * Date Last Modified: 18/12/2015
         */
        public function actionEditTranscriptCancel($studentregistrationid)
        {
            $studentregistration = StudentRegistration::find()
                    ->where(['studentregistrationid' => $studentregistrationid, 'isdeleted' => 0])
                    ->one();
            return $this->redirect(['transcript',
                                    'personid' => $studentregistration->personid,
                                    'studentregistrationid' => $studentregistrationid,
                            ]);
        }
    
        /**
         * Renders the Gradebook 'edit_transcript' view 
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 15/12/2015
         * Date Last Modified: 16/12/2015
         */
        public function actionEditTranscript($batchid, $studentregistrationid)
        {
            //Coursework flags
            $course_load_flag = false;
            $course_validation_flag = false;
            $course_save_flag = false;
            
            $course_record = NULL;
            $course_summary = NULL;
            
            $is_cape = StudentRegistration::isCape($studentregistrationid);
            
            if ($is_cape == true)
            {
                $course_record = BatchStudentCape::find()
                        ->where(['batchcapeid' => $batchid, 'studentregistrationid' => $studentregistrationid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $course_summary = BatchStudentCape::getCourseRecord($studentregistrationid, $batchid);
            }
            else
            {
                $course_record = BatchStudent::find()
                            ->where(['batchid' => $batchid, 'studentregistrationid' => $studentregistrationid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
            
                $course_summary = BatchStudent::getCourseRecord($studentregistrationid, $batchid);
            }
            
           
            if ($post_data = Yii::$app->request->post())
            {
                $course_load_flag = $course_record->load($post_data);
                if ($course_load_flag == true)
                {
                    $course_validation_flag = $course_record->validate();
                    if ($course_validation_flag == true)
                    {
                        //Ensures negatvie marks can't be entered 
                        if ($course_record->courseworktotal >= 0 && $course_record->examtotal >= 0)
                        {
                            //Ensure cw and exam mark does not exceed weighting requirements
                            if ($course_record->courseworktotal <= $course_summary["courseworkweight"]  && $course_record->examtotal <= $course_summary["examweight"])
                            {
                                //Ensures change in cw or exam mark add up to 100%
                                $total = $course_record->courseworktotal + $course_record->examtotal; 
                                if($total < 100)
                                {
                                    $course_record->final = $total;         //recalculates grade total
                                    
                                    if ($is_cape == false)
                                    {
                                        //update 'grade' and 'gradepoint' fields
                                        if($total>=90 && $total <=100)
                                        {
                                            $course_record->grade = "A+";
                                            $course_record->gradepoints = 4.0;
                                        }
                                        elseif($total>=85 && $total <=89)
                                        {
                                            $course_record->grade = "A";
                                            $course_record->gradepoints = 3.75;
                                        }
                                        elseif($total>=80 && $total <=84)
                                        {
                                            $course_record->grade = "A-";
                                            $course_record->gradepoints = 3.5;
                                        }
                                        elseif($total>=75 && $total <=79)
                                        {
                                            $course_record->grade = "B+";
                                            $course_record->gradepoints = 3.25;
                                        }
                                        elseif($total>=70 && $total <=74)
                                        {
                                            $course_record->grade = "B";
                                            $course_record->gradepoints = 3.0;
                                        }
                                        elseif($total>=65 && $total <=69)
                                        {
                                            $course_record->grade = "B-";
                                            $course_record->gradepoints = 2.75;
                                        }
                                        elseif($total>=60 && $total <=64)
                                        {
                                            $course_record->grade = "C+";
                                            $course_record->gradepoints = 2.5;
                                        }
                                        elseif($total>=55 && $total <=59)
                                        {
                                            $course_record->grade = "C";
                                            $course_record->gradepoints = 2.25;
                                        }
                                        elseif($total>=50 && $total <=54)
                                        {
                                            $course_record->grade = "C-";
                                            $course_record->gradepoints = 2.0;
                                        }
                                        elseif($total>=40 && $total <=49)
                                        {
                                            $course_record->grade = "D";
                                            $course_record->gradepoints = 1.0;
                                        }
                                        elseif($total>=0 && $total <=39)
                                        {
                                            $course_record->grade = "F";
                                            $course_record->gradepoints = 0;
                                        }

                                        //Makes adjustments to 'grade' displayed based on a course's pass/fail status
                                        if (strcmp($course_record->grade,"F") != 0 && $course_summary["passfailtypeid"] != 1)
                                            $course_record->grade = "P";

                                        $coursework_pass_mark = ($course_summary["passmark"]/100)* $course_summary["courseworkweight"];
                                        $exam_pass_mark = ($course_summary["passmark"]/100)* $course_summary["examweight"];

                                        $pass_coursework = NULL;
                                        $pass_exam = NULL;
                                        $pass_overall = NULL;

                                        //Assignes incomplete "INC" grade to specialization courses
                                        if ($course_summary["coursetypeid"] == 3 )
                                        {
                                            //if stuent pases overall
                                            if($total >= $course_summary["passmark"])       //if passed overall
                                                $pass_overall = true;
                                            else
                                                $pass_overall = false;

                                            //if coursework mark >= passmark of that component
                                            if ($course_record->courseworktotal >= $coursework_pass_mark)
                                                $pass_coursework = true;
                                            else 
                                                $pass_coursework = false;

                                            //if exam mark >= passmark of that component
                                            if ($course_record->examtotal >= $exam_pass_mark)
                                                $pass_exam = true;
                                            else 
                                                $pass_exam = false;

                                            //if student failed either compnonent grade shall be set to "INC"
                                            if ($pass_coursework == false || $pass_exam == false)
                                                $course_record->grade = "INC";
                                        }

                                        /*************** Updates course status to appropriate value *****************/
                                        if ($course_summary["passcriteriaid"] == 1)         //if student must pass overall
                                        {
                                            if($pass_overall == true)       //if passed overall
                                                $course_record->coursestatusid = 1;
                                            elseif($pass_coursework == false  && $pass_exam == false)
                                                $course_record->coursestatusid = 2;
                                            elseif($pass_coursework ==true  && $pass_exam == false)
                                                $course_record->coursestatusid = 3;
                                            elseif($pass_coursework == false  && $pass_exam == true)
                                                $course_record->coursestatusid = 4;
                                        }

                                        elseif ($course_summary["passcriteriaid"] == 2)     //if student must pass coursework and pass overall
                                        {
                                            if ($pass_coursework == true && $pass_overall == true)
                                                $course_record->coursestatusid = 1;
                                            elseif($pass_coursework == false  && $pass_exam == false)
                                                $course_record->coursestatusid = 2;
                                            elseif($pass_coursework == true  && $pass_exam == false)
                                                $course_record->coursestatusid = 2;
                                            elseif($pass_coursework == false  && $pass_exam == true)
                                                $course_record->coursestatusid = 2;
                                        }

                                        elseif ($course_summary["passcriteriaid"] == 3)     //if student must pass exam and pass overall
                                        {
                                            if ($pass_exam == true && $pass_overall == true)
                                                $course_record->coursestatusid = 1;
                                            elseif($pass_coursework == false  && $pass_exam == false)
                                                $course_record->coursestatusid = 2;
                                            elseif($pass_coursework == true  && $pass_exam == false)
                                                $course_record->coursestatusid = 2;
                                            elseif($pass_coursework == false  && $pass_exam == true)
                                                $course_record->coursestatusid = 2;
                                        }

                                        elseif ($course_summary["passcriteriaid"] == 4)     //if student must pass coursework and pass overall
                                        {
                                            if ($pass_coursework == true && $pass_exam == true)
                                                $course_record->coursestatusid = 1;
                                            elseif($pass_coursework == false  && $pass_exam == false)
                                                $course_record->coursestatusid = 2;
                                            elseif($pass_coursework ==true  && $pass_exam == false)
                                                $course_record->coursestatusid = 3;
                                            elseif($pass_coursework == false  && $pass_exam == true)
                                                $course_record->coursestatusid = 4;
                                        }

                                        elseif ($course_summary["passcriteriaid"] == 5)     //if student must pass each assessment
                                        {

                                        }
                                    }
                                    
                                    $course_save_flag = $course_record->save();
                                    if ($course_save_flag == true)
                                    {
                                        $studentregistration = StudentRegistration::find()
                                                            ->where(['studentregistrationid' => $studentregistrationid, 'isdeleted' => 0])
                                                            ->one();
                                        if ($studentregistration)
                                        {
                                            return $this->redirect(['transcript',
                                                                'personid' => $studentregistration->personid, 
                                                                'studentregistrationid' => $studentregistrationid,                     
                                                        ]);
                                        }
                                    }
                                }
                                else
                                     Yii::$app->getSession()->setFlash('error', 'Please review the grades you have just entered. Final mark currently exceeds 100.');
                            }
                            else
                                Yii::$app->getSession()->setFlash('error', 'Please review the grades you have just entered. Grades can not exceed the stipulated component weightnings.');
                        }
                        else
                             Yii::$app->getSession()->setFlash('error', 'Invalid coursework or exam mark entered.');
                    }
                }
            }
            
            
            if ($is_cape == true  && $course_record == true && $course_summary == true)
            {
                return $this->render('edit_cape_transcript', [
                        'course_record' => $course_record,
                        'course_summary' => $course_summary,
                ]);
            }
            if ($is_cape == false  && $course_record == true && $course_summary == true)
            {
                return $this->render('edit_associate_transcript', [
                        'course_record' => $course_record,
                        'course_summary' => $course_summary,
                ]);
            }            
        }
        
        
    }

