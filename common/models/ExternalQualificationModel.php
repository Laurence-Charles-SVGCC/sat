<?php

namespace common\models;

class ExternalQualificationModel
{
    public static function getExternalQualifications($applicant)
    {
        return ExternalQualification::find()
            ->where([
                'personid' => $applicant->personid,
                'isactive' => 1,
                'isdeleted' => 0
            ])
            ->all();
    }


    public static function allQualificationsVerified($qualifications)
    {
        if ($qualifications == false) {
            return false;
        } else {
            foreach ($qualifications as $qualification) {
                if (
                    $qualification->isverified == 0
                    || $qualification->isqueried == 1
                ) {
                    return false;
                }
            }
            return true;
        }
    }
}
