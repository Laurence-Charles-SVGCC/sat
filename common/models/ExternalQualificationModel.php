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


    public static function hasQualificationsQueried($qualifications)
    {
        if ($qualifications == false) {
            return false;
        } else {
            foreach ($qualifications as $qualification) {
                if ($qualification->isqueried == 1) {
                    return true;
                }
            }
            return false;
        }
    }


    public static function hasQualificationsPending($qualifications)
    {
        if ($qualifications == false) {
            return false;
        } else {
            foreach ($qualifications as $qualification) {
                if (
                    $qualification->isverified == 0
                    && $qualification->isqueried == 0
                ) {
                    return true;
                }
            }
            return false;
        }
    }


    public static function qualificationsClassifiedAsPending($qualifications)
    {
        if (
            self::hasQualificationsQueried($qualifications) == false
            && self::hasQualificationsPending($qualifications) == true
        ) {
            return true;
        } else {
            return false;
        }
    }
}
