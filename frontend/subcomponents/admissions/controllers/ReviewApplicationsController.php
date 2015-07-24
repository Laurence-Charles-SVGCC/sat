<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use frontend\models\Application;
use frontend\models\Division;
use frontend\models\ProgrammeCatalog;
use frontend\models\AcademicOffering;

class ReviewApplicationsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //$amt_received = self::divisionApplicationsReceivedCount($division_id, 1);
        //$amt_offers = self::divisionOffersCount($division_id);
        //$amt_queried = self::centreApplicantsQueriedCount($centre_id);
        //$amt_pending = $amt_received - $amt_verified;
        
        //Determine user's division_id
        $division_id = 4;
        
        if (Yii::$app->request->post())
        {
            $application_status = Yii::$app->request->post('application_status');
            $applications = Application::find()->where(['applicationstatusid' => $application_status])->all();
            self::actionViewApplicationApplicant($division_id, $applications);
        }

        return $this->render('index',
                [
                    //'centre_name' => $centre_name,
                    //'all' => $amt_received,
                    /*'pending' => $amt_pending,
                    'verified' => $amt_verified,
                    'queried' => $amt_queried,
                    'total' => $amt_received,*/
                ]);
    }
    
    private function actionViewApplicationApplicant($division_id, $applications)
    {
        $data = array();
        foreach($applications as $application)
        {
            $app_details = array();
            $programme = ProgrammeCatalog::find()
                ->joinWith('academic_offering')
                ->joinWith('application')
                ->where(['application.applicationid' => $application->applicationid])->one();
            $applicant = Applicant::find()->where(['personid' => $application->personid])->one();
            
            $app_details['firstname'] = $applicant->firstname;
            $app_details['lastname'] = $applicant->lastname;
            $app_details['programme'] = $programme->name;
            
            $data[] = $app_details;
        }
        
        $programmes = ProgrammeCatalog::find()
                ->join(`academic_offering`, `academic_offering`.``)
                ->joinWith('applicationperiod')
                ->where(['application_period.isactive' => 1, 'application_period.divisionid' => $division_id])
                ->all();
        var_dump($programmes[0]->programme );
        return $this->render('view-application-applicant',
            [
                'results' => $data,
                'programmes' => $programmes,
            ]);
    }

    public function actionViewBorderline()
    {
        return $this->render('view-borderline');
    }

    public function actionViewOffers()
    {
        return $this->render('view-offers');
    }

    public function actionViewPending()
    {
        return $this->render('view-pending');
    }

    public function actionViewRejected()
    {
        return $this->render('view-rejected');
    }

    public function actionViewShortlist()
    {
        return $this->render('view-shortlist');
    }
    
    /*
    * Purpose: Gets counts of the Applications to a particular Division relevant to active application periods
     *          who have already been fully verified
    * Created: 23/07/2015 by Gamal Crichton
    * Last Modified: 23/07/2015 by Gamal Crichton
    */
    public function divisionApplicationsReceivedCount($division_id, $order)
    {
        return DatabaseWrapperController::divisionApplicationsReceivedCount($division_id, $order);
    }

}
