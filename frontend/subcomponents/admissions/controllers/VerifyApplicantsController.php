<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Request;
use yii\helpers\Json;
//use common\controllers\DatabaseWrapperController;
use yii\base\Model;

use common\models\User;
use frontend\models\Applicant;
use frontend\models\CsecQualification;
use frontend\models\ApplicationStatus;
use frontend\models\Application;
use frontend\models\CsecCentre;
use frontend\models\PostSecondaryQualification;
use frontend\models\Subject;
use frontend\models\ExaminationProficiencyType;
use frontend\models\ExaminationGrade;
use frontend\models\ExaminationBody;




class VerifyApplicantsController extends \yii\web\Controller
{
    /*
    * Purpose: Displays centres and statistics of verification
    * Created: 15/07/2015 by Gii
    * Last Modified: 16/07/2015 by Gamal Crichton | 18/02/2016 by L.Charles
    */
    public function actionIndex()
    {
        $data = array();
        $current_centres = CsecCentre::getCurrentCentres();
        
        /*
         *  If there is an active application the associated csec centres are retreived
         */
        if ($current_centres == true)      
        {
            foreach ($current_centres as $centre)
            {
                $amt_received = Application::centreApplicantsReceivedCount($centre->cseccentreid);
                $amt_verified = Application::centreApplicantsVerifiedCount($centre->cseccentreid);

                $centre_row = array();
                $centre_row['centre_name'] = $centre->name;
                $centre_row['centre_id'] = $centre->cseccentreid;
                $centre_row['status'] = ($amt_received - $amt_verified) <= 0 ? "Complete" : "Incomplete";
                $centre_row['applicants_verified'] = $amt_verified;
                $centre_row['total_received'] = $amt_received;
                $centre_row['percentage_completed'] = $amt_received == 0 ? 0 : round(($amt_verified/$amt_received) * 100, 2);
                array_push($data, $centre_row);
            }

            //For external applicants
            $amt_received = count(Application::getExternal());
            $centre_row = array();
            $centre_row['centre_name'] = "External";
            $centre_row['centre_id'] = '00000';
            $centre_row['status'] = "N/A";
            $centre_row['applicants_verified'] = "N/A";
            $centre_row['total_received'] = $amt_received;
            $centre_row['percentage_completed'] = 0;
            array_push($data, $centre_row);

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
        }
        else
            $dataProvider = false;
        
        return $this->render('index',
                ['dataProvider' => $dataProvider]
                );
    }
    
    
    /*
    * Purpose: Displays verification dashboard of a centre
    * Created: 15/07/2015 by Gii
    * Last Modified: 16/07/2015 by Gamal Crichton | 18/02/2016 by L.Charles
    */
    public function actionCentreDetails($centre_id, $centre_name)
    {
        if (strcasecmp($centre_name, "external") == 0)
        {
            $amt_received = count(Application::getExternal());
            $amt_verified = "N/A";
            $amt_queried = "N/A";
            $amt_pending = 0;
        }
        else
        {
            $amt_received = Application::centreApplicantsReceivedCount($centre_id);
            $amt_verified = Application::centreApplicantsVerifiedCount($centre_id);
            $amt_queried = Application::centreApplicantsQueriedCount($centre_id);
//            $amt_pending = $amt_received - ($amt_verified + $amt_queried);
            $amt_pending = Application::centreApplicantsPendingCount($centre_id);
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
            $data = Application::getExternal();
        }
        else
        {
            $data = array();
            foreach(Application::centreApplicantsReceived($cseccentreid) as $application)
            {
                $data[] = Applicant::find()
                        ->where(['personid' => $application->personid])
                        ->one();
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
    * Last Modified: 16/07/2015 by Gamal Crichton | 18/02/2016 by L.Charles
    */
    public function actionViewPending($cseccentreid, $centrename)
    {
        $data = array();
        foreach(Application::centreApplicantsPending($cseccentreid) as $application)
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
    * Last Modified: 16/07/2015 by Gamal Crichton | 18/02/2016 by L.Charles
    */
    public function actionViewVerified($cseccentreid, $centrename)
    {
        $data = array();
        foreach(Application::centreApplicantsVerified($cseccentreid) as $application)
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
    * Last Modified: 16/07/2015 by Gamal Crichton | 18/02/2016 by L.Charles
    */
    public function actionViewQueried($cseccentreid, $centrename)
    {
        $data = array();
        foreach(Application::centreApplicantsQueried($cseccentreid) as $application)
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
    * Last Modified: 18/07/2015 by Gamal Crichton | 20/02/2016 by L.Charles
    */
    public function actionViewApplicantQualifications($applicantid, $centrename, $cseccentreid, $type)
    {
        $post_qualification = PostSecondaryQualification::find()
                ->where(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
            
        if (Yii::$app->request->post())
        {
            $qualifications = CsecQualification::find()
                            ->where(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0])
                            ->all();
            
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
                if ($post_qualification == true)        //if post secondary qualification record exists
                {
                    $post_secondary_save_flag = false;
                    $post_secondary_load_flag = false;

                    if ($post_qualification == true)
                    {
                        $post_secondary_load_flag = $post_qualification->load(Yii::$app->request->post());
                        if ($post_secondary_load_flag == true)
                        {
                            $post_secondary_save_flag = $post_qualification->save();
                            if ($post_secondary_save_flag == false)
                            {
                                Yii::$app->getSession()->setFlash('error', 'Error updating post secondary qualification record.');
                                return self::actionViewApplicantQualifications($applicantid, $centrename, $cseccentreid, $type);
                            }
                        }
                        else
                        {
                            Yii::$app->getSession()->setFlash('error', 'Error loading updating post secondary qualification recrord.');
                            return self::actionViewApplicantQualifications($applicantid, $centrename, $cseccentreid, $type);
                        }
                    }
                }
                
                if ($request->post('CsecQualification'))
                {
                    $verify_all = $request->post('verified') === '' ? true : false;
                    $qualifications = $request->post('CsecQualification');
                    foreach ($qualifications as $qual)
                    {
                        if ($qual['personid'] == true)
                        {
                            $cert = CsecQualification::find()
                                    ->where(['csecqualificationid' => $qual['csecqualificationid']])
                                    ->one();

                            $cert->examinationbodyid = $qual['examinationbodyid'];
                            $cert->candidatenumber = $qual['candidatenumber'];
                            $cert->year = $qual['year'];
                            $cert->examinationproficiencytypeid = $qual['examinationproficiencytypeid'];
                            $cert->subjectid = $qual['subjectid'];
                            $cert->examinationgradeid = $qual['examinationgradeid'];
                            if ($verify_all)
                            {
                                //Save as verified submit button
                                $cert->isverified = 1;
                                $cert->isqueried = 0;
                                if($post_qualification)
                                {
                                    $post_qualification->isverified = 1;
                                    $post_qualification->isqueried = 0;
                                    $post_qualification->save();
                                }
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
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'No Certificates data.');
                }
                $all_certs = count(CsecQualification::findAll(['personid' => $applicantid, 'isdeleted' => 0]));
                $verified_certs = count(CsecQualification::findAll(['personid' => $applicantid, 'isdeleted' => 0, 'isverified' => 1]));
                
                /*
                 * If post secondary qualification exists then both previous counts must take it into consideration
                 */
                if ($post_qualification)
                {
                    $all_certs++;
                    if ($post_qualification->isverified == 1)
                        $verified_certs++;
                }
                
                
                /*
                 * If all certifications are verified then all application statuses are set to "Pending'
                 */
                if ($all_certs == $verified_certs)
                {
                    $pending = ApplicationStatus::findOne(['name' => 'Pending']);
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
                /*
                 * If all certifications are not verified then all application statuses are reset to "Unverified'
                 */
                else
                {
                    $unverified = ApplicationStatus::findOne(['name' => 'Unverified']);
                    if ($unverified)
                    {
                        $applications = Application::findAll(['personid' => $applicantid]);
                        foreach($applications as $application)
                        {
                            $application->applicationstatusid = $unverified->applicationstatusid;
                            $application->save();
                        }
                    }
                }
                
                
                //redirect
                if (strcasecmp($type, "pending")==0)
                {
                    return self::actionViewPending($cseccentreid, $centrename);
                }
                elseif (strcasecmp($type, "queried")==0)
                {
                    return self::actionViewQueried($cseccentreid, $centrename);
                }
                elseif (strcasecmp($type, "all")==0)
                {
                    return self::actionViewAll($cseccentreid, $centrename);
                }
                elseif (strcasecmp($type, "verified")==0)
                {
                    return self::actionViewVerified($cseccentreid, $centrename);
                }
                
            }
        }
        $qualifications = CsecQualification::find()
                            ->where(['personid' => $applicantid, 'isdeleted' => 0])
                            ->all();
        $record_count = CsecQualification::find()
                            ->where(['personid' => $applicantid, 'isdeleted' => 0])
                            ->count();
        
        for ($k=0; $k<10; $k++)
        {
            $temp = new CsecQualification();
            
            $centres = CsecCentre::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
            $temp->cseccentreid = $centres[0]->cseccentreid;
            
            $temp->candidatenumber = "00000";
            
//            $temp->personid = $qualifications[0]->personid;
            
            $bodies = ExaminationBody::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
            foreach ($bodies as $key => $body) 
            {
                $subs = Subject::find()
                        ->where(['examinationbodyid' => $body->examinationbodyid , 'isactive' => 1, 'isdeleted' => 0])
                        ->all();
                $profs = ExaminationProficiencyType::find()
                        ->where(['examinationbodyid' => $body->examinationbodyid , 'isactive' => 1, 'isdeleted' => 0])
                        ->all();
                $grds = ExaminationGrade::find()
                        ->where(['examinationbodyid' => $body->examinationbodyid , 'isactive' => 1, 'isdeleted' => 0])
                        ->all();
                if (count($subs)==0 || count($profs)==0 || count($grds)==0)
                {
                    $temp->examinationbodyid = $bodies[$key]->examinationbodyid;
                    break;
                }  
            }
            
            $subjects = Subject::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
            $temp->subjectid = $subjects[0]->subjectid;
            
            $proficiencies = ExaminationProficiencyType::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
            $temp->examinationproficiencytypeid = $proficiencies[0]->examinationproficiencytypeid;
            
            $grades = ExaminationGrade::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
            $temp->examinationgradeid = $grades[0]->examinationgradeid;
            
            $temp->year = "1970";
            
            $qualifications[] = $temp;
            $temp = NULL;
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $qualifications,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['personid', 'examiningbody', 'examyear', 'proficiency', 'subject', 'grade', 'verified', 'queried'],
            ],
        ]);
        
        $applicant_model = Applicant::find()
                        ->where(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
        
        $username = $applicant_model->getPerson()->one()->username;
       
//        $record_count = count($qualifications);
                
        return $this->render('view-applicant-qualifications',
                [
                    'dataProvider' => $dataProvider,
                    'applicant' => $applicant_model,
                    'username' => $username,
                    'centrename' => $centrename,
                    'centreid' => $cseccentreid,
                    'type' => $type,
                    'isexternal' => $applicant_model->isexternal,
                    'post_qualification' => $post_qualification,
                    'record_count' => $record_count,
                ]);
    }
    
    
    /*
    * Purpose: Soft deletes a given certificate.
    * Created: 18/07/2015 by Gamal Crichton
    * Last Modified: 18/07/2015 by Gamal Crichton
    */
    public function actionDeleteCertificate($certificate_id)
    {
        $save_flag = false;
        $cert = CsecQualification::find()
                ->where(['csecqualificationid' => $certificate_id])
                ->one();
        if ($cert == true)
        {
            $cert->isdeleted = 1;
            $cert->isactive = 0;
            $save_flag = $cert->save();

            if ($save_flag == false)
                Yii::$app->session->setFlash('error', 'Certificate could not be deleted');
        }
        else
            Yii::$app->session->setFlash('error', 'Error retrieving certificate');
      
        return $this->redirect(\Yii::$app->request->getReferrer());
    }
    
    
    /**
     * Deletes 'Post Secondary Qualification' qualification
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 02/03/2016
     * Date Last Modified: 10/03/2016
     */
    public function actionDeletePostSecondaryQualification($recordid)
    {
        $save_flag = false;

        $qualification = PostSecondaryQualification::find()
                    ->where(['postsecondaryqualificationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($qualification)
        {
            $qualification->isactive = 0;
            $qualification->isdeleted = 1;
            $save_flag = $qualification->save();
            if ($save_flag == false)
                    Yii::$app->session->setFlash('error', 'Post Secondary Qualification could not be deleted');
        }
        else
            Yii::$app->session->setFlash('error', 'Error retrieving certificate');

        return $this->redirect(\Yii::$app->request->getReferrer());
    }
    
    
    /**
     * Saves "PostSecondaryQualification' record
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 15/03/2016
     * Date Last Modified: 25/05/2016
     */
    public function actionAddPostSecondaryQualification($personid, $cseccentreid, $centrename, $type)
    {
        $user = User::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        
        $qualification = new PostSecondaryQualification();

        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $qualification->load($post_data);
            if($load_flag == true)
            {
                
                $qualification->personid = $user->personid;
                $validation_flag = $qualification->validate();

                if($validation_flag == true)
                {
                    $save_flag = $qualification->save();
                    if($save_flag == true)
                    {
//                        return self::actionViewApplicantQualifications($user->personid, $centrename, $cseccentreid, $type);
                        //redirect
                        if (strcasecmp($type, "pending")==0)
                        {
                            return self::actionViewPending($cseccentreid, $centrename);
                        }
                        elseif (strcasecmp($type, "queried")==0)
                        {
                            return self::actionViewQueried($cseccentreid, $centrename);
                        }
                        elseif (strcasecmp($type, "all")==0)
                        {
                            return self::actionViewAll($cseccentreid, $centrename);
                        }
                        elseif (strcasecmp($type, "verified")==0)
                        {
                            return self::actionViewVerified($cseccentreid, $centrename);
                        }
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save qualification record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate qualification  record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load qualification  record. Please try again.');              
        }

        return $this->render('add_post_secondary_qualificiation', [
            'user' => $user,
            'qualification' => $qualification,
        ]); 
    }
    
    
    /**
     * Handles 'examination_body' dropdownlist of 'add_csecqualification' view
     * 
     * @param type $exam_body_id
     * 
     * Author: Laurence Charles
     * Date Created: 18/03/2016
     * Date Last Modified: 18/03/2016
     */
    public function actionExaminationBodyDependants($exam_body_id, $index)
    {
        $subjects = Subject::getSubjectList($exam_body_id);      
        $proficiencies = ExaminationProficiencyType::getExaminationProficiencyList($exam_body_id);
        $grades = ExaminationGrade::getExaminationGradeList($exam_body_id);
        $pass = NULL;

        if (count($subjects)>0  && count($proficiencies)>0  && count($grades)>0)    //if subjects related to examination body exist
        {     
            $pass = 1;
            echo Json::encode(['recordid' => $index, 'subjects' => $subjects, 'proficiencies' => $proficiencies, 'grades' => $grades, 'pass' => $pass]);       //return json encoded array of subjects    
        }
        else
        {
            $pass = 0;
            echo Json::encode(['recordid' => $index, 'pass'=> $pass]);
        }    
    }
    
    
    /**
     * Saves CsecQualifcation
     * 
     * @param type $personid
     * @param type $centrename
     * @param type $centreid
     * @param type $type
     * @param type $record_count
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 18/03/2016
     * Date Last Modified: 20/03/2016
     */
    public function actionSaveNewQualifications($personid, $centrename, $centreid, $type, $record_count, $qual_limit)
    {
        $all_qualifications = array();
        
        //creates CsecQualification record containers
        for ($i = 0 ; $i < $qual_limit ; $i++)
        {
            $temp = new CsecQualification();
            array_push($all_qualifications, $temp);               
        }
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
               
            $load_flag = Model::loadMultiple($all_qualifications, $post_data);
            if($load_flag == true)
            {
                /* Removes all the previously saved records.
                 * Ensures only additional are save by this operation
                 */
                for ($i=0 ; $i<$record_count ; $i++)
                {
                    unset($all_qualifications[$i]);
                }
                
                $transaction = \Yii::$app->db->beginTransaction();
                try 
                {
                    foreach ($all_qualifications as $qualification) 
                    {
                        $save_flag = false;
                        if($qualification->isValid() == true)
                        {
                            $qualification->personid = $personid;
                            if ($qualification->validate() == false)
                            {
                                continue;
                            }
                            else
                            {
                                $save_flag = $qualification->save();
                                if ($save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error saving certificates. Please try again');
                                }
                            }
                        }
                    }
                    $transaction->commit();
                  
                    //redirect
                    if (strcasecmp($type, "pending")==0)
                    {
                        return self::actionViewPending($centreid, $centrename);
                    }
                    elseif (strcasecmp($type, "queried")==0)
                    {
                        return self::actionViewQueried($centreid, $centrename);
                    }
                    elseif (strcasecmp($type, "all")==0)
                    {
                        return self::actionViewAll($centreid, $centrename);
                    }
                    elseif (strcasecmp($type, "verified")==0)
                    {
                        return self::actionViewVerified($centreid, $centrename);
                    }
                } catch (Exception $ex) 
                {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing your request. Please try again');
                }
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured loading records. Please try again');
            }
        }
    }
    
    
    
}
