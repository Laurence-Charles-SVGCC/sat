<?php

    namespace app\subcomponents\admissions\controllers;

    use Yii;
    use yii\data\ArrayDataProvider;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\helpers\Url;

    use common\models\User;
    use frontend\models\Rejection;
    use frontend\models\ApplicationPeriod;
    use frontend\models\Division;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\Applicant;
    use frontend\models\Employee;
    use frontend\models\ApplicationCapesubject;
    use frontend\models\Application;
    use frontend\models\Email;
    use frontend\models\ApplicationStatus;
    use frontend\models\PublishForm;
    use frontend\models\CapeSubject;
    use frontend\models\CsecQualification;
    use frontend\models\ExaminationBody;
    use frontend\models\Subject;
    use frontend\models\ExaminationGrade;
    use frontend\models\EmployeeDepartment;
    use frontend\models\RejectionApplications;

/**
 * RejectionController implements the CRUD actions for Rejection model.
 */
class RejectionController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get'],
                ],
            ],
        ];
    }

    /*
    * Gets rejection information for a particular division for active application periods
    * Created: 29/03/2015 by Gii
    * Last Modified: 29/07/2015 by Gamal Crichton | Laurence Charles (05/03/2016)
    */
    public function actionIndex($rejectiontype, $criteria = NULL)
    {
        $division_id = EmployeeDepartment::getUserDivision();
        
        $division = Division::findOne(['divisionid' => $division_id, 'isactive' => 1, 'isdeleted' => 0]);
        $division_abbr = $division ? $division->abbreviation : 'Undefined Division';
        $app_period = ApplicationPeriod::findOne(['divisionid' => $division_id, 'isactive' => 1, 'isdeleted' => 0, 'iscomplete' => 0]);
        $app_period_name = $app_period ? $app_period->name : 'Undefined Application Period';
        
        $rejection_cond['application_period.isactive'] = 1;
        $rejection_cond['application_period.iscomplete'] = 0;
        $rejection_cond['rejection.rejectiontypeid'] = $rejectiontype;
        $rejection_cond['rejection.isdeleted'] = 0;
        $rejection_cond['rejection_applications.isactive'] = 1;
        $rejection_cond['rejection_applications.isdeleted'] = 0;
        
       
        if($criteria != NULL)
        {
            if (strcmp($criteria, "awaiting-publish") == 0)
            {
                $rejection_cond['rejection.ispublished'] = 0;
                $rejection_cond['rejection.isactive'] = 1;
            }
            elseif (strcmp($criteria, "ispublished") == 0)
            {
                $rejection_cond['rejection.ispublished'] = 1;
//                $rejection_cond['rejection.isactive'] = 1;
            }
            elseif (strcmp($criteria, "revoked") == 0)
            {
                $rejection_cond['rejection.ispublished'] = 1;
                $rejection_cond['rejection.isactive'] = 0;
            }
        }
        
        
        /*
         * if user has cross divisional authority then all application 
         * periods are considered
         */
        if ($division_id && $division_id == 1)      
            $app_period_name = "All Active Application Periods";
        
        /*
         * if user's authority is confined to one division division
         * then only the applocation periods related to that division are considered.
         */
        elseif ($division_id && $division_id != 1)
            $rejection_cond['application_period.divisionid'] = $division_id;
        
        $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($rejection_cond)
                ->groupby('rejection.rejectionid')
                ->all();
        
        $subjects_req = Applicant::getRejectedWithFivePasses($rejections);
        $english_req = Applicant::getRejectedWithEnglish($rejections);
        $math_req = Applicant::getRejectedWithMath($rejections);
        
        $dte_science_req = false;
        $dne_science_req = false;
        $open_periods = ApplicationPeriod::getOpenPeriodIDs();
        if($open_periods == true)
        {
            $dte_open = in_array(6, $open_periods);
            if ($dte_open == true)
                $dte_science_req = Applicant::getRejectedWithDteScienceCriteria($rejections, $details = false);
            
            $dne_open = in_array(7, $open_periods);
            if ($dne_open == true)
                $dne_science_req = Applicant::getRejectedWithDneScienceCriteria($rejections, $details = false);
        }
        
        $rejection_issues = false;
        if ($english_req==true  || $subjects_req==true  || $math_req==true || $dte_science_req==true  || $dne_science_req==true)
            $rejection_issues = true;
        
        $data = array();
        foreach ($rejections as $rejection)
        {
            $cape_subjects_names = array();
            $applications = $rejection->getApplications()->all();
            $applicant = Applicant::findOne(['personid' => $rejection->personid]);
            $username = $applicant->getPerson()->one()->username;
            
            //generate array of all programmes applicant applied for
            $programme_listing = array();
            foreach($applications as $application)
            {
                $programme_record = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
                
                $cape_subjects = array();
                $cape_subjects_names = array();
                $cape_subjects = ApplicationCapesubject::find()
                            ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                            ->where(['application.applicationid' => $application->applicationid,
                                    'application.isactive' => 1,
                                    'application.isdeleted' => 0]
                                    )
                            ->all();
                foreach ($cape_subjects as $cs) 
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $programme_name = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                array_push($programme_listing, $programme_name);
            }
            
            $issuer = Employee::findOne(['personid' => $rejection->issuedby]);
            $issuername = $issuer ? $issuer->title . '. ' . $issuer->lastname : 'Undefined Issuer';
            $revoker = Employee::findOne(['personid' => $rejection->revokedby]);
            $revokername = $revoker ? $revoker->title . '. ' . $revoker->lastname : 'N/A';
            
            $info = Applicant::getApplicantInformation($applicant->personid);
            $prog = $info["prog"];
            $application_status = $info["status"];
            
            $rejection_data = array();
            $rejection_data['prog'] = $prog;
            $rejection_data['status'] = $application_status;
            $rejection_data['personid'] = $applicant->personid;
            $rejection_data['rejectionid'] = $rejection->rejectionid;
            $rejection_data['divisionid'] = $applications[0]->divisionid;
            $rejection_data['rejectiontype'] = $rejection->rejectiontypeid;
            $rejection_data['username'] = $username;
            $rejection_data['firstname'] = $applicant->firstname;
            $rejection_data['lastname'] = $applicant->lastname;
            
            $rejected_programme_listing = " ";
            foreach ($programme_listing as $key=>$entry)
            {
                if((count($programme_listing)-1) == $key)
                {
                    $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry;
                }
                else
                {
                    $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry . ",";
                }
            }
            $rejection_data['programme'] = $rejected_programme_listing;
             
            $rejection_data['issuedby'] = $issuername;
            $rejection_data['issuedate'] = $rejection->issuedate;
            $rejection_data['revokedby'] = $revokername;
            $rejection_data['revokedate'] = $rejection->revokedate ? $rejection->revokedate : 'N/A' ;
            $rejection_data['ispublished'] = $rejection->ispublished;
            
            $data[] = $rejection_data;
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                    'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                    'attributes' => ['lastname', 'firstname',  'programme'],
            ],
        ]);
        
        
        $periods = ApplicationPeriod::getOpenPeriod();
        $divisions = array(0 => 'None');
        foreach ($periods as $period)
        {
            $divisions[$period->divisionid] = $period->getDivisionName();
        }
        
        $prog_cond = array();
        $prog_cond['application_period.iscomplete'] = 0;
        $prog_cond['application_period.isactive'] = 1;
        $prog_cond['application_period.isdeleted'] = 0;
        $prog_cond['programme_catalog.isactive'] = 1;
        $prog_cond['programme_catalog.isdeleted'] = 0;
        
        if ($division_id && $division_id != 1)
            $prog_cond['application_period.divisionid'] = $division_id;
            
        
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
        
        $cape_cond = array();
        $cape_cond['application_period.iscomplete'] = 0;
        $cape_cond['application_period.isactive'] = 1;
        $cape_cond['application_period.isdeleted'] = 0;
        $cape_cond['cape_subject.isactive'] = 1;
        $cape_cond['cape_subject.isdeleted'] = 0;
        
        if ($division_id && $division_id != 1)
            $cape_cond['application_period.divisionid'] = $division_id;
            
        $cape = CapeSubject::find()
                ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `cape_subject`.`academicofferingid`')
                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                ->where($cape_cond)
                ->all();
        $capes = array(0 => 'None');
        foreach ($cape as $c)
        {
            $capes[$c->capesubjectid] = $c->subjectname;
        }
        
        if ($division_id && $division_id != 1)
            $cape_cond['application_period.divisionid'] = $division_id;
        
        
        return $this->render('current_rejections', [
            'dataProvider' => $dataProvider,
            'divisionabbr' => $division_abbr,
            'applicationperiodname' => $app_period_name,
            'divisions' => $divisions,
            'programmes' => $progs,
            'cape_subjects' => $capes,
            'rejection_issues' => $rejection_issues,
            'english_req' => $english_req,
            'math_req' => $math_req,
            'subjects_req' => $subjects_req,
            'dte_science_req' => $dte_science_req,
            'dne_science_req' => $dne_science_req,
            'rejectiontype' => $rejectiontype,
        ]);
    }
    

    /**
     * Rescinds an existing rejection.
     * If rejection was already published, the record is made inactive;
     * If it has not been published, the record is deleted.
     * 
     * @param string $id
     * @return mixed
     * 
     * Author: Laurence Charles
     * Date Created: 30/03/2016
     * Date Last Modified: 30/03/2016
     */
    public function actionRescind($id, $rejectiontype)
    {
        $rejection = Rejection::find()
                ->where(['rejectionid' => $id])
                ->one();
        
        if ($rejection)
        {
            if($rejection->ispublished == 1)
            {
                $rejection->isactive = 0;
                $rejection->isdeleted = 0;
                $rejection->revokedby = Yii::$app->user->getId();
                $rejection->revokedate = date('Y-m-d');
            }
            else
            {
                $rejection->isactive = 0;
                $rejection->isdeleted = 1;
                $rejection->revokedby = Yii::$app->user->getId();
                $rejection->revokedate = date('Y-m-d');
            }
           
            if ($rejection->save())
            {
                //Update application status
                $applications = $rejection->getApplications()->all();
                if ($applications)
                {
                    /*
                     * If rejection was 'pre-interview',
                     * -> last applications are set to 'pending'
                     */
                    if($rejection->rejectiontypeid == 1)
                    {
                        foreach($applications as $key=>$application)
                        {
                            if((count($applications)-1) == $key)    //if record is the last application
                            {
                                $application->applicationstatusid = 3;
                                $application->save();
                            }
                        }
                    }
                    /*
                     * If rejection was 'post-interview' then,
                     * -> preceeding applications are unchanged i.e. remain set to 'rejected'
                     * -> the current application is 'Conditional Offer'
                     * -> subsequent applications are unchanged i.e. remain set to 'rejected'
                     */
                    else
                    {
                        foreach($aplications as $application)
                        {
                            //if application is the target application it is set back to "Conditional Offer"
                            if ($application->applicationstatusid == 10)
                            {
                                $application->applicationstatusid = 8;
                                $application->save();
                            }
                        }
                    }
                    
                    //remove 'RejectionApplication' records
                    $rej_applications = RejectionApplications::find()
                                    ->where(['rejectionid' => $rejection->rejectionid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->all();
                    foreach($rej_applications as $record)
                    {
                        $record->isactive = 0;
                        $record->isdeleted = 1;
                        $record->save();
                    }
                    
               }
               Yii::$app->session->setFlash('success', 'Rejection Revoked');
           }
           else
           {
               Yii::$app->session->setFlash('error', 'Rejection could not be revoked');
           }
        }
        else
        {
            Yii::$app->session->setFlash('error', 'Rejection not found');
        }

        return $this->redirect(['index', 
                                'rejectiontype' => $rejectiontype 
                               ]);
    }

    
    
    
    /**
     * Update applicant listing after filering option is applied
     * 
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 06/03/2016 | 30/03/2016 (L.Charles)
     */
    public function actionUpdateView($rejectiontype)
    {
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            
            $target_division = $request->post('rejection-division-field');
            $programme = $request->post('rejection-programme-field');
            $cape = $request->post('rejection-cape-field');
            
            Yii::$app->session->set('division', $target_division);
            Yii::$app->session->set('programme', $programme);
            Yii::$app->session->set('cape', $cape);
        }
        else
        {
            $target_division = Yii::$app->session->get('rejection-division-field');
            $programme = Yii::$app->session->get('programme');
            $cape = Yii::$app->session->get('cape');
        }
        
        $division_id = EmployeeDepartment::getUserDivision();
        
        $division = Division::findOne(['divisionid' => $division_id, 'isactive' => 1, 'isdeleted' => 0]);
        $division_abbr = $division ? $division->abbreviation : 'Undefined Division';
        $app_period = ApplicationPeriod::findOne(['divisionid' => $division_id, 'isactive' => 1, 'isdeleted' => 0/*, 'iscomplete' => 0*/]);
        $app_period_name = $app_period ? $app_period->name : 'Undefined Application Period';
        
        $rejection_cond['application_period.isactive'] = 1;
        $rejection_cond['application_period.iscomplete'] = 0;
        $rejection_cond['rejection.rejectiontypeid'] = $rejectiontype;
        $rejection_cond['rejection.isdeleted'] = 0;
        $rejection_cond['rejection_applications.isactive'] = 1;
        $rejection_cond['rejection_applications.isdeleted'] = 0;
        
        
        /*
         * if user has cross divisional authority then all application 
         * periods are considered
         */
        if ($division_id && $division_id == 1)      
            $app_period_name = "All Active Application Periods";
        
        /*
         * if user's authority is confined to one division division
         * then only the applocation periods related to that division are considered.
         */
        elseif ($division_id && $division_id != 1)
            $rejection_cond['application_period.divisionid'] = $division_id;
        
        if ($target_division != 0)
        {
            $rejection_cond['application.divisionid'] = $target_division;
            $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($rejection_cond)
                ->groupby('rejection.rejectionid')
                ->all();
        }
        
        elseif ($programme != 0)
        {
            $rejection_cond['programme_catalog.programmecatalogid'] = $programme;
            $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->innerJoin('programme_catalog', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                ->where($rejection_cond)
                ->groupby('rejection.rejectionid')
                ->all();
        }
        
        elseif ($cape != 0)
        {
            $rejection_cond['application_capesubject.capesubjectid'] = $cape;
            $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->innerJoin('`application_capesubject`', '`application`.`applicationid` = `application_capesubject`.`applicationid`')
                ->where($rejection_cond)
                ->groupby('rejection.rejectionid')
                ->all();
        }
        
        else
        {
            $rejections = array();
            Yii::$app->session->setFlash('error', 'Select either a divsion, programme OR a CAPE Subject.');
        }
        
        $subjects_req = Applicant::getRejectedWithFivePasses($rejections);
        $english_req = Applicant::getRejectedWithEnglish($rejections);
        $math_req = Applicant::getRejectedWithMath($rejections);
        
        $dte_science_req = false;
        $dne_science_req = false;
        $open_periods = ApplicationPeriod::getOpenPeriodIDs();
        if($open_periods == true)
        {
            $dte_open = in_array(6, $open_periods);
            if ($dte_open == true)
                $dte_science_req = Applicant::getRejectedWithDteScienceCriteria($rejections);
            
            $dne_open = in_array(7, $open_periods);
            if ($dne_open == true)
                $dne_science_req = Applicant::getRejectedWithDneScienceCriteria($rejections);
        }
        
        $rejection_issues = false;
        if ($english_req==true  || $subjects_req==true  || $math_req==true || $dte_science_req==true  || $dne_science_req==true)
            $rejection_issues = true;
        
        $data = array();
        foreach ($rejections as $rejection)
        {
            $applications = $rejection->getApplications()->all();
            $applicant = Applicant::findOne(['personid' => $rejection->personid]);
            $username = $applicant->getPerson()->one()->username;
            
            //generate array of all programmes applicant applied for
            $programme_listing = array();
            foreach($applications as $application)
            {
                $programme_record = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
                
                $cape_subjects = array();
                $cape_subjects_names = array();
                $cape_subjects = ApplicationCapesubject::find()
                            ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                            ->where(['application.applicationid' => $application->applicationid, 'application.isactive' => 1,  'application.isdeleted' => 0])
                            ->all();
                foreach ($cape_subjects as $cs) 
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $programme_name = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                array_push($programme_listing, $programme_name);
            }
            
            $issuer = Employee::findOne(['personid' => $rejection->issuedby]);
            $issuername = $issuer ? $issuer->title . '. ' . $issuer->lastname : 'Undefined Issuer';
            $revoker = Employee::findOne(['personid' => $rejection->revokedby]);
            $revokername = $revoker ? $revoker->title . '. ' . $revoker->lastname : 'N/A';
            
            $info = Applicant::getApplicantInformation($applicant->personid);
            $prog = $info["prog"];
            $application_status = $info["status"];
            
            $rejection_data = array();
            $rejection_data['prog'] = $prog;
            $rejection_data['status'] = $application_status;
            $rejection_data['personid'] = $applicant->personid;
            $rejection_data['rejectionid'] = $rejection->rejectionid;
            $rejection_data['rejectiontype'] = $rejection->rejectiontypeid;
            $rejection_data['divisionid'] = $applications[0]->divisionid;
            $rejection_data['username'] = $username;
            $rejection_data['firstname'] = $applicant->firstname;
            $rejection_data['lastname'] = $applicant->lastname;
            
            $rejected_programme_listing = " ";
            foreach ($programme_listing as $key=>$entry)
            {
                if((count($programme_listing)-1) == $key)
                {
                    $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry;
                }
                else
                {
                    $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry . ",";
                }
            }
            $rejection_data['programme'] = $rejected_programme_listing;
              
            $rejection_data['issuedby'] = $issuername;
            $rejection_data['issuedate'] = $rejection->issuedate;
            $rejection_data['revokedby'] = $revokername;
            $rejection_data['revokedate'] = $rejection->revokedate ? $rejection->revokedate : 'N/A' ;
            $rejection_data['ispublished'] = $rejection->ispublished;
            
            $data[] = $rejection_data;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        
        $periods = ApplicationPeriod::getOpenPeriod();
        $divisions = array(0 => 'None');
        foreach ($periods as $period)
        {
            $divisions[$period->divisionid] = $period->getDivisionName();
        }
        
        $prog_cond = array();
        $prog_cond['application_period.isactive'] = 1;
        $prog_cond['application_period.iscomplete'] = 0;
        $prog_cond['application_period.isdeleted'] = 0;
        $prog_cond['programme_catalog.isactive'] = 1;
        $prog_cond['programme_catalog.isdeleted'] = 0;
        
        if ($division_id && $division_id != 1)
            $prog_cond['application_period.divisionid'] = $divisionid;
            
        
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
        
        $cape_cond = array();
        $cape_cond['application_period.iscomplete'] = 0;
        $cape_cond['application_period.isactive'] = 1;
        $cape_cond['application_period.isdeleted'] = 0;
        $cape_cond['cape_subject.isactive'] = 1;
        $cape_cond['cape_subject.isdeleted'] = 0;
        
        if ($division_id && $division_id != 1)
            $cape_cond['application_period.divisionid'] = $division_id;
        
        $cape = CapeSubject::find()
                ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `cape_subject`.`academicofferingid`')
                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                ->where($cape_cond)
                ->all();
        $capes = array(0 => 'None');
        foreach ($cape as $c)
        {
            $capes[$c->capesubjectid] = $c->subjectname;
        }
        

        return $this->render('current_rejections', [
            'dataProvider' => $dataProvider,
            'divisionabbr' => $division_abbr,
            'applicationperiodname' => $app_period_name,
            'divisions' => $divisions,
            'programmes' => $progs,
            'cape_subjects' => $capes,
            'rejection_issues' => $rejection_issues,
            'english_req' => $english_req,
            'subjects_req' => $subjects_req,
            'dte_science_req' => $dte_science_req,
            'dne_science_req' => $dne_science_req,
            'rejectiontype' => $rejectiontype,
        ]);
    } 
    
    
    /**
     * Generates "Questionable Rejections' control panel
     * @return type
     * 
     * Author: Gamal Cricheton
     * Date Created: ??
     * Date Last Modified: 31/03/2016 (L. Charles) | 30/08/2016
     */
    public function actionRejectionDetailsHome($rejectiontype, $criteria = NULL)
    {
        $dataProvider = false;
        
        $division_id = EmployeeDepartment::getUserDivision();
        
        $app_period = ApplicationPeriod::findOne(['divisionid' => $division_id, 'isactive' => 1, 'isdeleted' => 0/*, 'iscomplete' => 0*/]);
        $app_period_name = $app_period ? $app_period->name : 'Undefined Application Period';
        
        $rejection_cond['application_period.isactive'] = 1;
        $rejection_cond['application_period.isdeleted'] = 0;
        $rejection_cond['application_period.iscomplete'] = 0;
        $rejection_cond['rejection.rejectiontypeid'] = $rejectiontype;
        $rejection_cond['rejection.isdeleted'] = 0;
        $rejection_cond['rejection_applications.isactive'] = 1;
        $rejection_cond['rejection_applications.isdeleted'] = 0;
        
        /*
         * if user has cross divisional authority then all application 
         * periods are considered
         */
        if ($division_id && $division_id == 1)      
            $app_period_name = "All Active Application Periods";
        
        /*
         * if user's authority is confined to one division division
         * then only the applocation periods related to that division are considered.
         */
        elseif ($division_id && $division_id != 1)
            $rejection_cond['application_period.divisionid'] = $division_id;
        
        $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($rejection_cond)
                ->groupby('rejection.rejectionid')
                ->all();
        
        $subjects_and_english_req = Applicant::getRejectedWithFivePassesAndEnglishPass($rejections);
        $subjects_req = Applicant::getRejectedWithFivePasses($rejections);
        $english_req = Applicant::getRejectedWithEnglish($rejections);
        $math_req = Applicant::getRejectedWithMath($rejections);
        
        $dte_science_req = false;
        $dne_science_req = false;
        $open_periods = ApplicationPeriod::getOpenPeriodIDs();
        if($open_periods == true)
        {
            $dte_open = in_array(6, $open_periods);
            if ($dte_open == true)
                $dte_science_req = Applicant::getRejectedWithDteScienceCriteria($rejections);
            
            $dne_open = in_array(7, $open_periods);
            if ($dne_open == true)
                $dne_science_req = Applicant::getRejectedWithDneScienceCriteria($rejections);
        }
           
        if ($criteria == "maths")
        {
            $math = Applicant::getRejectedWithMath($rejections, true);
            $math_req1 = $math ? $math : array();
        }
        elseif ($criteria == "english")
        {
            $eng = Applicant::getRejectedWithEnglish($rejections, true);
            $english_req1 = $eng ? $eng : array();
        }
        elseif ($criteria == "five_passes")
        {
            $subs = Applicant::getRejectedWithFivePasses($rejections, true);
            $subjects_and_english_req1 = $subs ? $subs : array();
        }
        elseif ($criteria == "five_passes_and_english")
        {
            $subs = Applicant::getRejectedWithFivePassesAndEnglishPass($rejections, true);
            $subjects_and_english_req1 = $subs ? $subs : array();
        }
        elseif ($criteria == "dte")
        {
            if($open_periods == true)
            {
                $dte_open = in_array(6, $open_periods);
                if ($dte_open == true)
                {
                    $teaching = Applicant::getRejectedWithDteScienceCriteria($rejections, true);
                    $dte_science_req1 = $teaching ? $teaching : array();
                }
            }
        }
        elseif ($criteria == "dne")
        {
            if($open_periods == true)
            {
                $dte_open = in_array(7, $open_periods);
                if ($dte_open == true)
                {
                    $teaching = Applicant::getRejectedWithDneScienceCriteria($rejections, true);
                    $dte_science_req1 = $teaching ? $teaching : array();
                }
            }
        }
        
        $english_req_data = array();
        $math_req_data = array();
        $subjects_req_data = array();
        $dte_req_data = array();
        $dne_req_data = array();
        
        
        if ($criteria != NULL)
        { 
            foreach ($rejections as $rejection)
            {
                if ($criteria == "maths")
                {
                    if (!in_array($rejection, $math_req1))
                        continue;
                }
                elseif ($criteria == "english")
                {
                    if (!in_array($rejection, $english_req1))
                        continue;
                }
                elseif ($criteria == "five_passes")
                {
                    if (!in_array($rejection, $subjects_req1))
                       continue;
                }
                elseif ($criteria == "five_passes_and_english")
                {
                    if (!in_array($rejection,  $subjects_and_english_req1))
                       continue;
                }
                elseif ($criteria == "dte")
                {
                    if (!in_array($rejection, $dte_science_req1))
                       continue;
                }
                elseif ($criteria == "dne")
                {
                    if (!in_array($rejection, $dne_science_req1))
                       continue;
                }
                

                $cape_subjects_names = array();
                $applications = $rejection->getApplications()->all();
                $applicant = Applicant::findOne(['personid' => $rejection->personid]);
                $username = $applicant->getPerson()->one()->username;
                
                //generate array of all programmes applicant applied for
                $programme_listing = array();
                foreach($applications as $application)
                {
                    $programme_record = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);

                    $cape_subjects = array();
                    $cape_subjects_names = array();
                    $cape_subjects = ApplicationCapesubject::find()
                                ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                                ->where(['application.applicationid' => $application->applicationid, 'application.isactive' => 1,  'application.isdeleted' => 0])
                                ->all();
                    foreach ($cape_subjects as $cs) 
                    { 
                        $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                    }
                    $programme_name = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                    array_push($programme_listing, $programme_name);
                }
               
                $issuer = Employee::findOne(['personid' => $rejection->issuedby]);
                $issuername = $issuer ? $issuer->title . '. ' . $issuer->lastname : 'Undefined Issuer';
                $revoker = Employee::findOne(['personid' => $rejection->revokedby]);
                $revokername = $revoker ? $revoker->title . '. ' . $revoker->lastname : 'N/A';

                $info = Applicant::getApplicantInformation($applicant->personid);
                $prog = $info["prog"];
                $application_status = $info["status"];

                $rejection_data = array();
                $rejection_data['prog'] = $prog;
                $rejection_data['status'] = $application_status;
                $rejection_data['personid'] = $applicant->personid;
                $rejection_data['rejectionid'] = $rejection->rejectionid;
                $rejection_data['rejectiontype'] = $rejection->rejectiontypeid;
                $rejection_data['username'] = $username;
                $rejection_data['firstname'] = $applicant->firstname;
                $rejection_data['lastname'] = $applicant->lastname;
                
                $rejected_programme_listing = " ";
                foreach ($programme_listing as $key=>$entry)
                {
                    if((count($programme_listing)-1) == $key)
                    {
                        $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry;
                    }
                    else
                    {
                        $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry . ",";
                    }
                }
                $rejection_data['programme'] = $rejected_programme_listing;
      
                $rejection_data['issuedby'] = $issuername;
                $rejection_data['issuedate'] = $rejection->issuedate;
                $rejection_data['revokedby'] = $revokername;
                $rejection_data['revokedate'] = $rejection->revokedate ? $rejection->revokedate : 'N/A' ;
                $rejection_data['ispublished'] = $rejection->ispublished;
                $rejection_data['subjects_no'] = CsecQualification::getSubjectsPassedCount($applicant->personid);
                $rejection_data['ones_no'] = CsecQualification::getSubjectGradesCount($applicant->personid, 1);
                $rejection_data['twos_no'] = CsecQualification::getSubjectGradesCount($applicant->personid, 2);
                $rejection_data['threes_no'] = CsecQualification::getSubjectGradesCount($applicant->personid, 3);
            
                
                if ($criteria == "maths")
                {
                    if (in_array($rejection, $math_req1))
                    {
                         $math_req_data[] = $rejection_data;
                    }
                }
                elseif ($criteria == "english")
                {
                    if (in_array($rejection, $english_req1))
                    {
                         $english_req_data[] = $rejection_data;
                    }
                }
                elseif ($criteria == "five_passes")
                {
                    if (in_array($rejection, $subjects_req1))
                    {
                         $subjects_req_data[] = $rejection_data;
                    }
                } 
                elseif ($criteria == "five_passes_and_english")
                {
                    if (in_array($rejection, $subjects_and_english_req1))
                    {
                         $subjects_and_english_req_data[] = $rejection_data;
                    }
                }
                elseif ($criteria == "dte")
                {
                    if (in_array($rejection, $dte_science_req1))
                    {
                         $dte_req_data[] = $rejection_data;
                    }
                }
                elseif ($criteria == "dne")
                {
                    if (in_array($rejection, $dte_science_req1))
                    {
                         $dne_req_data[] = $rejection_data;
                    }
                }
            }
        }
        
        $rejection_type = "No Filter Applied";
        if ($criteria == "maths")
        {
            $rejection_type = "CSEC Mathematics Requirement Violation";
            $dataProvider = new ArrayDataProvider([
                'allModels' => $math_req_data,
                'pagination' => [
                    'pageSize' => 25,
                    ],
                'sort' => [
                    'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                    'attributes' => ['lastname', 'firstname', 'programme', 'issuedby'],
                ],
            ]);
        }
        elseif ($criteria == "english")
        {
            $rejection_type = "CSEC English Requirement Violation";
            $dataProvider = new ArrayDataProvider([
                'allModels' => $english_req_data,
                'pagination' => [
                    'pageSize' => 25,
                    ],
                'sort' => [
                    'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                    'attributes' => ['lastname', 'firstname', 'programme', 'issuedby'],
                ],
            ]);
        }
        elseif ($criteria == "five_passes")
        {
            $rejection_type = "Minimum Subject Total Entry Requirements Violation";
            $dataProvider = new ArrayDataProvider([
                'allModels' => $subjects_req_data,
                'pagination' => [
                    'pageSize' => 25,
                    ],
                'sort' => [
                    'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                    'attributes' => ['lastname', 'firstname', 'programme', 'issuedby'],
                ],
            ]);
        } 
        elseif ($criteria == "five_passes_and_english")
        {
            $rejection_type = "Minimum Subject Total Entry Requirements With CSEC English Violation";
            $dataProvider = new ArrayDataProvider([
                'allModels' => $subjects_and_english_req_data,
                'pagination' => [
                    'pageSize' => 25,
                    ],
                'sort' => [
                    'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                    'attributes' => ['lastname', 'firstname', 'programme', 'issuedby'],
                ],
            ]);
        }
        elseif ($criteria == "dte")
        {
            $rejection_type = "DTE Relevant Science Requirement Violation";
            $dataProvider = new ArrayDataProvider([
                'allModels' => $dte_req_data,
                'pagination' => [
                    'pageSize' => 25,
                    ],
                'sort' => [
                    'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                    'attributes' => ['lastname', 'firstname', 'programme', 'issuedby'],
                ],
            ]);
        }
        elseif ($criteria == "dne")
        {
            $rejection_type = "DNE Relevant Science Requirement Violation";
            $dataProvider = new ArrayDataProvider([
                'allModels' => $dne_req_data,
                'pagination' => [
                    'pageSize' => 25,
                    ],
                'sort' => [
                    'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                    'attributes' => ['lastname', 'firstname', 'programme', 'issuedby'],
                ],
            ]);
        }

        return $this->render('questionable-rejections-home', [
            'applicationperiodname' => $app_period_name,
            'english_req' => $english_req,
            'math_req' => $math_req,
            'subjects_req' => $subjects_req,
            'subjects_and_english_req' => $subjects_and_english_req,
            'dte_science_req' => $dte_science_req,
            'dne_science_req' => $dne_science_req,
            
            'dataProvider' => $dataProvider,
            'rejectiontype' => $rejectiontype,
            'rejection_type' => $rejection_type,
        ]);
    }
    
    
    /**
     * Generates Report for All Rejections
     * 
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 21/03/2016 | 31/03/2016 (L. Charles)  | 30/08/2016
     */
    public function actionExportAllRejections($rejectiontype)
    {
        $division_id = EmployeeDepartment::getUserDivision();
        
        $rejection_cond = array();
        
        $rejection_cond['application_period.isactive'] = 1;
        $rejection_cond['application_period.isdeleted'] = 0;
        $rejection_cond['application_period.iscomplete'] = 0;
        $rejection_cond['rejection.rejectiontypeid'] = $rejectiontype;
        $rejection_cond['rejection.isactive'] = 1;
        
        if ($division_id == 4 || $division_id == 5 || $division_id == 6  || $division_id == 7)
            $rejection_cond['application.divisionid'] = $division_id;
        
        $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($rejection_cond)
                ->groupby('rejection.rejectionid')
                ->all();
        
        $data = array();
        foreach ($rejections as $rejection)
        {
            $cape_subjects_names = array();
            $applications = $rejection->getApplications()->all();
            $applicant = Applicant::findOne(['personid' => $rejection->personid]);
            $username = $applicant->getPerson()->one()->username;
            
            //generate array of all programmes applicant applied for
            $programme_listing = array();
            foreach($applications as $application)
            {
                $programme_record = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
                
                $cape_subjects = array();
                $cape_subjects_names = array();
                $cape_subjects = ApplicationCapesubject::find()
                            ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                            ->where(['application.applicationid' => $application->applicationid,
                                    'application.isactive' => 1,
                                    'application.isdeleted' => 0]
                                    )
                            ->all();
                foreach ($cape_subjects as $cs) 
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $programme_name = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                array_push($programme_listing, $programme_name);
            }
            
            $issuer = Employee::findOne(['personid' => $rejection->issuedby]);
            $issuername = $issuer ? $issuer->title . '. ' . $issuer->lastname : 'Undefined Issuer';
            $revoker = Employee::findOne(['personid' => $rejection->revokedby]);
            $revokername = $revoker ? $revoker->title . '. ' . $revoker->lastname : 'N/A';
            
            $info = Applicant::getApplicantInformation($applicant->personid);
            $prog = $info["prog"];
            $application_status = $info["status"];
            
            $rejection_data = array();
            $rejection_data['prog'] = $prog;
            $rejection_data['status'] = $application_status;
            $rejection_data['personid'] = $applicant->personid;
            $rejection_data['rejectionid'] = $rejection->rejectionid;
            $rejection_data['rejectiontype'] = $rejection->rejectiontypeid;
            $rejection_data['username'] = $username;
            $rejection_data['firstname'] = $applicant->firstname;
            $rejection_data['lastname'] = $applicant->lastname;
            
            $email = Email::find()
                    ->where(['personid' => $rejection->personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $rejection_data['email'] = $email->email;
            
            $rejected_programme_listing = " ";
            foreach ($programme_listing as $key=>$entry)
            {
                if((count($programme_listing)-1) == $key)
                {
                    $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry;
                }
                else
                {
                    $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry . ",";
                }
            }
            $rejection_data['programme'] = $rejected_programme_listing;
             
            $rejection_data['issuedby'] = $issuername;
            $rejection_data['issuedate'] = $rejection->issuedate;
            $rejection_data['revokedby'] = $revokername;
            $rejection_data['revokedate'] = $rejection->revokedate ? $rejection->revokedate : 'N/A' ;
            $rejection_data['ispublished'] = $rejection->ispublished;
            
            $data[] = $rejection_data;
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 2000,
            ],
        ]);
        
        if ($rejectiontype == 1)
            $rejection_name = "Pre-Interview";
        else
            $rejection_name = "Conditional";
        
        $title = "Title: All Rejection" . "(" . $rejection_name .  ")     ";
        $date =  " Date: " . date('Y-m-d') . "     ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = " Generator: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;
        
        return $this->renderPartial('rejection-export', [
            'dataProvider' => $dataProvider,
            'filename' => $filename,
        ]);
    }
    
    
    /**
     * Generates Report for Unpublished Rejections that have not been published yet
     * 
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 21/03/2016 | 31/03/2016 (L. Charles) | 30/08/2016
     */
    public function actionExportUnpublishedRejections($rejectiontype)
    {
        $division_id = EmployeeDepartment::getUserDivision();
        
        $rejection_cond = array();
        
        $rejection_cond['application_period.isactive'] = 1;
        $rejection_cond['application_period.isdeleted'] = 0;
        $rejection_cond['application_period.iscomplete'] = 0;
        $rejection_cond['rejection.isdeleted'] = 0;
        $rejection_cond['rejection.rejectiontypeid'] = $rejectiontype;
        $rejection_cond['rejection.isactive'] = 1;
        $rejection_cond['rejection.ispublished'] = 0;
        
        if ($division_id == 4 || $division_id == 5 || $division_id == 6  || $division_id == 7)
            $rejection_cond['application.divisionid'] = $division_id;
        
        $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($rejection_cond)
                ->groupby('rejection.rejectionid')
                ->all();
        
        $data = array();
        foreach ($rejections as $rejection)
        {
            $applications = $rejection->getApplications()->all();
            $applicant = Applicant::findOne(['personid' => $rejection->personid]);
            $username = $applicant->getPerson()->one()->username;
            
            //generate array of all programmes applicant applied for
            $programme_listing = array();
            foreach($applications as $application)
            {
                $programme_record = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
                
                $cape_subjects = array();
                $cape_subjects_names = array();
                $cape_subjects = ApplicationCapesubject::find()
                            ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                            ->where(['application.applicationid' => $application->applicationid,
                                    'application.isactive' => 1,
                                    'application.isdeleted' => 0]
                                    )
                            ->all();
                foreach ($cape_subjects as $cs) 
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $programme_name = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                array_push($programme_listing, $programme_name);
            }
            
            $issuer = Employee::findOne(['personid' => $rejection->issuedby]);
            $issuername = $issuer ? $issuer->title . '. ' . $issuer->lastname : 'Undefined Issuer';
            $revoker = Employee::findOne(['personid' => $rejection->revokedby]);
            $revokername = $revoker ? $revoker->title . '. ' . $revoker->lastname : 'N/A';
            
            $info = Applicant::getApplicantInformation($applicant->personid);
            $prog = $info["prog"];
            $application_status = $info["status"];
            
            $rejection_data = array();
            $rejection_data['prog'] = $prog;
            $rejection_data['status'] = $application_status;
            $rejection_data['personid'] = $applicant->personid;
            $rejection_data['rejectionid'] = $rejection->rejectionid;
            $rejection_data['rejectiontype'] = $rejection->rejectiontypeid;
            $rejection_data['username'] = $username;
            $rejection_data['firstname'] = $applicant->firstname;
            $rejection_data['lastname'] = $applicant->lastname;
            
            $email = Email::find()
                    ->where(['personid' => $rejection->personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $rejection_data['email'] = $email->email;
            
            $rejected_programme_listing = " ";
            foreach ($programme_listing as $key=>$entry)
            {
                if((count($programme_listing)-1) == $key)
                {
                    $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry;
                }
                else
                {
                    $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry . ",";
                }
            }
            $rejection_data['programme'] = $rejected_programme_listing;
             
            $rejection_data['issuedby'] = $issuername;
            $rejection_data['issuedate'] = $rejection->issuedate;
            $rejection_data['revokedby'] = $revokername;
            $rejection_data['revokedate'] = $rejection->revokedate ? $rejection->revokedate : 'N/A' ;
            $rejection_data['ispublished'] = $rejection->ispublished;
            
            $data[] = $rejection_data;
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 2000,
            ],
        ]);
        
        if ($rejectiontype == 1)
            $rejection_name = "Pre-Interview";
        else
            $rejection_name = "Conditional";
        $title = "Title: Rejections Awaiting Publishing" . "(" . $rejection_name .  ")     ";
        
        $date =  " Date: " . date('Y-m-d') . "     ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = " Generator: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;
        
        return $this->renderPartial('rejection-export', [
            'dataProvider' => $dataProvider,
            'filename' => $filename,
        ]);
    }
    
    
    /**
     * Generates Report for All Published Rejections
     * 
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 21/03/2016 | 31/03/2016  (L. Charles)  | 30/08/2016
     */
    public function actionExportPublishedRejections($rejectiontype)
    {
        $division_id = EmployeeDepartment::getUserDivision();
        
        $rejection_cond = array();
        
        $rejection_cond['application_period.isactive'] = 1;
        $rejection_cond['application_period.isdeleted'] = 0;
        $rejection_cond['application_period.iscomplete'] = 0;
        $rejection_cond['rejection.isdeleted'] = 0;
        $rejection_cond['rejection.rejectiontypeid'] = $rejectiontype;
        $rejection_cond['rejection.isactive'] = 1;
        $rejection_cond['rejection.ispublished'] = 1;
        
        if ($division_id == 4 || $division_id == 5 || $division_id == 6  || $division_id == 7)
            $rejection_cond['application.divisionid'] = $division_id;
        
        $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($rejection_cond)
                ->groupby('rejection.rejectionid')
                ->all();
        
        $data = array();
        foreach ($rejections as $rejection)
        {
            $cape_subjects_names = array();
            $applications = $rejection->getApplications()->all();
            $applicant = Applicant::findOne(['personid' => $rejection->personid]);
            $username = $applicant->getPerson()->one()->username;
            
            //generate array of all programmes applicant applied for
            $programme_listing = array();
            foreach($applications as $application)
            {
                $programme_record = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
                
                $cape_subjects = array();
                $cape_subjects_names = array();
                $cape_subjects = ApplicationCapesubject::find()
                            ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                            ->where(['application.applicationid' => $application->applicationid,
                                    'application.isactive' => 1,
                                    'application.isdeleted' => 0]
                                    )
                            ->all();
                foreach ($cape_subjects as $cs) 
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $programme_name = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                array_push($programme_listing, $programme_name);
            }
            
            $issuer = Employee::findOne(['personid' => $rejection->issuedby]);
            $issuername = $issuer ? $issuer->title . '. ' . $issuer->lastname : 'Undefined Issuer';
            $revoker = Employee::findOne(['personid' => $rejection->revokedby]);
            $revokername = $revoker ? $revoker->title . '. ' . $revoker->lastname : 'N/A';
            
            $info = Applicant::getApplicantInformation($applicant->personid);
            $prog = $info["prog"];
            $application_status = $info["status"];
            
            $rejection_data = array();
            $rejection_data['prog'] = $prog;
            $rejection_data['status'] = $application_status;
            $rejection_data['personid'] = $applicant->personid;
            $rejection_data['rejectionid'] = $rejection->rejectionid;
            $rejection_data['rejectiontype'] = $rejection->rejectiontypeid;
            $rejection_data['username'] = $username;
            $rejection_data['firstname'] = $applicant->firstname;
            $rejection_data['lastname'] = $applicant->lastname;
            
            $email = Email::find()
                    ->where(['personid' => $rejection->personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $rejection_data['email'] = $email->email;
            
            $rejected_programme_listing = " ";
            foreach ($programme_listing as $key=>$entry)
            {
                if((count($programme_listing)-1) == $key)
                {
                    $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry;
                }
                else
                {
                    $rejected_programme_listing.= " " . "(" . ($key+1) . ") " . $entry . ",";
                }
            }
            $rejection_data['programme'] = $rejected_programme_listing;
             
            $rejection_data['issuedby'] = $issuername;
            $rejection_data['issuedate'] = $rejection->issuedate;
            $rejection_data['revokedby'] = $revokername;
            $rejection_data['revokedate'] = $rejection->revokedate ? $rejection->revokedate : 'N/A' ;
            $rejection_data['ispublished'] = $rejection->ispublished;
            
            $data[] = $rejection_data;
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 2000,
            ],
        ]);
        
        if ($rejectiontype == 1)
            $rejection_name = "Pre-Interview";
        else
            $rejection_name = "Conditional";
        $title = "Title: Published Rejections" . "(" . $rejection_name .  ")     ";
        
        $date =  " Date: " . date('Y-m-d') . "     ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = " Generator: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;
        
        return $this->renderPartial('rejection-export', [
            'dataProvider' => $dataProvider,
            'filename' => $filename,
        ]);
    }
    
    
    /**
     * Generates Report for All Revoked Rejections
     * 
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 21/03/2016 | 31/03/2016  (L. Charles)  | 30/08/2016
     */
    public function actionExportRevokedRejections($rejectiontype)
    {
        $division_id = EmployeeDepartment::getUserDivision();
        
        $rejection_cond = array();
        
        $rejection_cond['application_period.isactive'] = 1;
        $rejection_cond['application_period.isdeleted'] = 0;
        $rejection_cond['application_period.iscomplete'] = 0;
        $rejection_cond['rejection.isactive'] = 1;
         $rejection_cond['rejection.rejectiontypeid'] = $rejectiontype;
        $rejection_cond['rejection.isdeleted'] = 1;
        $rejection_cond['rejection.ispublished'] = 1;
        
        if ($division_id == 4 || $division_id == 5 || $division_id == 6  || $division_id == 7)
            $rejection_cond['application.divisionid'] = $division_id;
        
        $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($rejection_cond)
                ->groupby('rejection.rejectionid')
                ->all();
        
        $data = array();
        foreach ($rejections as $rejection)
        {
            $cape_subjects_names = array();
            $applications = $rejection->getApplications()->all();
            $applicant = Applicant::findOne(['personid' => $rejection->personid]);
            $username = $applicant->getPerson()->one()->username;
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $issuer = Employee::findOne(['personid' => $rejection->issuedby]);
            $issuername = $issuer ? $issuer->title . '. ' . $issuer->lastname : 'Undefined Issuer';
            $revoker = Employee::findOne(['personid' => $rejection->revokedby]);
            $revokername = $revoker ? $revoker->title . '. ' . $revoker->lastname : 'N/A';
            
            $has_cape = false;
            foreach($applications as $application)
            {
                $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
                if($cape_subjects == true)
                    $has_cape = true;
                
                $cape_subjects_row = "CAPE: ";
                foreach ($cape_subjects as $i=>$cs)
                { 
                    $cape_subjects_row.= $cs->getCapesubject()->one()->subjectname;
                    if ($i != count($cape_subjects-1))
                        $cape_subjects_row.= ", ";
                    else
                        $cape_subjects_row.= ".";
                }
                $cape_subjects_names[] = $cape_subjects_row;
            }
            
            $rejection_data = array();
            $rejection_data['rejectionid'] = $rejection->rejectionid;
            $rejection_data['username'] = $username;
            $rejection_data['firstname'] = $applicant->firstname;
            $rejection_data['lastname'] = $applicant->lastname;
            
            $email = Email::find()
                    ->where(['personid' => $rejection->personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $rejection_data['email'] = $email->email;
            
            $rejection_data['programme'] = ($has_cape == false) ? $programme->getFullName() : implode(' && ', $cape_subjects_names);
            $rejection_data['issuedby'] = $issuername;
            $rejection_data['issuedate'] = $rejection->issuedate;
            $rejection_data['revokedby'] = $revokername;
            $rejection_data['revokedate'] = $rejection->revokedate ? $rejection->revokedate : 'N/A' ;
            $rejection_data['ispublished'] = $rejection->ispublished;
            
            $data[] = $rejection_data;
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 2000,
            ],
        ]);
        
        if ($rejectiontype == 1)
            $rejection_name = "Pre-Interview";
        else
            $rejection_name = "Conditional";
        $title = "Title: Revoked Rejections" . "(" . $rejection_name .  ")     ";
        
        $date =  " Date: " . date('Y-m-d') . "     ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = " Generator: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;
        
        return $this->renderPartial('rejection-export', [
            'dataProvider' => $dataProvider,
            'filename' => $filename,
        ]);
    }
    
    
    
    
    
    
    
}


