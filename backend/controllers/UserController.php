<?php
    namespace backend\controllers;

    use Yii;
    use yii\helpers\Url;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\data\ArrayDataProvider;
    
    use common\models\User;
    
    use backend\models\SignupUserForm;
    use \backend\models\PersonType;
    
    use frontend\models\Employee;
    use frontend\models\Student;
    use frontend\models\StudentRegistration;
    use frontend\models\Email;
    use frontend\models\EmployeeDepartment;
    

    /**
     * UserController implements the CRUD actions for User model.
     */
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

        
        public function actionIndex()
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $info_string = "All users";
            $dataProvider = NULL;
            $user_container = array();
            
            if (Yii::$app->request->post())
            {
                $request = Yii::$app->request;
                $firstname = $request->post('fname_field');
                $lastname = $request->post('lname_field');
                $username = $request->post('username_field');
                $personid = $request->post('personid_field');
                
                if($firstname == false && $lastname == false && $username == false && $personid == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'No search criteria was entered.');
                    $users = User::find()
                        ->where(['persontypeid' => [2,3], 'isactive' => 1, 'isdeleted' => 0])
                        ->all();
                }
                else
                {
                    if($firstname == true || $lastname == true)
                    {
                        $users = array();
                        
                        if ($firstname)
                        {
                            $info_string = $info_string .  " First Name: " . $firstname; 
                        }
                        if ($lastname)
                        {
                            $info_string = $info_string .  " Last Name: " . $lastname;
                        }
                        
                        /********************   employee search   ************************/
                        $cond_arr = array();
                        $cond_arr['person.isactive'] = 1;
                        $cond_arr['person.isdeleted'] = 0;
                        if ($firstname)
                        {
                            $cond_arr['employee.firstname'] = $firstname;
                        }
                        if ($lastname)
                        {
                            $cond_arr['employee.lastname'] = $lastname;
                        }
                        $employees = User::find()
                             ->innerJoin('employee' , '`person`.`personid` = `employee`.`personid`')
                             ->where($cond_arr)
                             ->all();
                        if($employees)
                        {
                            $users = array_merge($users, $employees);
                        }
                        
                        /********************   student search   ************************/
                        $cond_arr = array();
                        $cond_arr['person.isactive'] = 1;
                        $cond_arr['person.isdeleted'] = 0;
                        if ($firstname)
                        {
                            $cond_arr['student.firstname'] = $firstname;
                        }
                        if ($lastname)
                        {
                            $cond_arr['student.lastname'] = $lastname;
                        }
                        $students = User::find()
                            ->innerJoin('student' , '`person`.`personid` = `student`.`personid`')
                            ->where($cond_arr)
                            ->all();
                        if($students)
                        {
                            $users = array_merge($users, $students);
                        }
                    }

                    elseif($username)
                    {
                        $cond_arr = array();
                        $cond_arr['isactive'] = 1;
                        $cond_arr['isdeleted'] = 0;
                        $cond_arr['username'] = $username;
                        $info_string = $info_string .  " Username: " . $username;
                        
                        $users = User::find()
                            ->where($cond_arr)
                            ->all();
                    }

                    elseif($personid)
                    {
                        $cond_arr = array();
                        $cond_arr['isactive'] = 1;
                        $cond_arr['isdeleted'] = 0;
                        $cond_arr['personid'] = $personid;
                        $info_string = $info_string .  " Person ID: " . $personid;
                        
                        $users = User::find()
                            ->where($cond_arr)
                            ->all();
                    }
                    
                    if (empty($users))
                    {
                        Yii::$app->getSession()->setFlash('error', 'No users found matching this criteria.');
                    }
                }
            }
            else
            {
                $users = User::find()
               ->where(['persontypeid' => [2,3], 'isactive' => 1, 'isdeleted' => 0])
               ->all();
            }
           
           
           foreach ($users as $user)
           { 
               $user_info = array();
               $user_info['personid'] = $user->personid;
               $user_info['username'] = $user->username;
               $user_info['isactive'] = $user->isactive;
               $user_info['isdeleted'] = $user->isdeleted;
               
               if ($user->persontypeid == 2) //if student
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
            ]);
        }
        
        

        /**
         * Displays a single User model.
         * @param string $id
         * @return mixed
         */
        public function actionView($id)
        {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }

        /**
         * Creates a new User.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         * Date Created: 30/07/2015 By Gamal Crichton
         * Date Last Modified: 20/01/2016 By Laurence Charles
         */
        public function actionCreate()
        {
            $model = new SignupUserForm();

            if ($model->load(Yii::$app->request->post())) 
            {   
                $username = $model->username == '' ? SiteController::createUsername() : $model->username;
                $personal_email = (strcmp($model->personal_email,"") == 0 || $model->personal_email == NULL)? "pending..." : $model->personal_email;

                if ($user = $model->signup($username, $model->institutional_email)) 
                {
                    $email = new Email();
                    $email->email = $model->personal_email;
                    $email->personid = $user->personid;
                    $email->priority = 1;
                    if ($email->save())
                    {
                        $employee_model = new Employee();
                        $employee_model->personid = $user->personid;
                        $employee_model->firstname = ucfirst($model->firstname);
                        $employee_model->lastname = ucfirst($model->lastname);
                        if ($employee_model->save()) 
                        {
                            $department = new EmployeeDepartment();
                            $department->departmentid = $model->department;
                            $department->personid = $user->personid;
                            if ($department->save())
                            {
                                return $this->redirect(Url::to(['employee/update', 'id' => $employee_model->employeeid ])); 
                            }
                        }
                        Yii::$app->session->setFlash('error', 'Employee could not be saved.');
                    }
                }
            }

            return $this->render('create', [
                'model' => $model,
            ]);

        }

        /**
         * Updates an existing User model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param string $id
         * @return mixed
         */
        public function actionUpdate($id)
        {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->personid]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }

        /**
         * Deletes an existing User model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param string $id
         * @return mixed
         */
        public function actionDelete($id)
        {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
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
