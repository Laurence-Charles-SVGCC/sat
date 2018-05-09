<?php

    namespace app\subcomponents\admissions\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\base\Model;
    use yii\data\ArrayDataProvider;
    use yii\helpers\Json;
    
    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\AcademicYear;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\CapeSubject;
    use frontend\models\Subject;
    use frontend\models\CapeGroup;
    use frontend\models\CapeSubjectGroup;
    use frontend\models\AcademicOffering;
    use frontend\models\EmployeeDepartment;
    use frontend\models\Email;
    use frontend\models\Applicant;
    use frontend\models\Offer;
    use frontend\models\StudentRegistration;
    use frontend\models\Application;
    use frontend\models\ApplicationCapesubject;
    use frontend\models\ApplicationPeriodType;
    use frontend\models\ApplicationperiodStatus;
    use frontend\models\Division;
    use frontend\models\ApplicantIntent;
    use frontend\models\Employee;
    use frontend\models\DocumentSubmitted;

class AdmissionsController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    
    /**
     * Facilitates search for current applicants
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 24/02/2016
     * Date Last Modified: 24/02/2016
     */
    public function actionFindCurrentApplicant($status)
    {
        $division_id = EmployeeDepartment::getUserDivision();
        
        $dataProvider = null;
        $info_string = null;
        
        if (Yii::$app->request->post())
        {
            //Everytime a new search is initiated session variable must be removed
             if (Yii::$app->session->get('app_id'))
                Yii::$app->session->remove('app_id');
             
            if (Yii::$app->session->get('firstname'))
                Yii::$app->session->remove('firstname');
            
            if (Yii::$app->session->get('lastname'))
                Yii::$app->session->remove('lastname');
            
             if (Yii::$app->session->get('email'))
                Yii::$app->session->remove('email');
             
            $request = Yii::$app->request;
            $app_id = $request->post('applicantid_field');
            $email = $request->post('email_field');
            $firstname = $request->post('FirstName_field');
            $lastname = $request->post('LastName_field');
            
             if(Yii::$app->session->get('app_id') == null  && $app_id == true)
                Yii::$app->session->set('app_id', $app_id);
            
            if(Yii::$app->session->get('firstname') == null  && $firstname == true)
                Yii::$app->session->set('firstname', $firstname);
            
            if(Yii::$app->session->get('lastname') == null  && $lastname == true)
                Yii::$app->session->set('lastname', $lastname);
            
            if(Yii::$app->session->get('email') == null  && $email == true)
                Yii::$app->session->set('email', $email);
        }
        else    
        {
            $app_id = Yii::$app->session->get('app_id');
            $firstname = Yii::$app->session->get('firstname');
            $lastname = Yii::$app->session->get('lastname');
            $email = Yii::$app->session->get('email');
        }
            
        
        //if user initiates search based on applicantid
        if ($app_id)
        {
            $user = User::findOne(['username' => $app_id, 'isdeleted' => 0]);
            $cond_arr['applicant.personid'] = $user? $user->personid : null;
            $info_string = $info_string .  " Applicant ID: " . $app_id;
        }    

        //if user initiates search based on applicant name    
        if ($firstname)
        {
            $cond_arr['applicant.firstname'] = $firstname;
            $info_string = $info_string .  " First Name: " . $firstname; 
        }
        if ($lastname)
        {
            $cond_arr['applicant.lastname'] = $lastname;
            $info_string = $info_string .  " Last Name: " . $lastname;
        }        

        //if user initiates search based on applicant email
        if ($email)
        {
//            $email_add = Email::findOne(['email' => $email, 'isdeleted' => 0]);
//            $cond_arr['applicant.personid'] = $email_add? $email_add->personid: null;
//            $info_string = $info_string .  " Email: " . $email;
            
            $email_records = Email::find()
                    ->where(['email' => $email, 'isdeleted' => 0])
                    ->all();
            if ($email_records == true)
            {
                $ids = array();
                foreach($email_records as $address)
                {
                    $ids[] = $address->personid;
                }
                 $cond_arr['applicant.personid'] = $ids;
            }
            else
            {
                $cond_arr['applicant.personid'] = null;
            }
            $info_string = $info_string .  " Email: " . $email;
        }


        if (empty($cond_arr))
        {
            Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
        }
        else
        {
            $cond_arr['applicant.isactive'] = 1;
            $cond_arr['applicant.isdeleted'] = 0;
            $cond_arr['academic_offering.isactive'] = 1;
            $cond_arr['academic_offering.isdeleted'] = 0;
            $cond_arr['application_period.isactive'] = 1;

            if ($status== "pending")
                $cond_arr['application_period.iscomplete'] = 0;

            $cond_arr['application.isactive'] = 1;
            $cond_arr['application.isdeleted'] = 0;
            if ($status == "pending" || $status == "pending-unlimited"  ||  $status == "submitted-unlimited")
            {
                $cond_arr['application.applicationstatusid'] = [2,3,4,5,6,7,8,9,10,11];
            }
            elseif ($status == "successful")
            {
                $cond_arr['application.applicationstatusid'] = 9;
                $cond_arr['offer.isactive'] = 1;  
                $cond_arr['offer.isdeleted'] = 0;
                $cond_arr['offer.ispublished'] = 1;
            }

            /*
             *  If DASGS or DTVE, both divisions are searched
             *  This is because applicants may apply to both divisions
             */
            if ($division_id == 4  || $division_id == 5 )
                $cond_arr['application.divisionid'] = [4,5];

            /*
             *  If DTE or DNE the applicants are constrained to each division
             */
            elseif ($division_id == 6  || $division_id == 7 )
                $cond_arr['application.divisionid'] = $division_id;

            if ($status == "pending" || $status == "pending-unlimited"  ||  $status == "submitted-unlimited")
            {
                $applicants = Applicant::find()
                            ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                            ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                            ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                            ->where($cond_arr)
                            ->groupBy('applicant.personid')
                            ->all();
            }
            elseif($status == "successful")
            {
                $applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                         ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where($cond_arr)
                        ->groupBy('applicant.personid')
                        ->all();
            }

            if (empty($applicants))
            {
                Yii::$app->getSession()->setFlash('error', 'No applicant found matching this criteria.');
            }
            else
            {
                $data = array();
                foreach ($applicants as $applicant)
                {
                    if($status == "pending"  || $status == "pending-unlimited"   ||  $status == "submitted-unlimited")
                    {
                        $app = array();
                        $user = $applicant->getPerson()->one();
                        
                        
                        $app['status'] = $status;
                        $app['username'] = $user ? $user->username : '';
                        $app['personid'] = $applicant->personid;
                        $app['applicantid'] = $applicant->applicantid;
                        $app['firstname'] = $applicant->firstname;
                        $app['middlename'] = $applicant->middlename;
                        $app['lastname'] = $applicant->lastname;
                        $app['gender'] = $applicant->gender;
                        $app['dateofbirth'] = $applicant->dateofbirth;

                        $applications = Application::getApplications($applicant->personid);
                        $divisionid = $applications[0]->divisionid;

                        /*
                         * If division is DTE or DNE then all applications refer to one division
                         */
                        if ($divisionid == 6  || $divisionid == 7)
                        {
                            $division = Division::getDivisionAbbreviation($divisionid);
                            $app["division"] = $division;
                        }
                        /*
                         * If division is DASGS or DTVE then applications may refer to multiple divisions
                         */
                        elseif ($divisionid == 4  || $divisionid == 5)
                        {
                            $dasgs = 0;
                            $dtve = 0;
                            foreach($applications as $application)
                            {
                                if ($application->divisionid == 4)
                                    $dasgs++;
                                elseif ($application->divisionid == 5)
                                    $dtve++;
                            }
                            if ($dasgs>=1  && $dtve>=1)
                                $divisions = "DASGS & DTVE";
                            elseif ($dasgs>=1  && $dtve==0)
                                $divisions = "DASGS";
                            elseif ($dasgs==0  && $dtve>=1)
                                $divisions = "DTVE";
                            else
                                 $divisions = "Unknown";
                            $app["division"] = $divisions;
                        }


                        if($status == "pending-unlimited"  ||  $status == "submitted-unlimited")
                            $info = Applicant::getApplicantInformation($applicant->personid, true);
                        else
                            $info = Applicant::getApplicantInformation($applicant->personid);

                        $app['programme_name'] = $info["prog"];
                        $app['application_status'] = $info["status"];

                        if(Application::hasOldApplication($applicant->personid)==true)
                            $app['has_deprecated_application'] = true;
                        else
                            $app['has_deprecated_application'] = false;

                        if(Offer::hasActivePublishedFullOffer($applicant->personid))
                            $app['has_offer'] = true;
                        else
                            $app['has_offer'] = false;

                        if(Application::hasActiveApplications($applicant->personid))
                            $app['has_active_applications'] = true;
                        else
                            $app['has_active_applications'] = false;

                        if(Application::hasInactiveApplications($applicant->personid))
                            $app['has_inactive_applications'] = true;
                        else
                            $app['has_inactive_applications'] = false;

                        $data[] = $app;
                    }
                    elseif($status =="successful")
                    {
                        $offers = Offer::hasOffer($applicant->personid);

                        if($offers == true)
                        {
                            foreach ($offers as $offer) 
                            {
                                $has_enrolled = StudentRegistration::find()
                                        ->where(['offerid' => $offer->offerid, 'isactive' => 1, 'isdeleted' => 0])
                                        ->one();

                                if($has_enrolled == false)
                                {
                                    $username = User::findOne(['personid' => $applicant->personid, 'isdeleted' => 0])->username;

                                    $programme = "N/A";
                                    $target_application = Application::find()
                                            ->where(['applicationid' => $offer->applicationid, 'isactive' => 1, 'isdeleted' => 0])
                                            ->one();
                                    if ($target_application) 
                                    {
                                        $programme_record = ProgrammeCatalog::find()
                                                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                                                ->where(['academicofferingid' => $target_application->academicofferingid])
                                                ->one();
                                        $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $target_application->applicationid]);
                                        foreach ($cape_subjects as $cs) 
                                        {
                                            $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname;
                                        }
                                        $programme = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                                    }

                                    $app = array();
                                    $app['status'] = $status;
                                    $app['personid'] = $applicant->personid;
                                    $app['applicantid'] = $applicant->applicantid;
                                    $app['username'] = $username;
                                    $app['title'] = $applicant->title;
                                    $app['firstname'] = $applicant->firstname;
                                    $app['middlename'] = $applicant->middlename;
                                    $app['lastname'] = $applicant->lastname;
                                    $app['offerid'] = $offer->offerid;
                                    $app['applicationid'] = $offer->applicationid;
                                    $app['programme_name'] = $programme;

                                    $data[] = $app;

                                    $cape_subjects = NULL;
                                    $cape_subjects_names = NULL;
                                }
                            }
                        }
                    }
                }
                $dataProvider = new ArrayDataProvider([
                    'allModels' => $data,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => [
                        'attributes' => ['applicantid', 'firstname', 'lastname'],
                        ],
                ]);
            }
        }
        //}removed to rescope post block
        
        $search_status = $status;
        
        return $this->render('find_current_applicant', 
            [
            'dataProvider' => $dataProvider,
//            'status' => $status,
            'info_string' => $info_string,
            'search_status' => $search_status,
        ]);
    }
    
    
    
    public function actionProcessApplicantIntentid($divisionid, $applicationperiodtypeid, $applicantintentid)
    {
        $academicYearExists = 0;
        $applicationPeriodExists = 0;
        
        if ($applicantintentid == 1)
        {
            $academicYear = AcademicYear::find()
                    ->where(['applicantintentid' => $applicantintentid, 'iscurrent' => 1, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
             if ($academicYear)   
             {
                 $academicYearExists = 1;
                 $period = ApplicationPeriod::find()
                         ->where(['divisionid' => $divisionid, 'iscomplete' => 0, 'isactive' => 1, 'isdeleted' => 0])
                         ->one();
                 if ($period)
                 {
                     $applicationPeriodExists = 1;
                 }
             }
        }
        
        
        echo Json::encode(['academicYearExists' => $academicYearExists, 'applicationPeriodExists' => $applicationPeriodExists]);
    }
    
    
    
    
    
    /**
     * @param type $personid
     * @return type
     * 
     * Author: charles.laurence1@gmail.com
     * Date Created: 2018_03_07
     * Date Last Modified: 2018_03_07
     */
    public function actionVerifyApplicantDocuments($personid)
    {
        $applicant = Applicant::find()
                        ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            
        $username = $applicant->getPerson()->one()->username;

        $selections = array();
        foreach (DocumentSubmitted::findAll(['personid' => $personid, 'isdeleted' => 0]) as $doc)
        {
            array_push($selections, $doc->documenttypeid);
        }
        
        
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            
            $transaction = \Yii::$app->db->beginTransaction();
            try 
            {
                //Update document submission
                $submitted = $request->post('documents');
                $docs = DocumentSubmitted::findAll(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0]);

                //if form has none selected then any documents that were prevously selected must be deleted
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
                            return self::actionVerifyApplicantDocuments($personid);
                        }
                    }
                }
                else
                {
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
                                $doc->isactive = 0;
                                $doc->isdeleted = 1;
                                $document_save_flag = $doc->save();
                                if ($document_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error deleting document record.');
                                    return self::actionVerifyApplicantDocuments($personid);
                                }
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
                           $doc->documentintentid = 1;
                           $document_save_flag = $doc->save(); 
                           if ($document_save_flag == false)
                           {
                               $transaction->rollBack();
                               Yii::$app->session->setFlash('error', 'Document could not be added');
                               return self::actionVerifyApplicantDocuments($personid);
                           }
                        }
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Documents updated successfully');   
                return $this->redirect(['find-current-applicant', 'status' => 'submitted-unlimited']);
                
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Error occured processing request.'); 
                return $this->redirect(['find-current-applicant', 'status' => 'submitted-unlimited']);
            }
        }
        
        return $this->render('verify_applicant_documents', [
                    'personid' => $personid,
                    'username' => $username,
                    'applicant' => $applicant,
                    'selections' => $selections,
                ]);
    }
    
    
    
}
