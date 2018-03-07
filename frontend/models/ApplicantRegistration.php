<?php

    namespace frontend\models;

    use Yii;
    use yii\custom\ModelNotFoundException;
    use yii\web\UrlManager;
    
     use common\models\User;
    use frontend\models\Email;
    use frontend\models\Student;
    use frontend\models\Applicant;
    use frontend\models\Application;

    /**
     * This is the model class for table "applicant_registration".
     *
     * @property string $applicantregistrationid
     * @property string $applicantintentid
     * @property string $title
     * @property string $firstname
     * @property string $lastname
     * @property string $email
     * @property string $applicantname
     * @property string $created_at
     * @property string $token
     * @property string $updated_at
     *
     * @property ApplicantIntent $applicantintent
     */
    class ApplicantRegistration extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'applicant_registration';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['applicantintentid'], 'integer'],
                [['title', 'firstname', 'lastname', 'email'], 'required'],
                [['created_at', 'updated_at'], 'safe'],
                [['title'], 'string', 'max' => 3],
                [['firstname', 'lastname', 'email', 'applicantname'], 'string', 'max' => 45],
                [['token'], 'string', 'max' => 15]
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'applicantregistrationid' => 'Applicantregistrationid',
                'applicantintentid' => 'Applicantintentid',
                'title' => 'Title',
                'firstname' => 'Firstname',
                'lastname' => 'Lastname',
                'email' => 'Email',
                'applicantname' => 'Applicantname',
                'created_at' => 'Created At',
                'token' => 'Token',
                'updated_at' => 'Updated At',
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getApplicantintent()
        {
            return $this->hasOne(ApplicantIntent::className(), ['applicantintentid' => 'applicantintentid']);
        }

        
        
        /**
         * Return applicant status
         * 
         * @return string
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2018_03_07
         * Modified: 2018_03_07
         */
        public function getApplicantStatus()
        {
            $status = "--";
            
            $applicant_name = $this->applicantname;
            $year_as_string = substr($this->applicantname, 0, 4);
            $year = (int)$year_as_string;
            
            $account_pending_test1 = User::find()
                        ->where(['username' => $this->applicantname, 'isactive' => 1, 'isdeleted' =>0])
                        ->one();
            $account_pending_test2 = Student::find()
                        ->where(['applicantname' => $this->applicantname, 'isactive' => 1, 'isdeleted' =>0])
                        ->one();
                
            if ($year < 2018)
            {
                
                if ($account_pending_test1 == false && $account_pending_test2 == false)
                {
                    $status = "Account Pending";
                }
                else
                {
                    if ($account_pending_test1 == true)
                    {
                        $personid = $account_pending_test1->personid;
                    }
                    elseif ($account_pending_test2 == true)
                    {
                        $personid = $account_pending_test2->personid;
                    }

                    $applications = Application::find()
                            ->where(['applicationstatusid' => [1,2,3,4,5,6,7,8,9,10,11], 'personid' => $personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->all();
                    if (empty($applications) == true)
                    {
                        $status = "Account Created";
                    }
                    else
                    {
                        if (Applicant::isAbandoned($personid) == true)
                        {
                            $status = "Removed";
                        }
                        else
                        {
                            $processed_applications =  Application::find()
                                ->where(['applicationstatusid' => [4,5,6,7,8,9,10], 'personid' => $personid, 'isactive' => 1, 'isdeleted' =>0])
                                ->all();
                            if (empty($processed_applications) == false)
                            {
                                $status = "Processed";
                            }
                            else
                            {
                                $verified_applications =  Application::find()
                                    ->where(['applicationstatusid' => [3], 'personid' => $personid, 'isactive' => 1, 'isdeleted' =>0])
                                    ->all();
                                if (empty($verified_applications) == false)
                                {
                                    $status = "Verified";
                                }
                                else
                                {
                                    $submitted_unverified_applications =  Application::find()
                                        ->where(['applicationstatusid' => [2], 'personid' => $personid, 'isactive' => 1, 'isdeleted' =>0])
                                        ->all();
                                    if (empty($submitted_unverified_applications) == false)
                                    {
                                        $status = "Submitted";
                                    }
                                    else
                                    {
                                        $status = "Programme(s) Selected";
                                    }
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                if ($account_pending_test1 == false && $account_pending_test2 == false)
                {
                    $status = "Account Pending";
                }
                else
                {
                    $submitted_applications = Application::find()
                            ->where(['applicationstatusid' => [2,3,4,5,6,7,8,9,10,11], 'personid' => $account_pending_test1->personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->all();
                     if ($submitted_applications == true)   //if applicant has submitted their application
                     {
                         if (Applicant::isAbandoned($account_pending_test1->personid) == true)
                        {
                            $status = "Removed";
                        }
                        else
                        {
                            $processed_applications =  Application::find()
                                ->where(['applicationstatusid' => [4,5,6,7,8,9,10], 'personid' => $account_pending_test1->personid, 'isactive' => 1, 'isdeleted' =>0])
                                ->all();
                            if (empty($processed_applications) == false)
                            {
                                $status = "Processed";
                            }
                            else
                            {
                                $verified_applications =  Application::find()
                                    ->where(['applicationstatusid' => [3], 'personid' => $account_pending_test1->personid, 'isactive' => 1, 'isdeleted' =>0])
                                    ->all();
                                if (empty($verified_applications) == false)
                                {
                                    $status = "Verified";
                                }
                                else
                                {
                                    $submitted_unverified_applications =  Application::find()
                                        ->where(['applicationstatusid' => [2], 'personid' => $account_pending_test1->personid, 'isactive' => 1, 'isdeleted' =>0])
                                        ->all();
                                    if (empty($submitted_unverified_applications) == false)
                                    {
                                        $status = "Submitted";
                                    }
                                }
                            }
                        }
                     }
                    else    //determine stage withing application process before submission
                    {
                        $applicant = Applicant::find()
                                ->where(['personid' => $account_pending_test1->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();
                        if ($this->applicantintentid == 1)      // if DasgsDtveFull
                        {
                           if ($applicant->isDasgsDtveAdditionalQualificationQueryEntryComplete() == true)
                           {
                               $status = "Post Sec. Qualifications";
                           }
                           elseif ($applicant->isDasgsDtveAcademicQualificationsEntryComplete() == true)
                           {
                               $status = "Academic Qualifications";
                           }
                           elseif ($applicant->isDasgsDtveTertiaryInstitutionQueryEntryComplete() == true)
                           {
                               $status = "Tertiary Attendance";
                           }
                           elseif ($applicant->isDasgsDtveSecondaryInstitutionEntryComplete() == true)
                           {
                               $status = "Secondary Attendance";
                           }
                           elseif ($applicant->isDasgsDtvePrimaryInstitutionEntryComplete() == true)
                           {
                               $status = "Primary Attendance";
                           }
                           elseif ($applicant->isDasgsDtveFamilyContactsEntryComplete() == true)
                           {
                               $status = "Relatives";
                           }
                           elseif ($applicant->isDasgsDtveAddressEntryComplete() == true)
                           {
                               $status = "Addresses";
                           }
                           elseif ($applicant->isDasgsDtveContactEntryComplete() == true)
                           {
                               $status = "Contacts";
                           }
                           elseif ($applicant->isDasgsDtveExtracurricularEntryComplete() == true)
                           {
                               $status = "Extracurricular Activities";
                           }
                           elseif ($applicant->isDasgsDtveProfileEntryComplete() == true)
                           {
                               $status = "Profile";
                           }
                           elseif ($applicant->isDasgsDtveProgrammeEntryComplete() == true)
                           {
                               $status = "Programme(s) Selected";
                           }
                           else
                            {
                                $status = "Account Created";
                            }
                        }

                        elseif ($this->applicantintentid == 4)      // if DteFull
                        {
                            if ($applicant->isDteCriminalRecordEntryComplete() == true)
                            {
                                $status = "Criminal Record";
                            }
                            elseif ($applicant->isDteReferencesEntryComplete() == true)
                            {
                                $status = "References";
                            }
                            elseif ($applicant->isDteGeneralWorkExperienceEntryComplete() == true)
                            {
                                $status = "General Work Experience";
                            }
                            elseif ($applicant->isDteTeachingExperienceEntryComplete() == true)
                            {
                                $status = "Teaching Experience";
                            }
                            elseif ($applicant->isDteTeachingAdditionalInfoEntryComplete() == true)
                            {
                                $status = "DTE Information";
                            }
                            elseif ($applicant->isDteAdditionalQualificationQueryEntryComplete() == true)
                            {
                                $status = "Post Sec. Qualifications";
                            }
                            elseif ($applicant->isDteAcademicQualificationsEntryComplete() == true)
                            {
                                $status = "Academic Qualifications";
                            }
                            elseif ($applicant->isDteTertiaryInstitutionQueryEntryComplete() == true)
                            {
                                $status = "Tertiary Attendance";
                            }
                            elseif ($applicant->isDteSecondaryInstitutionEntryComplete() == true)
                            {
                                $status = "Secondary Attendance";
                            }
                            elseif ($applicant->isDtePrimaryInstitutionEntryComplete() == true)
                            {
                                $status = "Primary Attendance";
                            }
                            elseif ($applicant->isDteFamilyContactsEntryComplete() == true)
                            {
                                $status = "Relatives";
                            }
                            elseif ($applicant->isDteAddressEntryComplete() == true)
                            {
                                $status = "Addresses";
                            }
                            elseif ($applicant->isDteContactEntryComplete() == true)
                            {
                                $status = "Contacts";
                            }
                            elseif ($applicant->isDteExtracurricularEntryComplete() == true)
                            {
                                $status = "Extracurricular Activities";
                            }
                            elseif ($applicant->isDteProfileEntryComplete() == true)
                            {
                                $status = "Profile";
                            }
                            elseif ($applicant->isDteProgrammeEntryComplete() == true)
                            {
                                $status = "Programme(s) Selected";
                            }
                            else
                            {
                                $status = "Account Created";
                            }
                        }

                        elseif ($this->applicantintentid == 6)      // if DneFull
                        {
                           if ($applicant->isDneCriminalRecordEntryComplete() == true)
                           {
                               $status = "Criminal Record";
                           }
                           elseif ($applicant->isDneReferencesEntryComplete() == true)
                           {
                               $status = "References";
                           }
                           elseif ($applicant->isDneGeneralWorkExperienceEntryComplete() == true)
                           {
                               $status = "General Work Experience";
                           }
                           elseif ($applicant->isDneNursingExperienceEntryComplete() == true)
                           {
                               $status = "Nursing Experience";
                           }
                           elseif ($applicant->isDneNursingAdditionalInfoEntryComplete() == true)
                           {
                               $status = "DNE Information";
                           }
                           elseif ($applicant->isDneAdditionalQualificationQueryEntryComplete() == true)
                           {
                               $status = "Post Sec. Qualifications";
                           }
                           elseif ($applicant->isDneAcademicQualificationsEntryComplete() == true)
                           {
                               $status = "Academic Qualifications";
                           }
                           elseif ($applicant->isDneTertiaryInstitutionQueryEntryComplete() == true)
                           {
                               $status = "Tertiary Attendance";
                           }
                           elseif ($applicant->isDneSecondaryInstitutionEntryComplete() == true)
                           {
                               $status = "Secondary Attendance";
                           }
                           elseif ($applicant->isDnePrimaryInstitutionEntryComplete() == true)
                           {
                               $status = "Primary Attendance";
                           }
                           elseif ($applicant->isDneFamilyContactsEntryComplete() == true)
                           {
                               $status = "Relatives";
                           }
                           elseif ($applicant->isDneAddressEntryComplete() == true)
                           {
                               $status = "Addresses";
                           }
                           elseif ($applicant->isDneContactEntryComplete() == true)
                           {
                               $status = "Contacts";
                           }
                           elseif ($applicant->isDneExtracurricularEntryComplete() == true)
                           {
                               $status = "Extracurricular Activities";
                           }
                           elseif ($applicant->isDneProfileEntryComplete() == true)
                           {
                               $status = "Profile";
                           }
                           elseif ($applicant->isDneProgrammeEntryComplete() == true)
                           {
                               $status = "Programme(s) Selected";
                           }
                           else
                            {
                                $status = "Account Created";
                            }
                        }
                    }
                }
            }
                 
            
            
//            $account_pending_test1 = User::find()
//                        ->where(['username' => $this->applicantname, 'isactive' => 1, 'isdeleted' =>0])
//                        ->one();
//            $account_pending_test2 = Student::find()
//                        ->where(['applicantname' => $this->applicantname, 'isactive' => 1, 'isdeleted' =>0])
//                        ->one();
//            if ($account_pending_test1 == false && $account_pending_test2 == false)
//            {
//                $status = "Account Pending";
//            }
//            else
//            {
//                if ($account_pending_test1 == true)
//                {
//                    $personid = $account_pending_test1->personid;
//                }
//                elseif ($account_pending_test2 == true)
//                {
//                    $personid = $account_pending_test2->personid;
//                }
//                
//                $applications = Application::find()
//                        ->where(['applicationstatusid' => [1,2,3,4,5,6,7,8,9,10,11], 'personid' => $personid, 'isactive' => 1, 'isdeleted' =>0])
//                        ->all();
//                if (empty($applications) == true)
//                {
//                    $status = "Account Created";
//                }
//                else
//                {
//                    if (Applicant::isAbandoned($personid) == true)
//                    {
//                        $status = "Removed";
//                    }
//                    else
//                    {
//                        $processed_applications =  Application::find()
//                            ->where(['applicationstatusid' => [4,5,6,7,8,9,10], 'personid' => $personid, 'isactive' => 1, 'isdeleted' =>0])
//                            ->all();
//                        if (empty($processed_applications) == false)
//                        {
//                            $status = "Processed";
//                        }
//                        else
//                        {
//                            $verified_applications =  Application::find()
//                                ->where(['applicationstatusid' => [3], 'personid' => $personid, 'isactive' => 1, 'isdeleted' =>0])
//                                ->all();
//                            if (empty($verified_applications) == false)
//                            {
//                                $status = "Verified";
//                            }
//                            else
//                            {
//                                $submitted_unverified_applications =  Application::find()
//                                    ->where(['applicationstatusid' => [2], 'personid' => $personid, 'isactive' => 1, 'isdeleted' =>0])
//                                    ->all();
//                                if (empty($submitted_unverified_applications) == false)
//                                {
//                                    $status = "Submitted";
//                                }
//                                else
//                                {
//                                    $status = "Programme(s) Selected";
//                                }
//                            }
//                        }
//                    }
//                }
//            }
            return $status;
        }
        
        
         /**
         * Return username of applicant
         * 
         * @return string
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2018_02_27
         * Modified: 2018_02_28
         */
        public function getApplicantUsername()
        {
            $account_pending_test1 = User::find()
                        ->where(['username' => $this->applicantname, 'isactive' => 1, 'isdeleted' =>0])
                        ->one();
            $account_pending_test2 = Student::find()
                        ->where(['applicantname' => $this->applicantname, 'isactive' => 1, 'isdeleted' =>0])
                        ->one();
            
            if ($account_pending_test1 == true && $account_pending_test2 == false)
            {
                return $account_pending_test1->username;
            }
            
            elseif ($account_pending_test1 == false && $account_pending_test2 == true)
            {
                $user = User::find()
                        ->where(['personid' => $account_pending_test2->personid, 'isactive' => 1, 'isdeleted' =>0])
                        ->one();
                return $user->username;
            }
            
            elseif ($account_pending_test1 == false && $account_pending_test2 == false)
            {
                return "--";
            }
            
        }
        
        
        
        /**
         * Return status of applicant account
         * 
         * @return string
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2017_10_06
         * Modified: 2018_02_27
         */
        public function getStatus()
        {
            $status = "--";
            
            $email = $this->getEmail();
            if ($email == false)
            {
                $status = "Account Pending";
            }
            else
            {
                $applications = Application::find()
                    ->where(['applicationstatusid' => [1,2,3,4,5,6,7,8,9,10,11], 'personid' => $email->personid, 'isactive' => 1, 'isdeleted' =>0])
                    ->all();
                if (empty($applications) == true)
                {
                    $status = "Account Created";
                }
                else
                {
                    if (Applicant::isAbandoned($email->personid) == true)
                    {
                        $status = "Removed";
                    }
                    else
                    {
                        $processed_applications =  Application::find()
                            ->where(['applicationstatusid' => [4,5,6,7,8,9,10], 'personid' => $email->personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->all();
                        if (empty($processed_applications) == false)
                        {
                            $status = "Processed";
                        }
                        else
                        {
                            $verified_applications =  Application::find()
                                ->where(['applicationstatusid' => [3], 'personid' => $email->personid, 'isactive' => 1, 'isdeleted' =>0])
                                ->all();
                            if (empty($verified_applications) == false)
                            {
                                $status = "Verified";
                            }
                            else
                            {
                                $submitted_unverified_applications =  Application::find()
                                    ->where(['applicationstatusid' => [2], 'personid' => $email->personid, 'isactive' => 1, 'isdeleted' =>0])
                                    ->all();
                                if (empty($submitted_unverified_applications) == false)
                                {
                                    $status = "Submitted";
                                }
                                else
                                {
                                    $status = "Programme(s) Selected";
                                }
                            }
                        }
                    }
                }
            }
            return $status;
        }
        
        
        /**
         * Return User record that is associated with ApplicantRegristration record
         * 
         * @return User
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2017_10_06
         * Modified: 2017_10_06
         */
        public function getUser()
        {
            $user = NULL;
             
             $email = $this->getEmail();
             if ($email == true)
             {
                $user = User::find()
                            ->where(['personid' => $email->personid])
                            ->one();
             }
             return $user;
             
        }
        
        
         /**
         * Return most recent Email record associated with ApplicantRegistration account
         * Needed to account for cases with reapplicants that have mulitple Email records...
         * this ensures the most recent is utilized as a basis for finding most recent User record.
         * 
         * @return Email
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2018_02_21
         * Modified: 2018_02_21
         */
        private function getEmail()
        {
            $possible_emails = Email::find()
                        ->where(['email' => $this->email, 'isactive' => 1, 'isdeleted' =>0])
                        ->all();
             if ($possible_emails == true)
             {
                 return end($possible_emails);
             }
             else
             {
                 return false;
             }
        }
        
        
        
         /**
        * Generates an applicant token
        * 
        * @return string
        * 
        * Author: charles.laurence1@gmail.com  
        * Created: 2018_02_28
        * Modified: 2017_02_28
        */
        public function generateToken()
       {
            return Yii::$app->getSecurity()->generateRandomString(15);
       }
    
    
       /**
        * Returns full name for record
        * 
        * @return string
        * 
        * Author: charles.laurence1@gmail.com  
        * Created: 2018_02_28
        * Modified: 2018_02_28
        */
       public function getFullName()
       {
           return $this->title . ' '. $this->firstname . ' ' . $this->lastname;
       }
    
    
         /**
        * Send an email verification correspondence to user
        * 
        * @return boolean
        * 
        * Author: charles.laurence1@gmail.com  
        * Created: 2018_02_28
        * Modified: 2017_02_28
        */
       public function sendApplicantAccountRequestEmail()
       {
           $host = "http://www.svgcc.vc/subdomains/apply2/web";
           $generated_reset_link = Yii::$app->urlManager->createUrl(['account-management/applicant-account-confirmation', 'id' => $this->applicantname, 'token' => $this->token]);
           $formatted_reset_link = str_replace("/sat_dev/frontend/web", "", $generated_reset_link);
           $reset_url = $host . $formatted_reset_link;
           
           $feedback = Yii::$app->mailer->compose(['html' => 'applicant_account_request_email'], ['model' => $this, 'reset_url' => $reset_url])
                   ->setFrom(Yii::$app->params['applicationEmail'])
                   ->setTo($this->email)
                   ->setSubject('SVGCC Applicant Account Creation Instructions')
                   ->send();
           return $feedback;
       }
       
        
        
    }
