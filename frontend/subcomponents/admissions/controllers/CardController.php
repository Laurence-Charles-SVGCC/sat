<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use frontend\models\Division;
use frontend\models\StudentRegistration;
use frontend\models\ApplicationPeriod;
use frontend\models\Offer;
use frontend\models\Applicant;
use frontend\models\ProgrammeCatalog;
use frontend\models\ApplicationCapesubject;

class CardController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $dasgs = Division::findOne(['abbreviation' => 'DASGS']);
        $dtve = Division::findOne(['abbreviation' => 'DTVE']);
        $dasgsid = $dasgs ? $dasgs->divisionid : Null;
        $dtveid = $dtve ? $dtve->divisionid : Null;
        return $this->render('index',
                [
                    'dasgsid' => $dasgsid,
                    'dtveid' => $dtveid,
                ]);
    }
    
    public function actionViewApplicants($divisionid)
    {
        $division = Division::findOne(['divisionid' => $divisionid ]);
        $division_abbr = $division ? $division->abbreviation : 'Undefined Division';
        $app_period = ApplicationPeriod::findOne(['divisionid' => $divisionid, 'isactive' => 1]);
        $app_period_name = $app_period ? $app_period->name : 'Undefined Application Period';
        $offer_cond = array('application_period.divisionid' => $divisionid, 'application_period.isactive' => 1);
        
        if ($divisionid && $divisionid == 1)
        {
            $app_period_name = "All Active Application Periods";
            $offer_cond = array('application_period.isactive' => 1);
        }
        $offer_cond['offer.isdeleted'] = 0;
        
        $offers = Offer::find()
                ->joinWith('application')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($offer_cond)
                ->all();
        $data = array();
        foreach ($offers as $offer)
        {
            $application = $offer->getApplication()->one();
            $applicant = Applicant::findOne(['personid' => $application->personid]);
            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
            $student_reg = StudentRegistration::findOne(['personid' => $applicant->personid, 'isactive' => 1]);
            
            $offer_data = array();
            $offer_data['offerid'] = $offer->offerid;
            $offer_data['studentreg'] = $student_reg;
            $offer_data['title'] = $applicant->title;
            $offer_data['firstname'] = $applicant->firstname;
            $offer_data['middlename'] = $applicant->middlename;
            $offer_data['lastname'] = $applicant->lastname;
            $offer_data['programme'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
            $offer_data['studentno'] = $applicant->potentialstudentid;
            $offer_data['published'] = $offer->ispublished;
            $offer_data['registered'] = $student_reg ? True : False;
            $offer_data['picturetaken'] = $student_reg ? $student_reg->receivedpicture : False;
            $offer_data['cardready'] = $student_reg ? $student_reg->cardready : False ;
            $offer_data['cardcollected'] = $student_reg ? $student_reg->cardcollected : False;
            $data[] = $offer_data;
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        return $this->render('view-applicants', [
            'dataProvider' => $dataProvider,
            'divisionabbr' => $division_abbr,
            'applicationperiodname' => $app_period_name,
        ]);
    }
    
    public function actionUpdateApplicants()
    {
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $studentreg = $request->post('studentreg');
            $receivedpicture = $request->post('receivedpicture') ? $request->post('receivedpicture') : array();
            $cardready = $request->post('cardready') ? $request->post('cardready') : array();
            $cardcollected = $request->post('cardcollected') ? $request->post('cardcollected') : array();
            
            foreach ($studentreg as $stureg)
            {
                $reg = StudentRegistration::findOne(['studentregistrationid' => $stureg]);
                $reg->receivedpicture = in_array($reg->studentregistrationid, array_keys($receivedpicture)) ? 1 : 0;
                $reg->cardready = in_array($reg->studentregistrationid, array_keys($cardready)) ? 1 : 0;
                $reg->cardcollected = in_array($reg->studentregistrationid, array_keys($cardcollected)) ? 1 : 0;
                $reg->save();
            }
            Yii::$app->session->setFlash('success', 'Card data updated sucessfully');
        }
        return $this->redirect(Url::to(['index']));
        
    }

}
