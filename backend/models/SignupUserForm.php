<?php
namespace backend\models;

use common\models\User;
use yii\base\Model;

/**
 * Signup User form
 */
class SignupUserForm extends Model
{
    public $firstname;
    public $lastname;
    public $password;
    public $confirm_password;
    public $email;
    public $persontypeid;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'confirm_password'], 'required'],
            [['password', 'confirm_password'], 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'password'],
            
            ['email', 'email'],
            ['email', 'svgccmail'],
            
            [['firstname', 'lastname'], 'string', 'max' => 45],
            [['persontypeid'], 'integer'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup($username)
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $username;
            $user->setPassword($this->password);
            $user->setPersonTypeID($this->persontypeid);
            $user->setSalt();
            $user->isactive = 1;
            $user->isdeleted = 0;
            
            if ($user->save()) {
                return $user;
            }   
        }
        return null;
    }
    
    /*
    * Purpose: Restrict ability to sign up to only College emails
    * Created: 30/07/2015 by Gamal Crichton
    * Last Modified: 30/07/2015 by Gamal Crichton
    */
    public function svgccMail($attribute, $params)
    {
        
        if (!stripos($this->$attribute, 'svgcc.vc') && !stripos($this->$attribute, 'svgcc.net'))
        {
            $this->addError($attribute, 'Only SVGCC Email addresses are allowed.');
        }
    }
}
