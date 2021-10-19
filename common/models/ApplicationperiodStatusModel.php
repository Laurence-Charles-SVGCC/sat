<?php

namespace common\models;

class ApplicationperiodStatusModel
{
    public static function getApplicationPeriodStatusByID(
        $applicationperiodstatusid
    ) {
        return ApplicationperiodStatus::find()
            ->where(
                [
                    'applicationperiodstatusid' => $applicationperiodstatusid,
                    'isdeleted' => 0
                ]
            )
            ->one();
    }


    public static function getApplicationPeriodStatusNameByID(
        $applicationperiodstatusid
    ) {
        $model = self::getApplicationPeriodStatusByID($applicationperiodstatusid);
        if ($model == true && $model->name == "closed") {
            return "Closed to applicants";
        } elseif ($model == true && $model->name == "open") {
            return "Open to applicants";
        }
        return null;
    }
}
