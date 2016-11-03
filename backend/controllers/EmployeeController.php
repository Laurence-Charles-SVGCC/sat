<?php

    namespace backend\controllers;

    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;

    use common\models\User;
    use frontend\models\Employee;
    use backend\models\AssignEmployeePassword;

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
         * Lists all Employee models.
         * @return mixed
         */
        public function actionIndex()
        {
            $dataProvider = new ActiveDataProvider([
                'query' => Employee::find(),
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        }

        /**
         * Displays a single Employee model.
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
         * Creates a new Employee model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new Employee();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->employeeid]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }

        /**
         * Updates an existing Employee model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param string $id
         * @return mixed
         */
        public function actionUpdate($id)
        {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->employeeid]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }

        /**
         * Deletes an existing Employee model.
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


        /**
         * Assign password to employee
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 02/11/2016
         * Date Last Modified: 03/11/2016
         */
        public function actionAssignPassword()
        {

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
                    $user = User::find()
                            ->where(['personid' => $model->userid,  'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($user == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Employee record not found.');
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
                             Yii::$app->getSession()->setFlash('success', 'Password assignment to employee was successful.');
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
