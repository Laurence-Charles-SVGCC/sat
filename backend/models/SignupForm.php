<?php
namespace backend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $firstname;
    public $lastname;
    public $password;
    public $confirm_password;
    public $email;

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
            ['email', 'required'],
            ['email', 'canSignUp'],
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
            $user->setPersonTypeID('employee');
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
    * Purpose: Restrict ability to sign up to only E-College persons
    * Created: 13/07/2015 by Gamal Crichton
    * Last Modified: 14/07/2015 by Gamal Crichton
    */
    public function canSignUp($attribute, $params)
    {
        
        /*if ($this->hasErrors())
            return;
         Needed?*/
        /*$ecollege_emails = array('ulrick.sutherland@svgcc.vc', 'melissia.charles@svgcc.vc', 'silkie.prescott@svgcc.vc',
            'bevan.lewis@svgcc.vc', 'kadauna.wilkes@svgcc.vc', 'john.defreitas@svgcc.vc', 'dwayne.defreitas@svgcc.vc',
            'laurence.charles@svgcc.vc', 'krislin.gouldbourne@svgcc.vc',
            'gamal.crichton@svgcc.vc');
        if (!in_array($this->$attribute, $ecollege_emails))
        {
            $this->addError($attribute, 'Only members of E-College can sign up');
        }*/
    }
}
