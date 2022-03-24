<?php

namespace common\models;

use Yii;

class UserPasswordResetRequest
{
    private $user;

    public function __construct($user, $randomString, $timeStamp)
    {
        $this->user = $user;
        $this->randomString = $randomString;
        $this->timestamp = $timeStamp;
    }

    public function generatePasswordResetToken()
    {
        return "{$this->randomString}_{$this->timestamp}";
    }

    public function updateResetToken($resetToken)
    {
        $this->user->resettoken = $resetToken;
        return $this->user->save();
    }

    public function generateResetLink()
    {
        return Yii::$app->urlManager->createAbsoluteUrl(
            [
                'site/reset-password',
                'token' => $this->user->resettoken
            ]
        );
    }
    public function resetTokenValid()
    {
        if ($this->user->resettoken == null) {
            return false;
        }

        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $this->user->resettoken);
        $timestamp = (int) end($parts);
        return ($timestamp + $expire) >= time();
    }

    public function processPasswordResetLinkRequest()
    {
        $resetToken = $this->generatePasswordResetToken();
        $updatedUser = $this->updateResetToken($resetToken);
        if ($updatedUser == true) {
            return $this->generateResetLink($resetToken);
        }
        return false;
    }
}
