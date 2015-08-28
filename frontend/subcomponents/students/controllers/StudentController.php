<?php

namespace app\subcomponents\students\controllers;

use yii\web\Controller;

use frontend\models\Division;

class StudentController extends Controller
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
    
    public function actionViewStudents($divisionid)
    {
        $division = Division::findOne(['divisionid' => $divisionid ]);
        $division_abbr = $division ? $division->abbreviation : 'Undefined Division';
        
        
        if ($divisionid && $divisionid == 1)
        {
            $app_period_name = "All Active Application Periods";
            $offer_cond = array('application_period.isactive' => 1);
        }
        
        $data = array();
        foreach ($offers as $offer)
        {
            $cape_subjects_names = array();
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
            'sort' => [
                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                'attributes' => ['firstname', 'lastname', 'studentno'],
              ]
        ]);

        return $this->render('view-applicants', [
            'dataProvider' => $dataProvider,
            'divisionabbr' => $division_abbr,
            'applicationperiodname' => $app_period_name,
        ]);
    }
}
