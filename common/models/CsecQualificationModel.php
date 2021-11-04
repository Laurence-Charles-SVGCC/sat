<?php

namespace common\models;

class CsecQualificationModel
{
    public static function getCsecQualifications($applicant)
    {
        return CsecQualification::find()
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