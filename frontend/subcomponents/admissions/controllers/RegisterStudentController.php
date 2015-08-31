<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use common\models\User;
use frontend\models\Applicant;
use frontend\models\Application;
use frontend\models\Offer;
use yii\helpers\Url;
use frontend\models\ProgrammeCatalog;
use frontend\models\ApplicationCapesubject;
use frontend\models\DocumentSubmitted;
use frontend\models\TransactionPurpose;
use frontend\models\Transaction;
use frontend\models\Student;
use frontend\models\StudentRegistration;
use frontend\models\RegistrationType;
use frontend\models\DocumentIntent;

class RegisterStudentController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /*
    * Purpose: Responds to user indicating they want to register an applicant.
     * Does prelinary checks to ensure that applicant can be registered
    * Created: 3/08/2015 by Gamal Crichton
    * Last Modified: 3/08/2015 by Gamal Crichton
    */
    public function actionRegisterApplicant($applicantusername)
    {
        $user = User::findOne(['username' => $applicantusername, 'isdeleted' => 0]);
        $applicant = $user ? Applicant::findOne(['personid' => $user->personid, 'isdeleted' => 0]) : NULL;
        $personid = $user ? $user->personid : NULL;
        $applications = $personid ? Application::findAll(['personid' => $personid, 'isdeleted' => 0]) : array();
        $offers = array();
        $success_application = NULL;
        $success_offer = NULL;
        foreach($applications as $app)
        {
            
            $offer = Offer::findOne(['applicationid' => $app->applicationid, 'isdeleted' => 0]);
            if ($offer)
            {
                $offers[] = $offer;
                $programme = ProgrammeCatalog::find()
                    ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->where(['application.applicationid' => $app->applicationid])->one();
                $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $app->applicationid]);
                $success_application = $app;
                $success_offer = $offer;
            }
        }
        
        if (count($offers) == 0)
        {
            Yii::$app->session->setFlash('error', 'This applicant has no valid offer.');
        }
        else if (count($offers) > 1)
        {
            Yii::$app->session->setFlash('error', 'This applicant has multiple valid offers.');
        }
        else
        {
            //Check Bursary Status
            /*$app_purpose = TransactionPurpose::findOne(['name' => 'application', 'isdeleted' => 0]);
            $reg_purpose = TransactionPurpose::findOne(['name' => 'registration', 'isdeleted' => 0]);
            $app_fee = Transaction::findOne(['transactionpurposeid' => $app_purpose->transactionpurposeid, 'personid' => $personid,
                'isdeleted' => 0]);
            $reg_fee = Transaction::findOne(['transactionpurposeid' => $reg_purpose->transactionpurposeid, 'personid' => $personid,
                'isdeleted' => 0]);
            if (!$app_fee)
            {
                Yii::$app->session->setFlash('error', 'This applicant has not settled application fees.');
            }
            else if (!$reg_fee)
            {
                Yii::$app->session->setFlash('error', 'This applicant has not settled registration fees.');
            }
            else
            {*/
                //Get documents already submitted
                $selections = array();
                foreach (DocumentSubmitted::findAll(['personid' => $personid]) as $doc)
                {
                    array_push($selections, $doc->documenttypeid);
                }
                //Register user
                return $this->render('register-student', [
                    'applicant' => $applicant,
                    'selections' => $selections,
                    'offerid' => $success_offer->offerid,
                    'applicationid' => $success_application->applicationid,
                ]);
            //}
        }
                 
        return $this->redirect(Url::to(['view-applicant/view-applicant', 'applicantid' => $applicant->applicantid,
                'username' => $applicantusername]) );
   }
   
   public function actionMakeStudent()
   {
       if (Yii::$app->request->post())
       {
           $request = Yii::$app->request;
           //Make applicant a student
           $applicant = Applicant::findOne(['applicantid' => $request->post('applicantid')]);
           $application = Application::findOne(['applicationid' => $request->post('applicationid')]);
           if (!$applicant){ $applicant = new Applicant; }
           $applicant->load(Yii::$app->request->post());
           if ($applicant->save())
           {
               $new_student = False;
               $student = Student::findOne(['personid' => $applicant->personid]);
               if (!$student){ $student = new Student(); $new_student = True; }
               $user = User::findOne(['personid' => $applicant->personid]);
               $student->personid = $applicant->personid;
               $student->applicantname = $user ? $user->username : Null;
               $student->title = $applicant->title;
               $student->firstname = $applicant->firstname;
               $student->middlename = $applicant->middlename;
               $student->lastname = $applicant->lastname;
               $student->gender = $applicant->gender;
               $student->dateofbirth = $applicant->dateofbirth;
               $student->admissiondate = date('Y-m-d');
               if ($student->save())
               {
                   //Update User
                   if ($applicant->potentialstudentid)
                   {
                       $user->username = $applicant->potentialstudentid;
                   }
                   else
                   {
                       Yii::$app->session->setFlash('error', 'No Student Number assigned.');
                   }
                   
                   if ($new_student)
                   {
                       //Capture student registration
                       $reg = new StudentRegistration();
                       $reg_type = RegistrationType::findOne(['name' => 'fulltime', 'isdeleted' => 0]);

                       $reg->personid = $applicant->personid;
                       $reg->academicofferingid = $application->academicofferingid;
                       $reg->registrationtypeid = $reg_type->registrationtypeid;
                       $reg->currentlevel = 1;
                       $reg->registrationdate = date('Y-m-d');
                   }
                   
                   if (($new_student == False) || $reg->save())
                   {   
                       //Update document submission
                       $submitted = $request->post('documents');
                       $docs = DocumentSubmitted::findAll(['personid' => $applicant->personid, 'isdeleted' => 0]);
                       $docs_arr = array();
                       if ($docs)
                       {
                           foreach ($docs as $doc){ $docs_arr[] = $doc->documenttypeid; }
                           foreach ($docs as $doc)
                           {
                               if (!in_array($doc->documenttypeid ,$submitted))
                               { 
                                   //Document has been unchecked
                                   $doc->isdeleted = 1;
                                   $doc->save();
                               }
                            }  
                        }

                       foreach ($submitted as $sub)
                       {
                           if (!in_array($sub, $docs_arr))
                           { 
                               $doc = new DocumentSubmitted();
                               $intent = DocumentIntent::findOne(['description' => 'registration']);
                               $doc->documenttypeid = $sub;
                               $doc->personid = $applicant->personid;
                               $doc->recepientid = Yii::$app->user->getId();
                               $doc->documentintentid = $intent ? $intent->documentintentid : Null;
                               if (!$doc->save())
                               {
                                   Yii::$app->session->setFlash('error', 'Document could not be added');
                               }
                           }
                       }
                       Yii::$app->session->setFlash('success', 'Student registered successfully');
                       return $this->redirect(Url::to(['view-applicant/index']));
                   }
                   else
                   {
                       Yii::$app->session->setFlash('error', 'Student Registration could not be added');
                   }
                   
               }
               else
               {
                   Yii::$app->session->setFlash('error', 'Student could not be added');
               }
               $user = User::findOne(['personid' => $applicant->personid]);
               if ($user)
               {
                return $this->redirect(Url::to(['register-student/register-applicant', 'applicantusername' => $user->username]));
               }
               return $this->redirect(Url::to(['view-applicant/index']));
           }
       }
   }
          

}
