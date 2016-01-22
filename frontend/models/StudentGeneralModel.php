<?php
    namespace frontend\models;
    
/* 
 * This acts as an intermediary model for the 'Student Profile' model
 * 
 * Author: Laurence Charles
 * Date Created: 25/12/2015
 * Date Last Modified: 25/12/2015
 */
    
    use Yii;
    use yii\base\Model;
    
    use common\models\User;
    
    /**
     * PersonalInfomration is the model behind the 'student profile' forms.
     */
    class StudentGeneralModel extends Model
    { 
        public $personid;
        public $username;
        public $studentregistrationid;
        
        public $title;
        public $firstname;
        public $middlename;
        public $lastname;
        public $gender;
        public $dateofbirth;
        public $maritalstatus;
        public $nationality;
        public $religion;
        public $placeofbirth;
        public $sponsorname;
        public $studentstatusid;

        public function rules()
        {
            return [
                [['personid', 'studentregistrationid', 'studentstatusid', 'title', 'firstname', 'middlename', 'lastname', 'nationality', 'religion', 'placeofbirth', 'gender', 'dateofbirth', 'maritalstatus'],'required'],
                [['personid', 'studentregistrationid', 'studentstatusid'], 'integer'],
                [['title'], 'string', 'max' => 3],
                [['firstname', 'middlename', 'lastname', 'sponsorname', 'nationality', 'religion', 'placeofbirth'], 'string', 'max' => 45],               
                [['gender'], 'string', 'max' => 6],
                ['dateofbirth', 'safe'],
                [['maritalstatus'], 'string', 'max' => 15], 
                [['username'], 'string', 'max' => 225], 
            ];
        }


        public function attributeLabels()
        {
            return [
                'username' => 'Username',
                'studentregistrationid' => 'Registration ID',
                'studentstatusid' => 'studentstatusid',
                'personid' => 'PersonID',
                'title' => 'Title',
                'firstname' => 'First Name',
                'middlename' => 'Middle Name',
                'lastname' => 'Last Name',
                'gender' => 'Gender',
                'dateofbirth' => 'Date of Birth',
                'maritalstatus'=> 'Marital Status',
                'sponsorname' => 'Sponsorname',
                'nationality' => 'Nationality',           
                'religion' => 'Religion',
                'placeofbirth' => 'Place of Birth',
            ];
        }
        
        
        /**
         * Transfers information from applicant, student and studentregistration models to PersonalInformationModel model
         * 
         * @param type $applicant
         * 
         * Author: Laurence Charles
         * Date Created: 29/10/2015
         * Date Last Modified: 05/01/2016
         */
        public function transferInfo($applicant, $student, $studentregistrationid)
        {
            $user = User::find()
                    ->where(['personid' => $student->personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $studentregistration = StudentRegistration::find()
                    ->where(['studentregistrationid' => $studentregistrationid, 'isdeleted' => 0])
                    ->one();
            
            $this->username = $user->username;
            $this->studentregistrationid = $studentregistrationid;
            $this->personid = $student->personid;
            $this->title = $student->title;
            $this->firstname = $student->firstname;       
            $this->middlename = $student->middlename;   
            $this->lastname = $student->lastname;
            $this->dateofbirth = $student->dateofbirth;
            $this->gender = $student->gender;
            $this->nationality = $applicant->nationality;
            $this->placeofbirth = $applicant->placeofbirth;
            $this->religion = $applicant->religion;     
            $this->maritalstatus = $applicant->maritalstatus;
            $this->sponsorname = $applicant->sponsorname;
            $this->studentstatusid = $studentregistration->studentstatusid;
        }
      
        
    }
    



