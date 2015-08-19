<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use frontend\models\Offer;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /*
    * Purpose: Gets offer information for a particular division for active application periods
    * Created: 29/07/2015 by Gii
    * Last Modified: 29/07/2015 by Gamal Crichton
    */
    public function actionIndex()
    {
        $division_id = Yii::$app->session->get('divisionid');
        
        
        $division = Division::findOne(['divisionid' => $division_id ]);
        $division_abbr = $division ? $division->abbreviation : 'Undefined Division';
        $app_period = ApplicationPeriod::findOne(['divisionid' => $division_id, 'isactive' => 1]);
        $app_period_name = $app_period ? $app_period->name : 'Undefined Application Period';
        $offer_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1, 'offer.isdeleted' => 0);
        
        if ($division_id && $division_id == 1)
        {
            $app_period_name = "All Active Application Periods";
            $offer_cond = array('application_period.isactive' => 1, 'offer.isdeleted' => 0);
        }
        
        $offers = Offer::find()
                ->joinWith('application')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($offer_cond)
                ->all();
        $data = array();
        foreach ($offers as $offer)
        {
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
                'pageSize' => 50,
            ],
        ]);
        
        $prog_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1, 'programme_catalog.isdeleted' => 0);
        if ($division_id && $division_id == 1)
        {
            $prog_cond = array('application_period.isactive' => 1, 'programme_catalog.isdeleted' => 0);
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
        
        $cape_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1, 'cape_subject.isdeleted' => 0);
        if ($division_id && $division_id == 1)
        {
            $cape_cond = array('application_period.isactive' => 1, 'cape_subject.isdeleted' => 0);
        }
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

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'divisionabbr' => $division_abbr,
            'applicationperiodname' => $app_period_name,
            'programmes' => $progs,
            'cape_subjects' => $capes,
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
    public function actionDelete($id)
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
               $application->applicationstatusid = $appstatus ? $appstatus->applicationstatusid : 3;
               $application->save();
               $applicant = $application ? $application->getPerson()->one() : Null;
               if ($applicant){ $applicant->potentialstudentid = Null; $applicant->save();}
               
               Yii::$app->session->setFlash('success', 'Offer Revoked');
           }
           else
           {
               Yii::$app->session->setFlash('error', 'Offer could not be revoked');
           }
        }
        Yii::$app->session->setFlash('error', 'Offer not found');

        return $this->redirect(['index']);
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
        
        $offer_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1);
        if ($division_id && $division_id == 1)
        {
            $offer_cond = array('application_period.isactive' => 1);
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
            $application = $offer->getApplication()->one();
            $applicant = Applicant::findOne(['personid' => $application->personid]);
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
            $division = Division::findOne(['divisionid' => $application->divisionid]);
            $contact = Email::findOne(['personid' => $applicant->personid, 'isdeleted' => 0]);
            
            $divisionabbr = strtolower($division->abbreviation);
            $viewfile = 'publish-offer-' . $divisionabbr;
            $divisioname = $division->name;
            $firstname = $applicant->firstname;
            $lastname = $applicant->lastname;
            $programme_name = empty($cape_subjects) ? $programme->name : $programme->name . ": " . implode(' ,', $cape_subjects_names);
            $email = $contact ? $contact->email : '';
            
            if (!empty($email))
            {
                if (self::publishOffer($firstname, $lastname, $programme_name, $divisioname, $email, 'Your SVGCC Application',
                        $viewfile))
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
        if ($mail_error)
        {
            sleep(Yii::$app->params['admissionsEmailInterval']);
            Yii::$app->session->setFlash('error', 'There were mail errors.');
        }
        $this->redirect(Url::to(['offer/index']));
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
                if (strcasecmp($status, 'rejected'))
                {
                    self::publishReject($firstname, $lastname, $email, 'Your SVGCC Application');
                }
                else if (strcasecmp($status, 'interviewoffer'))
                {
                    self::publishInterviews($firstname, $lastname, $programme_name, $divisioname, $email, 'Your SVGCC Application', $viewfile);
                }
            }
            else
            {
                $mail_error = True;
            }
        }
        if ($mail_error)
        {
            Yii::$app->session->setFlash('error', 'There were mail errors.');
        }
        $this->redirect(Url::to(['offer/index']));
    }
    
    /*
    * Purpose: Publishes (email) a single offer
    * Created: 29/07/2015 by Gamal Crichton
    * Last Modified: 29/07/2015 by Gamal Crichton
    */
    private function publishOffer($firstname, $lastname, $programme, $divisioname, $email, $subject, $viewfile)
    {
       return Yii::$app->mailer->compose('@common/mail/' . $viewfile, ['first_name' => $firstname, 'last_name' => $lastname, 
           'programme' => $programme, 'division_name' => $divisioname])
                ->setFrom(Yii::$app->params['admissionsEmail'])
                ->setTo($email)
                ->setSubject($subject)
                ->send();
    }
    
    /*
    * Purpose: Publishes (email) a single rejection
    * Created: 30/07/2015 by Gamal Crichton
    * Last Modified: 30/07/2015 by Gamal Crichton
    */
    private function publishReject($firstname, $lastname, $email, $subject)
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
                    self::PublishBulkOffers($model->divisionid);
                }
                case 2:
                {
                    self::PublishBulkNonOffer($model->divisionid, 'interviewoffer');
                }
                case 3:
                {
                    self::PublishBulkNonOffer($model->divisionid, 'rejected');
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
    
    
    
    
    public function actionUpdateView()
    {
        
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $programme = $request->post('programme');
            $cape = $request->post('cape');
            
            Yii::$app->session->set('programme', $programme);
            Yii::$app->session->set('cape', $cape);
        }
        else
        {
            $programme = Yii::$app->session->get('programme');
            $cape = Yii::$app->session->get('cape');
        }
        
        $division_id = Yii::$app->session->get('divisionid');
        
        $division = Division::findOne(['divisionid' => $division_id ]);
        $division_abbr = $division ? $division->abbreviation : 'Undefined Division';
        $app_period = ApplicationPeriod::findOne(['divisionid' => $division_id, 'isactive' => 1]);
        $app_period_name = $app_period ? $app_period->name : 'Undefined Application Period';
        $offer_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1, 'offer.isdeleted' => 0);
        
        if ($division_id && $division_id == 1)
        {
            $app_period_name = "All Active Application Periods";
            $offer_cond = array('application_period.isactive' => 1, 'offer.isdeleted' => 0);
        }
        
        if (! ($programme != 0 && $cape != 0))
        {
            if ($programme != 0)
            {
                $offer_cond['programme_catalog.programmecatalogid'] = $programme;
            }
            $offers = Offer::find()
                    ->joinWith('application')
                    ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->innerJoin('programme_catalog', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                    ->where($offer_cond)
                    ->all();
            if ($cape != 0)
            {
                $offer_cond['application_capesubject.capesubjectid'] = $cape;
                $offers = Offer::find()
                    ->joinWith('application')
                    ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    //->innerJoin('`cape_subject`', '`cape_subject`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                    ->innerJoin('`application_capesubject`', '`application`.`applicationid` = `application_capesubject`.`applicationid`')    
                    ->where($offer_cond)
                    ->all();
            }
        }
        else
        {
            $offers = array();
            Yii::$app->session->setFlash('error', 'Select either a programme OR a CAPE Subject.');
        }
        
        $data = array();
        foreach ($offers as $offer)
        {
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
                'pageSize' => 50,
            ],
        ]);
        
        $prog_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1, 'programmecatalogid.isdeleted' => 0);
        if ($division_id && $division_id == 1)
        {
            $prog_cond = array('application_period.isactive' => 1);
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
        
        $cape_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1, 'cape_subject.isdeleted' => 0);
        if ($division_id && $division_id == 1)
        {
            $cape_cond = array('application_period.isactive' => 1, 'cape_subject.isdeleted' => 0);
        }
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

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'divisionabbr' => $division_abbr,
            'applicationperiodname' => $app_period_name,
            'programmes' => $progs,
            'cape_subjects' => $capes,
        ]);
    }
}
