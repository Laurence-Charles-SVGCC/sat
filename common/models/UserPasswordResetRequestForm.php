<?php

namespace common\models;

use Yii;

use yii\base\Model;

class UserPasswordResetRequestForm extends Model
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
            [
                'email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['isdeleted' => 0],
                'message' => 'User not found'
            ],
        ];
    }


    public function publishResetInstructions()
    {
        $user = UserDAO::getByEmail($this->email);

        $request =
            new UserPasswordResetRequest(
                $user,
                Yii::$app->security->generateRandomString(),
                time()
            );

        $resetLink = $request->processPasswordResetLinkRequest();
        if ($resetLink == true) {
            return \Yii::$app->mailer->compose(
                [
                    "html" => "passwordResetToken-html",
                    "text" => "passwordResetToken-text"
                ],
                ["username" => $user->username, "resetLink" => $resetLink]
            )
                ->setFrom([\Yii::$app->params['supportEmail'] => 'SAT Administrator'])
                ->setTo($this->email)
                ->setSubject('Password reset for SAT.')
                ->send();
        } else {
            return false;
        }
    }
}
