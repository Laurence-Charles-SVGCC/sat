<?php
namespace common\models;

use Yii;
use yii\base\Model;

use frontend\models\EmployeeDepartment;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    //public $rememberMe = false; //Not implemented

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            // password is validated by validatePassword()
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
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
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
     * Date Last Modified: 01/06/2016
     */
    public function login()
    {
        if ($this->validate())
        {
            $res = Yii::$app->user->login($this->getUser(), 60 * 60 * 5);
            $emp_department = EmployeeDepartment::findOne(['personid' => Yii::$app->user->getId()]);
            
            //identify users that are applicants or students
            $user = User::find()
                    ->where(['personid' => Yii::$app->user->getId()])
                    ->one();
            if($user->persontypeid == 1 || $user->persontypeid == 2)
            {
                Yii::$app->user->logout();
                return -1;
            }
              
            $department = $emp_department ? $emp_department->getDepartment()->one() : NULL;
            $division_id = $department ? $department->divisionid : NULL;

            if ($division_id)
            {
                Yii::$app->session->set('divisionid', $division_id);
                return 1;
            }
        } 
        else 
        {
            return 0;
        }
    }
//    public function login()
//    {
//        if ($this->validate()) {
//        {
//            $res = Yii::$app->user->login($this->getUser(), 60 * 60 * 5);
//            $emp_department = EmployeeDepartment::findOne(['personid' => Yii::$app->user->getId()]);
//            $department = $emp_department ? $emp_department->getDepartment()->one() : NULL;
//            $division_id = $department ? $department->divisionid : NULL;
//            
//            if ($division_id)
//            {
//                Yii::$app->session->set('divisionid', $division_id);
//                return true;
//            }
//            Yii::$app->session->setFlash('error', 'User not assigned a valid department');
//        }
//        } else {
//            return false;
//        }
//    }

    
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
