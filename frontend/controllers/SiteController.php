<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
                'only' => ['login', 'logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) 
        {
            return $this->actionLogin();
        }
        else
        {
            return $this->render('index');
        }
    }
    

    /**
     * Logs in a user.
     *
     * @return mixed
     * 
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 08/08/2017 (L. Charles)
     */
    public function actionLogin()
    {
        $this->layout = 'loginlayout';
        
        if (!\Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()))
        {
            //if login is successful
            if ($model->login() == true) 
            {
                return $this->redirect(['index']);
            }
        }
        
        return $this->render('login', ['model' => $model]);
    } 


    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    
    /**
     * Requests password reset.
     *
     * @return mixed
     * 
     * Author: Gamal Crichton, Laurence Charles
     * Date Last Modified: 08/08/2017
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'loginlayout';
        
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            if ($model->sendEmail())
            {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }
            else 
            {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [ 'model' => $model ]);
    }

    
    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     * 
     * Author: Gamal Crichton, Laurence Charles
     * Date Last Modified: 08/08/2017
     */
    public function actionResetPassword($token)
    {
        $this->layout = 'loginlayout';
        
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) 
        {
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }

        return $this->render('resetPassword', ['model' => $model]);
    }
    
}
