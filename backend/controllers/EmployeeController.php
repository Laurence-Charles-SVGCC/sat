<?php

    namespace backend\controllers;

    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    
    use backend\models\AssignEmployeePassword;

    use common\models\User;
    use frontend\models\Employee;
    use frontend\models\EmployeeTitle;
    use frontend\models\Division;
    use frontend\models\Department;
    use backend\models\AuthAssignment;
     use backend\models\AuthItemChild;
    

    /**
     * EmployeeController implements the CRUD actions for Employee model.
     */
    class EmployeeController extends Controller
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


        /**
         * Finds the Employee model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param string $id
         * @return Employee the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (($model = Employee::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        // (laurence_charles) - Renders employee's profile
        public function actionEmployeeProfile($personid)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $employee_title = "";
            $user = User::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $employee = Employee::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            if($employee == true &&  $employee->employeetitleid != false)
            {
                $employee_title = EmployeeTitle::find()
                    ->where(['employeetitleid' => $employee->employeetitleid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                    ->name;
            }
            
            $employee_division ="N/A";
            $employee_department = "N/A";
            
            $department = Department::find()
                    ->innerJoin('employee_department' , '`department`.`departmentid` = `employee_department`.`departmentid`')
                    ->where(['department.isactive' => 1, 'department.isdeleted' => 0,
                                    'employee_department.personid' => $personid, 'employee_department.isactive' => 1, 'employee_department.isdeleted' => 0])
                    ->one();
            if ($department)
            {
                $employee_department = $department->name;

                $employee_division = Division::find()
                        ->where(['divisionid' => $department->divisionid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one()
                        ->abbreviation;
            }
            
            $descendants = array();
            $ancestors = array();
            $roles = AuthAssignment::getUserRoleDetails($personid);
            
            if (empty($roles) == false)
            {
                foreach ($roles as $role)
                {
                    $parents = AuthItemChild::getRoleAncestors($role["name"]);
                    foreach ($parents as $parent)
                    {
                        if(in_array($parent, $ancestors) == false)
                        {
                            $ancestors[] = $parent;
                        }
                    }
                }
                
                foreach ($roles as $role)
                {
                    $children = AuthItemChild::getRoleDescendants($role["name"]);
                    foreach ($children as $child)
                    {
                        if(in_array($child, $descendants) == false)
                        {
                            $descendants[] = $child;
                        }
                    }
                }
            }
            
            return $this->render('employee_profile',
                                            ['user' => $user,
                                                'employee' => $employee,
                                                'employee_title' => $employee_title,
                                                'employee_division' => $employee_division,
                                                'employee_department' => $employee_department,
                                                'roles' => $roles,
                                                'descendants' => $descendants,
                                                'ancestors' => $ancestors
                                            ]);
        }
        
        
        // (laurence_charles) - Update/Edit employee's profile
        public function actionEditProfile($personid)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $user = User::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            
            $employee = Employee::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                $load_flag = $employee->load($post_data); 
                
                if ($load_flag == true)
                {
                    $save_flag = $employee->save();
                    if ($save_flag == true)
                    {
                        return self::actionEmployeeProfile($personid);
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured whensaving record. Please try again.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when loading record.');
                }
            }
            return $this->render('edit_profile', 
                                            ['employee' => $employee,
                                                'username' => $user->username,
                                            ]);
        }

        
        // (laurence_charles) - Assign password to employee; will generally be used when upgrading a user from
        // Lecturuer to FullUser.
        public function actionAssignPassword()
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }

            $model = new AssignEmployeePassword(); 
            $employees = Employee::getAllEmployees();

             if ($post_data = Yii::$app->request->post())
            { 
                $save_flag = false;
                $load_flag = false;
                
                $load_flag = $model->load($post_data);
                if($load_flag == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'Error loading input data.');
                }
                else
                {
                    $employee = Employee::find()
                             ->where(['personid' => $model->userid,  'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($employee = false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Employee record not found.');
                    }
                    $name = Employee::getEmployeeName($model->userid);
                    
                    $user = User::find()
                            ->where(['personid' => $model->userid,  'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($user == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'User record not found.');
                    }
                    else
                    {
                         $user->setPassword($model->password);
                         $user->setSalt();
                         $save_flag = $user->save();
                         if ($save_flag == false)
                         {
                             Yii::$app->getSession()->setFlash('error', 'Error occured setting new password.');
                         }
                         else
                         {
                             Yii::$app->getSession()->setFlash('success', 'Password assignment to '. $name . ' was successful.');
                             return self::actionIndex();
                         }
                    }
                }
            }
            return $this->render('assign_password',[
                                    'model' => $model,
                                    'employees' => $employees
                                    ]);
        }
        
    }
