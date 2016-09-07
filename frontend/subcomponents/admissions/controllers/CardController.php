<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

use common\models\User;
use frontend\models\Division;
use frontend\models\StudentRegistration;
use frontend\models\ApplicationPeriod;
use frontend\models\Email;
use frontend\models\Offer;
use frontend\models\Applicant;
use frontend\models\ProgrammeCatalog;
use frontend\models\ApplicationCapesubject;
use frontend\models\EmployeeDepartment;
use frontend\models\Employee;


class CardController extends \yii\web\Controller
{
    /**
     * Renders Index and processes search
     * 
     * @param type $criteria
     * @param type $periodid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 23/04/2016
     * Date Last Modified: 23/04/2016
     */
    public function actionIndex($criteria = NULL, $periodid = NULL)
    {
        $dataProvider = NULL;
        $info_string = NULL;
        $enrolled_filename = NULL;
        
        $division_id = EmployeeDepartment::getUserDivision();
        
        if($criteria != NULL)
        {
            //if search is done by application period
            if ($criteria == "application-period")
            {
                $period = ApplicationPeriod::find()
                        ->where(['applicationperiodid' => $periodid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();

                $info_string = " Application Period [ " . $period->name . " ]";

                $offer_cond = array();
                $offer_cond['application.isactive'] = 1;
                $offer_cond['application.isdeleted'] = 0;
                $offer_cond['academic_offering.isactive'] = 1;
                $offer_cond['academic_offering.isdeleted'] = 0;
                $offer_cond['application_period.applicationperiodid'] = $periodid;
                $offer_cond['application_period.isactive'] = 1;
                $offer_cond['application_period.isdeleted'] = 0;
                $offer_cond['offer.isactive'] = 1;
                $offer_cond['offer.isdeleted'] = 0;
                $offer_cond['offer.ispublished'] = 1;
                $offer_cond['student_registration.isactive'] = 1;
                $offer_cond['student_registration.isdeleted'] = 0;

                $offers = Offer::find()
                        ->joinWith('application')
                        ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->innerJoin('`student_registration`', '`application`.`personid` = `student_registration`.`personid`')
                        ->where($offer_cond)
                        ->all();

                $data = array();
                foreach ($offers as $offer)
                {
                    $cape_subjects_names = array();
                    $application = $offer->getApplication()->one();
                    $applicant = Applicant::findOne(['personid' => $application->personid, 'isactive' => 1, 'isdeleted' => 0]);
                    $username = User::findOne(['personid' => $applicant->personid, 'isdeleted' => 0])->username;
                    $email = Email::findOne(['personid' => $application->personid, 'isactive' => 1, 'isdeleted' => 0])->email;
                    $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid, 'isactive' => 1, 'isdeleted' => 0]);
                    $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid, 'isactive' => 1, 'isdeleted' => 0]);
                    
                    foreach ($cape_subjects as $cs) 
                    { 
                        $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                    }
                    $student_reg = StudentRegistration::findOne(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0]);

                    $offer_data = array();
                    $offer_data['offerid'] = $offer->offerid;
                    $offer_data['studentreg'] = $student_reg;
                    $offer_data['title'] = $applicant->title;
                    $offer_data['firstname'] = $applicant->firstname;
                    $offer_data['middlename'] = $applicant->middlename;
                    $offer_data['lastname'] = $applicant->lastname;
                    $offer_data['programme'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
                    $offer_data['registrationdate'] = $student_reg->registrationdate;
                    $offer_data['division'] = Division::getDivisionAbbreviation($application->divisionid);
                    $offer_data['email'] = $email;
                    $offer_data['username'] = $username;
                    $offer_data['published'] = $offer->ispublished;
                    $offer_data['registered'] = $student_reg ? True : False;
                    $offer_data['picturetaken'] = $student_reg ? $student_reg->receivedpicture : False;
                    $offer_data['cardready'] = $student_reg ? $student_reg->cardready : False ;
                    $offer_data['cardcollected'] = $student_reg ? $student_reg->cardcollected : False;
                    $data[] = $offer_data;
                }
                
                
                $enrolled_header = "Enrolled Applicants Report - ";
                $enrolled_title = "Title: " . $period->name . " " .  $enrolled_header;

                $date = " Date: " . date('Y-m-d') . "   ";
                $employeeid = Yii::$app->user->identity->personid;
                $generating_officer = " Generator: " . Employee::getEmployeeName($employeeid);

                $enrolled_filename = $enrolled_title . $date . $generating_officer;
            }

            
            elseif($criteria == "student-id"  ||  $criteria == "student-name")
            {
                if($criteria == "student-id")
                    $info_string = "Student ID";

                elseif($criteria == "student-name")
                    $info_string = "Student Name";

                if (Yii::$app->request->post())
                {
                    $request = Yii::$app->request;
                    $student_id = $request->post('field_studentid');
                    $firstname = $request->post('field_firstname');
                    $lastname = $request->post('field_lastname');

                    $offer_cond = array();
                    $offer_cond['application_period.isactive'] = 1;
                    $offer_cond['application_period.isdeleted'] = 0;
                    $offer_cond['offer.isactive'] = 1;
                    $offer_cond['offer.isdeleted'] = 0;
                    $offer_cond['offer.ispublished'] = 1;
                    $offer_cond['student_registration.isactive'] = 1;
                    $offer_cond['student_registration.isdeleted'] = 0;
                    
                    //if user initiates search based on studentid
                    if ($student_id)
                    {
                        $user = User::findOne(['username' => $student_id, 'isactive' => 1, 'isdeleted' => 0]);
                        $offer_cond['student.personid'] = $user->personid;
                        $info_string = $info_string .  " Applicant ID: " . $student_id;
                    }    

                    //if user initiates search based on student name    
                    if ($firstname)
                    {
                        $offer_cond['student.firstname'] = $firstname;
                        $info_string = $info_string .  " First Name: " . $firstname; 
                    }
                    if ($lastname)
                    {
                        $offer_cond['student.lastname'] = $lastname;
                        $info_string = $info_string .  " Last Name: " . $lastname;
                    } 
                    
                    $offers = Offer::find()
                        ->joinWith('application')
                        ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->innerJoin('`student_registration`', '`application`.`personid` = `student_registration`.`personid`')
                        ->innerJoin('`student`', '`student_registration`.`personid` = `student`.`personid`')
                        ->where($offer_cond)
                        ->all();

                    $data = array();
                    foreach ($offers as $offer)
                    {
                        $cape_subjects_names = array();
                        $application = $offer->getApplication()->one();
                        $applicant = Applicant::findOne(['personid' => $application->personid]);
                        $username = User::findOne(['personid' => $applicant->personid, 'isdeleted' => 0])->username;
                        $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
                        $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
                        foreach ($cape_subjects as $cs) 
                        { 
                            $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                        }
                        $student_reg = StudentRegistration::findOne(['personid' => $applicant->personid, 'isactive' => 1]);

                        $offer_data['offerid'] = $offer->offerid;
                        $offer_data['studentreg'] = $student_reg;
                        $offer_data['title'] = $applicant->title;
                        $offer_data['firstname'] = $applicant->firstname;
                        $offer_data['middlename'] = $applicant->middlename;
                        $offer_data['lastname'] = $applicant->lastname;
                        $offer_data['programme'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
                        $offer_data['username'] = $username;
                        $offer_data['published'] = $offer->ispublished;
                        $offer_data['registered'] = $student_reg ? True : False;
                        $offer_data['picturetaken'] = $student_reg ? $student_reg->receivedpicture : False;
                        $offer_data['cardready'] = $student_reg ? $student_reg->cardready : False ;
                        $offer_data['cardcollected'] = $student_reg ? $student_reg->cardcollected : False;
                        $data[] = $offer_data;
                    }
                }
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 25,
                ],
                'sort' => [
                    'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                    'attributes' => ['firstname', 'lastname', 'studentno'],
                  ]
            ]);          
        }
        
        return $this->render('index',
                [
                    'dataProvider' => $dataProvider,
                    'info_string' => $info_string,
                    
                    'enrolled_filename' => $enrolled_filename,
                ]);
    }

    
    /**
     * Updates applicant card status
     * 
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created:??
     * Date Last Modified: 23/04/2016 (L.Charles)
     */
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
//            Yii::$app->session->setFlash('success', 'Card data updated sucessfully');
        }
        return $this->redirect(Url::to(['index']));
        
    }

}
