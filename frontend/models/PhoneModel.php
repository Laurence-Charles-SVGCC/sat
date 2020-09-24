<?php

namespace frontend\models;

use Yii;

class PhoneModel
{
    public static function getPhoneById($id)
    {
        return Phone::find()->where(['personid' => $id])->one();
    }
}
