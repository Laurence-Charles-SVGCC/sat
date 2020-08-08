<?php

namespace frontend\models;

use Yii;

class CsecQualificationModel
{
    public static function getVerifiedCsecQualificationsByPersonId($personId)
    {
        return CsecQualification::find()
        ->where(
            [
              'personid' => $personId,
              'isverified' => 1,
              'isactive' => 1,
              'isdeleted' => 0
            ]
        )
        ->all();
    }
}
