<?php
    namespace common\models;

    use Yii;
    use yii\base\Model;

    /**
     * Login form
     */
    class LoginForm extends Model
    {
        public $username;
        public $password;
        private $_user;


        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['username', 'password'], 'required'],
                ['password', 'validatePassword'],
            ];
        }


        /**
         * Validates the password.
         * This method serves as the inline validation for password.
         *
         * @param string $attribute the attribute currently being validated
         * @param array $params the additional name-value pairs given in the rule
         */
        public function validatePassword($attribute, $params)
        {
            if (!$this->hasErrors()) 
            {
                $user = $this->getUser();

                //Determines is user credentials are valid
                if (!$user || !$user->validatePassword($this->password)) 
                {
                    $this->addError($attribute, 'Incorrect username or password.');
                }
                elseif ($user)
                {
                    //Determines if user is a student
                    if ($user->persontypeid == 1 || $user->persontypeid == 2)
                    {
                        $this->addError($attribute, 'You are not authorized to access the SVGCC administrative terminal.');
                    }
                    //Determines if user is a credentialed employee that has not yet been assigned to  a division
                    elseif ($user->persontypeid == 3)
                    {
                        $division_id =  $user->getUserDivision();
                        if ( $division_id == false)
                        {
                            $this->addError($attribute, 'All authorized users must be assigned to a division.  Please contact E-College Unit.');
                        }
                    }
                }
            }
        }


        /**
         * Logs in a user using the provided username and password.
         *
         * @return boolean whether the user is logged in successfully
         * 
         * Author: Gamal Crichton
         * Date Created: ??
         * Date Last Modified: 08/08/2017 (Laurence Charles)
         */
        public function login()
        {
            if ($this->validate())
            {
                $user = $this->getUser();
                $flag = Yii::$app->user->login($user,  60 * 60 * 5);
                $division_id =  $user->getUserDivision();

                if ($division_id)
                {
                    Yii::$app->session->set('divisionid', $division_id);
                    return true;
                }
            } 
            return false;
        }


        /**
         * Finds user by [[username]]
         *
         * @return User|null
         */
        protected function getUser()
        {
            if ($this->_user === null) {
                $this->_user = User::findByUsername($this->username);
            }
            return $this->_user;
        }
    }
