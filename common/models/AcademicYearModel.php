<?php

namespace common\models;

use Yii;

class AcademicYearModel extends \yii\base\Model
{
    public static function getAcademicYearsByPeriod($period)
    {
        $applicantintentid = ApplicationPeriodModel::getApplicantIntent($period);

        return AcademicYear::find()
            ->where(
                [
                    'applicantintentid' => $applicantintentid, 'isactive' => 1,
                    'isdeleted' => 0
                ]
            )
            ->all();
    }


    public static function getAcademicYearByID($academicyearid)
    {
        return  AcademicYear::find()
            ->where(['academicyearid' => $academicyearid, 'isdeleted' => 0])
            ->one();
    }


    public static function getAcademicYearTitleByID($academicyearid)
    {
        $model =  self::getAcademicYearByID($academicyearid);
        if ($model == true) {
            return $model->title;
        }
        return null;
    }


    public static function prepreAcademicYearsListingForDropdonwList()
    {
        return array();
        // $years = array();

        // $academicYears =
        //     AcademicYear::find()
        //     ->where(['isactive' => 1, 'isdeleted' => 0])
        //     ->all();

        // foreach ($academicYears as $year) {
        //     $record = array();

        //     $startYear = substr($year->title, 0, 4);

        //     $applicantIntent =
        //         ApplicantIntent::find()
        //         ->where(['applicantintentid' => $year->applicantintentid])
        //         ->one();

        //     $applicants =
        //         ApplicantRegistrationModel::getApplicantRegistrationAccountsByYearIntent(
        //             $startYear,
        //             $year->applicantintentid
        //         );
        //     $applicantCount = count($applicants);

        //     $record["startYear"] = $startYear;
        //     $record["label"] = "{$applicantIntent->name} {$startYear} - ({$applicantCount} accounts)";
        //     $record["applicantintentid"] = $year->applicantintentid;
        //     $record["applicantCount"] = $applicantCount;
        //     $years[] = $record;
        // }

        // return $years;
    }
}
