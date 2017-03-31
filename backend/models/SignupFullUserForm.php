<?php
    namespace backend\models;

    use common\models\User;
    use yii\base\Model;

    class SignupFullUserForm extends Model
    {
        public $title;
        public $firstname;
        public $middlename;
        public $lastname;
        public $maritalstatus;
        public $religion;
        public $nationality;
        public $placeofbirth;
        public $nationalidnumber;
        public $nationalinsurancenumber;
        public $inlandrevenuenumber;
        public $gender;
        public $dateofbirth;
        public $personal_email;
        public $institutional_email;
        public $username;
        public $password;
        public $confirm_password;
        public $departmentid;
        public $employeetitleid;

        public function rules()
        {
            return [
                [['title', 'firstname', 'lastname', 'gender', 'institutional_email', 'password', 'confirm_password', 'employeetitleid', 'departmentid'], 'required'],
                [['title'], 'string', 'max' => 3],
                [['firstname', 'middlename', 'lastname', 'maritalstatus', 'nationality', 'religion', 'placeofbirth', 'nationalidnumber', 'nationalinsurancenumber', 'inlandrevenuenumber'], 'string', 'max' => 45],
                [['gender'], 'string', 'max' => 6],
                [['dateofbirth'], 'safe'],
                [['personal_email', 'institutional_email'], 'email'],
                ['institutional_email', 'svgccmail'],
                [['username'], 'string', 'max' => 8],
                [['password', 'confirm_password'], 'string', 'min' => 6],
                ['confirm_password', 'compare', 'compareAttribute' => 'password'],
                [['employeetitleid', 'departmentid'], 'integer']
            ];
        }

        
        //(laurence_charles) - Creates username for employees
        public static function createEmployeeUsername()
        {
            $last_user = User::find()->orderBy('personid DESC', 'desc')->one();
            //150 used to prevent username clashes with the users already entered on eCampus.
            $num = $last_user ? strval($last_user->personid + 1) : 150;
            while (strlen($num) < 4)
            {
                $num = '0' . $num;
            }
            return '1401' . $num;
        }


        // (gamal_crichton) - Creates "User' record. 
        public function signup($username, $institutional_email)
        {
            if ($this->validate()) 
            {
                $user = new User();
                $user->username = $username;
                $user->email = $institutional_email;
                $user->persontypeid = 3;
                $user->setPassword($this->password);
                $user->setSalt();
                $user->isactive = 1;
                $user->isdeleted = 0;

                if ($user->save() == true) 
                {
                    return $user;
                }   
            }
            return null;
        }

        
        // (gamal_crichton) - Restrict ability to sign up to only College emails
        public function svgccMail($attribute, $params)
        {

            if (!stripos($this->$attribute, 'svgcc.vc') && !stripos($this->$attribute, 'svgcc.net'))
            {
                $this->addError($attribute, 'Only SVGCC Email addresses are allowed.');
            }
        }
    }
