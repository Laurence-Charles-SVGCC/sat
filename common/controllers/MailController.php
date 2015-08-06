<?php

namespace common\controllers;

use Yii;

class MailController extends \yii\web\Controller
{
    
    /*
    * Purpose: Sends a single mail
    * Created: 6/08/2015 by Gamal Crichton
    * Last Modified: 6/08/2015 by Gamal Crichton
    */
    public static function sendMail($recepient, $template, $subject, $sender = 'noreply@svgcc.vc', $body_array = '' )
    {
        $body_arr = $body_array ? $body_array : array();
       return Yii::$app->mailer->compose('@common/mail/' . $template, $body_arr)
                ->setFrom($sender)
                ->setTo($recepient)
                ->setSubject($subject)
                ->send();
    }

}
