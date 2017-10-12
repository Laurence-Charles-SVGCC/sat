<?php

    namespace app\subcomponents\applications\controllers;

    use Yii;
    use yii\filters\VerbFilter;
    
    use yii\custom\UnauthorizedAccessException;
    use frontend\models\provider_builders\ApplicationPeriodBuilder;
    use frontend\models\ApplicationPeriod;
    
    
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
         * Author: charles.laurence1@gmail.com
         * Created: 2017_07_21
         * Last Modified: 2017_10_12
         */
        public function actionViewPeriods()
        {
            if (Yii::$app->user->can('System Administrator') == false && Yii::$app->user->can('Registrar') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
            $period_details_data_provider = ApplicationPeriodBuilder::generateApplicationPeriodListing(25) ;
            $unconfigured_period = ApplicationPeriod::getUnconfiguredAppplicationPeriod();
                    
            return $this->render('periods', [
                'period_details_data_provider' => $period_details_data_provider,
                'unconfigured_period' => $unconfigured_period]);
        }


        /**
         * Renders the Application Period Statistics
         * 
         * @return type
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2017_07_21
         * Last Modified: 2017_10_12
         */
        public function actionViewPeriodStatistics()
        {
           if (Yii::$app->user->can('System Administrator') == false && Yii::$app->user->can('Registrar') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
            $period_stats_data_provider = ApplicationPeriodBuilder::generateApplicationPeriodStatistics(25);
            return $this->render('period_statistics', [ 'period_stats_data_provider' => $period_stats_data_provider]);
        }


        
    }
    
    
    
