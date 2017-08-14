<?php
    namespace backend\controllers;

    use Yii;
    use yii\filters\AccessControl;
    use yii\web\Controller;
    use common\models\LoginForm;
    use yii\filters\VerbFilter;
    use yii\helpers\Url;

    use common\controllers\MailController;

    use backend\models\SignupForm;

    use frontend\models\Employee;
    use frontend\models\Email;
    use common\models\User;
    use frontend\models\Department;
    use frontend\models\EmployeeDepartment;
    use frontend\models\ApplicationSettings;


    /**
     * Site controller
     */
    class SiteController extends Controller
    {
        /**
         * @inheritdoc
         */
//        public function behaviors()
//        {
//            return [
//                'access' => [
//                    'class' => AccessControl::className(),
//                    'rules' => [
//                        [
//                            'actions' => [ 'login'],
//                            'allow' => true,
//                        ],
//                        [
//                            'actions' => ['logout', 'index'],
//                            'allow' => true,
//                            'roles' => ['@'],
//                        ],
//                    ],
//                ],
//                'verbs' => [
//                    'class' => VerbFilter::className(),
//                    'actions' => [
//                        'logout' => ['post', 'get'],
//                    ],
//                ],
//            ];
//        }
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
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

        
//        public function actionIndex()
//        {
//            return $this->render('index');
//        }
//        public function actionLogin()
//        {
//            $this->layout = 'loginlayout';
//
//            if (!\Yii::$app->user->isGuest){ 
//                return $this->goHome();
//            }
//
//            $model = new LoginForm();
//            if ($model->load(Yii::$app->request->post()) && $model->login()) {
//                return $this->goBack();
//            } else {
//                return $this->render('login', [
//                    'model' => $model,
//                ]);
//            }
//        }
//        public function actionLogout()
//        {
//            Yii::$app->user->logout();
//
//            return $this->goHome();
//        }

        
        
        /**
         * Displays homepage.
         *
         * @return mixed
         * 
         * Author: Laurence Charles
         * Date Created: 14/08/2017
         * Date Last Modified: 14/08/2017
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
         * Author: Laurence Charles
         * Date Created: 14/08/2017
         * Date Last Modified: 14/08/2017
         */
        public function actionLogin($mode = "online")
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
                    if ($mode == "online" || ($mode == "offline" && Yii::$app->user->can('System Administrator')) )
                    {
                        return $this->redirect(['index']);
                    }
                    else
                    {
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
         * Date Created: 14/08/2017
         * Date Last Modified: 14/08/2017
         */
        public function actionOfflineLogin()
        {
            $this->layout = false;
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
            return $this->render('under_maintenance');
        }

        
        /**
         * Logs out the current user.
         *
         * @return mixed
         * 
         * Author: Laurence Charles
         * Date Created: 14/08/2017
         * Date Last Modified: 14/08/2017
         */
        public function actionLogout()
        {
            Yii::$app->user->logout();
            return $this->goHome();
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
            if ($status == true)
            {
                return $this->redirect(['index']);
            }
            else
            {
                return $this->render('index');
            }   
        }
        

        /*
        * Purpose: Creates username for new user
        * Created: 13/07/2015 by Gamal Crichton
        * Last Modified: 14/07/2015 by Gamal Crichton
        */
        public static function createUsername()
        {
            $last_user = User::find()->orderBy('personid DESC', 'desc')->one();
            //150 used to prevent username clashes with the users already entered on eCampus.
            $num = $last_user ? strval($last_user->personid + 1) : 150;
            while (strlen($num) < 4)
            {
                $num = '0' . $num;
            }
            return '1401' . $num;
        }

    }
