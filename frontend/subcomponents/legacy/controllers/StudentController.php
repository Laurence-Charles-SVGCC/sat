<?php

/* 
 * To change this license header&& choose License Headers in Project Properties.
 * To change this template file&& choose Tools | Templates
 * and open the template in the editor.
 */

    namespace app\subcomponents\legacy\controllers;
    
    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\data\ArrayDataProvider;
    use yii\base\Model;

    use frontend\models\LegacyStudent;
    use frontend\models\LegacyFaculty;
    use frontend\models\LegacyYear;
    use frontend\models\LegacyBatch;
    use frontend\models\LegacyMarksheet;
    
    
    class StudentController extends Controller
    {

        /**
         * Renders the student listing view
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 06/07/2016
         * Date Last Modified: 06/07/2016
         */
        public function actionIndex()
        {
            if (false/*Yii::$app->user->can('manageLegacyStudents') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            $dataProvider = NULL;
            $student_container = array();
            $student_info = array();

            $students = LegacyStudent::find()
                    ->where(['isactive' => 1, 'isdeleted' => 0])
                    ->all();

            foreach ($students as $student)
            {
                $student_info['studentid'] = $student->legacystudentid;
                
                $faculty = LegacyFaculty::find()
                        ->where(['legacyfacultyid' => $student->legacyfacultyid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one()
                        ->name;
                $student_info['faculty'] = $faculty;
                
                $fullname = $student->middlename? $student->title . '. ' .  $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname: $student->title . '. ' .  $student->firstname . ' ' . $student->lastname;
                $student_info['fullname'] = $fullname;
                $student_info['dateofbirth'] = $student->dateofbirth;
                $student_info['gender'] = $student->gender;
                $student_info['address'] = $student->address;
                
                $year = LegacyYear::find()
                        ->where(['legacyyearid' => $student->legacyyearid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one()
                        ->name;
                $student_info['admissionyear'] = $year;
                
                $student_container[] = $student_info;
            }

            $dataProvider = new ArrayDataProvider([
                        'allModels' => $student_container,
                        'pagination' => [
                            'pageSize' => 25,
                        ],
                        'sort' => [
                            'defaultOrder' => ['admissionyear' => SORT_ASC],
                            'attributes' => ['admissionyear', 'faculty'],
                        ]
                ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        }
        
        
        /**
         * Renders screen for user to select mode of student record entry
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 06/07/2016
         * Date Last Modified: 06/07/2016
         * 
         */
        public function actionChooseCreate()
        {
            if (false/*Yii::$app->user->can('createLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            return $this->render('choose_creation_mode');
        }
            
            
        /**
         * Renders a single student entry form and processes the entered data.
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 08/07/2016
         * Date Last Modified: 08/07/2016
         */
        public function actionCreateSingleStudent()
        {
            if (false/*Yii::$app->user->can('createLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            $student = new LegacyStudent();
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                
                $load_flag = $student->load($post_data);
                if($load_flag == true)
                {
                    $date = date('Y-m-d');
                    $employeeid = Yii::$app->user->identity->personid;
                    $student->createdby = $employeeid;
                    $student->datecreated = $date;
                    $student->lastmodifiedby =$employeeid ;
                    $student->datemodified = $date;
                    $save_flag = $student->save();
                    if($save_flag == true)
                    {
                        return self::actionIndex();
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured saving student record.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured loading student record.');
                }  
            }
            
            return $this->render('single_student_form',
                    [
                        'student' => $student,
                    ]);
        }
        
        
        /**
         * Generates batch student creation form
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 08/07/2016
         * Date Last Modified: 08/07/2016
         */
        public function actionGenerateBatchForm()
        {
             if (Yii::$app->request->post())
            {
                $request = Yii::$app->request;
                $record_count = $request->post('student-count-field');
                
                $students = array();
                for ($i = 0 ; $i< $record_count; $i++)
                {
                    $student = new LegacyStudent();
                    $students[] = $student;
                }
                
                return $this->render('batch_student_form',
                    [
                        'students' => $students,
                    ]);
            }
        }
        
        /**
         * Saves records entered on batch student entry form
         * 
         * @param type $record_count
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 09/07/2016
         * Date Last Modified: 09/07/2016
         */
        public function actionCreateMultipleStudents($record_count)
        {
            if (false/*Yii::$app->user->can('studentLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $students = array();
                for($i=0; $i<$record_count ; $i++)
                {
                    $student = new LegacyStudent();
                    $students[] = $student;
                }
                
                $load_flag = false;
                $all_saves_successful = true;
                $load_flag = Model::loadMultiple($students, $post_data);
                if($load_flag == true)
                {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try 
                    {
                        foreach($students as $student)
                        {
                            if(LegacyStudent::isDummyRecord($student) == false)
                            {
                                $save_flag = false;
                                $date = date('Y-m-d');
                                $employeeid = Yii::$app->user->identity->personid;
                                $student->createdby = $employeeid;
                                $student->datecreated = $date;
                                $student->lastmodifiedby =$employeeid ;
                                $student->datemodified = $date;
                                $save_flag = $student->save();
                                if($save_flag == false)
                                {
                                    $all_saves_successful = false;
                                    $transaction->rollback();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured when saving records.');
                                    break;
                                }
                            }
                        }
                        
                        if($all_saves_successful ==true)
                            $transaction->commit();
                    } catch (Exception $ex) {
                        Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured loading student record.');
                }
            }
            return self::actionIndex();
        }
        
        
        /**
         * Updates a student record
         * 
         * @param type $id
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 09/07/2016
         * Date Last Modified: 09/07/2016
         */
        public function actionUpdateStudent($id)
        {
            if (false/*Yii::$app->user->can('updateLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            $student = LegacyStudent::find()
                        ->where(['legacystudentid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                
                $load_flag = $student->load($post_data);
                if($load_flag == true)
                {
                    $date = date('Y-m-d');
                    $employeeid = Yii::$app->user->identity->personid;
                    $student->lastmodifiedby =$employeeid ;
                    $student->datemodified = $date;
                    $save_flag = $student->save();
                    if($save_flag == true)
                    {
                        return self::actionIndex();
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured saving student record.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured loading student record.');
                }  
            }
            
            return $this->render('update_student',
                    [
                        'student' => $student,
                    ]);
        }
        
        
        /**
         * Soft deletes a student record
         * 
         * @param type $id
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 09/07/2016
         * Date Last Modified: 09/07/2016
         */
        public function actionDeleteStudent($id)
        {
            if (false/*Yii::$app->user->can('deleteLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $student = LegacyStudent::find()
                        ->where(['legacystudentid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $student->isactive = 0;
                $student->isdeleted = 1;
                $save_flag = $student->save();
                if($save_flag == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when deleting student.');
                }
            }
            return self::actionIndex();
        }
        
        
        public function actionFindAStudent()
        {
            if (false/*Yii::$app->user->can('findLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            $info_string = "";
            $student_container = array();
            $dataProvider = NULL;
            $student_info = array();
            
            if (Yii::$app->request->post())
            {
                $request = Yii::$app->request;
                $firstname = $request->post('fname_field');
                $lastname = $request->post('lname_field');
            
                if($firstname!= false || $lastname != false)
                {
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
                    
                    $cond_arr['isactive'] = 1;
                    $cond_arr['isdeleted'] = 0;

                    $students = LegacyStudent::find()
                            ->where($cond_arr)
                            ->all();

                    if (empty($students))
                    {
                        Yii::$app->getSession()->setFlash('error', 'No students found matching this criteria.');
                    }
                    else
                    {
                        foreach ($students as $student)
                        {
                            $student_info['studentid'] = $student->legacystudentid;

                            $faculty = LegacyFaculty::find()
                                    ->where(['legacyfacultyid' => $student->legacyfacultyid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->one()
                                    ->name;
                            $student_info['faculty'] = $faculty;

                            $student_info['title'] = $student->title;
                            $student_info['firstname'] = $student->firstname;
                            $student_info['middlename'] = $student->middlename;
                            $student_info['lastname'] = $student->lastname;
                            $student_info['dateofbirth'] = $student->dateofbirth;
                            $student_info['gender'] = $student->gender;
                            $student_info['address'] = $student->address;

                            $year = LegacyYear::find()
                                    ->where(['legacyyearid' => $student->legacyyearid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->one()
                                    ->name;
                            $student_info['admissionyear'] = $year;

                            $student_container[] = $student_info;
                        }

                        $dataProvider = new ArrayDataProvider([
                                    'allModels' => $student_container,
                                    'pagination' => [
                                        'pageSize' => 25,
                                    ],
                                    'sort' => [
                                        'defaultOrder' => ['admissionyear' => SORT_ASC],
                                        'attributes' => ['firstname', 'lastname', 'admissionyear', 'faculty']
                                    ]
                            ]);
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
                }
            }
                
            return $this->render('find_a_student',[
                'dataProvider' => $dataProvider,
                'info_string' => $info_string,
            ]);
        }
        
        
        
        public function actionView()
        {
            if (true/*Yii::$app->user->can('viewLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            
            return $this->render('view_student',
                    [
                        
                    ]);
        }
        
        
        /**
         * Preconfigure student to facilitate data entry
         *
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 13/07/2016
         * Date Last Modified: 13/07/2016
         */
        public function actionEnrollStudentsStepOne()
        {
            if (false/*Yii::$app->user->can('enrollLegacyStudents') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if (Yii::$app->request->post()) 
            {
                $request = Yii::$app->request;
                $batchtypeid = $request->post('enroll_batch_type_field');
                $levelid = $request->post('enroll_level_field');
                $subjecttypeid = $request->post('enroll_subject_type_field');
                $subjectid = $request->post('enroll_subject_field');
                $yearid = $request->post('enroll_year_field');
                $termid = $request->post('enroll_term_field');
                $student_count = $request->post('enroll_student_count_field');
                
                if ($batchtypeid && $levelid  &&  $subjecttypeid  && $subjectid  && $yearid  && $termid  && $student_count)
                {
                    $marksheets = array();
                    for($i = 0; $i<$student_count ; $i++)
                    {
                        $marksheet = new LegacyMarksheet();
                        $marksheets[] = $marksheet;
                    }
                    
                    $batch = LegacyBatch::find()
                            ->where(['legacybatchtypeid' => $batchtypeid , 'legacylevelid' => $levelid  , 'legacysubjectid' => $subjectid, 
                                            'legacytermid' => $termid , 'isactive' => 1 , 'isdeleted' => 0
                                        ])
                            ->one();
                    if($batch == true)
                    {
                    $possible_years = array();
                        $target_year = LegacyYear::find()
                                ->where(['legacyyearid' => $yearid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();
                        $possible_years[] = $yearid;

                        $possible_year = intval(substr($target_year->name, 0, 4));

                        $plus_one = ($possible_year + 1);
                        $plus_one_year = $plus_one . "/" . $plus_one+1;
                        $target_year_plus_one = LegacyYear::find()
                                ->where(['name' => $plus_one_year , 'isactive' => 1 , 'isdeleted' => 0])
                                ->one();
                        if ($target_year_plus_one)
                             $possible_years[] = $target_year_plus_one->legacyyearid;

                        $plus_two = $possible_year + 2;
                        $plus_two_year = $plus_two . "/" . $plus_two+1;
                        $target_year_plus_two = LegacyYear::find()
                                ->where(['name' =>$plus_two_year , 'isactive' => 1 , 'isdeleted' => 0])
                                ->one();
                         if ($target_year_plus_two)
                             $possible_years[] = $target_year_plus_two->legacyyearid;

                        $students = LegacyStudent::find()
                                ->where(['legacyyearid' => $possible_years , 'isactive' => 1 , 'isdeleted' => 0])
                                ->orderBy('lastname ASC')
                                ->all();

                        $keys = array();
                        array_push($keys, 0);

                        $values = array();
                        array_push($values, "Select...");

                        $student_listing = array();

                        foreach ($students as $student) 
                        {
                            $id = strval($student->legacystudentid);
                            array_push($keys, $id);

                            $record = LegacyStudent::find()
                                    ->where(['legacystudentid' =>$student->legacystudentid , 'isactive' => 1 , 'isdeleted' => 0])
                                    ->one();
                            $name = $record->lastname . ", " . $record->firstname . " " . $record->middlename;
                            array_push($values, $name);
                        }

                        $student_listing = array_combine($keys, $values);

                        return $this->render('enroll_student_step_two',
                                [
                                    'marksheets' => $marksheets,
                                    'legacybatchid' => $batch->legacybatchid,
                                    'student_listing' => $student_listing,
                                ]);
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'No batch currently exists matching your entered criteria.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                }
            }
            return $this->render('enroll_student_step_one');
        }
        
        
        /**
         * Creates LegacyMarksheet records
         * 
         * @param type $record_count
         * @param type $batchid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 13/07/2016
         * Date Last Modified: 13/07/2016
         */
        public function actionEnrollStudentsStepTwo($record_count, $batchid)
        {
            if (false/*Yii::$app->user->can('enrollLegacyStudents') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $marksheets = array();
                for($i=0; $i<$record_count ; $i++)
                {
                    $marksheet = new LegacyMarksheet();
                    $marksheets[] = $marksheet;
                }
                
                $load_flag = false;
                $unique_studentids = array();
//                $all_entries_unique = true;
                $all_saves_successful = true;
                $load_flag = Model::loadMultiple($marksheets, $post_data);
                if($load_flag == true)
                {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try 
                    {
                        
                        foreach($marksheets as $marksheet)
                        {
                            //must ensure no duplicate entries from session are saved
                            if(in_array($marksheet->legacystudentid , $unique_studentids) == false)
                            {
                                $unique_studentids[] = $marksheet->legacystudentid;
                            }
                            else
                            {
                                $all_saves_successful = false;
                                $transaction->rollback();
                                Yii::$app->getSession()->setFlash('error', 'You have entered a duplicate enrollment record.');
                                break;
                            }
                            
                            if($marksheet->legacystudentid != 0)
                            {
                                //must ensure marksheet record was not created in previous operation
                                $saved_marksheet_records = LegacyMarksheet::find()
                                        ->where(['legacybatchid' => $batchid, 'isactive' => 1, 'isdeleted' => 0])
                                        ->one();
                                if($saved_marksheet_records == false)
                                {
                                    $save_flag = false;
                                    $date = date('Y-m-d');
                                    $employeeid = Yii::$app->user->identity->personid;

                                    $marksheet->legacybatchid =$batchid;
                                    $marksheet->createdby = $employeeid;
                                    $marksheet->datecreated = $date;
                                    $marksheet->lastmodifiedby =$employeeid ;
                                    $marksheet->datemodified = $date;
                                    $save_flag = $marksheet->save();
                                    if($save_flag == false)
                                    {
                                        $all_saves_successful = false;
                                        $transaction->rollback();
                                        Yii::$app->getSession()->setFlash('error', 'Error occured when saving records.');
                                        break;
                                    }
                                }
                            }
                        }
                        
                        if($all_saves_successful ==true)
                        {
                            $transaction->commit();
                            return self::actionIndex();
                        }
                    } catch (Exception $ex) {
                        Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured loading marksheet record.');
                }
            }
            return $this->redirect(\Yii::$app->request->getReferrer());
         }
        
        
       
        
        
        
        
    }