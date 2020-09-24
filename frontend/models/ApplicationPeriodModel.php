<?php

namespace frontend\models;

class ApplicationPeriodModel
{
    public static function getApplicationPeriodById($id)
    {
        return ApplicationPeriod::find()
        ->where(['applicationperiodid' => $id])
        ->one();
    }
}
