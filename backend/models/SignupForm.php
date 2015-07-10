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
    public $username;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) { 
            $user = new User();
            $user->username = $this->username;
            $user->setPassword($this->password);
            //$user->generateAuthKey();
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
}
