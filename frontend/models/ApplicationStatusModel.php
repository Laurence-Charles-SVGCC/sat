<?php

namespace frontend\models;

class ApplicationStatusModel
{
    public static function getApplicationStatusById($id)
    {
        return ApplicationStatus::find()
    ->where(['applicationstatusid' => $id])
    ->one();
    }

    public static function getApplicationStatusNameById($id)
    {
        $applicationStatus = self::getApplicationStatusById($id);
        if ($applicationStatus == true) {
            return $applicationStatus->name;
        }
        return false;
    }
}
