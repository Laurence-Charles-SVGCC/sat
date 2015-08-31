<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use common\controllers\DatabaseWrapperController;
use frontend\models\Applicant;
use frontend\models\CsecQualification;
use frontend\models\ApplicationStatus;
use frontend\models\Application;
use frontend\models\CsecCentre;

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
        
        //For external applicants
        $amt_received = count(self::getExternal());
        $centre_row = array();
        $centre_row['centre_name'] = "External";
        $centre_row['centre_id'] = '00000';
        $centre_row['status'] = "N/A";
        $centre_row['applicants_verified'] = "N/A";
        $centre_row['total_received'] = $amt_received;
        $centre_row['percentage_completed'] = 0;
        $data[] = $centre_row;
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'defaultOrder' => ['centre_name' => SORT_ASC],
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
        if (strcasecmp($centre_name, "external") == 0)
        {
            $amt_received = count(self::getExternal());
            $amt_verified = "N/A";
            $amt_queried = "N/A";
            $amt_pending = 0;
        }
        else
        {
            $amt_received = self::centreApplicantsReceivedCount($centre_id);
            $amt_verified = self::centreApplicantsVerifiedCount($centre_id);
            $amt_queried = self::centreApplicantsQueriedCount($centre_id);
            $amt_pending = $amt_received - ($amt_verified + $amt_queried);
        }

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
        if (strcasecmp($centrename, "external") == 0)
        {
            $data = self::getExternal();
        }
        else
        {
            $data = array();
            foreach(self::centreApplicantsReceived($cseccentreid) as $application)
            {
                $data[] = Applicant::find()->where(['personid' => $application->personid])->one();
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
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
        foreach(self::centreApplicantsPending($cseccentreid) as $application)
        {
            $data[] = Applicant::find()->where(['personid' => $application->personid])->one();
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
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
                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
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
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
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
                
                if (count($qualifications) > 0)
                {
                    $qmodel = $qualifications[0];
                }
                else
                {
                    $qmodel = NULL;
                    $centre = CsecCentre::findOne(['isactive' => 0, 'isdeleted' => 0]);
                    $cseccentreid = $centre ? $centre->cseccentreid : NULL;
                }
                if ($qmodel || $cseccentreid)
                {
                    for ($i = 0; $i < $add_amt; $i++)
                    {
                        $qualification = new CsecQualification();
                        $qualification->personid = $applicantid;
                        $qualification->cseccentreid = $cseccentreid;
                        $qualification->examinationbodyid = $qmodel ? $qmodel->examinationbodyid : 3;
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
                    Yii::$app->session->setFlash('error', 'Centre to add certificates to not found');
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
                $all_certs = count(CsecQualification::findAll(['personid' => $applicantid, 'isdeleted' => 0]));
                $verified_certs = count(CsecQualification::findAll(['personid' => $applicantid, 'isdeleted' => 0, 'isverified' => 1]));
                if ($all_certs == $verified_certs)
                {
                    $pending = ApplicationStatus::findOne(['name' => 'pending']);
                    if ($pending)
                    {
                        $applications = Application::findAll(['personid' => $applicantid]);
                        foreach($applications as $application)
                        {
                            $application->applicationstatusid = $pending->applicationstatusid;
                            $application->save();
                        }
                    }
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
    * Purpose: Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who are still pending
    * Created: 14/08/2015 by Gamal Crichton
    * Last Modified: 14/08/2015 by Gamal Crichton
    */
    private function centreApplicantsPending($cseccentreid)
    {
        return DatabaseWrapperController::centreApplicantsPending($cseccentreid);
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
    
    private function getExternal()
    {
        $data = array();
        $applications = Application::find()
                ->leftjoin('applicant', '`application`.`personid` = `applicant`.`personid`')
                ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->leftjoin('application_history', '`application_history`.`applicationid` = `application`.`applicationid`')
                ->where(['application_period.isactive' => 1, 'application.isdeleted' => 0, 'applicant.isexternal' => 1,
                    'academic_offering.isdeleted' => 0, 'application_history.applicationstatusid' => [2,3,4,5,6,7,8,9]])
                ->groupby('application.personid')->all();
        
        $centre = CsecCentre::findOne(['isactive' => 0, 'isdeleted' => 0]);
        $cseccentreid = $centre ? $centre->cseccentreid : NULL;
        foreach($applications as $application)
        {
            $qual = CsecQualification::findOne(['personid' => $application->personid, 'isdeleted' => 0]);
            if (!$qual || $qual->cseccentreid == $cseccentreid)
            {
                $data[] = Applicant::find()->where(['personid' => $application->personid])->one();
            }
        }
        return $data;
    }
    

}
