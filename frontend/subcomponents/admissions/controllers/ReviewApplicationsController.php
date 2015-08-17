<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use frontend\models\Application;
use frontend\models\ProgrammeCatalog;
use frontend\models\AcademicOffering;
use frontend\models\Applicant;
use frontend\models\CsecQualification;
use frontend\models\Offer;
use frontend\models\ApplicationStatus;
use frontend\models\ApplicationCapesubject;
use frontend\models\CapeSubjectGroup;
use frontend\models\CapeGroup;
use frontend\models\AcademicYear;
use frontend\models\ExaminationBody;
use frontend\models\Subject;
use frontend\models\ExaminationGrade;
use common\models\User;

class ReviewApplicationsController extends \yii\web\Controller
{   
    /*
    * Purpose: Displays dashboard for reviewing offers
    * Created: 24/07/2015 by Gamal Crichton
    * Last Modified: 13/08/2015 by Gamal Crichton
    */
    public function actionIndex()
    {
        //Determine user's division_id
        $division_id = Yii::$app->session->get('divisionid');
        
        if (Yii::$app->request->post())
        {
            $application_status = Yii::$app->request->post('application_status_id');
            return $this->redirect(Url::to(['review-applications/view-by-status', 'division_id' => $division_id, 
                'application_status' => $application_status]));
        }
        
        $appstatuses = ApplicationStatus::find()->all();
        $statuscounts = array();
        foreach ($appstatuses as $key => $appstatus)
        {
            if (in_array(strtolower($appstatus->name), array('incomplete', 'unverified')))
            {
                unset($appstatuses[$key]);
            }
            else
            {
                if ($division_id == 1)
                {
                    $condarr = ['isdeleted' => 0, 'ordering' => 1, 
                    'applicationstatusid' => $appstatus->applicationstatusid];
                }
                else
                {
                    $condarr = ['divisionid' => $division_id, 'isdeleted' => 0, 'ordering' => 1, 
                    'applicationstatusid' => $appstatus->applicationstatusid];
                }
                $statuscounts[$appstatus->applicationstatusid] = Application::find()->where($condarr)->count();
  
            }
        }
        return $this->render('index',
                [
                    'division_id' => $division_id,
                    'appstatuses' => $appstatuses,
                    'statuscounts' => $statuscounts,
                ]);
    }
    
    /*
    * Purpose: Allows viewing of applications based on their current status. 
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 17/08/2015 by Gamal Crichton
    */
    public function actionViewByStatus($application_status, $division_id)
    {
        if ($division_id == 1)
        {
            $condarr = ['isdeleted' => 0, 'ordering' => 1, 
            'applicationstatusid' => $application_status];
        }
        else
        {
            $condarr = ['divisionid' => $division_id, 'isdeleted' => 0, 'ordering' => 1, 
            'applicationstatusid' => $application_status];
        }
        $applications = Application::find()->where($condarr)->all();
        return self::actionViewApplicationApplicant($division_id, $applications, $application_status);
    }
    
    /*
    * Purpose: Takes an array of applications and get necessary information for 
     *  review table columns. 
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton
    */
    private function actionViewApplicationApplicant($division_id, $applications, $application_status, $sort_attributes = '')
    {
        if ($sort_attributes == '')
        {
            $sort_attributes = array();
        }
        $data = array();
        foreach($applications as $application)
        {
            $app_details = array();
            $cape_subjects_names = array();
            $programme = ProgrammeCatalog::find()
                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->where(['application.applicationid' => $application->applicationid])->one();
            $applicant = Applicant::find()->where(['personid' => $application->personid])->one();
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
            
            $app_details['applicationid'] = $application->applicationid;
            $app_details['applicantid'] = $applicant->getPerson()->one()->username;
            $app_details['firstname'] = $applicant->firstname;
            $app_details['middlename'] = $applicant->middlename;
            $app_details['lastname'] = $applicant->lastname;
            $app_details['programme'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
            
            $app_details['subjects_no'] = self::getSubjectsPassedCount($application->personid);
            $app_details['ones_no'] = self::getSubjectGradesCount($application->personid, 1);
            $app_details['twos_no'] = self::getSubjectGradesCount($application->personid, 2);
            $app_details['threes_no'] = self::getSubjectGradesCount($application->personid, 3);
           
            $data[] = $app_details;
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'attributes' => ['subjects_no', 'ones_no', 'twos_no', 'threes_no'],
                ]
        ]);
        $prog_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1);
        if ($division_id && $division_id == 1)
        {
            $prog_cond = array('application_period.isactive' => 1);
        }
        $programmes = ProgrammeCatalog::find()
                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                ->where($prog_cond)
                ->all();
        $progs = array();
        foreach ($programmes as $programme)
        {
            $progs[$programme->programmecatalogid] = $programme->getFullName();
        }
        return $this->render('view-application-applicant',
            [
                'results' => $dataProvider,
                'programmes' => $progs,
                'application_status' => $application_status,
                'division_id' => $division_id,
            ]);
    }

    /*
    * Purpose: Updates view of applications by selected criteria
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton
    */
    public function actionUpdateView()
    {
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $application_status = $request->post('application_status');
            $division_id = $request->post('division_id');
            $programme = $request->post('programme');
            $first_priority = $request->post('first_priority');
            $second_priority = $request->post('second_priority');
            $third_priority = $request->post('third_priority');
            
            if ($programme != 0)
            {
                $applications = Application::find()
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('programme_catalog', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                        ->where(['application.applicationstatusid' => $application_status, 'programme_catalog.programmecatalogid' => $programme])
                        ->all();
            }
            else
            {
                $applications = Application::find()
                        ->where(['applicationstatusid' => $application_status])
                        ->all();
            }
            /*Priorities is not implemented. Need investigations into how multiple levels of sorting can be
               done by Yii dataProvider
              Gamal Crichton 27/07/2015 */
            $priorities = array();
            if ($first_priority != 'none')
            {   
                array_push($priorities, 
                        [$first_priority => 
                            ['desc' => [$first_priority => SORT_DESC]]
                        ]
                     );  
            }
            if ($first_priority != 'none' && $second_priority != 'none') 
            {
                array_push($priorities, 
                        [$second_priority => 
                            ['desc' => [$second_priority => SORT_DESC]]
                        ]
                     );  
            }
            if ($first_priority != 'none' && $second_priority != 'none' && $third_priority != 'none') 
            {
                array_push($priorities, 
                        [$third_priority => 
                            ['desc' => [$third_priority => SORT_DESC]]
                        ]
                     );  
            }  
            return self::actionViewApplicationApplicant($division_id, $applications, $application_status, $priorities);
        }
        
    }
    
    /*
    * Purpose: Gets counts of all csec_subjects an applicants entered
     * NOTE: Not to be confused with getSubjectsPassedCount which counts only those which passed.
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton
    */
    private function getSubjectsCount($applicantid)
    {
        return CsecQualification::find()
                    ->innerJoin('examination_grade', '`examination_grade`.`examinationgradeid` = `csec_qualification`.`examinationgradeid`')
                    ->where(['personid' => $applicantid, 'isverified' => 1])
                    ->count();
    }
    
    /*
    * Purpose: Gets all csec_subjects an applicants has passed
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton
    */
    private function getSubjects($applicantid)
    {
        return CsecQualification::find()
                    ->where(['personid' => $applicantid, 'isverified' => 1, 'isdeleted' => 0])
                    ->all();
    }
    
    /*
    * Purpose: Gets all csec_subjects an applicants has passed
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton
    */
    private function getSubjectsPassedCount($applicantid)
    {
        return CsecQualification::find()
                    ->innerJoin('examination_grade', '`examination_grade`.`examinationgradeid` = `csec_qualification`.`examinationgradeid`')
                    ->where(['csec_qualification.personid' => $applicantid, 'csec_qualification.isverified' => 1, 'csec_qualification.isdeleted' => 0,
                        'examination_grade.ordering' => [1, 2, 3]])
                    ->count();
    }
    
    /*
    * Purpose: Gets counts of all csec_subjects an applicants has of a particular grade 
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton
    */
    private function getSubjectGradesCount($applicantid, $grade)
    {
        return CsecQualification::find()
                    ->innerJoin('examination_grade', '`examination_grade`.`examinationgradeid` = `csec_qualification`.`examinationgradeid`')
                    ->where(['csec_qualification.personid' => $applicantid, 'csec_qualification.isverified' => 1, 'examination_grade.ordering' => $grade,
                        'csec_qualification.isdeleted' => 0])
                    ->count();
    }

    /*
    * Purpose: Prepares Applications and applicants info for displaying 
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton
    */
    public function actionViewApplicantCertificates($applicantid, $firstname, $middlename, $lastname, 
            $programme, $application_status, $applicationid)
    {
        $application = Application::findOne(['applicationid' => $applicationid]);
        $personid = $application ? $application->personid : Null;
        $certificates = self::getSubjects($personid);
        $subjects_passed = self::getSubjectsPassedCount($personid);
        $has_english = self::hasEnglish($certificates);
        $has_math = self::hasMath($certificates);
        
        if (!$has_math && !$has_english)
        {
            if ($subjects_passed < 5)
            {
                Yii::$app->session->setFlash('error', 'Applicant passed less than 5 subjects and did not pass CSEC Math or CSEC English Language!');
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Applicant did not pass CSEC Math or CSEC English Language!');
            }
        }
        else if (!$has_math)
        {
            if ($subjects_passed < 5)
            {
                Yii::$app->session->setFlash('error', 'Applicant passed less than 5 subjects and did not pass CSEC Math!');
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Applicant did not pass CSEC Math!');
            }
        }
        else if (!$has_english)
        {
            if ($subjects_passed < 5)
            {
                Yii::$app->session->setFlash('error', 'Applicant passed less than 5 subjects and did not pass CSEC English Language!');
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Applicant did not pass CSEC English Language!');
            }
        }
        if ($has_english && $has_math && $subjects_passed < 5)
        {
            Yii::$app->session->setFlash('error', 'Applicant Passed less than 5 CSEC Subjects!');
        }
        //Get possible duplicates. needs work to deal with multiple years of certificates, but should catch majority
        if ($certificates)
        {
            $dups = self::getPossibleDuplicate($personid, $certificates[0]->candidatenumber, $certificates[0]->year);
            if ($dups)
            {
                $dupes = '';
                foreach($dups as $dup)
                {
                    $user = User::findOne(['personid' => $dup, 'isdeleted' => 0]);
                    $dupes = $user ? $dupes . ' ' . $user->username : $dupes;
                }
                Yii::$app->session->setFlash('warning', 'Applicant(s) ' . $dupes . ' had same Candidate Number in same CSEC Exam year!');
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $certificates,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'attributes' => ['subjects_no', 'ones_no', 'twos_no', 'threes_no'],
                ]
        ]);
        return $this->render('view-applicant-certificates',
                [
                    'applicantid' => $applicantid,
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'lastname' => $lastname,
                    'programme' => $programme,
                    'application_status' => $application_status,
                    'applicationid' => $applicationid,
                    'dataProvider' => $dataProvider,
                ]);
    }
    
    /*
    * Purpose: Implements various decisions to be done to application 
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 28/07/2015 by Gamal Crichton
    */
    public function actionProcessApplication()
    {
        if (Yii::$app->request->post())
        {
            //Get user's division_id from session
            $division_id = Yii::$app->session->get('divisionid');
            
            $request = Yii::$app->request;
            //Hidden variables needed for redirection
            $applicantid = $request->post('applicantid');
            $firstname = $request->post('firstname');
            $middlename = $request->post('middlename'); 
            $lastname = $request->post('lastname'); 
            $programme = $request->post('programme');  
            $applicationid = $request->post('applicationid');
            
            $application_status = $request->post('application_status');
            $application = Application::findOne(['applicationid' => $applicationid]);
            if ($request->post('make_offer') === '')
            {
                if (self::actionMakeOffer($applicationid, False))
                {
                    return $this->redirect(Url::to(['review-applications/view-by-status', 'division_id' => $division_id, 
                            'application_status' => $application_status]));
                }
                Yii::$app->session->setFlash('error', 'Offer could not be added');
            }
            if ($request->post('interview') === '')
            {
                $app_status = ApplicationStatus::findOne(['name' => 'interviewoffer']);
                $application->applicationstatusid = $app_status->applicationstatusid;
                if ($application->save())
                {
                    Yii::$app->session->setFlash('success', 'Application Updated');
                    return $this->redirect(Url::to(['review-applications/view-by-status', 'division_id' => $division_id, 
                        'application_status' => $application_status]));
                }
            }
            if ($request->post('shortlist') === '')
            {
                $app_status = ApplicationStatus::findOne(['name' => 'shortlist']);
                $application->applicationstatusid = $app_status->applicationstatusid;
                if ($application->save())
                {
                    Yii::$app->session->setFlash('success', 'Application Updated');
                    return $this->redirect(Url::to(['review-applications/view-by-status', 'division_id' => $division_id, 
                        'application_status' => $application_status]));
                }
            }
            if ($request->post('borderline') === '')
            {
                $app_status = ApplicationStatus::findOne(['name' => 'borderline']);
                $application->applicationstatusid = $app_status->applicationstatusid;
                if ($application->save())
                {
                    Yii::$app->session->setFlash('success', 'Application Updated');
                    return $this->redirect(Url::to(['review-applications/view-by-status', 'division_id' => $division_id, 
                        'application_status' => $application_status]));
                }
            }
            if ($request->post('reject') === '')
            {
                $app_status = ApplicationStatus::findOne(['name' => 'rejected']);
                $application->applicationstatusid = $app_status->applicationstatusid;
                if ($application->save())
                {
                    Yii::$app->session->setFlash('success', 'Application Updated');
                    return $this->redirect(Url::to(['review-applications/view-by-status', 'division_id' => $division_id, 
                        'application_status' => $application_status]));
                }
            }
            if ($request->post('refer') === '')
            {
                $app_status = ApplicationStatus::findOne(['name' => 'referred']);
                $application->applicationstatusid = $app_status->applicationstatusid;
                if ($application->save())
                {
                    Yii::$app->session->setFlash('success', 'Application Updated');
                    return $this->redirect(Url::to(['review-applications/view-by-status', 'division_id' => $division_id, 
                        'application_status' => $application_status]));
                }
            }
            if ($request->post('alternate_offer') === '')
            {
                //echo "applicantid is: $applicantid";
                $person = User::findOne(['username' => $applicantid]);
                $applicant = Applicant::findOne(['personid' => $person->personid]);
                $personid = $applicant->getPerson()->one() ? $applicant->getPerson()->one()->personid : NULL;
                $applications = $personid ? Application::findAll(['personid' => $personid, 'isdeleted' => 0]) : array();
                $data = array();
                foreach($applications as $application)
                {
                    $app_details = array();
                    $cape_subjects_names = array();
                    $programme = ProgrammeCatalog::find()
                        ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                        ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->where(['application.applicationid' => $application->applicationid])->one();
                    $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
                    foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
                    
                    $programme_division = $programme->getDepartment()->one()->divisionid;

                    $app_details['order'] = $application->ordering;
                    $app_details['applicationid'] = $application->applicationid;
                    $app_details['programme_name'] = $programme->name;
                    $app_details['subjects'] = empty($cape_subjects) ? $programme->name : $programme->name . ": " . implode(' ,', $cape_subjects_names);
                    $app_details['offerable'] = ($programme_division == $division_id || $division_id == 1);

                    $data[] = $app_details;
                }
                $dataProvider = new ArrayDataProvider([
                    'allModels' => $data,
                    'pagination' => [
                        'pageSize' => 5,
                    ],
                ]);
                $prog_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1);
                if ($division_id && $division_id == 1)
                {
                    $prog_cond = array('application_period.isactive' => 1);
                }
                $programmes = ProgrammeCatalog::find()
                    ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->where($prog_cond)
                    ->all();
                
                //Cape group information
                $cape_data = array();
                $cape_grps = CapeGroup::findall(['cape_group.isactive' => 1]);
                foreach ($cape_grps as $grp)
                {
                    $cape_data[$grp->name] = CapeSubjectGroup::findAll(['capegroupid' => $grp->capegroupid]);
                }
                return $this->render('alternate-offer', 
                       [        
                            'dataProvider' => $dataProvider, 
                            'programmes' => $programmes,
                           'cape_data' => $cape_data,
                           'division_id' => $division_id,
                           'application_status' => $application_status,
                           'firstname' => $firstname,
                           'middlename' => $middlename,
                           'lastname' => $lastname,
                           'applicantid' => $applicantid,
                       ]
                    );
            }
            Yii::$app->session->setFlash('error', 'Application status could not be updated');
            return $this->redirect(Url::to(['review-applications/view-applicant-certificates', 'applicantid'=> $applicantid, 
                    'firstname' => $firstname, 'middlename' => $middlename, 'lastname'=> $lastname, 
                    'programme' => $programme, 'application_status' => $application_status, 'applicationid' => $applicationid
                ]));
        }
        return $this->redirect(Url::to(['review-applications/index']));
    }

    /*
    * Purpose: Make an offer to an applicant for a given application
    * Created: 28/07/2015 by Gamal Crichton
    * Last Modified: 4/08/2015 by Gamal Crichton
    */
    public function actionMakeOffer($applicationid, $redirect = True, $division_id = NULL, $application_status ='', $redirectto = '')
    {
        $application = Application::findOne(['applicationid' => $applicationid]);
        $offer = new Offer();
        $offer->applicationid = $applicationid;
        $offer->issuedby = Yii::$app->user->getId();
        $offer->issuedate = date("Y-m-d");
        if ($offer->save())
        {
            $app_status = ApplicationStatus::findOne(['name' => 'offer']);
            $application->applicationstatusid = $app_status->applicationstatusid;
            if ($application->save())
            {
                $applicant = Applicant::findOne(['personid' => $application->personid]);
                if ($applicant)
                { 
                    $applicant->potentialstudentid = self::getPotentialStudentID($application->divisionid, $applicant->applicantid);
                    $applicant->save();
                }
                Yii::$app->session->setFlash('success', 'Offer Added');
                if ($redirect && $application_status && $division_id)
                {
                    return $this->redirect(Url::to(['review-applications/view-by-status', 'division_id' => $division_id, 
                        'application_status' => $application_status]));
                }
                else if ($redirect && $redirectto)
                {
                    return $this->redirect(Url::to([$redirectto]));
                }
                else
                {
                    return True;
                }
            }
        }
        return False;
    }

    /*
    * Purpose: Allows an offer to be made to a programme which applicant did not apply for.
    * Created: 28/07/2015 by Gamal Crichton
    * Last Modified: 28/07/2015 by Gamal Crichton
    */
    public function actionAlternateOffer()
    {
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $applicantid = $request->post('applicantid');
            $applicant = Applicant::findOne(['applicantid' => $applicantid]);
            $applicant_personid =  $applicant ? $applicant->personid : Yii::$app->session->setFlash('error', 'Applicant not found');
            $app_count = Application::find()->where(['personid' => $applicant_personid])->count();
            
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $request->post('programme')]);
            $prog_name = $programme ? $programme->name : Yii::$app->session->setFlash('error', 'Programme not found');   
            $application = new Application();
            $application->personid =  $applicantid; // Correct Way: $applicant_personid
            $application->academicofferingid = AcademicOffering::findOne(['programmecatalogid' => $request->post('programme'), 'isactive' =>1])
                    ->academicofferingid;
            $application->applicationstatusid = ApplicationStatus::findOne(['name' => 'offer'])->applicationstatusid;
            $application->applicationdate = date("Y-m-d");
            $application->ordering =  $app_count >= 3 ? $app_count + 1 : 3;
            $application->ipaddress = $request->getUserIP() ;
            $application->browseragent = $request->getUserAgent();
            if ($application->save())
            {
                $cape_success = True;
                if (strcasecmp($prog_name, "cape"))
                {
                    //Deal with Cape Subjects
                    $groups_used = array();
                    foreach($request->post('cape_subject') as $key=>$value)
                    {
                        $groupid = CapeSubjectGroup::findOne(['capesubjectid' => $key])->capegroupid;
                        if (!in_array($groupid, $groups_used))
                        {
                            array_push($groups_used, $groupid);
                            $application_cape = new ApplicationCapesubject();
                            $application_cape->applicationid = $application->applicationid;
                            $application_cape->capesubjectid = $key;
                            if (!$application_cape->save())
                            {
                                Yii::$app->session->setFlash('error', 'Cape Subject could not be added');
                                $cape_success = False;
                                break;
                            }
                        }
                        else
                        {
                            Yii::$app->session->setFlash('error', 'Subjects from the same group selected');
                            $cape_success = False;
                            break;
                        }
                    }
                }
                if ($cape_success)
                {
                    return self::actionMakeOffer($application->applicationid, $redirect = True, $request->post('division_id'),
                            $request->post('application_status'));
                }
            }
        }
        return $this->redirect(Url::to(['review-applications/index']));
    }
    
    /*
    * Purpose: Creates potential Student number 
    * Created: 4/08/2015 by Gamal Crichton
    * Last Modified: 4/08/2015 by Gamal Crichton
    */
    public function getPotentialStudentID($divisionid, $base)
    {
        $ay = AcademicYear::findOne(['iscurrent' => 1, 'isdeleted' => 0]);
        $startyear = $ay ? substr(explode('-',$ay->startdate)[0], -2) : substr(date('Y'), -2);
        $div = str_pad(strval($divisionid), 2, '0', STR_PAD_LEFT);
        $num = str_pad(strval($base), 4, '0', STR_PAD_LEFT);
        return $startyear . $div . $num;         
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

    /*
    * Purpose: Determins if student passed CSEC Math 
    * Created: 4/08/2015 by Gamal Crichton
    * Last Modified: 4/08/2015 by Gamal Crichton
    */
    private function hasMath($certificates)
    {
        $exam_body = ExaminationBody::findOne(['abbreviation' => 'CSEC', 'isdeleted' => 0]);
        if ($exam_body)
        {
            $math = Subject::findOne(['name' => 'mathematics', 'examinationbodyid' => $exam_body->examinationbodyid, 'isdeleted' => 0]);
            if ($math)
            {
                foreach($certificates as $cert)
                {                 
                    if ($cert->subjectid == $math->subjectid && $cert)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                        {
                            return True;
                        }
                    }
                }
            }
        }
        return False;
    }
    
    /*
    * Purpose: Determins if student passed CSEC Math 
    * Created: 4/08/2015 by Gamal Crichton
    * Last Modified: 4/08/2015 by Gamal Crichton
    */
    private function hasEnglish($certificates)
    {
        $exam_body = ExaminationBody::findOne(['abbreviation' => 'CSEC', 'isdeleted' => 0]);
        if ($exam_body)
        {
            $english = Subject::findOne(['name' => 'english language', 'examinationbodyid' => $exam_body->examinationbodyid, 'isdeleted' => 0]);
            if ($english)
            {
                foreach($certificates as $cert)
                {
                    if ($cert->subjectid == $english->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                        {
                                return True;
                        }
                    }
                }
            }
        }
        return False;
    }
    
    /*
    * Purpose: Gets all csec_subjects an applicants has passed
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton
    */
    private function getPossibleDuplicate($applicantid, $candidateno, $year)
    {
        $groups = CsecQualification::find()
                    ->where(['candidatenumber' => $candidateno, 'isverified' => 1, 'isdeleted' => 0,
                        'year' => $year])
                    ->groupBy('personid')
                    ->all();
        if (count($groups) == 1)
        {
            return False;
        }
        else
        {
            foreach ($groups as $group)
            {
                if ($group->personid != $applicantid)
                {
                    $dups[] = $group->personid;
                }
            }
            return $dups;
        }
    }
}
