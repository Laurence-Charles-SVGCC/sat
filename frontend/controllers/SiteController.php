<?php

namespace frontend\controllers;

use Yii;

use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

use common\models\LoginForm;
use common\models\UserDAO;
// use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\ApplicationSettings;

use common\models\UserPasswordResetRequestForm;
use common\models\UserPasswordResetForm;
use common\models\UserLogin;

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
                'class' => AccessControl::class,
                'only' => ['login', 'logout', 'offline-login'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['offline-login'],
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
                'class' => VerbFilter::class,
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



    //        public function beforeAction($action)
    //        {
    //            // your custom code here, if you want the code to run before action filters,
    //            // which are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl
    //
    //            if (!parent::beforeAction($action)) {
    //                return false;
    //            }
    //
    //            // other custom code here
    //            $settings = ApplicationSettings::getApplicationSettings();
    //            if ($settings)
    //            {
    //                 if ($this->getRoute() == 'site/offline-login')
    //                 {
    //                    return true;
    //                 }
    //           
    //                if (\Yii::$app->user->isGuest == true)
    //                {
    //                    if ( $settings->is_online == false && $this->getRoute() != 'site/under-maintenance')
    //                    {
    //                        $this->redirect(['under-maintenance']);
    //                    }
    //                }
    //                else
    //                {
    //                    if (Yii::$app->user->can('System Administrator') == false)
    //                    {
    //                        if ( $settings->is_online == false && $this->getRoute() != 'site/under-maintenance')
    //                        {
    //                            $this->redirect(['under-maintenance']);
    //                        }
    //                    }
    //                    else
    //                    {
    //                        if ( $settings->allow_administrator == false && $this->getRoute() != 'site/under-maintenance')
    //                        {
    //                            $this->redirect(['under-maintenance']);
    //                        }
    //                    }
    //                }
    //            }
    //            else
    //            {
    //                $this->redirect(['under-maintenance']);
    //            }
    //
    //            return true; // or false to not run the action
    //        }


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->actionLogin();
        } else {
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
    public function actionLogin($mode = "online")
    {
        $this->layout = 'loginlayout';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            //if login is successful
            if ($model->login() == true) {
                if ($mode == "online" || ($mode == "offline" && Yii::$app->user->can('System Administrator'))) {
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->user->logout();
                    return $this->redirect(['under-maintenance']);
                }
            }
        }

        return $this->render('login', ['model' => $model]);
    }


    /**
     * Secret link to access application login when application is put in maintenance mode
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/08/2017
     * Date Last Modified: 14/08/2017
     */
    public function actionOfflineLogin()
    {
        return $this->actionLogin("offline");
    }


    /**
     * Renders maintenance screen
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 10/08/2017
     * Date Last Modified: 10/08/2017
     */
    public function actionUnderMaintenance()
    {
        $this->layout = false;
        return $this->render('under_maintenance');
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


    public function actionRequestPasswordReset()
    {
        $this->layout = "loginlayout";

        $model = new UserPasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->publishResetInstructions();
            return $this->goHome();
        }
        return $this->render("request-password-reset", ["model" => $model]);
    }


    public function actionResetPassword($token)
    {
        $this->layout = 'loginlayout';
        $user = UserDAO::getByResetToken($token);
        $model = null;
        if ($user == false) {
            Yii::$app->session->setFlash("warning", "Reset token invalid.");
        } else {
            $model = new UserPasswordResetForm($user);
            if ($model->passwordResetTokenValid() == false) {
                Yii::$app->session->setFlash("warning", "Reset token invalid.");
            }
        }

        if (
            $model->load(Yii::$app->request->post())
            && $model->validate() == true
            && $model->resetPassword() == true
        ) {
            $userLogin = new UserLogin($user);
            $userLogin->login();
            return $this->goHome();
        }

        return $this->render('reset-password', ['model' => $model]);
    }


    /**
     * Toggle application online/offline.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     * 
     * Author: Laurence Charles
     * DAte Creted: 14/08/2017
     * Date Last Modified: 14/08/2017
     */
    public function actionToggleMaintenanceMode($status)
    {
        $settings = ApplicationSettings::getApplicationSettings();
        $settings->is_online = $status;
        $settings->save();
        if ($status == true) {
            return $this->redirect(['index']);
        } else {
            return $this->render('index');
        }
    }
}
