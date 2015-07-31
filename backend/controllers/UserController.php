<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use common\models\User;
use backend\models\SignupUserForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Employee;
use frontend\models\Email;

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

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
     * Created: 30/07/2015 By Gamal Crichton
     * Modified: 30/07/2015 By Gamal Crichton
     */
    public function actionCreate()
    {
        $model = new SignupUserForm();
        
        if ($model->load(Yii::$app->request->post())) 
        {   
            //var_dump($model);
            $username = SiteController::createUsername();
            if ($user = $model->signup($username)) 
            {
                $email = new Email();
                $email->emailaddress = $model->email;
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
                        return $this->redirect(Url::to(['employee/update', 'id' => $employee_model->employeeid ]));   
                    }
                    var_dump($employee_model);
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
