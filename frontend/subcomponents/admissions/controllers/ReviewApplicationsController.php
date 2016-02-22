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
use frontend\models\CapeSubject;

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
        
        $appstatuses = ApplicationStatus::find()
                    ->all();
        $statuscounts = array();
        $referred_status = null;
        
        foreach ($appstatuses as $key => $appstatus)
        {
            if (strcasecmp($appstatus->name, 'referred') == 0)
            {
                $referred_status = $appstatus->applicationstatusid;
            }
            
            if (in_array(strtolower($appstatus->name), array('incomplete', 'unverified')))
            {
                unset($appstatuses[$key]);      //exclude 'incomplete' and 'unverified' applicationstatus from consideration
            }
            else
            {
                //if a member of 'All Division' division condarr exlcudes division as a query criteria
                if ($division_id == 1)      
                {
                    $condarr = ['isdeleted' => 0, /*'ordering' => 1,*/ 
                    'applicationstatusid' => $appstatus->applicationstatusid];
                }
                else
                {
                    $condarr = ['divisionid' => $division_id, 'isdeleted' => 0, /*'ordering' => 1,*/ 
                    'applicationstatusid' => $appstatus->applicationstatusid];
                }
                
                //Calculates the count of Pending applications.
                if (strcasecmp($appstatus->name, 'pending') == 0)
                {
                    $pending = 0;
                    
                    /* If 'applicationstatus' is pending then the 'ordering' constraint is added
                     * Only the first-order 'pending' applications are considered for summation
                     */
                    $condarr['ordering'] = 1;           
                    $apps = Application::find()
                            ->where($condarr)
                            ->groupby('personid')       //seems a bit redundant
                            ->all();
                    foreach($apps as $app)
                    {
                        $condarr['applicationstatusid'] = [4, 5, 6, 7, 8 , 9];
                        $condarr['personid'] = $app->personid;
                        unset($condarr['ordering']);
                        if (!Application::find()->where($condarr)->one())
                        {
                            $pending++;
                        }
                    }
                    $statuscounts[$appstatus->applicationstatusid] = $pending;
                }
                else if (in_array($appstatus->applicationstatusid, array(4, 5, 6, 7, 8)))
                {
                    $statcount = 0;
                    $apps = Application::find()
                            ->where($condarr)
                            ->groupby('personid')     //seems a bit redundant
                            ->all();
                    foreach($apps as $app)
                    {
                        if (!Offer::find()
                                ->innerJoin('application', '`application`.`applicationid` = `offer`.`applicationid`')
                                ->where(['application.personid' => $app->personid, 'offer.isdeleted' => 0])->one())
                        {
                            $statcount++;
                        }
                    }
                    $statuscounts[$appstatus->applicationstatusid] = $statcount;
                }
                else
                {
                    $statuscounts[$appstatus->applicationstatusid] = Application::find()->where($condarr)->count();
                }
            }
        }
        if ($division_id == 1)
        {
            $total_count = Application::find()->where(['isdeleted' => 0, 'ordering' => 1, 'applicationstatusid' => [3,4,5,6,7,8,9]])->count();
        }
        else
        {
            $total_count = Application::find()->where(['divisionid' => $division_id, 'isdeleted' => 0, 'ordering' => 1])->count();
        }
        
        //Get Referred to count
        $referred_to_count = 0;
         $apps = Application::find()->where('divisionid != ' . $division_id . ' and applicationstatusid = ' . $referred_status
                . ' and isdeleted = 0')->groupby('personid')->all();
        foreach($apps as $app)
        {
            if (!Offer::find()
                    ->innerJoin('application', '`application`.`applicationid` = `offer`.`applicationid`')
                    ->where(['application.personid' => $app->personid, 'offer.isdeleted' => 0])->one())
            {
                $referred_to_count++;
            }
        }
                    
        return $this->render('index',
                [
                    'division_id' => $division_id,
                    'appstatuses' => $appstatuses,
                    'statuscounts' => $statuscounts,
                    'total_count' => $total_count,
                    'referred_to_count' => $referred_to_count,
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
            $condarr = ['isdeleted' => 0, /*'ordering' => 1,*/ 
            'applicationstatusid' => $application_status];
        }
        else
        {
            $condarr = ['divisionid' => $division_id, 'isdeleted' => 0, /*'ordering' => 1,*/ 
            'applicationstatusid' => $application_status];
        }
        
        $app_status = ApplicationStatus::findOne(['name' => 'pending']);
        if ($application_status == $app_status->applicationstatusid)
        {
            $condarr['ordering'] = 1;
            $apps = Application::find()
                    ->where($condarr)
                    ->groupby('personid')
                    ->all();
            
            foreach($apps as $key => $app)
            {
                $condarr['applicationstatusid'] = [4, 5, 6, 7, 8, 9];
                $condarr['personid'] = $app->personid;
                unset($condarr['ordering']);
                if (Application::find()
                        ->where($condarr)
                        ->one())
                {
                    unset($apps[$key]);
                }
            }
            $applications = $apps;
        }
        else if (in_array($application_status, array(4,5,6,7,8)))
        {
            $apps = Application::find()
                    ->where($condarr)
                    ->groupby('personid')
                    ->all();
            
            foreach($apps as $key => $app)
            {
                if (Offer::find()
                        ->innerJoin('application', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->where(['application.personid' => $app->personid, 'offer.isdeleted' => 0])->one())
                {
                    unset($apps[$key]);
                }
            }
            $applications = $apps;
        }
        else
        {
            $applications = Application::find()->where($condarr)->all();
        }
        return self::actionViewApplicationApplicant($division_id, $applications, $application_status);
    }
    
     /*
    * Purpose: Allows viewing of applications referred to a particular Division 
    * Created: 21/08/2015 by Gamal Crichton
    * Last Modified: 21/08/2015 by Gamal Crichton
    */
    public function actionViewReferredTo($division_id)
    {
        $referred = ApplicationStatus::findOne(['name' => 'referred']);
        $referred_status = $referred ? $referred->applicationstatusid : Null;
        $applications = Application::find()->where('divisionid != ' . $division_id . ' and applicationstatusid = ' . $referred_status
                . ' and isdeleted = 0 and ordering = 1')->all();
        return self::actionViewApplicationApplicant($division_id, $applications, 'referred_to');
    }
    
    /*
    * Purpose: Allows viewing of applications whose first choice is a particular Division 
    * Created: 21/08/2015 by Gamal Crichton
    * Last Modified: 21/08/2015 by Gamal Crichton
    */
    public function actionViewAll($division_id)
    {
        if ($division_id == 1)
        {
            $applications = Application::find()->where(['isdeleted' => 0, 'ordering' => 1, 'applicationstatusid' => [3,4,5,6,7,8,9]])->all();
        }
        else
        {
            $applications = Application::find()->where(['divisionid' => $division_id, 'isdeleted' => 0, 'ordering' => 1])->all();
        }
        
        return self::actionViewApplicationApplicant($division_id, $applications, 'all');
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
        
        if (!$applications)
        {
            $applications = array();
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
                'defaultOrder' => ['subjects_no' => SORT_DESC, 'ones_no' => SORT_DESC, 'twos_no' => SORT_DESC, 'threes_no' => SORT_DESC],
                'attributes' => ['subjects_no', 'ones_no', 'twos_no', 'threes_no'],
                ]
        ]);
        $prog_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1, 
            'academic_offering.isactive' => 1);
        if ($division_id && $division_id == 1)
        {
            $prog_cond = array('application_period.isactive' => 1, 'academic_offering.isactive' => 1);
        }
        $programmes = ProgrammeCatalog::find()
                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                ->where($prog_cond)
                ->all();
        $progs = array(0 => 'None');
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
            /*$first_priority = $request->post('first_priority');
            $second_priority = $request->post('second_priority');
            $third_priority = $request->post('third_priority');*/
            
            Yii::$app->session->set('application_status', $application_status);
            Yii::$app->session->set('review_division', $division_id);
            Yii::$app->session->set('programme', $programme);
        }
        else
        {
            $application_status = Yii::$app->session->get('application_status');
            $division_id = Yii::$app->session->get('review_division');
            $programme = Yii::$app->session->get('programme');
        }
        if ($application_status && $division_id && ($programme || $programme ==0))
        {
            if ($division_id == 1)
            {
                $condarr = ['application.isdeleted' => 0, 'application.isdeleted' => 0,/*'application.ordering' => 1,*/ 
                'application.applicationstatusid' => $application_status];
            }
            else
            {
                $condarr = ['application.divisionid' => $division_id, 'application.isdeleted' => 0, /*'application.ordering' => 1,*/ 
                'application.applicationstatusid' => $application_status];
            }
            
             /*Pending*/
            if ($application_status == 3)
            {
                $condarr['application.ordering'] = 1;
            }
            if ($programme != 0)
            {
                $condarr['programme_catalog.programmecatalogid'] = $programme;
            }
            
            $applications = Application::find()
                    ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->innerJoin('programme_catalog', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                    ->where($condarr)
                    ->all();
            /*Priorities is not implemented. Need investigations into how multiple levels of sorting can be
               done by Yii dataProvider
              Gamal Crichton 27/07/2015 */
            $priorities = array();
            /*if ($first_priority != 'none')
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
            }  */
        }
        else
        {
            Yii::$app->session->setFlash('error', 'No critera');
            return self::actionViewApplicationApplicant(null, null, null, null);
        }
        return self::actionViewApplicationApplicant($division_id, $applications, $application_status, $priorities);
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
        $offers = $application ? Offer::find()
                ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
                ->where(['application.personid' => $application->personid, 'offer.isdeleted' => 0])
                ->all() :
                NULL;
        $error_mess = 'Applicant: ';
        
        if (!$has_math)
        {
            $error_mess = $error_mess . 'Did not pass CSEC Math!  ';
        }
        if (!$has_english)
        {
            $error_mess = $error_mess . 'Did not pass CSEC English Language!  ';
        } 
        if ( $subjects_passed < 5)
        {
            $error_mess = $error_mess . 'Passed less than 5 CSEC Subjects!  ';
        }
        if ( count($offers) == 1)
        {
            $error_mess = $error_mess . 'Has an offer.  ';
        }
        if ( count($offers) > 1)
        {
            $error_mess = $error_mess . 'Has multiple offers!  ';
        }
        
        if (!$has_english || !$has_math || $subjects_passed < 5 || $offers)
        {
            Yii::$app->session->setFlash('error', $error_mess);
        }
        //Get possible duplicates. needs work to deal with multiple years of certificates, but should catch majority
        if ($certificates)
        {
            $dups = self::getPossibleDuplicate($personid, $certificates[0]->candidatenumber, $certificates[0]->year);
            $message = '';
            if ($dups)
            {
                $dupes = '';
                foreach($dups as $dup)
                {
                    $user = User::findOne(['personid' => $dup, 'isdeleted' => 0]);
                    $dupes = $user ? $dupes . ' ' . $user->username : $dupes;
                }
                $message = 'Possible Duplicate of applicant(s) ' . $dupes;
            }
            $reapp = self::getPossibleReapplicant($personid, $certificates[0]->candidatenumber, $certificates[0]->year);
            if ($reapp)
            {
                $message = $message . ' Applicant applied to College in a previous year.';
            }
            if ($dups || $reapp)
            {
                Yii::$app->session->setFlash('warning', $message);
            }
        }
        else
        {
            Yii::$app->session->setFlash('error', 'Applicant certificates not yet verified OR Applicant has external Certificates.');
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $certificates,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        
        $offers_made = 0;
        $spaces = 0;
        $cape_info = array();
        $cape = False;
        $ao = $application ? AcademicOffering::findOne(['academicofferingid' => $application->academicofferingid]) : NULL;
        if ($ao)
        {
            $cape_prog = ProgrammeCatalog::findOne(['name' => 'cape']);
            $cape = $cape_prog ? $ao->programmecatalogid == $cape_prog->programmecatalogid : False;
            
            if ($cape)
            {
                $cape_subjects = CapeSubject::find()
                        ->innerJoin('application_capesubject', '`application_capesubject`.`capesubjectid` = `cape_subject`.`capesubjectid`')
                        ->where(['application_capesubject.applicationid' => $application->applicationid])
                        ->all();
                
                foreach ($cape_subjects as $cape)
                {
                    $cape_info[$cape->subjectname]['offers_made'] = count(Offer::find()
                        ->joinWith('application')
                        ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->innerJoin('`application_capesubject`', '`application`.`applicationid` = `application_capesubject`.`applicationid`')    
                        ->where(['application_capesubject.capesubjectid' => $cape->capesubjectid, 'application_period.isactive' => 1, 
                                'offer.isdeleted' => 0])
                        ->all());
                    $cape_info[$cape->subjectname]['capacity'] = $cape->capacity;
                }
            }
            else
            {
                $offers_made = count(Offer::find()
                        ->innerJoin('application', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->where(['academic_offering.academicofferingid' => $ao->academicofferingid])
                        ->all());

                $spaces = $ao->spaces;
            }
        }
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
                    'offers_made' => $offers_made,
                    'spaces' => $spaces,
                    'cape' => $cape,
                    'cape_info' => $cape_info,
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
            
            //Revoke any existing offers since new decision is being made
            self::implicitRevoke($applicationid);
            
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
    
    public function implicitRevoke($applicationid)
    {
        $offers = Offer::findAll(['applicationid' => $applicationid]);
        foreach($offers as $offer)
        {
           $offer->isactive = 0;
           $offer->isdeleted = 1;
           $offer->revokedby = Yii::$app->user->getId();
           $offer->revokedate = date('Y-m-d');
           if ($offer->save())
           {
               //Remove Potential student ID and update application status
               $appstatus = ApplicationStatus::findOne(['name' => 'pending', 'isdeleted' => 0]);
               $application = $offer->getApplication()->one();
               $application->applicationstatusid = $appstatus ? $appstatus->applicationstatusid : 3;
               $application->save();
               $user = $application ? $application->getPerson()->one(): NULL;
               $applicant = $user ? Applicant::findOne(['personid' => $user->personid]) : Null;
               if ($applicant){ $applicant->potentialstudentid = Null;  $applicant->save();}
            }
        }
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
            $person = User::findOne(['username' => $applicantid]);
            $applicant = Applicant::findOne(['personid' => $person->personid]);
            
            $applicant_personid =  $applicant ? $applicant->personid : Yii::$app->session->setFlash('error', 'Applicant not found');
            $app_count = Application::find()->where(['personid' => $applicant_personid])->count();
            
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $request->post('programme')]);
            $prog_name = $programme ? $programme->name : Yii::$app->session->setFlash('error', 'Programme not found');   
            $application = new Application();
            $application->personid =  $applicant_personid;
            $ac_off = AcademicOffering::findOne(['programmecatalogid' => $request->post('programme'), 'isactive' =>1]);
            $application->academicofferingid = $ac_off ? $ac_off->academicofferingid : Null;
            $application->divisionid = $ac_off ? $ac_off->getApplicationperiod()->one()->divisionid : Null;
            $application->applicationstatusid = ApplicationStatus::findOne(['name' => 'offer'])->applicationstatusid;
            $application->applicationtimestamp = date("Y-m-d H:i:s");
            $application->ordering =  $app_count >= 3 ? $app_count + 1 : 4;
            $application->ipaddress = $request->getUserIP() ;
            $application->browseragent = $request->getUserAgent();
            if ($application->save())
            {
                $cape_success = True;
                if (strcasecmp($prog_name, "cape") == 0)
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
        try
        {
            $potentialstudentid = intval($startyear . $div . $num);
        } catch (Exception $ex) {
            $potentialstudentid = NULL;
        }
        return $potentialstudentid;         
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
        try{
            $origcandidateno = $candidateno;
            $candidateno = intval($candidateno);
        } catch (Exception $ex) {
            return False;
        } 
        if ($candidateno == 0 || strlen($origcandidateno) != 10 )
        {
            return False;
        }
        $groups = CsecQualification::find()
                    ->where(['candidatenumber' => $candidateno, /*'isverified' => 1,*/ 'isdeleted' => 0,
                        'year' => $year])
                    ->groupBy('personid')
                    ->all();
        if (count($groups) == 1)
        {
            return False;
        }
        else
        {
            $dups = array();
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
    
    private function getPossibleReapplicant($candidateno, $year)
    {
        try{
            $origcandidateno = $candidateno;
            $candidateno = intval($candidateno);
        } catch (Exception $ex) {
            return False;
        } 
        if ($candidateno == 0 || strlen($origcandidateno) != 10 )
        {
            return False;
        }
        
        $reapplicant = Yii::$app->cms_db->createCommand(
                "select certificate_id from applicants_certificates where year = $year and candidate_no = $candidateno")
                ->queryOne();
        return $reapplicant ? True : False;
    }
}
