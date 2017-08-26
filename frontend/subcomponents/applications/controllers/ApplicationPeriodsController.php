<?php

    namespace app\subcomponents\applications\controllers;

    use Yii;
    use yii\filters\VerbFilter;
    
    use frontend\models\provider_builders\ApplicationPeriodBuilder;
    
    
//    use frontend\models\ApplicationPeriod;
    
    
//    use yii\web\NotFoundHttpException;
    //use common\models\User;
     

    class ApplicationPeriodsController extends \yii\web\Controller
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
         * Renders the Application Period Summary view
         * 
         * @return view
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_21
         * Date Last Modified: 2017_08_20
         */
        public function actionViewPeriods()
        {
            $period_details_data_provider = ApplicationPeriodBuilder::generateApplicaitonPeriodListing() ;
            return $this->render('periods', [ 'period_details_data_provider' => $period_details_data_provider ]);
        }


        /**
         * Renders the Application Period Statistics
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_21
         * Date Last Modified: 2017_08_20
         */
        public function actionViewPeriodStatistics()
        {
            $period_stats_data_provider = ApplicationPeriodBuilder::generateApplicaitonPeriodStatistics();
            return $this->render('period_statistics', [ 'period_stats_data_provider' => $period_stats_data_provider]);
        }



        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        /**
         * Lists all ApplicationPeriod models.
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new ApplicationPeriodSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        /**
         * Displays a single ApplicationPeriod model.
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
         * Creates a new ApplicationPeriod model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new ApplicationPeriod();

            if ($model->load(Yii::$app->request->post()))
            {
                $model->personid = Yii::$app->user->getID();
                if ($model->save())
                {
                    return $this->redirect(['view', 'id' => $model->applicationperiodid]);
                }
                Yii::$app->session->setFlash('error', 'Application Period not created');
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }

        /**
         * Updates an existing ApplicationPeriod model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param string $id
         * @return mixed
         */
        public function actionUpdate($id)
        {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->applicationperiodid]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }

        /**
         * Deletes an existing ApplicationPeriod model.
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
         * Finds the ApplicationPeriod model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param string $id
         * @return ApplicationPeriod the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (($model = ApplicationPeriod::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }


        /**
         * Updates an ApplicationPeriod record 
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 09/02/2016
         * Date Last Modified: 09/02/2016
         */
        public function actionEditApplicationPeriod($recordid)
        {
            $employeeid = Yii::$app->user->identity->personid;
            $period = ApplicationPeriod::find()
                        ->where(['applicationperiodid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            if ($period == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to retrieve application period record. Please try again.');
                return $this->render('edit_application_period',[
                                    'period' => $period
                                ]);
            }

            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;

                $load_flag = $period->load($post_data);
                $period->personid = $employeeid;
                if($load_flag == true)
                {
                    $save_flag = $period->save();
                    if($save_flag == true)
                        return $this->redirect(['admissions/manage-application-period']);
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update application period record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load application period record. Please try again.');              
            }

            $applicantintentid = NULL;
            if ($period->divisionid == 4)
            {
                if ($period->applicationperiodtypeid == 1)
                {
                    $applicantintentid = 1;
                }
                else
                {
                    $applicantintentid = 2;
                }
            }
            elseif ($period->divisionid == 5)
            {
                if ($period->applicationperiodtypeid == 1)
                {
                    $applicantintentid = 1;
                }
                else
                {
                    $applicantintentid = 3;
                }
            }
            elseif ($period->divisionid == 6)
            {
                if ($period->applicationperiodtypeid == 1)
                {
                    $applicantintentid = 4;
                }
                else
                {
                    $applicantintentid = 5;
                }
            }
            elseif ($period->divisionid == 7)
            {
                if ($period->applicationperiodtypeid == 1)
                {
                    $applicantintentid = 6;
                }
                else
                {
                    $applicantintentid = 7;
                }
            }

            return $this->render('edit_application_period',[
                                    'period' => $period,
                                    'applicantintentid' => $applicantintentid
                                ]);
        }


        /**
         * Deletes an application period
         * 
         * @param type $personid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 21/03/2016
         * Date Last Modified: 21/03/2016
         */
        public function actionDeleteApplicationPeriod($recordid)
        {
            $period = ApplicationPeriod::find()
                        ->where(['applicationperiodid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            if ($period == true)
            {
                $save_flag = false;
                $period->isdeleted = 1;
                $period->isactive = 0;
                $save_flag = $period->save();
                if($save_flag == false)
                    Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');             
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured locating record. Please try again.');

            return $this->redirect(\Yii::$app->request->getReferrer());
        }
    }
    
    
    
