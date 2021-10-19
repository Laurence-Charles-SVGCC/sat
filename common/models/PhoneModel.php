<?php

namespace common\models;

class PhoneModel
{
    public static function getPhoneByPersonid($id)
    {
        return Phone::find()->where(["personid" => $id])->one();
    }
}
