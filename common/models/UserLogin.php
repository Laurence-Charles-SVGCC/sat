<?php

namespace common\models;

use Yii;

class UserLogin
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function login()
    {
        $divisionId =  UserDAO::getUserDivision($this->user);
        if ($divisionId == true) {
            Yii::$app->user->login($this->user,  60 * 60 * 5);
            Yii::$app->session->set('divisionid', $divisionId);
            return true;
        }
        return false;
    }
}
