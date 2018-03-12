<?php
    namespace frontend\models\provider_builders;

    use Yii;
    use yii\data\ArrayDataProvider;
    use yii\custom\ModelNotFoundException;
    use frontend\models\ApplicantRegistration;
    use frontend\models\Application;
    use common\models\User;

     
    class ApplicantRegistrationProviderBuilder extends \yii\base\Model
    { 
        
        /**
         * Generates a dataprovider for applicants with matching email address
         * 
         * @param String $email
         * @param Integer $page_size
         * @return ArrayDataProvider
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2017_10_06
         * Modified: 2018_02_28
         */
        public static function generateApplicantRegistrationByEmail($email, $page_size)
        {
            $dataProvider = array();
            
            $applicant_registrations = ApplicantRegistration::find()
                    ->where(['email' => $email])
                    ->all();
            
            $records = array();
            if ($applicant_registrations)
            {
                foreach ($applicant_registrations as $applicant_registration)
                {
                    $applicant_record = array();
                    $applicant_record['applicantregistrationid'] = $applicant_registration->applicantregistrationid;
                    $applicant_record['email'] = $applicant_registration->email;
                    $applicant_record['applicantname'] = $applicant_registration->applicantname;
                    $applicant_record['token'] = $applicant_registration->token;
                    $applicant_record['firstname'] = $applicant_registration->firstname;
                    $applicant_record['lastname'] = $applicant_registration->lastname;
                    $applicant_record['status'] = $applicant_registration->getApplicantStatus();
                    
                    $username = $applicant_registration->getApplicantUsername();
                    $applicant_record['username'] = $username;
                    
                    if ($applicant_registration->created_at == "0000-00-00 00:00:00")
                    {
                        $applicant_record['start_date'] = "--";
                    }
                    else
                    {
                        $applicant_record['start_date'] = date_format(new \DateTime($applicant_registration->created_at), 'g:ia \o\n l jS F Y');
                    }
                    
                    
                    $user = User::find()
                            ->where(['username' => $username, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($user == false)
                    {
                        $applicant_record['submission_date'] = "--";
                        $applicant_record['p_word'] = "--";
                    }
                    else
                    {
                        if ($user->p_word == true)
                        {
                            $applicant_record['p_word'] = $user->p_word;
                        }
                        else
                        {
                            $applicant_record['p_word'] = "--";
                        }
                        
                        $applications = Application::find()
                            ->where(['applicationstatusid' => [2,3,4,5,6,7,8,9,10,11], 'personid' => $user->personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->all();
                        if ($applications == false)
                        {
                            $applicant_record['submission_date'] = "--";
                        }
                        else
                        {
                            $target_application = $applications[0];
                            if ($target_application->submissiontimestamp == "0000-00-00 00:00:00"  || $target_application->submissiontimestamp == NULL)
                            {
                                $applicant_record['submission_date'] = "--";
                            }
                            else
                            {
                                $applicant_record['submission_date'] = date_format(new \DateTime($target_application->submissiontimestamp), 'g:ia \o\n l jS F Y');
                            }
                        }
                    }
                    $records[] =  $applicant_record;
                }
            }
            
            $dataProvider = new ArrayDataProvider([
                'allModels' => $records,
                'pagination' => ['pageSize' => $page_size],
                'sort' => [
                    'defaultOrder' => ['email' => SORT_ASC],
                    'attributes' => ['email', 'firstname', 'lastname']]
                ]); 
            
            return $dataProvider;
        }
        
        
        /**
         * Generates a dataprovider for applicants with matching applicantID
         * 
         * @param String $applicantid
         * @param Integer $page_size
         * @return ArrayDataProvider
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2017_10_06
         * Modified: 2018_02_28
         */
        public static function generateApplicantRegistrationByApplicantID($applicantid, $page_size)
        {
            $dataProvider = array();
            
            $applicant_registrations = ApplicantRegistration::find()
                    ->where(['applicantname' => $applicantid])
                    ->all();
            
            $records = array();
            if ($applicant_registrations)
            {
                foreach ($applicant_registrations as $applicant_registration)
                {
                    $applicant_record = array();
                    $applicant_record['applicantregistrationid'] = $applicant_registration->applicantregistrationid;
                    $applicant_record['email'] = $applicant_registration->email;
                    $applicant_record['applicantname'] = $applicant_registration->applicantname;
                    $applicant_record['token'] = $applicant_registration->token;
                    $applicant_record['firstname'] = $applicant_registration->firstname;
                    $applicant_record['lastname'] = $applicant_registration->lastname;
                    $applicant_record['status'] = $applicant_registration->getApplicantStatus();
                    
                    $username = $applicant_registration->getApplicantUsername();
                    $applicant_record['username'] = $username;
                    
                    if ($applicant_registration->created_at == "0000-00-00 00:00:00")
                    {
                        $applicant_record['start_date'] = "--";
                    }
                    else
                    {
                        $applicant_record['start_date'] = date_format(new \DateTime($applicant_registration->created_at), 'g:ia \o\n l jS F Y');
                    }
                    
                    
                    $user = User::find()
                            ->where(['username' => $username, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($user == false)
                    {
                        $applicant_record['submission_date'] = "--";
                        $applicant_record['p_word'] = "--";
                    }
                    else
                    {
                        if ($user->p_word == true)
                        {
                            $applicant_record['p_word'] = $user->p_word;
                        }
                        else
                        {
                            $applicant_record['p_word'] = "--";
                        }
                        
                        $applications = Application::find()
                            ->where(['applicationstatusid' => [2,3,4,5,6,7,8,9,10,11], 'personid' => $user->personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->all();
                        if ($applications == false)
                        {
                            $applicant_record['submission_date'] = "--";
                        }
                        else
                        {
                            $target_application = $applications[0];
                            if ($target_application->submissiontimestamp == "0000-00-00 00:00:00"  || $target_application->submissiontimestamp == NULL)
                            {
                                $applicant_record['submission_date'] = "--";
                            }
                            else
                            {
                                $applicant_record['submission_date'] = date_format(new \DateTime($target_application->submissiontimestamp), 'g:ia \o\n l jS F Y');
                            }
                        }
                    }
                    $records[] =  $applicant_record;
                }
            }
            
            $dataProvider = new ArrayDataProvider([
                'allModels' => $records,
                'pagination' => ['pageSize' => $page_size],
                'sort' => [
                    'defaultOrder' => ['email' => SORT_ASC],
                    'attributes' => ['email', 'firstname', 'lastname']]
                ]); 
            
            return $dataProvider;
        }
        
        
         /**
         * Generates a dataprovider for applicants with matching firstname &/ lastname
         * 
         * @param AssociativeArray $name_search_criteria
         * @param Integer $page_size
         * @return ArrayDataProvider
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2017_10_06
         * Modified: 2018_02_28
         */
        public static function generateApplicantRegistrationByName($search_criteria, $page_size)
        {
            $dataProvider = array();
            
            $applicant_registrations = ApplicantRegistration::find()
                    ->where($search_criteria)
                    ->all();
            
            $records = array();
            if ($applicant_registrations)
            {
                foreach ($applicant_registrations as $applicant_registration)
                {
                    $applicant_record = array();
                    $applicant_record['applicantregistrationid'] = $applicant_registration->applicantregistrationid;
                    $applicant_record['email'] = $applicant_registration->email;
                    $applicant_record['applicantname'] = $applicant_registration->applicantname;
                    $applicant_record['token'] = $applicant_registration->token;
                    $applicant_record['firstname'] = $applicant_registration->firstname;
                    $applicant_record['lastname'] = $applicant_registration->lastname;
                    $applicant_record['status'] = $applicant_registration->getApplicantStatus();
                    
                    $username = $applicant_registration->getApplicantUsername();
                    $applicant_record['username'] = $username;
                    
                    if ($applicant_registration->created_at == "0000-00-00 00:00:00")
                    {
                        $applicant_record['start_date'] = "--";
                    }
                    else
                    {
                        $applicant_record['start_date'] = date_format(new \DateTime($applicant_registration->created_at), 'g:ia \o\n l jS F Y');
                    }
                    
                    
                    $user = User::find()
                            ->where(['username' => $username, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($user == false)
                    {
                        $applicant_record['submission_date'] = "--";
                        $applicant_record['p_word'] = "--";
                    }
                    else
                    {
                        if ($user->p_word == true)
                        {
                            $applicant_record['p_word'] = $user->p_word;
                        }
                        else
                        {
                            $applicant_record['p_word'] = "--";
                        }
                        
                        $applications = Application::find()
                            ->where(['applicationstatusid' => [2,3,4,5,6,7,8,9,10,11], 'personid' => $user->personid, 'isactive' => 1, 'isdeleted' =>0])
                            ->all();
                        if ($applications == false)
                        {
                            $applicant_record['submission_date'] = "--";
                        }
                        else
                        {
                            $target_application = $applications[0];
                            if ($target_application->submissiontimestamp == "0000-00-00 00:00:00"  || $target_application->submissiontimestamp == NULL)
                            {
                                $applicant_record['submission_date'] = "--";
                            }
                            else
                            {
                                $applicant_record['submission_date'] = date_format(new \DateTime($target_application->submissiontimestamp), 'g:ia \o\n l jS F Y');
                            }
                        }
                    }
                    $records[] =  $applicant_record;
                }
            }
            
            $dataProvider = new ArrayDataProvider([
                'allModels' => $records,
                'pagination' => ['pageSize' => $page_size],
                'sort' => [
                    'defaultOrder' => ['email' => SORT_ASC],
                    'attributes' => ['email', 'firstname', 'lastname']]
                ]); 
            
            return $dataProvider;
        }
        
        
    }
    
