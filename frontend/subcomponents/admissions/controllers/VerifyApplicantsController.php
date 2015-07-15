<?php

namespace app\subcomponents\admissions\controllers;

use yii\data\ArrayDataProvider;
use common\controllers\DatabaseWrapperController;

class VerifyApplicantsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //['centre_name'=>'test', 'status'=>'finished', 'applicants_verified'=>12, 'total_received' =>35, 'percentage_completed'=>25.65]
        $data = array();
        $current_centres = self::getCurrentCentres();
        echo "Get current centres is " . $current_centres[0];
        /*foreach ($current_centres as $centre)
        {
            $amt_received = count(self::centreApplicantsReceived($centre->cseccentreid));
            $amt_verified = count(self::centreApplicantsVerified($centre->cseccentreid));
            
            $centre_row = array();
            $centre_row['centre_name'] = $centre->name;
            $centre_row['status'] = ($amt_received - $amt_verified) <= 0 ? "Complete" : "Incomplete";
            $centre_row['applicants_verified'] = $amt_verified;
            $centre_row['total_received'] = $amt_received;
            $centre_row['applicants_verified'] = $amt_received == 0 ? 0 : round(($amt_verified/$amt_received) * 100, 2);
            $data[] = $centre_row;
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'attributes' => ['centre_name', 'status', 'applicants_verified', 'total_received', 'percentage_completed'],
            ],
        ]);
        
        return $this->render('index',
                ['dataProvider' => $dataProvider]
                );*/
    }
    
    public function actionCentreDetails()
    {
        return $this->render('centre-details');
    }

    public function actionViewAll()
    {
        return $this->render('view-all');
    }

    public function actionViewPending()
    {
        return $this->render('view-pending');
    }

    public function actionViewVerified()
    {
        return $this->render('view-verified');
    }
    
    /*
    * Purpose: Gets the CSEC Centres relevant to active application periods
    * Created: 15/07/2015 by Gamal Crichton
    * Last Modified: 15/07/2015 by Gamal Crichton
    */
    private function getCurrentCentres()
    {
        return DatabaseWrapperController::getCurrentCentres();
    }
    
    /*
    * Purpose: Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
    * Created: 15/07/2015 by Gamal Crichton
    * Last Modified: 15/07/2015 by Gamal Crichton
    */
    private function centreApplicantsReceived($cseccentreid)
    {
        return DatabaseWrapperController::centreApplicantsReceived($cseccentreid);
    }
    
    /*
    * Purpose: Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who have already been fully verified
    * Created: 15/07/2015 by Gamal Crichton
    * Last Modified: 15/07/2015 by Gamal Crichton
    */
    private function centreApplicantsVerified($cseccentreid)
    {
        return DatabaseWrapperController::centreApplicantsVerified($cseccentreid);
    }

}
