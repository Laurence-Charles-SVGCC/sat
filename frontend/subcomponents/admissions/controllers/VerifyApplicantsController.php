<?php

namespace app\subcomponents\admissions\controllers;

use yii\data\ArrayDataProvider;
//use yii\data\ActiveDataProvider;
use common\controllers\DatabaseWrapperController;
//use frontend\models\CsecCentre;
//use yii\helpers\ArrayHelper;
use frontend\models\Applicant;
use frontend\models\CsecQualification;

class VerifyApplicantsController extends \yii\web\Controller
{
    /*
    * Purpose: Displays centres and statistics of verification
    * Created: 15/07/2015 by Gii
    * Last Modified: 16/07/2015 by Gamal Crichton
    */
    public function actionIndex()
    {
        $data = array();
        $current_centres = self::getCurrentCentres();
        foreach ($current_centres as $centre)
        {
            $amt_received = self::centreApplicantsReceivedCount($centre->cseccentreid);
            $amt_verified = self::centreApplicantsVerifiedCount($centre->cseccentreid);
            
            $centre_row = array();
            $centre_row['centre_name'] = $centre->name;
            $centre_row['centre_id'] = $centre->cseccentreid;
            $centre_row['status'] = ($amt_received - $amt_verified) <= 0 ? "Complete" : "Incomplete";
            $centre_row['applicants_verified'] = $amt_verified;
            $centre_row['total_received'] = $amt_received;
            $centre_row['percentage_completed'] = $amt_received == 0 ? 0 : round(($amt_verified/$amt_received) * 100, 2);
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
                );
    }
    
    /*
    * Purpose: Displays verification dashboard of a centre
    * Created: 15/07/2015 by Gii
    * Last Modified: 16/07/2015 by Gamal Crichton
    */
    public function actionCentreDetails($centre_id, $centre_name)
    {
        $amt_received = self::centreApplicantsReceivedCount($centre_id);
        $amt_verified = self::centreApplicantsVerifiedCount($centre_id);
        $amt_pending = $amt_received - $amt_verified;
        $amt_queried = self::centreApplicantsQueriedCount($centre_id);

        return $this->render('centre-details',
                [
                    'centre_name' => $centre_name,
                    'centre_id' => $centre_id,
                    'pending' => $amt_pending,
                    'verified' => $amt_verified,
                    'queried' => $amt_queried,
                    'total' => $amt_received,
                ]);
    }

    public function actionViewAll($cseccentreid, $centrename)
    {
        $data = array();
        foreach(self::centreApplicantsReceived($cseccentreid) as $application)
        {
            $data[] = Applicant::find()->where(['personid' => $application->personid])->one();
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['personid', 'firstname', 'middlenames', 'lastname', 'gender'],
            ],
        ]);
        return $this->render('view-applicant',
                [
                    'dataProvider' => $dataProvider,
                    'type' => 'All',
                    'centrename' => $centrename,
                    'centreid' => $cseccentreid,
                ]);
    }

    public function actionViewPending($cseccentreid, $centrename)
    {
        $data = array();
        foreach(self::centreApplicantsReceived($cseccentreid) as $application)
        {
            $data[] = Applicant::find()->where(['personid' => $application->personid])->one();
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['personid', 'firstname', 'middlenames', 'lastname', 'gender'],
            ],
        ]);
        return $this->render('view-applicant',
                [
                    'dataProvider' => $dataProvider,
                    'type' => 'Pending',
                    'centrename' => $centrename,
                    'centreid' => $cseccentreid,
                ]);
    }

    public function actionViewVerified($cseccentreid, $centrename)
    {
        $data = array();
        foreach(self::centreApplicantsVerified($cseccentreid) as $application)
        {
            $data[] = Applicant::find()->where(['personid' => $application->personid])->one();
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => self::centreApplicantsVerified($cseccentreid),
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['personid', 'firstname', 'middlenames', 'lastname', 'gender'],
            ],
        ]);
        return $this->render('view-applicant',
                [
                    'dataProvider' => $dataProvider,
                    'type' => 'Verified',
                    'centrename' => $centrename,
                    'centreid' => $cseccentreid,
                ]);
    }
    
    public function actionViewQueried($cseccentreid, $centrename)
    {
        $data = array();
        foreach(self::centreApplicantsQueried($cseccentreid) as $application)
        {
            $data[] = Applicant::find()->where(['personid' => $application->personid])->one();
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => self::centreApplicantsQueried($cseccentreid),
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['personid', 'firstname', 'middlenames', 'lastname', 'gender'],
            ],
        ]);
        return $this->render('view-applicant',
                [
                    'dataProvider' => $dataProvider,
                    'type' => 'Queried',
                    'centrename' => $centrename,
                    'centreid' => $cseccentreid,
                ]);
    }
    
    public function actionViewApplicantQualifications($applicantid/*, $centrename, $cseccentreid, $type*/)
    {
        /*$data = array();
        foreach(self::centreApplicantsQueried($cseccentreid) as $application)
        {
            $data[] = Applicant::find()->where(['personid' => $application->personid])->one();
        }*/
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => CsecQualification::find()->where(['personid' => $applicantid, 'isdeleted' => 0])->all(),
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['personid', 'examiningbody', 'examyear', 'proficiency', 'subject', 'grade', 'verified', 'queried'],
            ],
        ]);
        return $this->render('view-applicant-qualifications',
                [
                    'dataProvider' => $dataProvider,
                    /*'centrename' => $centrename,
                    'centreid' => $cseccentreid,
                    'type' => $type,*/
                ]);
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
    
    /*
    * Purpose: Gets count of the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
    * Created: 16/07/2015 by Gamal Crichton
    * Last Modified: 16/07/2015 by Gamal Crichton
    */
    private function centreApplicantsReceivedCount($cseccentreid)
    {
        return DatabaseWrapperController::centreApplicantsReceivedCount($cseccentreid);
    }
    
    /*
    * Purpose: Gets counts of the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who have already been fully verified
    * Created: 16/07/2015 by Gamal Crichton
    * Last Modified: 16/07/2015 by Gamal Crichton
    */
    private function centreApplicantsVerifiedCount($cseccentreid)
    {
        return DatabaseWrapperController::centreApplicantsVerifiedCount($cseccentreid);
    }
    
    /*
    * Purpose: Gets counts of the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who have a certificate flagged as to be queried
    * Created: 16/07/2015 by Gamal Crichton
    * Last Modified: 16/07/2015 by Gamal Crichton
    */
    private function centreApplicantsQueriedCount($cseccentreid)
    {
        return DatabaseWrapperController::centreApplicantsVerifiedCount($cseccentreid);
    }

}
