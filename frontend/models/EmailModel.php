<?php

namespace frontend\models;

use Yii;

class EmailModel
{
    public static function getEmailById($id)
    {
        return Email::find()->where(['personid' => $id])->one();
    }
}
