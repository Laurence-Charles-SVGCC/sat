<?php

    namespace app\subcomponents\admissions\controllers;

    use Yii;
    use yii\data\ArrayDataProvider;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\helpers\Url;

    use common\models\User;
    use frontend\models\Offer;
    use frontend\models\ApplicationPeriod;
    use frontend\models\Division;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\AcademicOffering;
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

/**
 * OfferController implements the CRUD actions for Offer model.
 */
class OfferController extends Controller
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
    * Purpose: Gets offer information for a particular division for active application periods
    * Created: 29/07/2015 by Gii
    * Last Modified: 29/07/2015 by Gamal Crichton | Laurence Charles (05/03/2016)
    */
    public function actionIndex($offertype, $criteria = NULL)
    {
        $division_id = EmployeeDepartment::getUserDivision();
        
        $division = Division::findOne(['divisionid' => $division_id, 'isactive' => 1, 'isdeleted' => 0]);
        $division_abbr = $division ? $division->abbreviation : 'Undefined Division';
        $app_period = ApplicationPeriod::findOne(['divisionid' => $division_id, 'isactive' => 1, 'isdeleted' => 0/*, 'iscomplete' => 5*/]);
        $app_period_name = $app_period ? $app_period->name : 'Undefined Application Period';
        
        $offer_cond['application_period.isactive'] = 1;
        $offer_cond['application_period.iscomplete'] = 0;
        $offer_cond['offer.offertypeid'] = $offertype;
        $offer_cond['offer.isdeleted'] = 0;
        
        
        if($criteria != NULL)
        {
            if (strcmp($criteria, "awaiting-publish") == 0)
            {
                $offer_cond['offer.ispublished'] = 0;
                $offer_cond['offer.isactive'] = 1;
            }
            elseif (strcmp($criteria, "ispublished") == 0)
            {
                $offer_cond['offer.ispublished'] = 1;
                $offer_cond['offer.isactive'] = 1;
            }
            elseif (strcmp($criteria, "revoked") == 0)
            {
                $offer_cond['offer.ispublished'] = 1;
                $offer_cond['offer.isactive'] = 0;
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
         $offer_cond['application_period.divisionid'] = $division_id;
        
        $offers = Offer::find()
                ->joinWith('application')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($offer_cond)
                ->all();
        
        $multiple_offers = Applicant::getMultipleOffers($offers);
        $subjects_req = Applicant::getAcceptedWithoutFivePasses($offers);
        $english_req = Applicant::getAcceptedWithoutEnglish($offers);
        $math_req = Applicant::getAcceptedWithoutMath($offers);
        
        $dte_science_req = false;
        $dne_science_req = false;
        $open_periods = ApplicationPeriod::getOpenPeriodIDs();
        if($open_periods == true)
        {
            $dte_open = in_array(6, $open_periods);
            if ($dte_open == true)
                $dte_science_req = Applicant::getAcceptedWithoutDteScienceCriteria($offers, $details = false);
            
            $dne_open = in_array(7, $open_periods);
            if ($dne_open == true)
                $dne_science_req = Applicant::getAcceptedWithoutDneScienceCriteria($offers, $details = false);
        }
        
        $offer_issues = false;
        if ($multiple_offers==true || $english_req==true  || $subjects_req==true  || $math_req==true || $dte_science_req==true  || $dne_science_req==true)
            $offer_issues = true;
        
        $data = array();
        foreach ($offers as $offer)
        {
            $cape_subjects_names = array();
            $application = $offer->getApplication()->one();
            $applicant = Applicant::findOne(['personid' => $application->personid]);
            $username = $applicant->getPerson()->one()->username;
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $issuer = Employee::findOne(['personid' => $offer->issuedby]);
            $issuername = $issuer ? $issuer->title . '. ' . $issuer->lastname : 'Undefined Issuer';
            $revoker = Employee::findOne(['personid' => $offer->revokedby]);
            $revokername = $revoker ? $revoker->title . '. ' . $revoker->lastname : 'N/A';
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs)
            { 
                $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
            }
            
            $info = Applicant::getApplicantInformation($applicant->personid);
            $prog = $info["prog"];
            $application_status = $info["status"];
            
            $offer_data = array();
            $offer_data['prog'] = $prog;
            $offer_data['status'] = $application_status;
            $offer_data['personid'] = $applicant->personid;
            $offer_data['offerid'] = $offer->offerid;
            $offer_data['offertype'] = $offer->offertypeid;
            $offer_data['applicationid'] = $offer->applicationid;
            $offer_data['username'] = $username;
            $offer_data['firstname'] = $applicant->firstname;
            $offer_data['lastname'] = $applicant->lastname;
            $offer_data['programme'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
            $offer_data['issuedby'] = $issuername;
            $offer_data['issuedate'] = $offer->issuedate;
            $offer_data['revokedby'] = $revokername;
            $offer_data['revokedate'] = $offer->revokedate ? $offer->revokedate : 'N/A' ;
            $offer_data['ispublished'] = $offer->ispublished;
            
            $data[] = $offer_data;
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 15,
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

        
        return $this->render('current_offers', [
            'dataProvider' => $dataProvider,
            'divisionabbr' => $division_abbr,
            'applicationperiodname' => $app_period_name,
            'divisions' => $divisions,
            'programmes' => $progs,
            'cape_subjects' => $capes,
            'offer_issues' => $offer_issues,
            'multiple_offers' => $multiple_offers,
            'english_req' => $english_req,
            'math_req' => $math_req,
            'subjects_req' => $subjects_req,
            'dte_science_req' => $dte_science_req,
            'dne_science_req' => $dne_science_req,
            'offertype' => $offertype,
        ]);
    }
    

    /**
     * Displays a single Offer model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Offer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Offer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->offerid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Offer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->offerid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Offer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id, $offertype)
    {
        $model = $this->findModel($id);
        if ($model)
        {
           $model->isactive = 0;
           $model->isdeleted = 1;
           $model->revokedby = Yii::$app->user->getId();
           $model->revokedate = date('Y-m-d');
           if ($model->save())
           {
               //Remove Potential student ID and update application status
               $appstatus = ApplicationStatus::findOne(['name' => 'pending', 'isdeleted' => 0]);
               $application = $model->getApplication()->one();
               if ( $application && !Offer::findOne(['applicationid' =>$application->applicationid , 'isdeleted' => 0]))
               {
                   //no other offers for this application exists
                   $application->applicationstatusid = $appstatus ? $appstatus->applicationstatusid : 3;
                   $application->save();
               }
               $applicant = $application ? Applicant::findOne(['personid' => $application->personid]) : Null;
               $offers = $application ? Offer::find()
                ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
                ->where(['application.personid' => $application->personid, 'offer.isdeleted' => 0])
                ->all() :
                NULL;
               if ($applicant && !$offers)
               { 
                   //applicant has no other offers
                   $applicant->potentialstudentid = Null; 
                   $applicant->save();
               }
               Yii::$app->session->setFlash('success', 'Offer Revoked');
           }
           else
           {
               Yii::$app->session->setFlash('error', 'Offer could not be revoked');
           }
        }
        else
        {
            Yii::$app->session->setFlash('error', 'Offer not found');
        }

        return $this->redirect(['index', 
                                'offertype' => $offertype 
                               ]);
    }
    
    
    /**
     * Revokes an existing Offer.
     * If offer was already published, the record is made inactive;
     * If it has not been published, the record is deleted.
     * 
     * @param string $id
     * @return mixed
     * 
     * Author: Laurence Charles
     * Date Created: 30/03/2016
     * Date Last Modified: 30/03/2016 | 01/04/2016
     */
    public function actionRevoke($id, $offertype)
    {
        $offer = Offer::find()
                ->where(['offerid' => $id])
                ->one();
        
        if ($offer)
        {
            if($offer->ispublished == 1)
            {
                $offer->isactive = 0;
                $offer->isdeleted = 0;
                $offer->revokedby = Yii::$app->user->getId();
                $offer->revokedate = date('Y-m-d');
            }
            else
            {
                $offer->isactive = 0;
                $offer->isdeleted = 1;
                $offer->revokedby = Yii::$app->user->getId();
                $offer->revokedate = date('Y-m-d');
            }
           
            if ($offer->save())
            {
                /*
                * When offer is removed then all applications are reset to "Pending"
                */
                $application = $offer->getApplication()->one();
                if ($application)
                {
                    $applications = Application::find()
                                    ->where(['personid' => $application->personid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->all();
                    if ($applications)
                    {
                        /*
                         * If application is for a programme that requires an interview;
                         * -> the related appliction is reset to 'conditional offer'
                         */
                        if(AcademicOffering::requiresInterview($application->applicationid) == true)
                        {
                            /*
                             * If offer is a 'conditional-interview' offer;
                             * -> the related application is set to pending
                             */
                            if($offer->offertypeid == 2)        
                            {
                                foreach($applications as $app)
                                {
                                    $app->applicationstatusid = 3;
                                    $app->save();
                                }
                            }
                            /*
                             * If offer is a 'post-interview/full' offer;
                             * -> the related application is set to 'conditional offer'
                             * -> all other applications are set to 'rejected' 
                             * -> condoitional offer is created
                            */
                            else
                            {
                                foreach($applications as $app)
                                {
                                    //if this is the application related to offer
                                    if($app->applicationid == $offer->applicationid)
                                    {
                                        $app->applicationstatusid = 8;
                                        $app->save();
                                    }
                                    else
                                    {
                                        $app->applicationstatusid = 6;
                                        $app->save();
                                    }
                                }
                                
                                //creates conditional offer
                                $conditional_offer = new Offer();
                                $conditional_offer->applicationid = $offer->applicationid;
                                $conditional_offer->offertypeid = 2;
                                $conditional_offer->issuedby = Yii::$app->user->getID();
                                $conditional_offer->issuedate = date('Y-m-d');
                                $conditional_offer->save();
                                
                            }
                        }
                        /*
                         * If application is for a programme that does not require an interview;
                         * -> the related appliction is reset to pending
                         */
                        else
                        {
                            foreach($applications as $app)
                            {
                                $app->applicationstatusid = 3;
                                $app->save();
                            }
                        }
                    }
                    else
                        Yii::$app->session->setFlash('error', 'Error occured when retrieving all applications'); 
                    
                    
                    /*
                    * Remove Potential student ID
                    */
                    $applicant = $application ? Applicant::findOne(['personid' => $application->personid]) : NULL;
                    $offers = $application ? 
                                Offer::find()
                                    ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
                                    ->where(['application.personid' => $application->personid, 'offer.isactive' => 1, 'offer.isdeleted' => 0])
                                    ->all() :
                                NULL;
                    
                    //if applicant exists and applicant has no active offers
                    if ($applicant && !$offers)
                    { 
                        //applicant has no other offers
                        $applicant->potentialstudentid = NULL; 
                        $applicant->save();
                    }
                }
                else
                    Yii::$app->session->setFlash('error', 'Error occured when retrieving application');   
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Offer could not be revoked');
            }
        }
        else
        {
            Yii::$app->session->setFlash('error', 'Offer not found');
        }

        return $this->redirect(['index', 
                                'offertype' => $offertype 
                               ]);
    }
    

    /**
     * Finds the Offer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Offer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Offer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /*
    * Purpose: Publishs all offers for a particular division for active application periods
    * Created: 29/07/2015 by Gamal Crichton
    * Last Modified: 29/07/2015 by Gamal Crichton
    */
    public function PublishBulkOffers($division_id)
    {
        
        $offer_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1, 'offer.isdeleted' => 0, 
            'application.isdeleted' => 0, 'offer.ispublished' => 0);
        if ($division_id && $division_id == 1)
        {
            $offer_cond = array('application_period.isactive' => 1, 'offer.isdeleted' => 0, 
            'application.isdeleted' => 0, 'offer.ispublished' => 0);
        }
        
        $mail_error = False;
        $offers = Offer::find()
                ->joinWith('application')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($offer_cond)
                ->all();
        
        foreach ($offers as $offer)
        {
            $cape_subjects_names = array();
            $application = $offer->getApplication()->one();
            $applicant = Applicant::findOne(['personid' => $application->personid]);
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
            $division = Division::findOne(['divisionid' => $application->divisionid]);
            $contact = Email::findOne(['personid' => $applicant->personid, 'isdeleted' => 0]);
            
            $divisionabbr = strtolower($division->abbreviation);
            $viewfile = 'publish-offer-' . $divisionabbr;
            if (count($cape_subjects) > 0)
            {
                $viewfile = $viewfile . '-cape';
            }
            $divisioname = $division->name;
            $firstname = $applicant->firstname;
            $lastname = $applicant->lastname;
            $studentno = $applicant->potentialstudentid;
            $programme_name = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(', ', $cape_subjects_names);
            $email = $contact ? $contact->email : '';
            
            $attachments = array('../files/Library_Pre-Registration_Forms.PDF', '../files/Ecollege_services.pdf', '../files/Internet_and_Multimedia_Services_Policies.PDF',
                '../files/Uniform_Requirements_2015.pdf', '../files/Library_Information_Brochure.PDF');
            
            if ($division->divisionid == 5)
            {
                $attachments = array_merge($attachments, array('../files/Additional_requirements_for_Hospitality_and_Agricultural_Science_and_Entrepreneurship.pdf',
                    '../files/DTVE_PROGRAMME_FEES.pdf', '../files/Terms_of_Agreement_for_Discipline_DTVE.pdf',
                    '../files/DTVE_Orientation_ Schedule_August_2015.pdf'));
            }
            if ($division->divisionid == 4)
            {
                $attachments = array_merge($attachments, array('../files/Terms_of_Agreement_for_Discipline_DASGS.pdf',
                    '../files/Orientation_Groups_DASGS.pdf', '../files/Timetable_for_Orientation_2015-2016_DASGS.pdf'));
            }
            
            if (!empty($email))
            {
                if (self::publishOffer($firstname, $lastname, $studentno, $programme_name, $divisioname, $email, 'Your SVGCC Application',
                        $viewfile, $attachments))
                {
                    $offer->ispublished = 1;
                    $offer->save();
                }
                else
                {
                    $mail_error = True;
                }
            }
        }
        sleep(Yii::$app->params['admissionsEmailInterval']);
        if ($mail_error)
        {
            Yii::$app->session->setFlash('error', 'There were mail errors.');
        }
        else
        {
            Yii::$app->session->setFlash('success', 'Mails successfully sent.');
        }
        $this->redirect(Url::to(['offer/bulk-publish']));
    }
    
    /*
    * Purpose: Publishs all offers for a particular division for active application periods
    * Created: 29/07/2015 by Gamal Crichton
    * Last Modified: 29/07/2015 by Gamal Crichton
    */
    public function PublishTestOffer($division_id)
    {
        $offer_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1, 'offer.isdeleted' => 0, 
            'application.isdeleted' => 0, 'offer.ispublished' => 0);
        if ($division_id && $division_id == 1)
        {
            $offer_cond = array('application_period.isactive' => 1, 'offer.isdeleted' => 0, 
            'application.isdeleted' => 0, 'offer.ispublished' => 0);
        }
        
        $mail_error = False;
            $division = Division::findOne(['divisionid' => $division_id]);
            
            $divisionabbr = strtolower($division->abbreviation);
            $viewfile = 'publish-offer-' . $divisionabbr;
            $divisioname = $division->name;
            
            
            $attachments = array('../files/Library_Pre-Registration_Forms.PDF', '../files/Ecollege_services.pdf', '../files/Internet_and_Multimedia_Services_Policies.PDF',
                '../files/Uniform_Requirements_2015.pdf', '../files/Library_Information_Brochure.PDF');
            
            if ($division->divisionid == 5)
            {
                $attachments = array_merge($attachments, array('../files/Additional_requirements_for_Hospitality_and_Agricultural_Science_and_Entrepreneurship.pdf',
                    '../files/DTVE_PROGRAMME_FEES.pdf', '../files/Terms_of_Agreement_for_Discipline_DTVE.pdf',
                    '../files/DTVE_Orientation_ Schedule_August_2015.pdf'));
            }
            if ($division->divisionid == 4)
            {
                $attachments = array_merge($attachments, array('../files/Terms_of_Agreement_for_Discipline_DASGS.pdf',
                    '../files/Orientation_Groups_DASGS.pdf', '../files/Timetable_for_Orientation_2015-2016_DASGS.pdf'));
            }
            
            if (self::publishOffer('Test', 'User', '000000',  'Test Programme', $divisioname, 'gamal.crichton@svgcc.vc', 'Your SVGCC Application',
                    $viewfile, $attachments))
            {
            }
            else
            {
                $mail_error = True;
            }
            
        if ($mail_error)
        {
            Yii::$app->session->setFlash('error', 'There were mail errors.');
        }
        else
        {
            Yii::$app->session->setFlash('success', 'Mails successfully sent.');
        }
        $this->redirect(Url::to(['offer/bulk-publish']));
    }
    
    /*
    * Purpose: Publishs all non-offers (rejects and interview at this time) for a particular division for active application periods
    * Created: 29/07/2015 by Gamal Crichton
    * Last Modified: 30/07/2015 by Gamal Crichton
    */
    public function PublishBulkNonOffer($division_id, $status)
    {
        $mail_error = False;
        $app_status = ApplicationStatus::findOne(['name' => $status]);
        
        $offer_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1,
            'applicationstatusid' => $app_status->applicationstatusid);
        if ($division_id && $division_id == 1)
        {
            $offer_cond = array('application_period.isactive' => 1, 'applicationstatusid' => $app_status->applicationstatusid);
        }
        
        if (!$app_status)
        {
            Yii::$app->session->setFlash('error', 'Application status not found');
            return;
        }
        $applications = Application::find()
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where(['application_period.divisionid' => $division_id, 'application_period.isactive' => 1,
                    'applicationstatusid' => $app_status->applicationstatusid])
                ->all();
        
        foreach ($applications as $application)
        {
            $applicant = Applicant::findOne(['personid' => $application->personid]);
            $contact = Email::findOne(['personid' => $applicant->personid, 'isdeleted' => 0]);
            
            $firstname = $applicant->firstname;
            $lastname = $applicant->lastname;
            $email = $contact ? $contact->email : '';
            
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
            $division = Division::findOne(['divisionid' => $application->divisionid]);
            
            $divisionabbr = strtolower($division->abbreviation);
            $viewfile = 'interview-offer-' . $divisionabbr;
            $divisioname = $division->name;
            $programme_name = empty($cape_subjects) ? $programme->name : $programme->name . ": " . implode(' ,', $cape_subjects_names);
            
            if (!empty($email))
            {
                sleep(Yii::$app->params['admissionsEmailInterval']);
                if (strcasecmp($status, 'rejected') == 0)
                {
                    self::publishReject($firstname, $lastname, $email, 'Your SVGCC Application');
                }
                else if (strcasecmp($status, 'interviewoffer') == 0)
                {
                    self::publishInterviews($firstname, $lastname, $programme_name, $divisioname, $email, 'Your SVGCC Application', $viewfile);
                }
            }
            else
            {
                $mail_error = True;
            }
        }
        //sleep(Yii::$app->params['admissionsEmailInterval']);
        if ($mail_error)
        {
            Yii::$app->session->setFlash('error', 'There were mail errors.');
        }
        $this->redirect(Url::to(['offer/index']));
    }
    
    /*
    * Purpose: Publishs all non-offers (rejects and interview at this time) for a particular division for active application periods
    * Created: 29/07/2015 by Gamal Crichton
    * Last Modified: 30/07/2015 by Gamal Crichton
    */
    public function PublishTestNonOffer($division_id, $status)
    {
        if (strcasecmp($status, 'rejected') == 0)
        {
            self::publishReject('Test', 'User', 'gamal.crichton@svgcc.vc', 'Your SVGCC Application');
        }  
    }
    
    public static function actionPublishOffer($firstname, $lastname, $studentno, $programme, $divisioname, $email, $subject, $viewfile, $attachments = '')
    {
        $attach =  explode('::', $attachments);//   implode(', ', $cape_subjects_names)
        return self::publishOffer($firstname, $lastname, $studentno, $programme, $divisioname, $email, $subject, $viewfile, $attach);
    }
    
    /*
    * Purpose: Publishes (email) a single offer
    * Created: 29/07/2015 by Gamal Crichton
    * Last Modified: 29/07/2015 by Gamal Crichton
    */
    private static function publishOffer($firstname, $lastname, $studentno, $programme, $divisioname, $email, $subject, $viewfile, $attachments = '')
    {
       $mail = Yii::$app->mailer->compose('@common/mail/' . $viewfile, ['first_name' => $firstname, 'last_name' => $lastname, 
           'programme' => $programme, 'division_name' => $divisioname, 'studentno' => $studentno])
                ->setFrom(Yii::$app->params['admissionsEmail'])
                ->setTo($email)
                ->setSubject($subject);
       if ($attachments)
       {
           foreach($attachments as $attachment)
           {
               $mail->attach($attachment);
           }
       }
       
       return $mail->send();
    }
    
    public static function actionPublishReject($firstname, $lastname, $email, $subject)
    {
        return self::publishReject($firstname, $lastname, $email, $subject);
    }
    
    /*
    * Purpose: Publishes (email) a single rejection
    * Created: 30/07/2015 by Gamal Crichton
    * Last Modified: 30/07/2015 by Gamal Crichton
    */
    private static function publishReject($firstname, $lastname, $email, $subject)
    {
       return Yii::$app->mailer->compose('@common/mail/publish-reject', ['first_name' => $firstname, 'last_name' => $lastname])
                ->setFrom(Yii::$app->params['admissionsEmail'])
                ->setTo($email)
                ->setSubject($subject)
                ->send();
    }
    
    /*
    * Purpose: Publishes (email) a single interview offer
    * Created: 10/08/2015 by Gamal Crichton
    * Last Modified: 10/08/2015 by Gamal Crichton
    */
    private function publishInterviews($firstname, $lastname, $programme, $divisioname, $email, $subject, $viewfile)
    {
        return Yii::$app->mailer->compose('@common/mail/' . $viewfile, ['first_name' => $firstname, 'last_name' => $lastname, 
           'programme' => $programme, 'division_name' => $divisioname])
                ->setFrom(Yii::$app->params['admissionsEmail'])
                ->setTo($email)
                ->setSubject($subject)
                ->send();
    }
    
    /*
    * Purpose: Publishes (email) bulk decisions
    * Created: 10/08/2015 by Gamal Crichton
    * Last Modified: 10/08/2015 by Gamal Crichton
    */
    public function actionBulkPublish()
    {
        $model = new PublishForm();
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post()))
        {
            
            $statustype = $model->statustype;
            switch (intval($statustype))
            {
                case 1:
                {
                    if ($model->test)
                    {
                        self::PublishTestOffer($model->divisionid);
                    }
                    else
                    {
                        self::PublishBulkOffers($model->divisionid);
                    }
                    break;
                }
                case 2:
                {
                    
                     self::PublishBulkNonOffer($model->divisionid, 'interviewoffer');
                     break;
                }
                case 3:
                {
                    if ($model->test)
                    {
                        self::PublishTestNonOffer($model->divisionid, 'rejected');
                    }
                    else
                    {
                        self::PublishBulkNonOffer($model->divisionid, 'rejected');
                    }
                    break;
                }
            }
        }
        
        $division_id = Yii::$app->session->get('divisionid');
        $divisions_arr = array();
        if ($division_id && $division_id == 1)
        {
            $divisions_arr[1] = 'All Divisions';
            
            //Get all divisions with active application periods
            $app_periods = ApplicationPeriod::findAll(['isactive' => 1]);
            foreach ($app_periods as $ap)
            {
                $division = Division::findOne(['divisionid' => $ap->divisionid]);
                if ($division)
                {
                    $divisions_arr[$division->divisionid] = $division->name;
                }
            }
        }
        else if ($division_id && $division_id > 1)
        {
            $division = Division::findOne(['divisionid' => $division_id]);
            if ($division)
            {
                $divisions_arr[$division->divisionid] = $division->name;
            }
        }
        $status_types = array('1' => 'Offers', '2' => 'Interviews', '3' => 'Rejected');
        return $this->render('publish-form', 
                [
                    'model' => $model,
                    'divisions' =>$divisions_arr,
                    'statuses' =>$status_types,
                ]);
    }
    
    
    /**
     * Update applicant listing after filering option is applied
     * 
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 06/03/2016 (L.Charles)
     */
    public function actionUpdateView($offertype)
    {
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            
            $target_division = $request->post('offer-division-field');
            $programme = $request->post('offer-programme-field');
            $cape = $request->post('offer-cape-field');
            
            Yii::$app->session->set('division', $target_division);
            Yii::$app->session->set('programme', $programme);
            Yii::$app->session->set('cape', $cape);
        }
        else
        {
            $target_division = Yii::$app->session->get('offer-division-field');
            $programme = Yii::$app->session->get('programme');
            $cape = Yii::$app->session->get('cape');
        }
        
        $division_id = EmployeeDepartment::getUserDivision();
        
        $division = Division::findOne(['divisionid' => $division_id, 'isactive' => 1, 'isdeleted' => 0]);
        $division_abbr = $division ? $division->abbreviation : 'Undefined Division';
        $app_period = ApplicationPeriod::findOne(['divisionid' => $division_id, 'isactive' => 1, 'isdeleted' => 0/*, 'iscomplete' => 0*/]);
        $app_period_name = $app_period ? $app_period->name : 'Undefined Application Period';
        
        $offer_cond['application_period.isactive'] = 1;
        $offer_cond['application_period.iscomplete'] = 0;
        $offer_cond['offer.offertypeid'] = $offertype;
        $offer_cond['offer.isdeleted'] = 0;
        
        
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
            $offer_cond['application_period.divisionid'] = $division_id;
        
        if ($target_division != 0)
        {
            $offer_cond['application.divisionid'] = $target_division;
            $offers = Offer::find()
                ->joinWith('application')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($offer_cond)
                ->all();
        }
        
        elseif ($programme != 0)
        {
            $offer_cond['programme_catalog.programmecatalogid'] = $programme;
            $offers = Offer::find()
                    ->joinWith('application')
                    ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->innerJoin('programme_catalog', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                    ->where($offer_cond)
                    ->all();
        }
        
        elseif ($cape != 0)
        {
            $offer_cond['application_capesubject.capesubjectid'] = $cape;
            $offers = Offer::find()
                ->joinWith('application')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->innerJoin('`application_capesubject`', '`application`.`applicationid` = `application_capesubject`.`applicationid`')    
                ->where($offer_cond)
                ->all();
        }
        
        else
        {
            $offers = array();
            Yii::$app->session->setFlash('error', 'Select either a divsion, programme OR a CAPE Subject.');
        }
        
        $multiple_offers = Applicant::getMultipleOffers($offers);
        $subjects_req = Applicant::getAcceptedWithoutFivePasses($offers);
        $english_req = Applicant::getAcceptedWithoutEnglish($offers);
        $math_req = Applicant::getAcceptedWithoutMath($offers);
        
        $dte_science_req = false;
        $dne_science_req = false;
        $open_periods = ApplicationPeriod::getOpenPeriodIDs();
        if($open_periods == true)
        {
            $dte_open = in_array(6, $open_periods);
            if ($dte_open == true)
                $dte_science_req = Applicant::getAcceptedWithoutDteScienceCriteria($offers, $details = false);
            
            $dne_open = in_array(7, $open_periods);
            if ($dne_open == true)
                $dne_science_req = Applicant::getAcceptedWithoutDneScienceCriteria($offers, $details = false);
        }
        
        $offer_issues = false;
        if ($multiple_offers==true || $english_req==true  || $subjects_req==true  || $math_req==true || $dte_science_req==true  || $dne_science_req==true)
            $offer_issues = true;
        
        $data = array();
        foreach ($offers as $offer)
        {
            $cape_subjects_names = array();
            $application = $offer->getApplication()->one();
            $applicant = Applicant::findOne(['personid' => $application->personid]);
            $username = $applicant->getPerson()->one()->username;
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $issuer = Employee::findOne(['personid' => $offer->issuedby]);
            $issuername = $issuer ? $issuer->title . '. ' . $issuer->lastname : 'Undefined Issuer';
            $revoker = Employee::findOne(['personid' => $offer->revokedby]);
            $revokername = $revoker ? $revoker->title . '. ' . $revoker->lastname : 'N/A';
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs)
            { 
                $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
            }

            $info = Applicant::getApplicantInformation($applicant->personid);
            $prog = $info["prog"];
            $application_status = $info["status"];
            
            $offer_data = array();
            $offer_data['prog'] = $prog;
            $offer_data['status'] = $application_status;
            $offer_data['personid'] = $applicant->personid;
            $offer_data['offerid'] = $offer->offerid;
            $offer_data['offertype'] = $offer->offertypeid;
            $offer_data['applicationid'] = $offer->applicationid;
            $offer_data['username'] = $username;
            $offer_data['firstname'] = $applicant->firstname;
            $offer_data['lastname'] = $applicant->lastname;
            $offer_data['programme'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
            $offer_data['issuedby'] = $issuername;
            $offer_data['issuedate'] = $offer->issuedate;
            $offer_data['revokedby'] = $revokername;
            $offer_data['revokedate'] = $offer->revokedate ? $offer->revokedate : 'N/A' ;
            $offer_data['ispublished'] = $offer->ispublished;

            $data[] = $offer_data;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 15,
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

        return $this->render('current_offers', [
            'dataProvider' => $dataProvider,
            'divisionabbr' => $division_abbr,
            'applicationperiodname' => $app_period_name,
            'divisions' => $divisions,
            'programmes' => $progs,
            'cape_subjects' => $capes,
            'offer_issues' => $offer_issues,
            'multiple_offers' => $multiple_offers,
            'english_req' => $english_req,
            'subjects_req' => $subjects_req,
            'offertype' => $offertype,
        ]);
    } 
    
    
    /**
     * Generates "Questionable Offers' control panel
     * @return type
     * 
     * Author: Gamal Cricheton
     * Date Created: ??
     * Date Last Modified: 07/03/2016 (L. Charles)
     */
    public function actionOfferDetailsHome($offertype, $criteria = NULL)
    {
        $dataProvider = false;
        
        $division_id = EmployeeDepartment::getUserDivision();
        
        $app_period = ApplicationPeriod::findOne(['divisionid' => $division_id, 'isactive' => 1, 'isdeleted' => 0/*, 'iscomplete' => 0*/]);
        $app_period_name = $app_period ? $app_period->name : 'Undefined Application Period';
        
        $offer_cond['application_period.isactive'] = 1;
        $offer_cond['application_period.isdeleted'] = 0;
        $offer_cond['application_period.iscomplete'] = 0;
        $offer_cond['offer.offerypeid'] = $offerype;
        $offer_cond['offer.isdeleted'] = 0;
        
        
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
            $offer_cond['application_period.divisionid'] = $division_id;
        
        $offers = Offer::find()
                ->joinWith('application')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($offer_cond)
                ->all();
//        $offer_count = count($offers);
        $multiple_offers = Applicant::getMultipleOffers($offers);
        $english_req = Applicant::getAcceptedWithoutEnglish($offers);
        $math_req = Applicant::getAcceptedWithoutMath($offers);
        $subjects_req = Applicant::getAcceptedWithoutFivePasses($offers);
        
        
        $open_periods = ApplicationPeriod::getOpenPeriodIDs();
        if($open_periods == true)
        {
            $dte_open = in_array(6, $open_periods);
            if ($dte_open == true)
                $dte_science_req = Applicant::getAcceptedWithoutDteScienceCriteria($offers);
            
            $dne_open = in_array(7, $open_periods);
            if ($dne_open == true)
                $dne_science_req = Applicant::getAcceptedWithoutDneScienceCriteria($offers);
        }
              
        
        if ($criteria == "mult")
        {
            $mult = Applicant::getMultipleOffers($offers, true);
            $multiple_offers1 = $mult ? $mult : array();
            $mult_offerids = array();
            foreach($multiple_offers1 as $off)
            {
                $mult_offerids[] = $off->offerid; 
            }
        }
        elseif ($criteria == "maths")
        {
            $math = Applicant::getAcceptedWithoutMath($offers, true);
            $math_req1 = $math ? $math : array();
        }
        elseif ($criteria == "english")
        {
            $eng = Applicant::getAcceptedWithoutEnglish($offers, true);
            $english_req1 = $eng ? $eng : array();
        }
        elseif ($criteria == "five_passes")
        {
            $subs = Applicant::getAcceptedWithoutFivePasses($offers, true);
            $subjects_req1 = $subs ? $subs : array();
        }
        elseif ($criteria == "dte")
        {
            if($open_periods == true)
            {
                $dte_open = in_array(6, $open_periods);
                if ($dte_open == true)
                {
                    $teaching = Applicant::getAcceptedWithoutDteScienceCriteria($offers, true);
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
                    $teaching = Applicant::getAcceptedWithoutDneScienceCriteria($offers, true);
                    $dte_science_req1 = $teaching ? $teaching : array();
                }
            }
        }
        
        $multiple_offers_data = array();
        $english_req_data = array();
        $math_req_data = array();
        $subjects_req_data = array();
        $dte_req_data = array();
        $dne_req_data = array();
        
        
        if ($criteria != NULL)
        { 
            foreach ($offers as $offer)
            {
                if ($criteria == "mult")
                {
                    if (!in_array($offer->offerid, $mult_offerids))
                        continue;
                }
                elseif ($criteria == "maths")
                {
                    if (!in_array($offer, $math_req1))
                        continue;
                }
                elseif ($criteria == "english")
                {
                    if (!in_array($offer, $english_req1))
                        continue;
                }
                elseif ($criteria == "five_passes")
                {
                    if (!in_array($offer, $subjects_req1))
                       continue;
                }
                elseif ($criteria == "dte")
                {
                    if (!in_array($offer, $dte_science_req1))
                       continue;
                }
                elseif ($criteria == "dne")
                {
                    if (!in_array($offer, $dne_science_req1))
                       continue;
                }
                

                $cape_subjects_names = array();
                $application = $offer->getApplication()->one();
                $applicant = Applicant::findOne(['personid' => $application->personid]);
                $username = $applicant->getPerson()->one()->username;
                $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
                $issuer = Employee::findOne(['personid' => $offer->issuedby]);
                $issuername = $issuer ? $issuer->title . '. ' . $issuer->lastname : 'Undefined Issuer';
                $revoker = Employee::findOne(['personid' => $offer->revokedby]);
                $revokername = $revoker ? $revoker->title . '. ' . $revoker->lastname : 'N/A';
                $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
                foreach ($cape_subjects as $cs)
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }

                $info = Applicant::getApplicantInformation($applicant->personid);
                $prog = $info["prog"];
                $application_status = $info["status"];

                $offer_data = array();
                $offer_data['prog'] = $prog;
                $offer_data['status'] = $application_status;
                $offer_data['personid'] = $applicant->personid;
                $offer_data['offerid'] = $offer->offerid;
                $offer_data['applicationid'] = $offer->applicationid;
                $offer_data['username'] = $username;
                $offer_data['firstname'] = $applicant->firstname;
                $offer_data['lastname'] = $applicant->lastname;
                $offer_data['programme'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
                $offer_data['issuedby'] = $issuername;
                $offer_data['issuedate'] = $offer->issuedate;
                $offer_data['revokedby'] = $revokername;
                $offer_data['revokedate'] = $offer->revokedate ? $offer->revokedate : 'N/A' ;
                $offer_data['ispublished'] = $offer->ispublished;
                
                if ($criteria == "mult")
                {
                    if (in_array($offer->offerid, $mult_offerids))
                    {
                         $multiple_offers_data[] = $offer_data;
                    }
                }
                elseif ($criteria == "maths")
                {
                    if (in_array($offer, $math_req1))
                    {
                         $math_req_data[] = $offer_data;
                    }
                }
                elseif ($criteria == "english")
                {
                    if (in_array($offer, $english_req1))
                    {
                         $english_req_data[] = $offer_data;
                    }
                }
                elseif ($criteria == "five_passes")
                {
                    if (in_array($offer, $subjects_req1))
                    {
                         $subjects_req_data[] = $offer_data;
                    }
                }
                elseif ($criteria == "dte")
                {
                    if (in_array($offer, $dte_science_req1))
                    {
                         $dte_req_data[] = $offer_data;
                    }
                }
                elseif ($criteria == "dne")
                {
                    if (in_array($offer, $dte_science_req1))
                    {
                         $dne_req_data[] = $offer_data;
                    }
                }
            }
        }
        
        $offer_type = "No Filter Applied";
        if ($criteria == "mult")
        {
            $offer_type = "Multiple Offer Recepients";
            $dataProvider = new ArrayDataProvider([
                'allModels' => $multiple_offers_data,
                'pagination' => [
                    'pageSize' => 25,
                    ],
                'sort' => [
                    'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                    'attributes' => ['lastname', 'firstname', 'programme', 'issuedby'],
                ],
            ]);
        }
        elseif ($criteria == "maths")
        {
            $offer_type = "CSEC Mathematics Requirement Violation";
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
            $offer_type = "CSEC English Requirement Violation";
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
            $offer_type = "Minimum Subject Total Entry Requirements Violation";
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
        elseif ($criteria == "dte")
        {
            $offer_type = "DTE Relevant Science Requirement Violation";
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
            $offer_type = "DNE Relevant Science Requirement Violation";
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
        
        $info = Applicant::getApplicantInformation($applicant->personid);
        $programme = $info["prog"];
        $application_status = $info["status"];

        return $this->render('questionable-offers-home', [
            'applicationperiodname' => $app_period_name,
            'multiple_offers' => $multiple_offers,
            'english_req' => $english_req,
            'math_req' => $math_req,
            'subjects_req' => $subjects_req,
            'dte_science_req' => $dte_science_req,
            'dne_science_req' => $dne_science_req,
            
            'dataProvider' => $dataProvider,
            'offer_type' => $offer_type,
            'offertype' => $offertype,
        ]);
    }
    
    /**
     * Generates Report for All Offers
     * 
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 21/03/2016 | 29/03/2016 (L. Charles)
     */
    public function actionExportAllOffers($offertype)
    {
        $division_id = EmployeeDepartment::getUserDivision();
        
        $offer_cond = array();
        
        $offer_cond['application_period.isactive'] = 1;
        $offer_cond['application_period.iscomplete'] = 0;
        $offer_cond['offer.offerypeid'] = $offerype;
        $offer_cond['offer.isactive'] = 1;
        
        if ($division_id == 4 || $division_id == 5 || $division_id == 6  || $division_id == 7)
            $offer_cond['application.divisionid'] = $division_id;
        
        $offers = Offer::find()
                ->joinWith('application')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($offer_cond)
                ->all();
        
        $data = array();
        foreach ($offers as $offer)
        {
            $username = $offer->getApplicantUsername();
            $cape_subjects_names = array();
            $application = $offer->getApplication()->one();
            $applicant = Applicant::findOne(['personid' => $application->personid]);
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $issuer = Employee::findOne(['personid' => $offer->issuedby]);
            $issuername = $issuer ? $issuer->firstname . ' ' . $issuer->lastname : 'Undefined Issuer';
            $revoker = Employee::findOne(['personid' => $offer->revokedby]);
            $revokername = $revoker ? $revoker->firstname . ' ' . $revoker->lastname : 'N/A';
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
            
            $offer_data = array();
            $offer_data['username'] = $username;
            $offer_data['offerid'] = $offer->offerid;
            $offer_data['applicationid'] = $offer->applicationid;
            $offer_data['firstname'] = $applicant->firstname;
            $offer_data['lastname'] = $applicant->lastname;
            $offer_data['programme'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
            $offer_data['issuedby'] = $issuername;
            $offer_data['issuedate'] = $offer->issuedate;
            $offer_data['revokedby'] = $revokername;
            $offer_data['ispublished'] = $offer->ispublished;
            
            $data[] = $offer_data;
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 2000,
            ],
        ]);
        
        $title = "Title: All Offers     ";
        $date =  "Date Generated: " . date('Y-m-d') . "     ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = "Generated By: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;
        
        return $this->renderPartial('offer-export', [
            'dataProvider' => $dataProvider,
            'filename' => $filename,
        ]);
    }
    
    
    /**
     * Generates Report for All Offers that have not been published yet
     * 
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 21/03/2016 | 29/03/2016 (L. Charles)
     */
    public function actionExportUnpublishedOffers($offertype)
    {
        $division_id = EmployeeDepartment::getUserDivision();
        
        $offer_cond = array();
        
        $offer_cond['application_period.isactive'] = 1;
        $offer_cond['application_period.iscomplete'] = 0;
        $offer_cond['offer.isdeleted'] = 0;
        $offer_cond['offer.offerypeid'] = $offerype;
        $offer_cond['offer.isactive'] = 1;
        $offer_cond['offer.ispublished'] = 0;
        
        if ($division_id == 4 || $division_id == 5 || $division_id == 6  || $division_id == 7)
            $offer_cond['application.divisionid'] = $division_id;
        
        $offers = Offer::find()
                ->joinWith('application')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($offer_cond)
                ->all();
        
        $data = array();
        foreach ($offers as $offer)
        {
            $username = $offer->getApplicantUsername();
            $cape_subjects_names = array();
            $application = $offer->getApplication()->one();
            $applicant = Applicant::findOne(['personid' => $application->personid]);
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $issuer = Employee::findOne(['personid' => $offer->issuedby]);
            $issuername = $issuer ? $issuer->firstname . ' ' . $issuer->lastname : 'Undefined Issuer';
            $revoker = Employee::findOne(['personid' => $offer->revokedby]);
            $revokername = $revoker ? $revoker->firstname . ' ' . $revoker->lastname : 'N/A';
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
            
            $offer_data = array();
            $offer_data['username'] = $username;
            $offer_data['offerid'] = $offer->offerid;
            $offer_data['applicationid'] = $offer->applicationid;
            $offer_data['firstname'] = $applicant->firstname;
            $offer_data['lastname'] = $applicant->lastname;
            $offer_data['programme'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
            $offer_data['issuedby'] = $issuername;
            $offer_data['issuedate'] = $offer->issuedate;
            $offer_data['revokedby'] = $revokername;
            $offer_data['ispublished'] = $offer->ispublished;
            
            $data[] = $offer_data;
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 2000,
            ],
        ]);
        
        $title = "Title: Offers Awaiting Publishing     ";
        $date =  "Date Generated: " . date('Y-m-d') . "     ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = "Generated By: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;
        
        return $this->renderPartial('offer-export', [
            'dataProvider' => $dataProvider,
            'filename' => $filename,
        ]);
    }
    
    
    /**
     * Generates Report for All Published Offers
     * 
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 21/03/2016 | 29/03/2016  (L. Charles)
     */
    public function actionExportPublishedOffers($offertype)
    {
        $division_id = EmployeeDepartment::getUserDivision();
        
        $offer_cond = array();
        
        $offer_cond['application_period.isactive'] = 1;
        $offer_cond['application_period.iscomplete'] = 0;
        $offer_cond['offer.offerypeid'] = $offerype;
        $offer_cond['offer.isdeleted'] = 0;
        $offer_cond['offer.isactive'] = 1;
        $offer_cond['offer.ispublished'] = 1;
        
        if ($division_id == 4 || $division_id == 5 || $division_id == 6  || $division_id == 7)
            $offer_cond['application.divisionid'] = $division_id;
        
        $offers = Offer::find()
                ->joinWith('application')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($offer_cond)
                ->all();
        
        $data = array();
        foreach ($offers as $offer)
        {
            $username = $offer->getApplicantUsername();
            $cape_subjects_names = array();
            $application = $offer->getApplication()->one();
            $applicant = Applicant::findOne(['personid' => $application->personid]);
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $issuer = Employee::findOne(['personid' => $offer->issuedby]);
            $issuername = $issuer ? $issuer->firstname . ' ' . $issuer->lastname : 'Undefined Issuer';
            $revoker = Employee::findOne(['personid' => $offer->revokedby]);
            $revokername = $revoker ? $revoker->firstname . ' ' . $revoker->lastname : 'N/A';
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
            
            $offer_data = array();
            $offer_data['username'] = $username;
            $offer_data['offerid'] = $offer->offerid;
            $offer_data['applicationid'] = $offer->applicationid;
            $offer_data['firstname'] = $applicant->firstname;
            $offer_data['lastname'] = $applicant->lastname;
            $offer_data['programme'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
            $offer_data['issuedby'] = $issuername;
            $offer_data['issuedate'] = $offer->issuedate;
            $offer_data['revokedby'] = $revokername;
            $offer_data['ispublished'] = $offer->ispublished;
            
            $data[] = $offer_data;
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 2000,
            ],
        ]);
        
        $title = "Title: Published Offers     ";
        $date =  "Date Generated: " . date('Y-m-d') . "     ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = "Generated By: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;
        
        return $this->renderPartial('offer-export', [
            'dataProvider' => $dataProvider,
            'filename' => $filename,
        ]);
    }
    
    
    /**
     * Generates Report for All Revoked Offers
     * 
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 21/03/2016 | 29/03/2016  (L. Charles)
     */
    public function actionExportRevokedOffers($offertype)
    {
        $division_id = EmployeeDepartment::getUserDivision();
        
        $offer_cond = array();
        
        $offer_cond['application_period.isactive'] = 1;
        $offer_cond['application_period.iscomplete'] = 0;
        $offer_cond['offer.offerypeid'] = $offerype;
        $offer_cond['offer.isactive'] = 1;
        $offer_cond['offer.isdeleted'] = 1;
        $offer_cond['offer.ispublished'] = 1;
        
        if ($division_id == 4 || $division_id == 5 || $division_id == 6  || $division_id == 7)
            $offer_cond['application.divisionid'] = $division_id;
        
        $offers = Offer::find()
                ->joinWith('application')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($offer_cond)
                ->all();
        
        $data = array();
        foreach ($offers as $offer)
        {
            $username = $offer->getApplicantUsername();
            $cape_subjects_names = array();
            $application = $offer->getApplication()->one();
            $applicant = Applicant::findOne(['personid' => $application->personid]);
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $issuer = Employee::findOne(['personid' => $offer->issuedby]);
            $issuername = $issuer ? $issuer->firstname . ' ' . $issuer->lastname : 'Undefined Issuer';
            $revoker = Employee::findOne(['personid' => $offer->revokedby]);
            $revokername = $revoker ? $revoker->firstname . ' ' . $revoker->lastname : 'N/A';
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
            
            $offer_data = array();
            $offer_data['username'] = $username;
            $offer_data['offerid'] = $offer->offerid;
            $offer_data['applicationid'] = $offer->applicationid;
            $offer_data['firstname'] = $applicant->firstname;
            $offer_data['lastname'] = $applicant->lastname;
            $offer_data['programme'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
            $offer_data['issuedby'] = $issuername;
            $offer_data['issuedate'] = $offer->issuedate;
            $offer_data['revokedby'] = $revokername;
            $offer_data['ispublished'] = $offer->ispublished;
            
            $data[] = $offer_data;
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 2000,
            ],
        ]);
        
        $title = "Title: Revoked Offers     ";
        $date =  "Date Generated: " . date('Y-m-d') . "     ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = "Generated By: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;
        
        return $this->renderPartial('offer-export', [
            'dataProvider' => $dataProvider,
            'filename' => $filename,
        ]);
    }
    
    
    
    public function actionPreparePackagesDashboard()
    {
        
        return $this->render('packages_dashboard');
    }
    
    
    
  
}
