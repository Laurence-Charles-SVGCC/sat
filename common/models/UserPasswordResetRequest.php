<?php

namespace common\models;

use Yii;

class UserPasswordResetRequest
{
    private $user;
    private $randomString;
    private $timestamp;

    public function __construct($user, $randomString, $timestamp)
    {
        $this->user = $user;
        $this->randomString = $randomString;
        $this->timestamp = $timestamp;
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
