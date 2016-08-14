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
                'cors' => [
                    // restrict access to
                    'Origin' => ['http://sat.svgcc.vc'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT'],
                    
//                    'Access-Control-Allow-Origin' => ['http://www.sat.svgcc.vc'],
//                    'Origin' => ['http://sat.svgcc.vc/index.php?r=subcomponents%2Fadmissions%2Fadmissions%2Fperiod-setup-step-one'],
                    
                
                
                
                
                
                
                
                    // Allow only POST and PUT methods
//                    'Access-Control-Request-Headers' => ['X-Wsse'],
                    
                    // Allow only headers 'X-Wsse'
//                    'Access-Control-Allow-Credentials' => true,
                    
                    // Allow OPTIONS caching
//                    'Access-Control-Max-Age' => 3600,
                    
                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
//                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                ],
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

