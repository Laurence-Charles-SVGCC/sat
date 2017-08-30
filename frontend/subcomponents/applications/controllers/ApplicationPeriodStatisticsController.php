<?php

    namespace app\subcomponents\applications\controllers;

    use Yii;
    use yii\filters\VerbFilter;
    
    use frontend\models\provider_builders\ApplicationPeriodBuilder;
    use frontend\models\Employee;
    
    
    class ApplicationPeriodStatisticsController extends \yii\web\Controller
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
         * Downloads application period statistics
         * 
         * @return view
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_29
         * Date Last Modified: 2017_08_29
         */
        public function actionDownloadPeriodStatisticsReport()
        {
            $data_provider = ApplicationPeriodBuilder::generateApplicaitonPeriodStatistics(2000);
            $title = "Application Period Application Statistics";
            $date =  date('Y-m-d');
            $generating_officer = Employee::getEmployeeName(Yii::$app->user->identity->personid);
            $filename = $title . "    " . $date . "    " . $generating_officer;
        
            return $this->renderPartial( 'period-statistics-export', [
                'dataProvider' => $data_provider,
                'title' => $title,
                'generating_officer' => $generating_officer,
                'filename' => $filename,
            ]);
        }
        
        
        /**
         * Downloads report listing applicants that begin the application unverified applicants
         * 
         * @return view
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_08_25
         */
        public function actionDownloadCommencedApplicationsReport($academicyearid)
        {
            $data_provider = ApplicationPeriodBuilder::generateCommencedApplicationsReport($academicyearid, 2000);
            $title = "Commenced Applications";
            $date =  date('Y-m-d');
            $generating_officer = Employee::getEmployeeName(Yii::$app->user->identity->personid);
            $filename = $title . "    " . $date . "    " . $generating_officer;
        
            return $this->renderPartial( 'commenced-applications-export', [
                'dataProvider' => $data_provider,
                'title' => $title,
                'generating_officer' => $generating_officer,
                'filename' => $filename,
            ]);
        }
        
        
        /**
         * Downloads report listing applicants that completed the submission of their applications
         * 
         * @return view
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_08_25
         */
        public function actionDownloadCompletedApplicationsReport($academicyearid)
        {
            $data_provider = ApplicationPeriodBuilder::generateCompletedApplicationsReport($academicyearid, 2000);
            $title = "Completed Applications";
            $date =  date('Y-m-d');
            $generating_officer = Employee::getEmployeeName(Yii::$app->user->identity->personid);
            $filename = $title . "    " . $date . "    " . $generating_officer;
        
            return $this->renderPartial( 'completed-applications-export', [
                'dataProvider' => $data_provider,
                'title' => $title,
                'generating_officer' => $generating_officer,
                'filename' => $filename,
            ]);
        }

        
        /**
         * Downloads report listing who started but did not submit their applications
         * 
         * @return view
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_08_25
         */
        public function actionDownloadIncompleteApplicationsReport($academicyearid)
        {
            $data_provider = ApplicationPeriodBuilder::generateIncompleteApplicationsReport($academicyearid, 2000);
            $title = "Incomplete Applications";
            $date =  date('Y-m-d');
            $generating_officer = Employee::getEmployeeName(Yii::$app->user->identity->personid);
            $filename = $title . "    " . $date . "    " . $generating_officer;
            
            return $this->renderPartial( 'incomplete-applications-export', [
                'dataProvider' => $data_provider,
                'title' => $title,
                'generating_officer' => $generating_officer,
                'filename' => $filename,
            ]);
        }
        
        
        /**
         * Downloads report listing applicants whose certificates have been verified
         * 
         * @return view
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_08_25
         */
        public function actionDownloadVerifiedApplicationsReport($academicyearid)
        {
            $data_provider = ApplicationPeriodBuilder::generateVerifiedApplicationsReport($academicyearid, 2000);
            $title = "Verified Applications";
            $date =  date('Y-m-d');
            $generating_officer = Employee::getEmployeeName(Yii::$app->user->identity->personid);
            $filename = $title . "    " . $date . "    " . $generating_officer;
            
            return $this->renderPartial( 'verified-applications-export', [
                'dataProvider' => $data_provider,
                'title' => $title,
                'generating_officer' => $generating_officer,
                'filename' => $filename,
            ]);
        }
        
        
        /**
         * Downloads report listing applicants whose certificates have not  been verified
         * 
         * @return view
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_08_25
         */
        public function actionDownloadUnverifiedApplicationsReport($academicyearid)
        {
            $data_provider = ApplicationPeriodBuilder::generateUnverifiedApplicationsReport($academicyearid, 2000);
            $title = "Unverified Applications";
            $date =  date('Y-m-d');
            $generating_officer = Employee::getEmployeeName(Yii::$app->user->identity->personid);
            $filename = $title . "    " . $date . "    " . $generating_officer;
            
            return $this->renderPartial( 'unverified-applications-export', [
                'dataProvider' => $data_provider,
                'title' => $title,
                'generating_officer' => $generating_officer,
                'filename' => $filename,
            ]);
        }
        
        
        
}