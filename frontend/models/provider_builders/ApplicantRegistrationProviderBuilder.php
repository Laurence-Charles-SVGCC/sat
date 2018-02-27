<?php
    namespace frontend\models\provider_builders;

    use Yii;
    use yii\data\ArrayDataProvider;
    use yii\custom\ModelNotFoundException;
     use frontend\models\ApplicantRegistration;

     
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
         * Modified: 2017_10_009
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
                    $applicant_record['email'] = $applicant_registration->email;
                    $applicant_record['applicantname'] = $applicant_registration->applicantname;
                    $applicant_record['firstname'] = $applicant_registration->firstname;
                    $applicant_record['lastname'] = $applicant_registration->lastname;
                    $applicant_record['status'] = $applicant_registration->getApplicantStatus();
                    $applicant_record['username'] = $applicant_registration->getApplicantUsername();
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
         * Modified: 2017_10_09
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
                    $applicant_record['email'] = $applicant_registration->email;
                    $applicant_record['applicantname'] = $applicant_registration->applicantname;
                    $applicant_record['firstname'] = $applicant_registration->firstname;
                    $applicant_record['lastname'] = $applicant_registration->lastname;
                    $applicant_record['status'] = $applicant_registration->getApplicantStatus();
                    $applicant_record['username'] = $applicant_registration->getApplicantUsername();
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
         * Modified: 2017_10_09
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
                    $applicant_record['email'] = $applicant_registration->email;
                    $applicant_record['applicantname'] = $applicant_registration->applicantname;
                    $applicant_record['firstname'] = $applicant_registration->firstname;
                    $applicant_record['lastname'] = $applicant_registration->lastname;
                    $applicant_record['status'] = $applicant_registration->getApplicantStatus();
                    $applicant_record['username'] = $applicant_registration->getApplicantUsername();
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
    
