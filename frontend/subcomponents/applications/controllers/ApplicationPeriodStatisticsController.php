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
         * Downloads report listing applicants that begin the application unverified applicants
         * 
         * @return view
         * 
         * Author: Laurence Charles
         * Date Created: 2017_07_25
         * Date Last Modified: 2017_08_25
         */
        public static function downloadCommencedApplicationsReport($academicyearid)
        {
            $data_provider = ApplicationPeriodBuilder::generateCommencedApplicationsReport($academicyearid);
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
        public static function downloadCompletedApplicationsReport($academicyearid)
        {
            $data_provider = ApplicationPeriodBuilder::generateCompletedApplicationsReport($academicyearid);
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
        public static function downloadIncompleteApplicationsReport($academicyearid)
        {
            $data_provider = ApplicationPeriodBuilder::generateIncompleteApplicationsReport($academicyearid);
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
        public static function downloadVerifiedApplicationsReport($academicyearid)
        {
            $data_provider = ApplicationPeriodBuilder::generateVerifiedApplicationsReport($academicyearid);
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
        public static function downloadUnverifiedApplicationsReport($academicyearid)
        {
            $data_provider = ApplicationPeriodBuilder::generateUnverifiedApplicationsReport($academicyearid);
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