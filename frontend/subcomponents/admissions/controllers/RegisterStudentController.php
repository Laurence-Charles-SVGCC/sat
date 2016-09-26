<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;

use common\models\User;
use frontend\models\Applicant;
use frontend\models\Application;
use frontend\models\Offer;
use frontend\models\ProgrammeCatalog;
use frontend\models\ApplicationCapesubject;
use frontend\models\DocumentSubmitted;
use frontend\models\TransactionPurpose;
use frontend\models\Transaction;
use frontend\models\Student;
use frontend\models\StudentRegistration;
use frontend\models\RegistrationType;
use frontend\models\DocumentIntent;
use frontend\models\Division;
use frontend\models\ApplicationPeriod;
use frontend\models\EmployeeDepartment;
use frontend\models\Employee;
use frontend\models\Email;



class RegisterStudentController extends \yii\web\Controller
{
//    public function actionIndex()
//    {
//        return $this->render('index');
//    }
    
    
    
    /**
     * 
     * @param type $personid
     * @param type $programme
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: ??
     * Date Last Modified: 21/04/2016
     */
    public function actionViewProspectiveStudent($personid, $programme)
    {
        $applicant = Applicant::find()
                        ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            
        $username = $applicant->getPerson()->one()->username;
        
        $applications = Application::find()
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where([/*'application_period.iscomplete' => 0,*/  'application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                                'application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $applicant->personid])
                        ->all();
        
        $application_container = array();
        $target_application = NULL;
        $divisionid = NULL;
        
        foreach($applications as $application)
        {

            $combined = array();
            $keys = array();
            $values = array();

            array_push($keys, "application");
            array_push($keys, "istarget");
            array_push($keys, "division");
            array_push($keys, "programme");

            array_push($values, $application);
            
//            $offer = Offer::getActiveOffer($applicant->personid);
            $offer = Offer::getActiveFullOffer($applicant->personid);
            
            //if this application is the same as the one that is associated with current offer
            if ($application->applicationid == $offer->applicationid)
            {
                $divisionid = $application->divisionid;
                $istarget = true;
                $target_application = $application;
            }
            else
                $istarget = false;
            array_push($values, $istarget);
            
            $division = Division::find()
                            ->where(['divisionid' => $application->divisionid])
                            ->one()
                            ->abbreviation;
            array_push($values, $division);
            
            $cape_subjects_names = array();
            $cape_subjects = ApplicationCapesubject::find()
                        ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                        ->where(['application.applicationid' => $application->applicationid,
                                'application.isactive' => 1,
                                'application.isdeleted' => 0]
                                )
                        ->all();

            $programme_record = ProgrammeCatalog::find()
                        ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                        ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->where(['application.applicationid' => $application->applicationid])
                        ->one();

            foreach ($cape_subjects as $cs) 
            { 
                $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
            }

            $programme_name = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
            array_push($values, $programme_name);

            $combined = array_combine($keys, $values);
            array_push($application_container, $combined);  
        }
        
        //Get documents already submitted
        $selections = array();
        foreach (DocumentSubmitted::findAll(['personid' => $personid]) as $doc)
        {
            array_push($selections, $doc->documenttypeid);
        }
        
        return $this->render('prospective_student',
                    [
                        'personid' => $personid,
                        'username' => $username,
                        'applicant' => $applicant,
                        'applications' => $applications,
                        'application_container' => $application_container,
                        'applicationid' => $target_application->applicationid,
                        'target_application' => $target_application,
                        'programme' => $programme,
                        'divisionid' => $divisionid,
                        
                        'selections' => $selections,
                        'offerid' => $offer->offerid,
                        'applicationid' => $target_application->applicationid,
                    ]);
    }
    
    
    /**
     * Creates a student record for a successful appplicant
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 22/04/2016
     * Date Last Modified: 22/04/2016
     * 
     */
    public function actionEnrollStudent($personid, $programme)
    {
        $student_save_flag = false;
        $user_save_flag = false;
        $registration_save_flag = false;
        $document_save_flag = false;
        $email_save_flag = false;
    
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
           
            //Make applicant a student
            $offerid = $request->post('offerid');
            $applicant = Applicant::findOne(['applicantid' => $request->post('applicantid')]);
            $application = Application::findOne(['applicationid' => $request->post('applicationid')]);
            $user = User::findOne(['personid' => $applicant->personid]);
            
            /* Prevents the creation of multiple records during Student enrollment.
             * Possible source of problem;
             * 1. Unstable internet connection (most likely)
             * 2. User clicking "Submit" button multiple times.
             */
            $username = $user->username;
            if (substr($username, 0, 1) == "1")
            {
                $old_registration = StudentRegistration::find()
                        ->where(['perosnid' => $personid, 'offerid' => $offerid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                
                Yii::$app->getSession()->setFlash('error', 'Student has already been registered.');
                
                if($old_registration)
                {
                    return $this->redirect( Url::toRoute(['/subcomponents/students/profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $old_registration->studentregistrationid]));
                }
                else
                {
                    return self::actionViewProspectiveStudent($personid, $programme);
                }
            }
            
           
            $transaction = \Yii::$app->db->beginTransaction();
            try 
            {
                $student = new Student();
                $student->personid = $applicant->personid;
                $student->applicantname = $user->username;
                $student->title = $applicant->title;
                $student->firstname = $applicant->firstname;
                $student->middlename = $applicant->middlename;
                $student->lastname = $applicant->lastname;
                $student->gender = $applicant->gender;
                $student->dateofbirth = $applicant->dateofbirth;
                $student->admissiondate = date('Y-m-d');
                $student_save_flag = $student->save();
                if($student_save_flag == false)
                {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error saving student record.');
                     return self::actionViewProspectiveStudent($personid, $programme);
                }
                else
                {
                    $email = Email::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($email == false)
                    {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Error saving student record.');
                         return self::actionViewProspectiveStudent($personid, $programme);
                    }
                    
                    //Update username
                    if ($applicant->potentialstudentid)
                       $user->username = $applicant->potentialstudentid;
                    else
                    {
                       $student_number = Applicant::preparePotentialStudentID($application->divisionid, $applicant->applicantid, "generate");
                       $user->username = $student_number;
                    }
                    
                    $user->email = $email->email;
                    $user->persontypeid = 2;
                    
                    $user_save_flag = $user->save();
                    if ($user_save_flag == false)
                    {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Error saving user record.');
                        return self::actionViewProspectiveStudent($personid, $programme);
                    }
                    else
                    {
                        //Capture student registration
                        $reg = new StudentRegistration();
                        $reg->offerid = intval($offerid);
                        $reg->personid = $applicant->personid;
                        $reg->academicofferingid = $application->academicofferingid;
                        $reg_type = RegistrationType::findOne(['name' => 'fulltime', 'isdeleted' => 0]);
                        $reg->registrationtypeid = $reg_type->registrationtypeid;
                        $reg->currentlevel = 1;
                        $reg->registrationdate = date('Y-m-d');
                        $registration_save_flag = $reg->save();

                        if ($registration_save_flag == false)
                        {   
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error saving student registration record.');
                            return self::actionViewProspectiveStudent($personid, $programme);
                        }
                        else
                        {
                            //Update document submission
                            $submitted = $request->post('documents');
                            $docs = DocumentSubmitted::findAll(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0]);
                            
                            //if form has non selected then any documents that were prevously selected must be deleted
                            if (!$submitted)
                            {
                                foreach ($docs as $doc)
                                {
                                    $doc->isactive = 0;
                                    $doc->isdeleted = 1;
                                    $document_save_flag = $doc->save();
                                    if ($document_save_flag == false)
                                    {
                                        $transaction->rollBack();
                                        Yii::$app->getSession()->setFlash('error', 'Error deleting document record.');
                                        return self::actionViewDocuments( $applicantid, $centrename,  $cseccentreid, $type,  $personid );
                                    }
                                }
                                $transaction->commit();
                                return self::actionViewApplicantQualifications($applicantid, $centrename, $cseccentreid, $type, $personid);
                            }
                            
                            
                            $docs_arr = array();
                            if ($docs)
                            {
                                foreach ($docs as $doc)
                                { 
                                    $docs_arr[] = $doc->documenttypeid; 
                                }

                                foreach ($docs as $doc)
                                {
                                    if (!in_array($doc->documenttypeid, $submitted))
                                    { 
                                        //Document has been unchecked
                                        $doc->isdeleted = 1;
                                        $document_save_flag = $doc->save();
                                        if ($document_save_flag == false)
                                        {
                                            $transaction->rollBack();
                                            Yii::$app->getSession()->setFlash('error', 'Error deleting document record.');
//                                            break;
                                              return self::actionViewProspectiveStudent($personid, $programme);
                                        }
                                    }
                                }  
                            }
                            
                            if($submitted)
                            {
                                foreach ($submitted as $sub)
                                {
                                    if (!in_array($sub, $docs_arr))
                                    { 
                                       $doc = new DocumentSubmitted();
                                       $doc->documenttypeid = $sub;
                                       $doc->personid = $applicant->personid;
                                       $doc->recepientid = Yii::$app->user->getId();
                                       $doc->documentintentid = 1;
                                       $document_save_flag = $doc->save(); 
                                       if ($document_save_flag == false)
                                       {
                                           $transaction->rollBack();
                                           Yii::$app->session->setFlash('error', 'Document could not be added');
//                                           break;
                                            return self::actionViewProspectiveStudent($personid, $programme);
                                       }
                                    }
                                }
                            }
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Student registered successfully');   
                            return $this->redirect( Url::toRoute(['/subcomponents/students/profile/student-profile', 'personid' => $applicant->personid, 'studentregistrationid' => $reg->studentregistrationid]));
                        }
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Error occured processing request.');   
                return self::actionViewProspectiveStudent($personid, $programme);
            }
       }
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
            /*Removed on order 28/08/2015*/
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
           $offerid = $request->post('offerid');
           $applicant = Applicant::findOne(['applicantid' => $request->post('applicantid')]);
           $application = Application::findOne(['applicationid' => $request->post('applicationid')]);
           if (!$applicant)
           { 
               $applicant = new Applicant;   
           }
           $applicant->load(Yii::$app->request->post());
           if ($applicant->save())
           {
               $new_student = false;
               $student = Student::findOne(['personid' => $applicant->personid]);
               if (!$student)
               { 
                   $student = new Student(); 
                   $new_student = true; 
                   
                }
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
                       $user->save();
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
                       
                       $reg->offerid = intval($offerid);
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
