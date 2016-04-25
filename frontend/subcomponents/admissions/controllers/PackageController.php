<?php

    namespace app\subcomponents\admissions\controllers;
/* 
 * Controls all actions necessary to create and configure packages
 * for offers and rejections
 * 
 * Author: Laurence Charles
 * Date Created: 09/04/2016
 */

    use Yii;
    use yii\web\Controller;
    use yii\base\Model;
    use yii\helpers\FileHelper;
    use yii\web\UploadedFile;
    
    use common\models\User;
    use frontend\models\Employee;
    use frontend\models\Package;
    use frontend\models\PackageAttachment;
    use frontend\models\Offer;
    use frontend\models\Rejection;
    use frontend\models\Division;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\Applicant;
    use frontend\models\Email;
    use frontend\models\ApplicationPeriod;
    use frontend\models\RejectionApplications;
    use frontend\models\Application;
    
    
    
    class PackageController extends Controller
    {

        /**
         * Renders the Packages Summary which displays a record of all fully-configured
         * packages
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016
         */
        public function actionIndex()
        {
            $packages = Package::getPackages();
        
            if (count($packages) == 0)
                $container = false;
            else
            {
                $container = array();

                $keys = array();
                array_push($keys, 'id');
                array_push($keys, 'package_name');
                array_push($keys, 'period_name');
                array_push($keys, 'division');
                array_push($keys, 'year');
                array_push($keys, 'type');
                array_push($keys, 'progress');
                array_push($keys, 'created_by');
                array_push($keys, 'last_modified_by');
                array_push($keys, 'start_date');
                array_push($keys, 'completion_date');
                array_push($keys, 'document_count');


                foreach ($packages as $package)
                {
                    $values = array();
                    $row = array();
                    array_push($values, $package["id"]);
                    array_push($values, $package["package_name"]);
                    array_push($values, $package["period_name"]);
                    array_push($values, $package["division"]);
                    array_push($values, $package["year"]);
                    array_push($values, $package["type"]);
                    array_push($values, $package["progress"]);
                    $created_by = Employee::getEmployeeName($package["created_by"]);
                    array_push($values, $created_by);
                    $modified_by = Employee::getEmployeeName($package["last_modified_by"]);
                    array_push($values, $modified_by);
                    array_push($values, $package["start_date"]);
                    array_push($values, $package["completion_date"]);
                    array_push($values, $package["document_count"]);
                    $row = array_combine($keys, $values);
                    array_push($container, $row);

                    $values = NULL;
                    $row = NULL;
                }
            }
            
            return $this->render('packages_summary', 
            [
                'packages' => $container,
            ]);
        }
        
        
        /**
         * Reneders the Package Summary for a pending Package
         * 
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016
         */
        public function actionInitiatePackage($recordid = NULL)
        {
            if ($recordid == NULL)
            {
                $recordid = false;
                return $this->render('package_dashboard',
                    [
                        'recordid' => $recordid,
                    ]);
            }
            else
            {
                $package = Package::find()
                        ->where(['packageid' => $recordid])
                        ->one();
                
                return $this->render('package_dashboard', 
                [
                    'recordid' => $recordid,
                    'package' => $package,
                ]);
            }
        }
        
        
        /**
         * Processes a package depending on the action parameter;
         * -> if 'recordid==NULL', new package record is created,
         * -> else, current package configurations are edited,
         * 
         * @param type $action
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016 | 12/04/2016 | 15/04/2016
         */
        public function actionInitializePackage($recordid = NULL, $action = NULL)
        {
            if ($recordid == NULL)
            {
                $package = new Package();
                $name_changeable = true;
            }
            else
            {
                $package = Package::find()
                            ->where(['packageid' => $recordid])
                            ->one();
                $name_changeable = false;
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                
                $load_flag = $package->load($post_data);
                if($load_flag == true)
                { 
                    if (Package::currentPackageTypeExists($package->packagetypeid) == true)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Package of this type currently exists. This existing package must be deactivate first.');
                        return self::actionIndex();
                    }
                    else
                    {
                        /*
                         * If record is new the packageprogressid is set to 1 and creatorby field entered,
                         * else packageprogressid is not changed
                         */
                        if($package->packageid == NULL)
                        {
                            $package->packageprogressid = 1;
                            $package->createdby = Yii::$app->user->getID();
                            $package->datestarted = date('Y-m-d');
                            $package->isactive = 0;
                        } 
                        $package->lastmodifiedby = Yii::$app->user->getID();

                        /*
                         * If package does not require the upload of any documents;
                         * packageprogressid is updated accordingly so that test-package button will appear
                         */
                        if($package->documentcount == 0)
                            $package->packageprogressid = 2;

                        $save_flag = $package->save();
                        if($save_flag == true)
                        { 
                            /*
                             *  Creates folder for package in question if this is the first time the package is being created;
                             *  it is created regardless of whether document count > 0 to facilitate documents being added
                             *  subsequent to creation of package
                             */
                            if ($recordid == NULL)
                            {
                                $package_success = false;
                                $file = new FileHelper();
    //                            $dir = "@app/files/packages/" . $package->packageid . "_" . $package->name;
                                $dir = Yii::getAlias('@frontend') . "/files/packages/" . $package->packageid . "_" . $package->name;
                                $package_success = $file->createDirectory($dir, 509, true);
    //                            
                                if ($package_success == false) 
                                    Yii::$app->getSession()->setFlash('error', 'Error creating package folder. Please contact system administrator.');
                            }
                            /*
                             * if recordid is not null that mean package exists;
                             * -> the document count must be compared with the uploaded doucment count
                             * ->packageprogressid must be updated accordingly
                             */
                            else 
                            {
                                if($recordid == NULL)
                                    $has_docs= Package::hasAllDocuments();
                                else
                                    $has_docs= Package::hasAllDocuments($recordid);
                                if ($has_docs == false)
                                {
                                    $package->packageprogressid = 1;
                                    $package->save();
                                }
                                else
                                {
                                    $package->packageprogressid = 2;
                                    $package->save();
                                }
                            }
                            if ($action == NULL)
                                return self::actionInitiatePackage($package->packageid);
                            else
                                return self::actionIndex();
                        }
                        else
                            Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save package record. Please try again.');
                    }
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load package record. Please try again.');              
            }
            
            
            return $this->render('configure_package', 
                                [
                                    'package' => $package,
                                    'name_changeable' => $name_changeable,
                                    'recordid' => $recordid,
                                ]);
           
        }
        
        
        /**
         * Renders attatchment upload view
         * 
         * @param type $recordid
         * @param type $count
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016 | 12/04/2016
         */
        public function actionUploadAttachments($recordid, $count, $action = NULL)
        {
            $package = Package::find()
                            ->where(['packageid' => $recordid])
                            ->one();
            
            $model = new PackageAttachment();
            $model->package_id = $package->packageid;
            $model->package_name = $package->name;
            
            $saved_documents = Package::getDocuments($recordid);
            $model->limit = $package->documentcount - count($saved_documents);
            
            if ($model->limit == 0)
                $mandatory_delete = true;
            else
                $mandatory_delete = false;
            
            if (Yii::$app->request->isPost) 
            {
                $model->files = UploadedFile::getInstances($model, 'files');
                $pending_count = count($model->files);
                $saved_count = count(Package::getDocuments($recordid));
                
                /* 
                 * if summation of present files count and pending files <= stipulated document count,
                 * upload is allowed
                 */
                if( ($saved_count+$pending_count) <= $package->documentcount)
                {
                    if ($model->upload())   // file is uploaded successfully
                    {
                        if (Package::hasAllDocuments($recordid) == true)
                        {
                            $package->packageprogressid = 2;
                            $package->save();
                        }
                        
                        if ($action == NULL)
                            return self::actionInitiatePackage($package->packageid);
                        else
                            return self::actionIndex();
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'You have exceeded you stipulated attachment count.');              
                }
                
            }

            return $this->render('upload_attachments', 
                                [
                                    'model' => $model,
                                    'recordid' => $recordid,
                                    'mandatory_delete' => $mandatory_delete,
                                    'saved_documents' => $saved_documents,
                                    'count' => $count,
                                ]
            );
        }
        
        
        /**
         * Performs a test package delivery
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016 | 12/04/2016
         */
        public function actionTestPackage()
        {
            
            $package = Package::getIncompletePackage();
            $test_result = false;
            $final_test_results = true;
            
            if (Yii::$app->request->post()) 
            {
                $request = Yii::$app->request;
            
                $email_1 = $request->post('email-1');
                $email_2 = $request->post('email-2');
                $email_3 = $request->post('email-3');
                
                $emails = array();
                if ($email_1 == true)
                    $emails[] = $email_1;
                if ($email_2 == true)
                    $emails[] = $email_2;
                if ($email_3 == true)
                    $emails[] = $email_3;
                
                if(count($emails) > 0)
                {
                    foreach ($emails as $key => $email) 
                    {
//                        $applicantid = "2016" . strval(rand(1000,2000));
                        $applicantid = 24;
                        $firstname = "John";
                        $lastname = "Doe";
                        $studentno = "1604xxxx";
                        $programme = "Programme X";
                        $divisioname = "Division Y";
                        
//                        if ($package->packagetypeid == 1)
//                        {
//                            $attachments = NULL;
//                        }
//                        elseif($package->packagetypeid == 2)
//                        {
//                            $attachments = NULL;
//                        }
//                        elseif ($package->packagetypeid == 3)
//                        {
//                            $attachments = Package::getDocuments($package->packageid);
//                        }
//                        elseif ($package->packagetypeid == 4)
//                        {
//                            $attachments = Package::getDocuments($package->packageid);
//                        }
                        
                        $attachments = Package::getDocuments($package->packageid);
                        
                        $test_result = self::publishPackage($package, $applicantid, $firstname, $lastname, $programme, $divisioname, $email, $studentno, $attachments);
                        if ($test_result == false)
                        {
                            $final_test_results = false;
                            Yii::$app->getSession()->setFlash('error', 'Error occurred when sending email.');
                            break;
                        }
                    } 
                    
                    if ($final_test_results == true)
                    {
                        $package->packageprogressid = 3;
                        $package->save();
                    }
                    return self::actionInitiatePackage($package->packageid);
                }
            }
            
            return $this->render('test_package', 
                                [
                                    'recordid' => $package->packageid,
                                ]
            );
        }
        
        
        /*
         * Purpose: Publishes an email test package
         * 
         * Author: Gamal Crichton
         * Date Created: 29/07/2015
         * Last Modified : 13/04/2016 by [L. Charles]
        */
        private static function publishPackage($package, $applicantid, $firstname, $lastname, $programme, $divisioname, $email_address, $studentno = NULL, $attachments = NULL)
        {
            $user = User::find()
                    ->innerJoin('`applicant`', '`applicant`.`personid` = `person`.`personid`')
                    ->where(['applicant.applicantid' => $applicantid, 'applicant.isactive' => 1, 'applicant.isdeleted' => 0])
                    ->one();
            
            if ($package->packagetypeid == 1 || $package->packagetypeid == 2)
                $viewfile = '@common/mail/packages/rejection_email.php';
            elseif ($package->packagetypeid == 3)
                $viewfile = '@common/mail/packages/conditional_offer_email.php';
            elseif ($package->packagetypeid == 4)
                $viewfile = '@common/mail/packages/full_offer_email.php';
            
            /*
             * Creates files directory for applicants package and saves email
             */
            if ($package->packagetypeid == 1)
                $dir = '@common/mail/packages/outbox/pre_interview_rejects/';
            elseif($package->packagetypeid == 2)
                $dir = '@common/mail/packages/outbox/post_interview_rejects/';
            elseif ($package->packagetypeid == 3)
                $dir = '@common/mail/packages/outbox/conditional_offers/';
            elseif ($package->packagetypeid == 4)
                $dir = '@common/mail/packages/outbox/full_offers/';
            
            //Generate email that will be saved to local Directory
            $saved_mail = Yii::$app->savemailer;
            $saved_mail->fileTransportPath = $dir . $package->name . "_" . $user->username;
            $saved_status = $saved_mail->compose($viewfile,
                                    [
                                        'package' => $package,
                                        'first_name' => $firstname,
                                        'last_name' => $lastname, 
                                        'programme' => $programme, 
                                        'division_name' => $divisioname, 
                                        'studentno' => $studentno,
                                    ]
                                )
                                ->setFrom(Yii::$app->params['admissionsEmail'])
                                ->setTo($email_address)
                                ->setSubject($package->emailtitle)
                                ->send();

            
            
            /*
             * Sends package email to applicant
             */
            $mail = Yii::$app->mailer->compose($viewfile,
                                                [
                                                    'package' => $package,
                                                    'first_name' => $firstname,
                                                    'last_name' => $lastname, 
                                                    'programme' => $programme, 
                                                    'division_name' => $divisioname, 
                                                    'studentno' => $studentno,
                                                ]
                                            )
                                            ->setFrom(Yii::$app->params['admissionsEmail'])
                                            ->setTo($email_address)
                                            ->setSubject($package->emailtitle);
            if ($attachments)
            {
                foreach($attachments as $attachment)
                {
                    $mail->attach($attachment);
                }
            }
            $outgoing_status = $mail->send();
            
            return ($saved_status && $outgoing_status);
        }
        
        
        
        /**
         * Confirms package configurations
         * 
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016
         */
        public function actionConfirmPackage($recordid)
        {
            $package = Package::find()
                        ->where(['packageid' => $recordid])
                        ->one();
            if ($package)
            {
                $package->packageprogressid = 4;
                $package->lastmodifiedby = Yii::$app->user->getID();
                $package->datecompleted = date('Y-m-d');
                $package->isactive = 1;
                if (!$package->save())
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying confirm package confgurations. Please contact system administrator.');              
                }
                else
                    return self::actionIndex();
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Package was not found. Please contact system administrator.');
                return self::actionInitiatePackage($package->packageid);
            }
        }
        
        
        /**
         * Deletes an attachment
         * 
         * @param type $recordid
         * @param type $count
         * @param type $index
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016 | 12/04/2016
         */
        public function actionDeleteAttachment($recordid, $count, $index)
        {
            $package = Package::find()
                            ->where(['packageid' => $recordid])
                            ->one();
            
            $files = Package::getDocuments($recordid);
            foreach ($files as $key => $file)
            {
                if ($key == $index)
                {
                    unlink($file);
                }
                
                if (Package::hasAllDocuments($recordid) == true)
                {
                    $package->packageprogressid = 2;
                    $package->save();
                }
                else
                {
                    $package->packageprogressid = 1;
                    $package->save();
                }
                
            }
            return self::actionUploadAttachments($recordid, $count);
        }
        
        
        /**
         * Deletes package configuration record
         * 
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016
         */
        public function actionDeletePackage($recordid)
        {
            $package_save_flag = false;
            
            $package = Package::find()
                            ->where(['packageid' => $recordid])
                            ->one();
            
            //Deletes all files
            $files = Package::getDocuments($recordid);
            foreach ($files as $key => $file)
            {
                unlink($file);
            }
            
            
            // remove directory that was created for this package
            $file = new FileHelper();
            $dir = Yii::getAlias('@frontend') ."/files/packages/" . $package->packageid . "_" . $package->name;
            $file->removeDirectory($dir);
            
            //'Deletes' package
            $package->isactive = 0;
            $package->isdeleted = 1;
            $package_save_flag = $package->save();
            
            if ($package_save_flag == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error occurred when deleting package. Please contact system administrator.');
            }
            else 
            {         
                return self::actionIndex();
            }
        }
        
        
        /**
         * Deactivates package configuration record
         * 
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 18/04/2016
         * Date Last Modified: 18/04/2016
         */
        public function actionDeactivatePackage($recordid)
        {
            $package_save_flag = false;
            
            $package = Package::find()
                            ->where(['packageid' => $recordid])
                            ->one();
            
            $package->isactive = 0;
            $package_save_flag = $package->save();
            
            if ($package_save_flag == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error occurred when deactivating package. Please contact system administrator.');
            }
            else 
            {         
                return self::actionIndex();
            }
        }
        
        
        /**
         * Edits package configuration record
         * 
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016
         */
        public function actionEditPackage($recordid)
        {
            $package_save_flag = false;
            $document_save_flag = false;
            
            if ($recordid == NULL)
            {
                $package = new Package();
                $name_changeable = true;
            }
            else
            {
                $package = Package::find()
                            ->where(['packageid' => $recordid])
                            ->one();
                $name_changeable = false;
            }
            
            $model = new PackageAttachment();
            $saved_documents = Package::getDocuments($recordid);
            $model->limit = $package->documentcount - count($saved_documents);
            
            if ($model->limit == 0)
                $mandatory_delete = true;
            else
                $mandatory_delete = false;
       
            return $this->render('edit_package', 
                            [
                                'package' => $package,
                                'name_changeable' => $name_changeable,
                                'recordid' => $package->packageid,
                                'count' => $package->documentcount,
                                'saved_documents' => $saved_documents,
                                'model' => $model,
                                'mandatory_delete' => $mandatory_delete,
                            ]);
        }
            
            
        /**
         * View a package configuration record
         * 
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 16/04/2016
         * Date Last Modified: 16/04/2016
         */
        public function actionViewPackage($recordid)
        {
            $package = Package::find()
                        ->where(['packageid' => $recordid, 'isdeleted' => 0])
                        ->one();
            
            $model = new PackageAttachment();
            $saved_documents = Package::getDocuments($recordid);
       
            return $this->render('view_package', 
                            [
                                'package' => $package,
                                'recordid' => $package->packageid,
                                'saved_documents' => $saved_documents,
                                'model' => $model,
                            ]);
        }
        
        
        /**
         * Publishes an offer/rejection
         * 
         * @param type $category
         * @param type $sub_category
         * @param type $divisionid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 16/04/2016
         * Date Last Modified: 16/04/2016
         */
        public function actionBulkPublish($category, $sub_category, $divisionid = NULL)
        {
            //if publishing offer
            if ($category == 1)
            { 
                /*
                 * If no division is selected;
                 * all outstanding offers are eligible for publishing
                 */
                if ($divisionid == NULL)
                {
                    $offer_cond['application_period.isactive'] = 1;
                    $offer_cond['application_period.iscomplete'] = 0;
                    $offer_cond['offer.offertypeid'] = $sub_category;
                    $offer_cond['offer.isdeleted'] = 0;
                    $offer_cond['offer.ispublished'] = 0;
                    $offer_cond['offer.isactive'] = 1;
                }
                /*
                 * If division is selected;
                 * all outstanding offers for the particular division are eligible for publishing
                 */
                else
                {
                    $offer_cond['application_period.isactive'] = 1;
                    $offer_cond['application_period.iscomplete'] = 0;
                    $offer_cond['offer.offertypeid'] = $sub_category;
                    $offer_cond['offer.isdeleted'] = 0;
                    $offer_cond['offer.ispublished'] = 0;
                    $offer_cond['offer.isactive'] = 1;
                    $offer_cond['application_period.divisionid'] = $divisionid;
                }
                
                $offers = Offer::find()
                    ->joinWith('application')
                    ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where($offer_cond)
                    ->all();
                
                foreach ($offers as $offer) 
                {
                    $application = Application::find()
                            ->where(['applicationid' => $offer->applicationid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    
                    /*
                    * If no division selected;
                    * ->all posibble applicationperiods are selected
                    * -> the particular application period is selected
                    */
                   if ($divisionid == NULL)
                   {
                       $applicationperiods = ApplicationPeriod::find()
                                       ->where(['isactive' => 1, 'isdeleted' => 0, 'iscomplete' => 0])
                                       ->all();
                       $applicationperiodids = array();
                       foreach($applicationperiods as $period)
                       {
                           $exists = Offer::offerExists($period->applicationperiodid, $sub_category);
                           if ($exists == true)
                               $applicationperiodids[] = $period->applicationperiodid;
                       }
                   }
                   else
                   {
                       $applicationperiod = ApplicationPeriod::find()
                                       ->where(['divisionid' => $divisionid, 'isactive' => 1, 'isdeleted' => 0, 'iscomplete' => 0])
                                       ->one();
                       $applicationperiodids = $applicationperiod->applicationperiodid ; 

                   }
                   /*
                    * If $sub_category/rejectiontypeid = pre_interview;
                    * -> use pre_interview rejection package
                    * ->else use post_interview rejection package
                    */
                    if ($sub_category == 1)
                        $packagetypeid = 4;
                    else
                        $packagetypeid = 3;

                    $package = Package::find()
                       ->where(['applicationperiodid' => $applicationperiodids, 'packagetypeid' => $packagetypeid, 'isactive' => 1, 'isdeleted' => 0])
                       ->one();

                    $applicant = Applicant::find()
                           ->where(['personid' => $application->personid,  'isactive' => 1, 'isdeleted' => 0])
                           ->one();
                     
                    $email = Email::find()
                            ->where(['personid' => $application->personid,  'isactive' => 1, 'isdeleted' => 0])
                           ->one();
                   
                    $application = Application::find()
                                ->where(['applicationid' => $offer->applicationid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();
                    
                    $divisioname = Division::getDivisionName($application->divisionid);
                    $prog = ProgrammeCatalog::getApplicantProgramme($application->applicationid);
                    $programme = ($prog->specialisation)? $prog->name . "(" . $prog->specialisation . ")" : $prog->name;
                    
                    $studentno = $applicant->potentialstudentid;
                    $attachments = Package::getDocuments($package->packageid);
                    
                    self::publishPackage($package, $applicant->applicantid, $applicant->firstname, $applicant->lastname, $programme, $divisioname, $email->email, $studentno, $attachments);
                    
                    $offer->ispublished = 1;
                    $offer->packageid = $package->packageid;
                    $offer->save();
                }
                
                 //ensure package is recorded as nbeing published
                $package->waspublished = 1;
                $package->save();
                
                return $this->redirect(\Yii::$app->request->getReferrer());
            }
            
            //if publishing rejection
            elseif ($category == 2)
            {
                /*
                 * If no division is selected;
                 * all outstanding rejections are eligible for publishing
                 */
                if ($divisionid == NULL)
                {
                    $rejection_cond['application_period.isactive'] = 1;
                    $rejection_cond['application_period.iscomplete'] = 0;
                    $rejection_cond['rejection.rejectiontypeid'] = $sub_category;
                    $rejection_cond['rejection.isdeleted'] = 0;
                    $rejection_cond['rejection_applications.isactive'] = 1;
                    $rejection_cond['rejection_applications.isdeleted'] = 0;
                }
                /*
                 * If division is selected;
                 * all outstanding rejections for the particular division are eligible for publishing
                 */
                else
                {
                    $rejection_cond['application_period.isactive'] = 1;
                    $rejection_cond['application_period.iscomplete'] = 0;
                    $rejection_cond['rejection.rejectiontypeid'] = $sub_category;
                    $rejection_cond['rejection.isdeleted'] = 0;
                    $rejection_cond['rejection_applications.isactive'] = 1;
                    $rejection_cond['rejection_applications.isdeleted'] = 0;
                    $reject_cond['application_period.divisionid'] = $divisionid;
                }
                $rejections = Rejection::find()
                    ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                    ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                    ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where($rejection_cond)
                    ->groupby('rejection.rejectionid')
                    ->all();
                
                foreach ($rejections as $rejection) 
                {
                    /*
                    * If no division selected;
                    * ->all posibble applicationperiods are selected
                    * -> the particular pplication period is selected
                    */

                   if ($divisionid == NULL)
                   {
                       $applicationperiods = ApplicationPeriod::find()
                                       ->where(['isactive' => 1, 'isdeleted' => 0, 'iscomplete' => 0])
                                       ->all();
                       $applicationperiodids = array();
                       foreach($applicationperiods as $period)
                       {
                           $exists = Rejection::rejectionExists($period->applicationperiodid, $sub_category);
                           if ($exists == true)
                               $applicationperiodids[] = $period->applicationperiodid;
                       }
                   }
                   else
                   {
                       $applicationperiod = ApplicationPeriod::find()
                                       ->where(['divisionid' => $divisionid, 'isactive' => 1, 'isdeleted' => 0, 'iscomplete' => 0])
                                       ->one();
                       $applicationperiodids = $applicationperiod->$applicationperiodid ; 

                   }
                   /*
                    * If $sub_category/rejectiontypeid = pre_interview;
                    * -> use pre_interview rejection package
                    * ->else use post_interview rejection package
                    */
                    if ($sub_category == 1)
                       $packagetypeid = 1;
                    else
                        $packagetypeid = 2;

                    $package = Package::find()
                       ->where(['applicationperiodid' => $applicationperiodids, 'packagetypeid' => $packagetypeid, 'isactive' => 1, 'isdeleted' => 0])
                       ->one();

                    $applicant = Applicant::find()
                           ->where(['personid' => $rejection->personid,  'isactive' => 1, 'isdeleted' => 0])
                           ->one();
                     
                    $email = Email::find()
                            ->where(['personid' => $rejection->personid,  'isactive' => 1, 'isdeleted' => 0])
                           ->one();
                    
                    $application_rejections = RejectionApplications::find()
                            ->where(['rejectionid' => $rejection->rejectionid,  'isactive' => 1, 'isdeleted' => 0])
                            ->all();
                    $application_ids = array();
                    foreach ($application_rejections as $value) 
                    {
                        $application_ids[] = $value->applicationid;
                    }
                    
                    $divisioname = "";
                    $programme = "";
                    $applications = Application::find()
                                ->where(['applicationid' => $application_ids, 'isactive' => 1, 'isdeleted' => 0])
                                ->all();
                    
                    //if division is for DTE or DNE then applications are constrained to one division
                    if ($applications[0]->divisionid == 6 || $applications[0]->divisionid == 7)
                    {
                        $div = Division::getDivisionName($applications[0]->divisionid);
                        $divisioname .= $div;
                        
                        $prog = ProgrammeCatalog::getApplicantProgramme($applications[0]->applicationid);
                        $name = ($prog->specialisation)? $prog->name . "(" . $prog->specialisation . ")" : $prog->name;
                        $programme .= $name; 
                    }
                    else
                    {
                        $has_dasgs = false;
                        $has_dtve = false;
                        $application_count = count($applications);
                        foreach ($applications as $key=>$record)
                        {
                            if ($record->applicationid == 4)
                                $has_dasgs = true;
                            elseif ($record->applicationid == 5)
                                $has_dtve = true;
                            
                            $prog = ProgrammeCatalog::getApplicantProgramme($record->applicationid);
                            $name = ($prog->specialisation)? $prog->name . "(" . $prog->specialisation . ")" : $prog->name;
                            if($applicantcount - $key > 0)
                                $programme .= " " . $name; 
                            else
                                $programme .= " and " . $name; 
                        }
                        
                        if ($has_dasgs == true  && $has_dtve == true)
                            $divisioname .= Division::getDivisionName(4) . " and "  . Division::getDivisionName(5);
                        elseif ($has_dasgs == true  && $has_dtve == false)
                            $divisioname .= Division::getDivisionName(4);
                        elseif ($has_dasgs == false  && $has_dtve == true)
                            $divisioname .= Division::getDivisionName(5);
                    }
                    
                    $studentno = $applicant->potentialstudentid;
                    $attachments = Package::getDocuments($package->packageid);
                    
                    self::publishPackage($package, $applicant->applicantid, $applicant->firstname, $applicant->lastname, $programme, $divisioname, $email->email, $studentno, $attachments);
                
                    $rejection->ispublished = 1;
                    $rejection->packageid = $package->packageid;
                    $rejection->save();
                }
                
                //ensure package is recorded as nbeing published
                $package->waspublished = 1;
                $package->save();
                
                return $this->redirect(\Yii::$app->request->getReferrer());
            }
        }
        
        
        
        
    }
