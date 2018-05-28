<?php

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
    use frontend\models\LegacySubject;
    use frontend\models\LegacySubjectType;
    use frontend\models\LegacyTerm;
    
    
    class StudentController extends Controller
    {
        /**
         * Performs search for 'Legacy' student
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 09/07/2016
         *  Date Last Modified: 09/07/2016  | 22/03/2017
         */
        public function actionFindAStudent()
        {
            if (Yii::$app->user->can('viewLegacyStudents') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
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
        
              
        /**
         * Renders screen for user to select mode of student record entry
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 06/07/2016
         * Date Last Modified: 06/07/2016  | 22/03/2017
         * 
         */
        public function actionChooseCreate()
        {
            if (Yii::$app->user->can('manageLegacyStudents') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
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
         * Date Last Modified: 08/07/2016  | 22/03/2017
         */
        public function actionCreateSingleStudent()
        {
            if (Yii::$app->user->can('manageLegacyStudents') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
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
                    $student->fullname = $student->title . ". " . $student->firstname . " " . $student->lastname;
                    $student->createdby = $employeeid;
                    $student->datecreated = $date;
                    $student->lastmodifiedby =$employeeid ;
                    $student->datemodified = $date;
                    $save_flag = $student->save();
                    if($save_flag == true)
                    {
                        return self::actionFindAStudent();
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
         * Date Last Modified: 08/07/2016  | 22/03/2017
         */
        public function actionGenerateBatchForm()
        {
            if (Yii::$app->user->can('manageLegacyStudents') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
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
         * Date Last Modified: 09/07/2016  | 22/03/2017
         */
        public function actionCreateMultipleStudents($record_count)
        {
            if (Yii::$app->user->can('manageLegacyStudents') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
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
                                $student->fullname = $student->title . ". " . $student->firstname . " " . $student->lastname;
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
            return self::actionFindAStudent();
        }
        
        
        /**
         * Updates a student record
         * 
         * @param type $id
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 09/07/2016
         * Date Last Modified: 09/07/2016  | 22/03/2017
         */
        public function actionUpdateStudent($id)
        {
            if (Yii::$app->user->can('manageLegacyStudents') == false)
            {
                 Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                 return $this->redirect(['/site/index']);
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
            
            return $this->render('update_student', ['student' => $student]);
        }
        
        
        /**
         * Soft deletes a student record
         * 
         * @param type $id
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 09/07/2016
         * Date Last Modified: 09/07/2016 | 22/03/2017
         */
        public function actionDeleteStudent($id)
        {
            if (Yii::$app->user->can('manageLegacyStudents') == false)
            {
                 Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                 return $this->redirect(['/site/index']);
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
        
        
        /**
         * Soft student Transcript
         * 
         * @param type $id
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 09/07/2016
         * Date Last Modified: 09/07/2016 | 22/03/2017
         */
        public function actionView($id)
        {
            if (Yii::$app->user->can('viewLegacyStudents') == false)
            {
                 Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                 return $this->redirect(['/site/index']);
            }
            
            $student = LegacyStudent::find()
                    ->where(['legacystudentid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            
            $admission_year = LegacyYear::find()
                    ->where(['legacyyearid' => $student->legacyyearid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                    ->name;
            
            $faculty = LegacyFaculty::find()
                    ->where(['legacyfacultyid' => $student->legacyfacultyid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                    ->name;
                     
            $academic_years = LegacyYear::find()
                    ->innerJoin('legacy_term', '`legacy_year`.`legacyyearid` = `legacy_term`.`legacyyearid`')
                    ->innerJoin('legacy_batch', '`legacy_term`.`legacytermid` = `legacy_batch`.`legacytermid`')
                    ->innerJoin('legacy_marksheet', '`legacy_batch`.`legacybatchid` = `legacy_marksheet`.`legacybatchid`')
                    ->where(['legacy_year.isactive' => 1,  'legacy_year.isdeleted' => 0,
                                    'legacy_term.isactive' => 1,  'legacy_term.isdeleted' => 0,
                                    'legacy_batch.isactive' => 1,  'legacy_batch.isdeleted' => 0,
                                    'legacy_marksheet.legacystudentid'=> $id,  'legacy_marksheet.isactive' => 1,  'legacy_marksheet.isdeleted' => 0
                                ])
                    ->all();
            
            $years_container = array();
            $academic_year_info_keys = array();
            $academic_year_info_values = array();
            array_push($academic_year_info_keys, "name");
            array_push($academic_year_info_keys, "details");
            
            foreach($academic_years as $year)
            {
                $academic_year_info = array();
                $academic_year_combined = array();
                array_push($academic_year_info_values, $year->name);
                
                $terms_container = array();
                $term_info_keys = array();
                $term_info_values = array();
                array_push($term_info_keys, "name");
                array_push($term_info_keys, "details");
                $terms = LegacyTerm::find()
                    ->where(['legacyyearid' => $year->legacyyearid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
                
                foreach($terms as $term)
                {
                    $term_info = array();
                    $term_combined = array();
                    array_push($term_info_values, $term->name);
                    
                    $subjects = LegacySubject::find()
                        ->innerJoin('legacy_batch', '`legacy_subject`.`legacysubjectid` = `legacy_batch`.`legacysubjectid`')
                        ->innerJoin('legacy_term', '`legacy_batch`.`legacytermid` = `legacy_term`.`legacytermid`')
                        ->where(['legacy_subject.isactive' => 1,  'legacy_subject.isdeleted' => 0,
                                        'legacy_batch.isactive' => 1,  'legacy_batch.isdeleted' => 0,
                                        'legacy_term.legacytermid' => $term->legacytermid,  'legacy_term.isactive' => 1,  'legacy_term.isdeleted' => 0
                                    ])
                        ->all(); 
                    
                     $subjects_container = array();
                     $subject_info_keys = array();
                     $subject_info_values = array();
                     array_push($subject_info_keys, "name");
                     array_push($subject_info_keys, "details");
                    
                     foreach($subjects as $subject)
                     {
                         $subject_info = array();
                         $subject_type = LegacySubjectType::find()
                                 ->where(['legacysubjecttypeid' => $subject->legacysubjecttypeid, 'isactive' => 1, 'isdeleted' => 0])
                                 ->one()
                                 ->name;
                         $subject_name = $subject_type . " - " . $subject->name;
                         array_push($subject_info_values, $subject_name);
                         
                         $batch = LegacyBatch::find()
                                  ->where(['legacytermid' => $term->legacytermid, 'legacysubjectid' => $subject->legacysubjectid, 'isactive' => 1, 'isdeleted' => 0])
                                  ->one();
                         $keys = array();
                         $values = array();
                         array_push($keys, "batchid");
                         array_push($keys, "term");
                         array_push($keys, "exam");
                         array_push($keys, "final");
                         
                         $mark = LegacyMarksheet::find()
                                     ->where(['legacybatchid' => $batch->legacybatchid,  'isactive' => 1, 'isdeleted' => 0])
                                     ->one();
                         if ($mark == true)
                         {
                            $term_mark = $mark->term == true? $mark->term : "--";
                            $exam_mark = $mark->exam == true ? $mark->exam : "--";
                            $final_mark = $mark->final == true ? $mark->final : "--";
                         }
                         else
                         {
                             $term_mark = "--";
                             $exam_mark = "--";
                             $final_mark = "--";
                         }
                        
                         array_push($values, $batch->legacybatchid);
                         array_push($values, $term_mark);
                         array_push($values, $exam_mark);
                         array_push($values, $final_mark);
                         
                         $combined = array_combine($keys, $values);
                         array_push($subject_info_values, $combined);
                         
                         $subject_info = array_combine($subject_info_keys, $subject_info_values);
                         array_push($subjects_container, $subject_info);
                         
                         $keys = NULL;
                         $values = NULL;
                         $combined = NULL;
                         $subject_info = NULL;
                         $subject_info_keys = NULL;
                         $subject_info_values = NULL;
                     }
                     
                     array_push($term_info_values, $subjects_container);
                     $term_combined = array_combine($term_info_keys, $term_info_values);
                     array_push($terms_container, $term_combined);
                }
                array_push($academic_year_info_values, $terms_container);
                $academic_year_combined = array_combine($academic_year_info_keys, $academic_year_info_values);
                array_push($years_container, $academic_year_combined);
            }

            return $this->render('view_student',
                    [
                        'student' => $student,
                        'admission_year' => $admission_year,
                        'faculty' => $faculty,
                        'records' => $years_container,
                    ]);
        }   
        
        
    }