<?php

namespace frontend\models;

use Yii;

class ApplicantModel
{
    public static function getFullName($applicant)
    {
        if ($applicant->middlename == false) {
            return "{$applicant->title} {$applicant->firstname} {$applicant->lastname}";
        } else {
            return "{$applicant->title} {$applicant->firstname} {$applicant->middlename} {$applicant->lastname}";
        }
    }
}
