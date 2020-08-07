<?php

namespace frontend\models;

use Yii;

class CsecQualificationModel
{
    public static function getVerifiedCsecQualificationsByPersonId($personID)
    {
        return CsecQualification::find()
        ->where(
            [
            'personid' => $personid,
            'isverified' => 1,
            'isactive' => 1,
            'isdeleted' => 0
          ]
        )
        ->all();
    }
}
