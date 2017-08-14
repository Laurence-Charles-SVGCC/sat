<?php
    namespace backend\controllers;

    use Yii;
    use yii\helpers\Url;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\data\ArrayDataProvider;
    use frontend\models\Transaction;
    
    use common\models\User;
    use backend\models\SignupFullUserForm;
    use backend\models\SignupLecturerForm;
    use frontend\models\Employee;
    use frontend\models\Student;
    use frontend\models\StudentRegistration;
    use frontend\models\Email;
    use frontend\models\EmployeeDepartment;
    

    class UserController extends Controller
    {
        public function behaviors()
        {
            return [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post'],
                    ],
                ],
            ];
        }

        
        public function actionIndex($user_type = NULL)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $dataProvider =array();
            $user_container = array();
            $info_string = "";
            
            
            if (Yii::$app->request->post())
            {
                $request = Yii::$app->request;
                $firstname = $request->post('fname_field');
                $lastname = $request->post('lname_field');
                $username = $request->post('username_field');
                $personid = $request->post('personid_field');
                
                // if user submits empty search criteria all users matching initial search scope (all || employees || students) are returned
                if($firstname == false && $lastname == false && $username == false && $personid == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'No search criteria was entered.');
                    
                    if ($user_type == NULL)
                    {
                        $users = User::getUsers();
                    }
                    elseif ($user_type == 2)
                    {
                        $users = User::getUsers(2);
                    }
                    elseif($user_type == 3)
                    {
                        $users = User::getUsers(3);
                    }
                    
                    if ($user_type == NULL)
                    {
                        $info_string = "All users";
                    }
                    elseif ($user_type == 2)
                    {
                        $info_string = "All Students";
                    }
                    elseif($user_type == 3)
                    {
                        $info_string = "All Employees";
                    }
                }
                // If user submits search criteria that is not empty
                else
                {
                    // Constructs feedback string for search criteria
                    if ($firstname)
                    {
                        $info_string .= " First Name: " . $firstname; 
                    }
                    if ($lastname)
                    {
                        $info_string .= " Last Name: " . $lastname;
                    }
                    if ($username)
                    {
                        $info_string .= " Username: " . $username; 
                    }
                    if ($personid)
                    {
                        $info_string .= " Personid: " . $personid;
                    }
                    
                    
                    $users = array();
                    /*****************************  user search   *****************************/
                    if ($user_type == NULL)
                    {
                        // retreive employees
                        $employee_cond_arr = array();
                        $employee_cond_arr['person.isactive'] = 1;
                        $employee_cond_arr['person.isdeleted'] = 0;
                        $employee_cond_arr['person.persontypeid'] = 3;
                        if ($username)
                        {
                            $employee_cond_arr['person.username'] = $username;
                        }
                        if ($personid)
                        {
                            $employee_cond_arr['person.personid']  = $personid;
                        }
                        
                        if ($firstname)
                        {
                            $employee_cond_arr['employee.firstname'] = $firstname;
                        }
                        if ($lastname)
                        {
                            $employee_cond_arr['employee.lastname'] = $lastname;
                        }
                        $employees = User::find()
                             ->innerJoin('employee' , '`person`.`personid` = `employee`.`personid`')
                             ->where($employee_cond_arr)
                             ->all();
                        if($employees)
                        {
                            $users = array_merge($users, $employees);
                        }
                        
                        
                        // retreive students
                        $student_cond_arr = array();
                        $student_cond_arr['person.isactive'] = 1;
                        $student_cond_arr['person.isdeleted'] = 0;
                        $student_cond_arr['person.persontypeid'] = 2;
                        if ($username)
                        {
                            $student_cond_arr['person.username'] = $username;
                        }
                        if ($personid)
                        {
                            $student_cond_arr['person.personid']  = $personid;
                        }
                        
                        if ($firstname)
                        {
                            $student_cond_arr['student.firstname'] = $firstname;
                        }
                        if ($lastname)
                        {
                            $student_cond_arr['student.lastname'] = $lastname;
                        }
                        $students = User::find()
                             ->innerJoin('student' , '`person`.`personid` = `student`.`personid`')
                             ->where($student_cond_arr)
                             ->all();
                        if($students)
                        {
                            $users = array_merge($users, $students);
                        }
                    }
                    /**********************************************************************/
                    else
                    {
                        $cond_arr = array();
                        $cond_arr['person.isactive'] = 1;
                        $cond_arr['person.isdeleted'] = 0;
                        if ($username)
                        {
                            $cond_arr['person.username'] = $username;
                        }
                        if ($personid)
                        {
                            $cond_arr['person.personid']  = $personid;
                        }
                        $cond_arr['person.persontypeid'] = $user_type;
                         /********************   student search   ************************/
                        if ($user_type == 2)
                        {
                            if ($firstname)
                            {
                                $cond_arr['student.firstname'] = $firstname;
                            }
                            if ($lastname)
                            {
                                $cond_arr['student.lastname'] = $lastname;
                            }
                            $users = User::find()
                                 ->innerJoin('student' , '`person`.`personid` = `student`.`personid`')
                                 ->where($cond_arr)
                                 ->all();
                        }

                        /********************   employee search   ************************/
                        elseif ($user_type == 3)
                        {
                            if ($firstname)
                            {
                                $cond_arr['employee.firstname'] = $firstname;
                            }
                            if ($lastname)
                            {
                                $cond_arr['employee.lastname'] = $lastname;
                            }
                            $users = User::find()
                                 ->innerJoin('employee' , '`person`.`personid` = `employee`.`personid`')
                                 ->where($cond_arr)
                                 ->all();
                        }
                    }
                }
                
                if (empty($users))
                {
                    Yii::$app->getSession()->setFlash('error', 'No users found matching this criteria.');
                }
            }
             // Default search ->  all users matching initial search scope (all || employees || students) are returned
            else           
            {
                if ($user_type == NULL)
                {
                    $users = User::getUsers();
                }
                elseif ($user_type == 2)
                {
                    $users = User::getUsers(2);
                }
                elseif($user_type == 3)
                {
                    $users = User::getUsers(3);
                }

                if ($user_type == NULL)
                {
                    $info_string = "All users";
                }
                elseif ($user_type == 2)
                {
                    $info_string = "All Students";
                }
                elseif($user_type == 3)
                {
                    $info_string = "All Employees";
                }
            }
               
            // construct dataprovider information
            foreach ($users as $user)
            { 
                $user_info = array();
                $user_info['personid'] = $user->personid;
                $user_info['username'] = $user->username;
                $user_info['isactive'] = $user->isactive;
                $user_info['isdeleted'] = $user->isdeleted;

                if ($user->persontypeid == 2)   // if student
                {
                    $user_info['user_type'] = "Student";
                    $student = Student::find()
                             ->where(['personid' => $user->personid, 'isactive' => 1, 'isdeleted' => 0])
                             ->one();
                     if ($student)
                     {
                         $registration = StudentRegistration::find()
                             ->where(['personid' => $user->personid, 'isactive' => 1, 'isdeleted' => 0])
                             ->one();
                          if ($registration == true)
                          {
                              $user_info['studentregistrationid'] = $registration->studentregistrationid;
                          }
                          else
                          {
                              continue;
                          }
                         $user_info['title'] = $student->title;
                         $user_info['first_name'] = $student->firstname;
                         $user_info['middle_name'] = $student->middlename != false ? $student->middlename : "";
                         $user_info['last_name'] = $student->lastname;
                         $user_info['gender'] = $student->gender == "f" ? "Female" : "Male";
                     }
                     else
                     {
                         continue;
                     }
                }

                elseif($user->persontypeid == 3) //if employee
                {
                    $user_info['studentregistrationid'] = NULL;
                    $user_info['user_type'] = "Employee";
                    $employee = Employee::find()
                             ->where(['personid' => $user->personid, 'isactive' => 1, 'isdeleted' => 0])
                             ->one();
                     if ($employee)
                     {
                         $user_info['title'] = $employee->title;
                         $user_info['first_name'] = $employee->firstname;
                         $user_info['middle_name'] = $employee->middlename != false ? $employee->middlename : "";
                         $user_info['last_name'] = $employee->lastname;
                         $user_info['gender'] = $employee->gender == "f" ? "Female" : "Male";
                     }
                     else
                     {
                         continue;
                     }
                }
                $user_container[] = $user_info;
             }

            $dataProvider = new ArrayDataProvider([
                         'allModels' => $user_container,
                         'pagination' => [
                             'pageSize' => 25,
                         ],
                         'sort' => [
                             'defaultOrder' => ['username' => SORT_ASC],
                             'attributes' => ['username', 'first_name', 'last_name', 'user_type', 'personid']
                         ]
                 ]);
   
           
            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'info_string' => $info_string,
                'user_type' => $user_type,
            ]);
        }
                
        
        // (gamal_crichton & laurence_charles) - Creates a new full user account by creating User and Employee records.
        // Assigns login credentials to employee account
        public function actionCreateFullUser()
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $model = new SignupFullUserForm();

            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                $load_flag = $model->load($post_data); 
                
                if ($load_flag == true)
                {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try 
                    {
                        //(laurence_charles) -  if '$model->username' is not defined by user, the system will generate a username
                        $username = $model->username == '' ? SignupFullUserForm::createEmployeeUsername() : $model->username;

                        $personal_email = (strcmp($model->personal_email,"") == 0 || $model->personal_email == NULL)? "pending..." : $model->personal_email;

                        if ($user = $model->signup($username, $model->institutional_email)) 
                        {
                            $email = new Email();
                            $email->email = $model->personal_email;
                            $email->personid = $user->personid;
                            $email->priority = 1;   //(laurence_charles) - all employees have an email record with priority of 1
                            if ($email->save())
                            {
                                $employee_model = new Employee();
                                $employee_model->personid = $user->personid;
                                $employee_model->title = ucfirst($model->title);
                                $employee_model->firstname = ucfirst($model->firstname);
                                if($model->middlename == true)
                                {
                                    $employee_model->middlename = ucfirst($model->middlename);
                                }
                                $employee_model->lastname = ucfirst($model->lastname);

                                $employee_model->employeetitleid = $model->employeetitleid;
                                $employee_model->maritalstatus = $model->maritalstatus;
                                $employee_model->religion = $model->religion;
                                $employee_model->nationality = $model->nationality;
                                $employee_model->placeofbirth = $model->placeofbirth;
                                $employee_model->nationalidnumber = $model->nationalidnumber;
                                $employee_model->nationalinsurancenumber = $model->nationalinsurancenumber;
                                $employee_model->inlandrevenuenumber = $model->inlandrevenuenumber;
                                $employee_model->gender = $model->gender;
                                $employee_model->dateofbirth = $model->dateofbirth;

                                if ($employee_model->save() == true) 
                                {
                                    $department = new EmployeeDepartment();
                                    $department->departmentid = $model->departmentid;
                                    $department->personid = $user->personid;
                                    if ($department->save() == true)
                                    {
                                        $transaction->commit();
                                        Yii::$app->session->setFlash('success', 'User account creation was successful.');
                                        return $this->redirect(Url::to(['employee/employee-profile', 'personid' => $user->personid ])); 
                                    }
                                    else
                                    {
                                        $transaction->rollBack();
                                        Yii::$app->session->setFlash('error', 'Department assignment could not be saved.');
                                    }
                                }
                                else
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Employee could not be saved.');
                                }
                            }
                            else
                            {
                                $transaction->rollBack();
                                 Yii::$app->session->setFlash('error', 'Email could not be saved.');
                            }
                        }
                        else
                        {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured while creating User model.');
                        }
                    }catch (Exception $ex) {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'Error occured loading data entry.');
                } 
            }

            return $this->render('create_full_user', ['model' => $model]);
        }
        
        
        
        
         // (gamal_crichton & laurence_charles) - Creates a lecturer user account by creating User and Employee records.
          // Does not assign login credentials to employee account
        public function actionCreateLecturer()
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $model = new SignupLecturerForm();

            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                $load_flag = $model->load($post_data); 
                
                if ($load_flag == true)
                {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try 
                    {
                        //(laurence_charles) -  if '$model->username' is not defined by user, the system will generate a username
                        $username = $model->username == '' ? SignupLecturerForm::createEmployeeUsername() : $model->username;

                        $personal_email = (strcmp($model->personal_email,"") == 0 || $model->personal_email == NULL)? "pending..." : $model->personal_email;
                        
                        $user = $model-> signup_user_without_login_credentials($username, $model->institutional_email);
                        if ($user == false || $user == NULL)
                        {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'User record could not be saved.');
                        }
                        else
                        {
                            $email = new Email();
                            $email->email = $model->personal_email;
                            $email->personid = $user->personid;
                            $email->priority = 1;   //(laurence_charles) - all employees have an email record with priority of 1
                            $email_save_flag = $email->save();
                            if ($email_save_flag == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Email record could not be saved.');
                            }
                            else
                            {
                                $employee_model = new Employee();
                                $employee_model->personid = $user->personid;
                                $employee_model->title = ucfirst($model->title);
                                $employee_model->firstname = ucfirst($model->firstname);
                                if($model->middlename == true)
                                {
                                    $employee_model->middlename = ucfirst($model->middlename);
                                }
                                $employee_model->lastname = ucfirst($model->lastname);

                                $employee_model->employeetitleid = $model->employeetitleid;
                                $employee_model->maritalstatus = $model->maritalstatus;
                                $employee_model->religion = $model->religion;
                                $employee_model->nationality = $model->nationality;
                                $employee_model->placeofbirth = $model->placeofbirth;
                                $employee_model->nationalidnumber = $model->nationalidnumber;
                                $employee_model->nationalinsurancenumber = $model->nationalinsurancenumber;
                                $employee_model->inlandrevenuenumber = $model->inlandrevenuenumber;
                                $employee_model->gender = $model->gender;
                                $employee_model->dateofbirth = $model->dateofbirth;
                                $employee_save_flag = $employee_model->save();
                                if ($employee_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Employee record could not be saved.');
                                }
                                else
                                {
                                    $department = new EmployeeDepartment();
                                    $department->departmentid = $model->department;
                                    $department->personid = $user->personid;
                                    $department_save_flag = $department->save();
                                    if ($department_save_flag == false)
                                    {
                                        $transaction->rollBack();
                                        Yii::$app->session->setFlash('error', 'EmployeeDepartment record could not be saved.');
                                    }
                                    else
                                    {
                                        $transaction->commit();
                                        Yii::$app->session->setFlash('success', 'User account creation was successful.');
                                        return $this->redirect(Url::to(['employee/employee-profile', 'personid' => $user->personid ])); 
                                    }
                                }
                            }  
                        }
                    }catch (Exception $ex) {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'Error occured loading data entry.');
                }
            }

            return $this->render('create_lecturer', ['model' => $model]);
        }
                    
                    
                    
                    

        /**
         * Finds the User model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param string $id
         * @return User the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (($model = User::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
