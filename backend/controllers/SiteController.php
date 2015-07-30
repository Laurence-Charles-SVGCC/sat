<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use backend\models\SignupForm;
use frontend\models\Employee;
use frontend\models\Email;
use common\models\User;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [ 'login', 'error', 'signup'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->layout = 'loginlayout';
        
        if (!\Yii::$app->user->isGuest){ 
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    /**
     * Signs user up.
     * Created: -- by Gii
     * Last Modified: 14/07/2015 by Gamal Crichton
     * @return mixed
     */
    public function actionSignup()
    {
        $this->layout = 'loginlayout';
        
        $model = new SignupForm();
        $employee_model = new Employee();
        
        if ($model->load(Yii::$app->request->post()) && $employee_model->load(Yii::$app->request->post())) {
            $username = $this->createUsername();
            if ($user = $model->signup($username)) {
                $email = new Email();
                $email->emailaddress = $model->email;
                $email->personid = $user->personid;
                $email->priority = 1;
                if ($email->save())
                {
                    $employee_model->personid = $user->personid;
                    if ($employee_model->save() && Yii::$app->getUser()->login($user)) {
                        return $this->goHome();
                    }
                }
            }
            
        }
        return $this->render('signup', [
            'model' => $model,
            'employee_model' => $employee_model,
        ]);
    }
    
    /*
    * Purpose: Creates username for new user
    * Created: 13/07/2015 by Gamal Crichton
    * Last Modified: 14/07/2015 by Gamal Crichton
    */
    public static function createUsername()
    {
        $last_user = User::find()->orderBy('personid DESC', 'desc')->one();
        $num = $last_user ? strval($last_user->personid + 1) : 1;
        while (strlen($num) < 4)
        {
            $num = '0' . $num;
        }
        return '1401' . $num;
    }
}
