<?php

namespace frontend\models;

class NursingAdditionalInfoModel
{
    public static function getNursingInfoByPersonId($id)
    {
        return NursingAdditionalInfo::find()
        ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
        ->one();
    }

    public static function hasChildren($nursingInfo)
    {
        if ($nursingInfo == true && $nursingInfo->childcount > 0) {
            return true;
        }
        return false;
    }

    public static function isMember($nursingInfo)
    {
        if ($nursingInfo == true && $nursingInfo->ismember == 1) {
            return true;
        }

        return false;
    }

    public static function hasOtherApplications($nursingInfo)
    {
        if ($nursingInfo == true && $nursingInfo->hasotherapplications == 1) {
            return true;
        }
        return false;
    }

    public static function hasPreviousApplication($nursingInfo)
    {
        if ($nursingInfo == true && $nursingInfo->repeatapplicant) {
            return true;
        }
        return false;
    }
}
