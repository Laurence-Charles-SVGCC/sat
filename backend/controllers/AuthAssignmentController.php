<?php
    namespace backend\controllers;

    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\data\ArrayDataProvider;
    
    use frontend\models\Employee;
    use common\models\User;
    
    use backend\models\AuthAssignment;
    use backend\models\AuthItem;

    class AuthAssignmentController extends Controller
    {
        public function behaviors()
        {
            return [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post'],
                    ],
                ]
            ];
        }

        // (laurence_charles) - View listing of all role assignments
        public function actionIndex()
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $dataProvider = NULL;
            $container = array();
            
            $auth_assignments = AuthAssignment::find()
                        ->all();
            
            if (empty($auth_assignments) == false)
            {
                foreach ($auth_assignments as $auth_assignment)
                { 
                    $data = array();
                    $data['user_full_name'] =  User::getFullName($auth_assignment->user_id);
                    $employee = Employee::find()
                            ->where(['personid' => $auth_assignment->user_id, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    $data['personid'] = $auth_assignment->user_id;
                    $data['firstname'] = $employee->firstname;
                    $data['lastname'] = $employee->lastname;
                    $data['user_full_name'] =  User::getFullName($auth_assignment->user_id);
                    $data['name'] = $auth_assignment->item_name;
                    $auth_item = AuthItem::find()
                            ->where(['name' => $auth_assignment->item_name])
                            ->one();
                    $data['type'] = $auth_item->type;
                    $data['description'] = $auth_item->description;
                    $container[] = $data;
                }
            }
            
            $dataProvider = new ArrayDataProvider([
                        'allModels' => $container,
                        'pagination' => [
                            'pageSize' => 25,
                        ],
                        'sort' => [
                            'defaultOrder' => ['name' => SORT_ASC],
                            'attributes' => ['name', 'firstname', 'lastname']
                        ]
                ]);
           
            return $this->render('index', ['dataProvider' => $dataProvider,]);
        }


        // (laurence_charles) - Assigns role to an employee
        public function actionCreate()
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $model = new AuthAssignment();

            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                $load_flag = $model->load($post_data); 
                
                if ($load_flag == true)
                {
                    $model->created_at =  time();
                    if ($model->save())
                    {
                        return self::actionIndex();
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'Error occured assigning role.');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'Error occured loading data entry.');
                } 
            } 

            $employees = Employee::find()->all();
            $data = array();
            foreach ($employees as $employee)
            {
                $data[$employee->personid] = $employee->firstname . " " . $employee->lastname;
            }

            return $this->render('create', ['model' => $model,
                                                            'employees' => $data,]);

        }

        
        // (laurence_charles) - Modifies employee role assignment
        public function actionUpdate($item_name, $user_id)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $model = $this->findModel($item_name, $user_id);
            
            $employees = Employee::find()->all();
            $data = array();
            foreach ($employees as $employee)
            {
                $data[$employee->personid] = $employee->firstname . " " . $employee->lastname;
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                $load_flag = $model->load($post_data); 
                
                if ($load_flag == true)
                {
                    if ($model->save())
                    {
                        return self::actionIndex();
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'Error occured assigning role.');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'Error occured loading data entry.');
                } 
            } 
            
            return $this->render('update', ['model' => $model,
                                                            'employees' => $data,]);
        }

        
         // (laurence_charles) - Deletes an existing AuthAssignment model.
        public function actionDelete($item_name, $user_id)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $this->findModel($item_name, $user_id)->delete();

            return $this->redirect(['index']);
        }

        
        // (laurence_charles) - Finds the AuthAssignment model based on its primary key value.
        protected function findModel($item_name, $user_id)
        {
            if (($model = AuthAssignment::findOne(['item_name' => $item_name, 'user_id' => $user_id])) !== null) 
            {
                return $model;
            } 
            else 
            {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
