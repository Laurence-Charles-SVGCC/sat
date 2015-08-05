<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use common\controllers\DatabaseWrapperController;
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

    /*
    * Purpose: Displays all applicants from a given centre
    * Created: 15/07/2015 by Gii
    * Last Modified: 16/07/2015 by Gamal Crichton
    */
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

    /*
    * Purpose: Displays pending applicants from a given centre
    * Created: 15/07/2015 by Gii
    * Last Modified: 16/07/2015 by Gamal Crichton
    */
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

    /*
    * Purpose: Displays verified applicants from a given centre
    * Created: 15/07/2015 by Gii
    * Last Modified: 16/07/2015 by Gamal Crichton
    */
    public function actionViewVerified($cseccentreid, $centrename)
    {
        $data = array();
        foreach(self::centreApplicantsVerified($cseccentreid) as $application)
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
                    'type' => 'Verified',
                    'centrename' => $centrename,
                    'centreid' => $cseccentreid,
                ]);
    }
    
    /*
    * Purpose: Displays queried applicants from a given centre
    * Created: 15/07/2015 by Gii
    * Last Modified: 16/07/2015 by Gamal Crichton
    */
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
    
    /*
    * Purpose: Displays all certificates for a given applicant from a given centre.
     * Handles actions from the display: add more certificates, save all as verified
     * and save changes.
    * Created: 15/07/2015 by Gamal Crichton
    * Last Modified: 18/07/2015 by Gamal Crichton
    */
    public function actionViewApplicantQualifications($applicantid, $centrename, $cseccentreid, $type)
    {
        if (Yii::$app->request->post())
        {
            $qualifications = CsecQualification::find()->where(['personid' => $applicantid, 'isdeleted' => 0])->all();
            $request = Yii::$app->request;
            if ($request->post('add_more') === '')
            {
                $add_amt = $request->post('add_more_value');
                $qmodel = $qualifications[0];
                for ($i = 0; $i < $add_amt; $i++)
                {
                    $qualification = new CsecQualification();
                    $qualification->personid = $applicantid;
                    $qualification->cseccentreid = $cseccentreid;
                    $qualification->examinationbodyid = $qmodel ? $qmodel->examinationbodyid : 1;
                    $qualification->subjectid = 1;
                    $qualification->examinationproficiencytypeid = $qmodel ? $qmodel->examinationproficiencytypeid : 1;
                    if (!$qualification->save()) 
                    {
                        Yii::$app->getSession()->setFlash('error', 'Could not add more certificates.');
                        break;
                    }
                }
            }
            else 
            {
                if ($request->post('CsecQualification'))
                {
                    $verify_all = $request->post('verified') === '' ? True : False;
                    $qualifications = $request->post('CsecQualification');
                    foreach ($qualifications as $qual)
                    {
                        $cert = CsecQualification::find()->where(['csecqualificationid' => $qual['csecqualificationid']])->one();
                        $cert->examinationbodyid = $qual['examinationbodyid'];
                        $cert->year = $qual['year'];
                        $cert->examinationproficiencytypeid = $qual['examinationproficiencytypeid'];
                        $cert->subjectid = $qual['subjectid'];
                        $cert->examinationgradeid = $qual['examinationgradeid'];
                        if ($verify_all)
                        {
                            //Save as verified submit button
                            $cert->isverified = 1;
                            $cert->isqueried = 0;
                        }
                        else
                        {
                            //Update submit button
                            $cert->isverified = $qual['isverified'];
                            $cert->isqueried = $qual['isqueried'];
                        }
                        if (!$cert->save())
                        {
                            Yii::$app->getSession()->setFlash('error', 'Could not add Certificate: ' . $qual['subjectid'] . ' ' . $qual['grade']);
                        }
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'No Certificates data.');
                }
                //redirect
                if (strcasecmp($type, "pending"))
                {
                    self::actionViewPending($cseccentreid, $centrename);
                }
                if (strcasecmp($type, "queried"))
                {
                    self::actionViewQueried($cseccentreid, $centrename);
                }
                if (strcasecmp($type, "all"))
                {
                    self::actionViewAll($cseccentreid, $centrename);
                }
                if (strcasecmp($type, "verified"))
                {
                    self::actionViewVerified($cseccentreid, $centrename);
                }
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => CsecQualification::find()->where(['personid' => $applicantid, 'isdeleted' => 0])->all(),
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['personid', 'examiningbody', 'examyear', 'proficiency', 'subject', 'grade', 'verified', 'queried'],
            ],
        ]);
        $applicant_model = Applicant::find()->where(['personid' => $applicantid])->one();
        return $this->render('view-applicant-qualifications',
                [
                    'dataProvider' => $dataProvider,
                    'applicant' => $applicant_model,
                    'centrename' => $centrename,
                    'centreid' => $cseccentreid,
                    'type' => $type,
                ]);
    }
    
    /*
    * Purpose: Soft deletes a given certificate.
    * Created: 18/07/2015 by Gamal Crichton
    * Last Modified: 18/07/2015 by Gamal Crichton
    */
    public function actionDeleteCertificate($certificate_id)
    {
        $cert = CsecQualification::find()->where(['csecqualificationid' => $certificate_id])->one();
        $cert->isdeleted = 1;
        $cert->isactive = 0;
        if (!$cert->save())
        {
            Yii::$app->session->setFlash('error', 'Certificate could not be deleted');
        }
        return $this->redirect(\Yii::$app->request->getReferrer());
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
    * Purpose: Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who have already been fully verified
    * Created: 15/07/2015 by Gamal Crichton
    * Last Modified: 15/07/2015 by Gamal Crichton
    */
    private function centreApplicantsQueried($cseccentreid)
    {
        return DatabaseWrapperController::centreApplicantsQueried($cseccentreid);
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
        return DatabaseWrapperController::centreApplicantsQueriedCount($cseccentreid);
    }
    
    

}
