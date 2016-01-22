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
    public $personal_email;
    public $institutional_email;
    
    public $persontypeid;
    public $department;
    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'confirm_password', 'department'], 'required'],
            [['password', 'confirm_password'], 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'password'],
            
            ['personal_email', 'email'],
            
            ['institutional_email', 'email'],
            ['institutional_email', 'svgccmail'],
            
            [['firstname', 'lastname'], 'string', 'max' => 45],
            [['persontypeid'], 'integer'],
            [['username'], 'string', 'max' => 8],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     * 
     * Author: Game Crichton
     * Date Created: ??
     * Date Last Modified: 20/01/2016 (Laurence Charles)
     */
    public function signup($username, $institutional_email)
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $username;
            $user->email = $institutional_email;
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
