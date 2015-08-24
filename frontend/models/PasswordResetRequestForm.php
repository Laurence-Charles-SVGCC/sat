<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\frontend\models\Email',
                'filter' => ['isdeleted' => 0],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $email = Email::findOne(['email' => $this->email, 'isdeleted' => 0]);
        
        $user = $email? User::findOne(['isactive' => User::STATUS_ACTIVE, 'personid' => $email->personid]) : NULL;

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->resettoken)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                return \Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => 'SAT Administrator'])
                    ->setTo($this->email)
                    ->setSubject('Password reset for SAT.')
                    ->send();
            }
        }

        return false;
    }
}
