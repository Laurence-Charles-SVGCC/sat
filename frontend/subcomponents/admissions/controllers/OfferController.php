<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use frontend\models\Offer;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\ApplicationPeriod;
use frontend\models\Division;
use frontend\models\ProgrammeCatalog;
use frontend\models\Applicant;
use frontend\models\Employee;

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
        //Get Division
        $division_id = 4;
        
        $division = Division::findOne(['divisionid' => $division_id]);
        $division_abbr = $division ? $division->abbreviation : 'Undefined Division';
        $app_period = ApplicationPeriod::findOne(['divisionid' => $division_id, 'isactive' => 1]);
        $app_period_name = $app_period ? $app_period->name : 'Undefined Application Period';
        
        $offers = Offer::find()
                ->joinWith('application')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where(['application_period.divisionid' => $division_id, 'application_period.isactive' => 1])
                ->all();
        $data = array();
        foreach ($offers as $offer)
        {
            $application = $offer->getApplication()->one();
            $applicant = Applicant::findOne(['personid' => $application->personid]);
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $issuer = Employee::findOne(['personid' => $offer->issuedby]);
            $issuername = $issuer ? $issuer->firstname . ' ' . $issuer->lastname : 'Undefined Issuer';
            $revoker = Employee::findOne(['personid' => $offer->issuedby]);
            $revokername = $revoker ? $revoker->firstname . ' ' . $revoker->lastname : 'N/A';
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            
            $offer_data = array();
            $offer_data['offerid'] = $offer->offerid;
            $offer_data['applicationid'] = $offer->applicationid;
            $offer_data['firstname'] = $applicant->firstname;
            $offer_data['lastname'] = $applicant->lastname;
            $offer_data['programme'] = empty($cape_subjects) ? $programme->name : $programme->name . ": " . implode(' ,', $cape_subjects);
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

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'divisionabbr' => $division_abbr,
            'applicationperiodname' => $app_period_name,
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
           $model->revokedby = Yii::$app->user->getId();
           $model->revokedate = date('Y-m-d');
           if ($model->save())
           {
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
    public function actionPublishAll()
    {
        //Get Division ID
        $division_id = 4;
        
        $offers = Offer::find()
                ->joinWith('application')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where(['application_period.divisionid' => $division_id, 'application_period.isactive' => 1])
                ->all();
        
        foreach ($offers as $offer)
        {
            $application = $offer->getApplication()->one();
            $applicant = Applicant::findOne(['personid' => $application->personid]);
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            $division = Division::findOne(['divisionid' => $application->divisionid]);
            
            $divisioname = $division->name;
            $firstname = $applicant->firstname;
            $lastname = $applicant->lastname;
            $programme_name = empty($cape_subjects) ? $programme->name : $programme->name . ": " . implode(' ,', $cape_subjects);
            $email = $applicant->email;
            
            if (self::publishOffer($firstname, $lastname, $programme_name, $divisioname, $email, 'Your SVGCC Application'))
            {
                $offer->ispublished = 1;
                $offer->save();
            }
        }
    }
    
    /*
    * Purpose: Publishs all Rejects for a particular division for active application periods
    * Created: 29/07/2015 by Gamal Crichton
    * Last Modified: 29/07/2015 by Gamal Crichton
    */
    public function actionPublishRejects()
    {
        //Get Division ID
        $division_id = 4;
        
        $applications = \frontend\models\Application::find()
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where(['application_period.divisionid' => $division_id, 'application_period.isactive' => 1])
                ->all();
        
        foreach ($applications as $application)
        {
            $applicant = Applicant::findOne(['personid' => $application->personid]);
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            $division = Division::findOne(['divisionid' => $application->divisionid]);
            
            $divisioname = $division->name;
            $firstname = $applicant->firstname;
            $lastname = $applicant->lastname;
            $programme_name = empty($cape_subjects) ? $programme->name : $programme->name . ": " . implode(' ,', $cape_subjects);
            $email = $applicant->email;
            
            self::publishReject($firstname, $lastname, $programme_name, $divisioname, $email, 'Your SVGCC Application');
        }
    }
    
    /*
    * Purpose: Publishes (email) a single offer
    * Created: 29/07/2015 by Gamal Crichton
    * Last Modified: 29/07/2015 by Gamal Crichton
    */
    private function publishOffer($firstname, $lastname, $programme, $divisioname, $email, $subject)
    {
       return Yii::$app->mailer->compose('@common/mail/publish-offer', ['first_name' => $firstname, 'last_name' => $lastname, 
           'programme' => $programme, 'division_name' => $divisioname])
                ->setFrom('test@test.com')
                ->setTo($email)
                ->setSubject($subject)
                ->send();
    }
}
