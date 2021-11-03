<?php

namespace common\models;


class CsecCentreModel
{
    public static function getCsecCentreById($id)
    {
        return CsecCentre::find()->where(['cseccentreid' => $id])->one();
    }


    public static function centreApplicantsVerified(
        $cseccentreid = null,
        $external = false
    ) {
        if ($external == true) {
            $applicants = Applicant::find()
                ->innerJoin(
                    'application',
                    '`applicant`.`personid` = `application`.`personid`'
                )
                ->innerJoin(
                    'academic_offering',
                    '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`'
                )
                ->innerJoin(
                    'application_period',
                    '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`'
                )
                ->where([
                    'applicant.isexternal' => 1,
                    'applicant.isactive' => 1,
                    'applicant.isdeleted' => 0,
                    'application.isactive' => 1,
                    'application.isdeleted' => 0,
                    'application.applicationstatusid' => [
                        2, 3, 4, 5, 6, 7, 8, 9, 10
                    ],
                    'academic_offering.isactive' => 1,
                    'academic_offering.isdeleted' => 0,
                    'application_period.iscomplete' => 0,
                    'application_period.isactive' => 1,
                ])
                ->groupBy('applicant.personid')
                ->all();
        } else {
            $applicants = Application::find()
                ->innerJoin(
                    'applicant',
                    '`applicant`.`personid` = `application`.`personid`'
                )
                ->innerJoin(
                    'csec_qualification',
                    '`csec_qualification`.`personid` = `application`.`personid`'
                )
                ->innerJoin(
                    'csec_centre',
                    '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`'
                )
                ->innerJoin(
                    'academic_offering',
                    '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`'
                )
                ->innerJoin(
                    'application_period',
                    '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`'
                )
                ->where([
                    'applicant.isexternal' => 0,
                    'applicant.isactive' => 1,
                    'applicant.isdeleted' => 0,
                    'csec_centre.cseccentreid' => $cseccentreid,
                    'csec_qualification.isverified' => 1,
                    'csec_qualification.isactive' => 1,
                    'csec_qualification.isdeleted' => 0,
                    'application_period.iscomplete' => 0,
                    'application_period.isactive' => 1,
                    'application.isdeleted' => 0,
                    'application.applicationstatusid' => [
                        2, 3, 4, 5, 6, 7, 8, 9, 10
                    ],
                    'academic_offering.isdeleted' => 0
                ])
                ->groupBy('application.personid')
                ->all();
        }

        $eligible = array();
        foreach ($applicants as $applicant) {
            if ($external == true) {
                if (ApplicantModel::isExternalApplicantVerified($applicant) == true) {
                    $eligible[] = $applicant;
                    continue;
                }
            } else {
                if (ApplicantModel::isNonExternalApplicantVerified($applicant) == true) {
                    $eligible[] = $applicant;
                    continue;
                }
            }
        }

        return $eligible;
    }
}
