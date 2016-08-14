<?php

    namespace app\subcomponents\admissions\controllers;
    
    use Yii;
    use yii\helpers\Json;
    use yii\filters\auth\HttpBasicAuth;
    use yii\rest\ActiveController;
    
    use frontend\models\ApplicationPeriod;
    use frontend\models\AcademicYear;
    
    class AdmissionsController extends ActiveController
    {   
        
        public function behaviors()
        {
            $behaviors = parent::behaviors();

            // remove authentication filter
            $auth = $behaviors['authenticator'];
            unset($behaviors['authenticator']);

            // add CORS filter
            $behaviors['corsFilter'] = [
                'class' => \yii\filters\Cors::className(),
            ];

            // re-add authentication filter
            $behaviors['authenticator'] = $auth;
            // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
            $behaviors['authenticator']['except'] = ['options'];

            return $behaviors;
        }

        
        public function actionProcessApplicantIntentid($divisionid, $applicationperiodtypeid, $applicantintentid)
        {
            $academicYearExists = 0;
            $applicationPeriodExists = 0;

            if ($applicantintentid == 1)
            {
                $academicYear = AcademicYear::find()
                        ->where(['applicantintentid' => $applicantintentid, 'iscurrent' => 1, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                 if ($academicYear)   
                 {
                     $academicYearExists = 1;
                     $period = ApplicationPeriod::find()
                             ->where(['divisionid' => $divisionid, 'iscomplete' => 0, 'isactive' => 1, 'isdeleted' => 0, 'iscomplete' => 0])
                             ->one();
                     if ($period)
                     {
                         $applicationPeriodExists = 1;
                     }
                 }
            }

            echo Json::encode(['academicYearExists' => $academicYearExists, 'applicationPeriodExists' => $applicationPeriodExists]);
        }
    }

