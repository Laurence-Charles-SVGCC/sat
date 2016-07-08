<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
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
        
        public function actionCreateMultipleStudents()
        {
            if (false/*Yii::$app->user->can('studentLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            $students = array();
            for ($i = 0 ; $i< 25; $i++)
            {
                $student = new LegacyStudent();
                $students[] = $student;
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                
                $load_flag = Model::loadMultiple($students, $post_data);
                if($oad_flag == true)
                {
                    foreach($students as $student)
                    {
                        
                    }
                    $date = date('Y-m-d');
                    $employeeid = Yii::$app->user->identity->personid;
                    $student->createdby = $employeeid;
                    $student->datecreated = $date;
                    $student->lastmodifiedby =$employeeid ;
                    $student->datemodified = $date;
                    $save_flag = $studet->save();
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
            
            return $this->render('batch_student_form',
                    [
                        'students' => $students,
                    ]);
        }
        
        
        public function actionUpdateStudent()
        {
            if (true/*Yii::$app->user->can('updateLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                
            }
            
            return $this->render('update_student',
                    [
                        
                    ]);
        }
        
        
        public function actionDeleteStudent()
        {
            if (true/*Yii::$app->user->can('deleteLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
           
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
                                        'attributes' => ['firstname', 'lastname','admissionyear', 'faculty'],
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
        
        
        public function actionEnrollStudents()
        {
            if (true/*Yii::$app->user->can('enrollLegacyStudents') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                
            }
        
            return $this->render('enroll_students',
                    [
                        
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
        
        
        
        public function actionEnroll()
        {
            if (true/*Yii::$app->user->can('enrollLegacyStudents') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                
            }
            
            return $this->render('enroll',
                    [
                        
                    ]);
        }
        
        
        
        
        
    }