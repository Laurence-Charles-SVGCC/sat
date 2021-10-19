<?php

namespace common\models;

use Yii;

class EmailModel
{
    public static function getEmailByPersonid($id)
    {
        return Email::find()->where(["personid" => $id])->one();
    }
}
