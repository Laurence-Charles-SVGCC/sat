<?php

namespace common\models;

use Yii;

class ApplicationPeriodTypeModel
{
    public static function getApplicationPeriodTypeByID($applicationperiodtypeid)
    {
        return  ApplicationPeriodType::find()
            ->where(
                [
                    'applicationperiodtypeid' => $applicationperiodtypeid,
                    'isdeleted' => 0
                ]
            )
            ->one();
    }

    public static function getApplicationPeriodTypeNmeByID($applicationperiodtypeid)
    {
        $model =  self::getApplicationPeriodTypeByID($applicationperiodtypeid);
        if ($model == true) {
            return $model->name;
        }
        return null;
    }
}
