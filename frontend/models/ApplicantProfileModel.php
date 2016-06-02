<?php
    namespace frontend\models;
    
    use frontend\models\Applicant;
    
/* 
 * This acts as an intermediary model for the 'Personal Information' model
 * 
 * Author: Laurence Charles
 * Date Created: 23/10/2015
 * Date Last Modified: 23/10/2015
 */
    
    use Yii;
    use yii\base\Model;
    
    /**
     * PersonalInfomration is the model behind the 'personal_information' form.
     */
    class ApplicantProfileModel extends Model
    { 
        public $title;
        public $firstname;
        public $middlename;
        public $lastname;
        public $gender;
        public $dateofbirth;
        public $sponsorname;
        public $clubs;
        public $otherinterests;
        public $maritalstatus;
        public $nationality;
        public $religion;
        public $placeofbirth;
        public $nationalsports;
        public $othersports;
        public $otheracademics;
        public $isexternal;
        
        public function rules()
        {
            return [
                [['title','firstname', 'middlename', 'lastname', 'gender', 'dateofbirth', 'nationality', 'religion', 'placeofbirth', 'gender', 'dateofbirth', 'maritalstatus', 'isexternal'],'required'],               
                [['personid', 'isexternal'], 'integer'],
                [['title'], 'string', 'max' => 3],
                [['firstname', 'middlename', 'lastname', 'nationality', 'religion', 'placeofbirth'], 'string', 'max' => 45],               
                [['gender'], 'string', 'max' => 6],
                [['sponsorname'], 'string', 'max' => 100],
                ['dateofbirth', 'safe'],
                [['clubs', 'otherinterests', 'nationalsports', 'othersports','otheracademics'], 'string'],
                [['maritalstatus'], 'string', 'max' => 15],                      
            ];
        }


        public function attributeLabels()
        {
            return [
                'title' => 'Title',
                'firstname' => 'First Name',
                'middlename' => 'Middle Name',
                'lastname' => 'Last Name',
                'gender' => 'Gender',
                'dateofbirth' => 'Date of Birth',
                'maritalstatus'=> 'Marital Status',
                'sponsorname' => 'Sponsorname',
                'clubs' => 'Clubs',
                'otherinterests' => 'Other Interests',
                'nationality' => 'Nationality',           
                'religion' => 'Religion',
                'placeofbirth' => 'Place of Birth',
                'nationalsports' => 'National Sports',
                'othersports' => 'Other Sports',
                'isexternal' => 'Is External',
                'otheracademics' => 'Other Academics',
            ];
        }
        
        
    }
    



