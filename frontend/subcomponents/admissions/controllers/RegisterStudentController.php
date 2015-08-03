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

class RegisterStudentController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    
    public function actionRegisterApplicant($applicantusername)
    {
        $user = User::findOne(['username' => $applicantusername, 'isdeleted' => 0]);
        $applicant = $user ? Applicant::findOne(['personid' => $user->personid, 'isdeleted' => 0]) : NULL;
        $personid = $user ? $user->personid : NULL;
        $applications = $personid ? Application::findAll(['personid' => $personid, 'isdeleted' => 0]) : array();
        $offers = array();
        $application = NULL;
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
                $application = $app;
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
            $app_purpose = TransactionPurpose::findOne(['name' => 'application', 'isdeleted' => 0]);
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
            {
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
                ]);
            }
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
           if (!$applicant){ $applicant = new Applicant; }
           $applicant->load(Yii::$app->request->post());
           if ($applicant->save())
           {
               $student = new Student();
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
                   $submitted = $request->post('documents');
                   $docs = DocumentSubmitted::findOne(['personid' => $applicant->personid, 'isdeleted' => 0]);
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
                           $doc->documenttypeid = $sub;
                           $doc->personid = $applicant->personid;
                           $doc->recepientid = Yii::$app->user->getId();
                           if ($doc->save())
                           {
                               Yii::$app->session->setFlash('error', 'Document could nto be added');
                           }
                       }
                   }
                   
               }
               else
               {
                   Yii::$app->session->setFlash('error', 'Student could not be added');
               }
           }
       }
   }
          

}
