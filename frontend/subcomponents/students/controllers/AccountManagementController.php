<?php

/*
 * Author: Laurence Charles
 * Date Created: 24/05/2015
 */

    namespace app\subcomponents\students\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\helpers\Url;
    use yii\data\ArrayDataProvider;
    use yii\base\Model;
    use yii\helpers\ArrayHelper;
    use yii\web\Request;
    use yii\web\UploadedFile;
    use yii\helpers\FileHelper;
    use yii\web\Response;

    use common\models\User;
    use frontend\models\Applicant;
    use frontend\models\Email;
    use frontend\models\PersonAccountProgress;
    use frontend\models\InitializeAccountModel;
    use frontend\models\ApplicantProfileModel;
    use frontend\models\Phone;
    use frontend\models\Address;
    use frontend\models\Application;
    use frontend\models\Offer;
    use frontend\models\CapeGroup;
    use frontend\models\CapeSubject;
    use frontend\models\CapeSubjectGroup;
    use frontend\models\ApplicationCapesubject;
    use frontend\models\Student;
    use frontend\models\StudentRegistration;
    use frontend\models\AcademicOffering;
    use frontend\models\ProgrammeCatalog;

    class AccountManagementController extends Controller
    {

        /**
         * Renders the listing of incomplete applicant/student accounts
         *
         * Author: Laurence Charles
         * Date Created: 24/05/2015
         * Date Last Modified: 24/05/2015
         */
        public function actionIndex()
        {
            $incomplete_applicants = Applicant::find()
//                    ->where(['isactive' => 0, 'isdeleted' => 0])
                    ->where(['applicantintentid' => null])
                    ->all();

            $data = array();
            foreach ($incomplete_applicants as $applicant) {
                $applicant_data = array();
                $user = User::findOne(['personid' => $applicant->personid]);
                $recordid = PersonAccountProgress::find()
                        ->where(['personid' => $applicant->personid,  'isdeleted' => 0])
                        ->one()
                        ->personaccountprogressid;

                $registrationid = 0;

                if ($applicant->isactive == 0) {
                    $applicant_data['iscomplete'] = 0;
                } else {
                    $applicant_data['iscomplete'] = 1;
                    $registrationid = StudentRegistration::find()
                            ->where(['personid' => $applicant->personid, 'isactive'=>1, 'isdeleted' => 0])
                            ->one()
                            ->studentregistrationid;
                }

                $applicant_data['studentregistrationid'] = $registrationid;
                $applicant_data['recordid'] = $recordid;
                $applicant_data['personid'] = $user->personid;
                $applicant_data['applicantid'] = $applicant->applicantid;
                $applicant_data['username'] = $user->username;
                $applicant_data['title'] = $applicant->title;
                $applicant_data['firstname'] = $applicant->firstname;
                $applicant_data['middlename'] = $applicant->middlename;
                $applicant_data['lastname'] = $applicant->lastname;

                if ($registrationid == 0) {
                    $applicant_data['progress'] = "Incomplete";
                } else {
                    $applicant_data['progress'] = "Complete";
                }
                $data[] = $applicant_data;
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 50,
                ],
                'sort' => [
                    'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                    'attributes' => ['firstname', 'lastname'],
                  ]
            ]);

            return $this->render('index', [
                                'incomplete_applicants' => $incomplete_applicants,
                                'dataProvider' => $dataProvider,
                                ]);
        }


        /**
         * Renders account creation dashboard
         *
         * @param type $recordid
         * @param type $studentid
         * @return type
         *
         * Author: Laurence Charles
         * Date Created: 28/05/2015
         * Date Last Modified: 28/05/2015
         */
        public function actionAccountDashboard($recordid = null)
        {
            $progress = null;

            if ($recordid != null) {       //if new account has already been initiated
                $progress = PersonAccountProgress::find()
                        ->where(['personaccountprogressid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one()
                        ->accountprogressid;
            }

            return $this->render('dashboard', [
                                'recordid' => $recordid,
                                'progress' => $progress,
                                ]);
        }


        /**
         * Render account initialization view and process input
         *
         * @return type
         *
         * Author: Laurence Charles
         * Date Created: 28/05/2015
         * Date Last Modified: 28/05/2015
         */
        public function actionInitializeAccount()
        {
            $model = new InitializeAccountModel();

//            $recordid = PersonAccountProgress::find()
//                        ->where(['personid' => $applicant->personid,  'isdeleted' => 0])
//                        ->one()
//                        ->personaccountprogressid;

            if ($post_data = Yii::$app->request->post()) {
                $model_load_flag = false;
                $email_save_flag = false;
                $person_save_flag = false;
                $progress_save_flag = false;
                $applicant_save_flag = false;

                $model_load_flag = $model->load($post_data);

                if ($model_load_flag == false) {
                    Yii::$app->getSession()->setFlash('error', 'Error loading input data.');
                } else {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $person = new User();
                        $person->persontypeid = 2;
                        $person->username = $model->email;
                        $person->setPassword($model->pword);
                        $person->setSalt();
                        $person->email = $model->email;

                        $person_save_flag = $person->save();
                        if ($person_save_flag == false) {
                            Yii::$app->getSession()->setFlash('error', 'Error saving person record.');
                            $transaction->rollBack();
                        } else {
                            $email = new Email();
                            $email->priority = 2;
                            $email->email = $model->email;
                            $email->personid = $person->personid;
                            $email_save_flag = $email->save();
                            if ($email_save_flag == false) {
                                Yii::$app->getSession()->setFlash('error', 'Error saving email record.');
                                $transaction->rollBack();
                            } else {
                                $progress = new PersonAccountProgress();
                                $progress->accountprogressid = 1;
                                $progress->personid = $person->personid;
                                $progress_save_flag = $progress->save();
                                if ($progress_save_flag == false) {
                                    Yii::$app->getSession()->setFlash('error', 'Error saving progress record.');
                                    $transaction->rollBack();
                                } else {
                                    $applicant = new Applicant();
                                    $applicant->personid = $person->personid;
                                    $applicant->title = $model->title;
                                    $applicant->firstname = $model->firstname;
                                    $applicant->middlename = $model->middlename;
                                    $applicant->lastname = $model->lastname;
                                    $applicant->isactive = 0;
                                    $applicant->isdeleted = 0;
                                    $applicant_save_flag = $applicant->save();
                                    if ($applicant_save_flag == false) {
                                        Yii::$app->getSession()->setFlash('error', 'Error saving applicant record.');
                                        $transaction->rollBack();
                                    } else {
                                        $transaction->commit();
                                        return self::actionAccountDashboard($progress->personaccountprogressid);
                                    }
                                }
                            }
                        }
                    } catch (Exception $ex) {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Error processing record.');
                        $transaction->rollBack();
                    }
                }
            }

            return $this->render('initialize_account', [
                                'model' => $model,
//                                'recordid' => $recordid,
                                ]);
        }


        /**
         * Render profile view and processes its associated input
         *
         * @param type $recordid
         * @return type
         *
         * Author: Laurence Charles
         * Date Created: 29/05/2015
         * Date Last Modified: 29/05/2015
         */
        public function actionProfile($recordid)
        {
            $record = PersonAccountProgress::find()
                        ->where(['personaccountprogressid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();

            $personid = $record->personid;

            $applicant = Applicant::find()
                    ->where(['personid' => $personid, 'isactive' => 0, 'isdeleted' => 0])
                    ->one();

            $model = new ApplicantProfileModel();
            $model->title = $applicant->title;
            $model->firstname = $applicant->firstname;
            $model->middlename = $applicant->middlename;
            $model->lastname = $applicant->lastname;

            if ($applicant->gender) {
                $model->gender = $applicant->gender;
            }
            if ($applicant->dateofbirth) {
                $model->dateofbirth = $applicant->dateofbirth;
            }
            if ($applicant->sponsorname) {
                $model->sponsorname = $applicant->sponsorname;
            }
            if ($applicant->clubs) {
                $model->clubs = $applicant->clubs;
            }
            if ($applicant->otherinterests) {
                $model->otherinterests =$applicant->otherinterests;
            }
            if ($applicant->maritalstatus) {
                $model->maritalstatus = $applicant->maritalstatus;
            }
            if ($applicant->nationality) {
                $model->nationality = $applicant->nationality;
            }
            if ($applicant->religion) {
                $model->religion = $applicant->religion;
            }
            if ($applicant->placeofbirth) {
                $model->placeofbirth = $applicant->placeofbirth;
            }
            if ($applicant->nationalsports) {
                $model->nationalsports = $applicant->nationalsports;
            }
            if ($applicant->othersports) {
                $model->othersports = $applicant->othersports;
            }
            if ($applicant->otheracademics) {
                $model->otheracademics = $applicant->otheracademics;
            }
            $model->isexternal = $applicant->isexternal;


            if ($post_data = Yii::$app->request->post()) {
                $applicant_load_flag = false;

                $applicant_load_flag = $model->load($post_data);
                if ($applicant_load_flag == false) {
                    Yii::$app->getSession()->setFlash('error', 'Error loading applicant record.');
                } else {
                    $applicant_save_flag = false;
                    $progress_save_flag = false;

                    $applicant->title = $model->title;
                    $applicant->firstname =  $model->firstname;
                    $applicant->middlename = $model->middlename;
                    $applicant->lastname =  $model->lastname;
                    $applicant->gender = $model->gender;
                    $applicant->dateofbirth = $model->dateofbirth;
                    if ($model->sponsorname) {
                        $applicant->sponsorname =   $model->sponsorname;
                    }
                    if ($model->clubs) {
                        $applicant->clubs = $model->clubs;
                    }
                    if ($model->otherinterests) {
                        $applicant->otherinterests = $model->otherinterests;
                    }
                    $applicant->maritalstatus = $model->maritalstatus;
                    $applicant->nationality = $model->nationality;
                    $applicant->religion =  $model->religion;
                    $applicant->placeofbirth =  $model->placeofbirth;
                    if ($model->nationalsports) {
                        $applicant->nationalsports = $model->nationalsports;
                    }
                    if ($model->othersports) {
                        $applicant->othersports = $model->othersports;
                    }
                    if ($model->otheracademics) {
                        $applicant->otheracademics = $model->otheracademics;
                    }
                    $applicant->isexternal = $model->isexternal;

                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $applicant_save_flag = $applicant->save();
                        if ($applicant_save_flag == false) {
                            Yii::$app->getSession()->setFlash('error', 'Error saving applicant record.');
                            $transaction->rollBack();
                        } else {
                            if ($record->accountprogressid == 1) {
                                $record->accountprogressid++;
                            }

                            $progress_save_flag = $record->save();
                            if ($progress_save_flag == false) {
                                Yii::$app->getSession()->setFlash('error', 'Error saving progress record.');
                                $transaction->rollBack();
                            } else {
                                $transaction->commit();
                                return self::actionAccountDashboard($record->personaccountprogressid);
                            }
                        }
                    } catch (Exception $ex) {
                        Yii::$app->getSession()->setFlash('error', 'Error processing record.');
                        $transaction->rollBack();
                    }
                }
            }

            return $this->render('profile_entry', [
                                'model' => $model,
                                'recordid' => $recordid,
                                ]);
        }


        /**
         * Render contacts view and processes its associated input
         *
         * @param type $recordid
         * @return type
         *
         * Author: Laurence Charles
         * Date Created: 29/05/2015
         * Date Last Modified: 29/05/2015
         */
        public function actionContacts($recordid)
        {
            $record = PersonAccountProgress::find()
                        ->where(['personaccountprogressid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();

            $personid = $record->personid;

            $model = Phone::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            if ($model == false) {
                $model = new Phone();
            }

            if ($post_data = Yii::$app->request->post()) {
                $contacts_load_flag = false;
                $contacts_save_flag = false;
                $progress_save_flag = false;

                $contacts_load_flag = $model->load($post_data);
                if ($contacts_load_flag == false) {
                    Yii::$app->getSession()->setFlash('error', 'Error loading phone record.');
                } else {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $model->personid = $personid;
                        $contacts_save_flag = $model->save();
                        if ($contacts_save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error saving phone record.');
                        } else {
                            if ($record->accountprogressid == 2) {
                                $record->accountprogressid++;
                            }

                            $progress_save_flag = $record->save();
                            if ($progress_save_flag == false) {
                                Yii::$app->getSession()->setFlash('error', 'Error saving progress record.');
                                $transaction->rollBack();
                            } else {
                                $transaction->commit();
                                return self::actionAccountDashboard($record->personaccountprogressid);
                            }
                        }
                    } catch (Exception $ex) {
                        Yii::$app->getSession()->setFlash('error', 'Error processing record.');
                        $transaction->rollBack();
                    }
                }
            }

            return $this->render('contacts_entry', [
                                'model' => $model,
                                'recordid' => $recordid,
                ]);
        }


        /**
         * Render addresses view and processes its associated input
         *
         * @param type $recordid
         * @return type
         *
         * Author: Laurence Charles
         * Date Created: 29/05/2015
         * Date Last Modified: 29/05/2015
         */
        public function actionAddress($recordid)
        {
            $record = PersonAccountProgress::find()
                        ->where(['personaccountprogressid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();

            $personid = $record->personid;

            $permanentaddress = Address::findAddress($personid, 1);
            if ($permanentaddress == false) {
                $permanentaddress = new Address();
            }

            $residentaladdress = Address::findAddress($personid, 2);
            if ($residentaladdress == false) {
                $residentaladdress = new Address();
            }

            $postaladdress = Address::findAddress($personid, 3);
            if ($postaladdress == false) {
                $postaladdress = new Address();
            }
            $addresses = [$permanentaddress, $residentaladdress, $postaladdress];

            if ($post_data = Yii::$app->request->post()) {
                $address_load_flag = false;
                $address_save_flag = false;
                $progress_save_flag = false;

                $address_load_flag = Model::loadMultiple($addresses, $post_data);
                if ($address_load_flag == false) {
                    Yii::$app->getSession()->setFlash('error', 'Error loading address records.');
                } else {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        foreach ($addresses as $key => $address) {
                            $address->personid = $personid;
                            if ($key == 0) {
                                $address->addresstypeid = 1;
                            } elseif ($key == 1) {
                                $address->addresstypeid = 2;
                            } elseif ($key == 2) {
                                $address->addresstypeid = 3;
                            }

                            $address_save_flag = $address->save();

                            if ($address_save_flag == false) {          //if address model save operation failed
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error saving address record.');
                                return self::actionAddress($recordid);
                            }
                        }

                        if ($record->accountprogressid == 3) {
                            $record->accountprogressid++;
                        }

                        $progress_save_flag = $record->save();
                        if ($progress_save_flag == false) {
                            Yii::$app->getSession()->setFlash('error', 'Error saving progress record.');
                            $transaction->rollBack();
                        } else {
                            $transaction->commit();
                            return self::actionAccountDashboard($record->personaccountprogressid);
                        }
                    } catch (Exception $ex) {
                        Yii::$app->getSession()->setFlash('error', 'Error processing record.');
                        $transaction->rollBack();
                    }
                }
            }

            return $this->render('address_entry', [
                                'addresses' => $addresses,
                                'recordid' => $recordid,
                ]);
        }




        /**
         * Render addresses view and processes its associated input
         *
         * @param type $recordid
         * @return type
         *
         * Author: Laurence Charles
         * Date Created: 29/05/2015
         * Date Last Modified: 29/05/2015
         */
        public function actionProgramme($recordid)
        {
            $record = PersonAccountProgress::find()
                        ->where(['personaccountprogressid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();

            $personid = $record->personid;

            date_default_timezone_set('America/St_Vincent');

            $id = $personid;
            $capegroups = CapeGroup::getGroups();
            $applicationcapesubject = array();
            $groups = CapeGroup::getGroups();
            $groupCount = count($groups);
            $application = new Application();

            //Create blank records to accommodate capesubject-application associations
            for ($i = 0; $i < $groupCount; $i++) {
                $temp = new ApplicationCapesubject();
                //Values giving default value so as to facilitate validation (selective saving will be implemented)
                $temp->capesubjectid = 0;
                $temp->applicationid = 0;
                array_push($applicationcapesubject, $temp);
            }

            //Flags
            $application_load_flag = false;
            $application_save_flag = false;
            $capesubject_load_flag = false;
            $capesubject_validation_flag = false;
            $capesubject_save_flag = false;

            if ($post_data = Yii::$app->request->post()) {              //if post request made
                $application_load_flag = $application->load($post_data);

                if ($application_load_flag == true) {       //if application load operation is successful
                    $application->personid = $id;
                    $application->applicationtimestamp = date('Y-m-d H:i:s');
                    $application->submissiontimestamp = date('Y-m-d H:i:s');
                    $application->ordering = 4;
                    $application->ipaddress = Yii::$app->request->getUserIP();
                    $application->browseragent = Yii::$app->request->getUserAgent();
                    $application->applicationstatusid = 9;

                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $application_save_flag = $application->save();
                        if ($application_save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error occurred when saving application.');
                        } else {
                            $isCape = Application::isCapeApplication($application->academicofferingid);
                            if ($isCape == true) {       //if application is for CAPE programme
                                $capesubject_load_flag = Model::loadMultiple($applicationcapesubject, $post_data);
                                if ($capesubject_load_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occurred when loading capesubjects.');
                                } else {
                                    $capesubject_validation_flag = Model::validateMultiple($applicationcapesubject);
                                    if ($capesubject_validation_flag == false) {
                                        $transaction->rollBack();
                                        Yii::$app->getSession()->setFlash('error', 'Error occurred when validating capesubjects.');
                                    } else {
                                        //CAPE subject selection is only updated if 3-4 subjects have been selected
                                        $selected = 0;
                                        foreach ($applicationcapesubject as $subject) {
                                            if ($subject->capesubjectid != 0) {           //if valid subject is selected
                                                $selected++;
                                            }
                                        }

                                        if ($selected >= 2 || $selected <= 4) {            //if valid number of CAPE subjects have been selected
                                            $temp_status = true;
                                            foreach ($applicationcapesubject as $subject) {
                                                $subject->applicationid = $application->applicationid;      //updates applicationid

                                                if ($subject->capesubjectid != 0 && $subject->applicationid != 0) {       //if none is selected then reocrd should not be saved
                                                    $capesubject_save_flag = $subject->save();
                                                    if ($capesubject_save_flag == false) {          //CapeApplicationSubject save operation fails
                                                        $temp_status = false;
                                                        break;
                                                    }
                                                }
                                            }

                                            if ($temp_status == false) {
                                                $transaction->rollBack();
                                                Yii::$app->getSession()->setFlash('error', 'Error occured when saving capesubject associations.');
                                            }
                                        } else {         //if incorrect number of CAPE subjects selected.
                                            $transaction->rollBack();
                                            Yii::$app->getSession()->setFlash('error', 'CAPE subject selection has not been saved. You must select 3(min)  or 4(max) CAPE subjects.');
                                            return self::actionViewApplicantCertificates($personid, $programme, 9);
                                        }
                                    }
                                }
                            }//endif isCape

                            // create offer
                            $offer = new Offer();
                            $offer->applicationid = $application->applicationid;
                            $offer->offertypeid = 1;
                            $offer->issuedby = Yii::$app->user->getId();
                            $offer->issuedate = date("Y-m-d");
                            $offer->ispublished = 1;
                            $offer_save_flag = $offer->save();
                            if ($offer_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error occured when saving new offer.');
                            } else {
                                //generate potenialstudentid
                                $applicant_save_flag = false;
                                $applicant = Applicant::find()
                                                ->where(['personid' => $personid])
                                                ->one();
                                $generated_id = Applicant::preparePotentialStudentID($application->divisionid, $applicant->applicantid, "generate");
                                $applicant->potentialstudentid = $generated_id;
                                $applicant_save_flag = $applicant->save();

                                if ($applicant_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured when saving applicant record.');
                                } else {
                                    $user = User::findOne(['personid' => $applicant->personid]);
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
                                    if ($student_save_flag == false) {
                                        $transaction->rollBack();
                                        Yii::$app->getSession()->setFlash('error', 'Error saving student record.');
                                    } else {
                                        //Update username
                                        if ($applicant->potentialstudentid) {
                                            $user->username = strval($applicant->potentialstudentid);
                                        } else {
                                            $student_number = Applicant::preparePotentialStudentID($application->divisionid, $applicant->applicantid, "generate");
                                            $user->username = strval($student_number);
                                        }

                                        $user->persontypeid = 2;

                                        $user_save_flag = $user->save();
                                        if ($user_save_flag == false) {
                                            $transaction->rollBack();
                                            Yii::$app->getSession()->setFlash('error', 'Error saving user record.');
                                        } else {
                                            //Capture student registration
                                            $reg = new StudentRegistration();
                                            $reg->offerid = intval($offer->offerid);
                                            $reg->personid = $applicant->personid;
                                            $reg->academicofferingid = $application->academicofferingid;

                                            $programme_type = ProgrammeCatalog::find()
                                                    ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                                                    ->where(['academic_offering.academicofferingid' => $application->academicofferingid])
                                                    ->one()
                                                    ->programmetypeid;
                                            if ($programme_type == 1) {
                                                $reg->registrationtypeid = 1;
                                            } elseif ($programme_type == 2) {
                                                $reg->registrationtypeid = 2;
                                            }

                                            $reg->currentlevel = 1;
                                            $reg->registrationdate = date('Y-m-d');
                                            $registration_save_flag = $reg->save();

                                            if ($registration_save_flag == false) {
                                                $transaction->rollBack();
                                                Yii::$app->getSession()->setFlash('error', 'Error saving student registration record.');
                                            } else {
                                                $applicant->isactive = 1;
                                                $applicant_update = false;
                                                $applicant_update = $applicant->save();
                                                if ($applicant_update == false) {
                                                    $transaction->rollBack();
                                                    Yii::$app->getSession()->setFlash('error', 'Error activating student account.');
                                                } else {
                                                    $transaction->commit();
                                                    return self::actionIndex();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } //endif application_save_flag == true
                    } catch (Exception $ex) {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Error occurred when processing request.');
                    }
                }   //end-if application load
                else {
                    Yii::$app->getSession()->setFlash('error', 'Error occurred loading application record.');
                }
            }   //end-if POST operation

            return $this->render('programme_entry', [
                        'application' => $application,
                        'applicationcapesubject' =>  $applicationcapesubject,
                        'capegroups' => $capegroups,
                        'personid' => $personid,
                        'recordid' => $recordid,
                    ]);
        }
    }
