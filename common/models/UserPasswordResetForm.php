<?php

namespace common\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;


class UserPasswordResetForm extends Model
{
    public $password;
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function passwordResetTokenValid()
    {
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $this->user->resettoken);
        $timestamp = (int) end($parts);
        return ($timestamp + $expire) >= time();
    }

    public function removePasswordResetToken()
    {
        $this->user->resettoken = null;
    }

    public function setPassword($password)
    {
        $this->user->pword = Yii::$app->security->generatePasswordHash($password);
        $this->user->p_word = $password;
    }

    public function resetPassword()
    {
        $user = $this->user;
        $this->setPassword($this->password);
        $this->removePasswordResetToken();
        return $user->save();
    }

    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $flag = Yii::$app->user->login($user,  60 * 60 * 5);
            $division_id =  $user->getUserDivision();

            if ($division_id) {
                Yii::$app->session->set('divisionid', $division_id);
                return true;
            }
        }
        return false;
    }
}
