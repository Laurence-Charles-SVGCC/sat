<?php

namespace frontend\models;

use Yii;

class ApplicantModel
{
    public static function getApplicantById($id)
    {
        return Applicant::find()->where(['personid' => $id])->one();
    }

    public static function getFullName($applicant)
    {
        if ($applicant->middlename == false) {
            return "{$applicant->title} {$applicant->firstname} {$applicant->lastname}";
        } else {
            return "{$applicant->title} {$applicant->firstname} {$applicant->middlename} {$applicant->lastname}";
        }
    }


    public static function isPending($applicantUserId, $verifiedApplications)
    {
        $currentApplication =
        ApplicationModel::getCurrentApplication(
            $applicantUserId,
            $verifiedApplications
        );

        if ($currentApplication == true
        && $currentApplication->applicationstatusid == 3) {
            return true;
        }
        return false;
    }


    public static function isShortlisted($applicantUserId, $verifiedApplications)
    {
        $currentApplication =
        ApplicationModel::getCurrentApplication(
            $applicantUserId,
            $verifiedApplications
        );

        if ($currentApplication == true
        && $currentApplication->applicationstatusid == 4) {
            return true;
        }
        return false;
    }


    public static function isRejected($applicantUserId, $verifiedApplications)
    {
        $currentApplication =
        ApplicationModel::getCurrentApplication(
            $applicantUserId,
            $verifiedApplications
        );

        if ($currentApplication == true
        && $currentApplication->applicationstatusid == 6) {
            return true;
        }
        return false;
    }


    public static function isBorderlined($applicantUserId, $verifiedApplications)
    {
        $currentApplication =
        ApplicationModel::getCurrentApplication(
            $applicantUserId,
            $verifiedApplications
        );

        if ($currentApplication == true
        && $currentApplication->applicationstatusid == 7) {
            return true;
        }
        return false;
    }


    public static function isInterviewOffer($applicantUserId, $verifiedApplications)
    {
        $currentApplication =
        ApplicationModel::getCurrentApplication(
            $applicantUserId,
            $verifiedApplications
        );

        if ($currentApplication == true
        && $currentApplication->applicationstatusid == 8) {
            return true;
        }
        return false;
    }


    public static function isOffer($applicantUserId, $verifiedApplications)
    {
        $currentApplication =
        ApplicationModel::getCurrentApplication(
            $applicantUserId,
            $verifiedApplications
        );

        if ($currentApplication == true
        && $currentApplication->applicationstatusid == 9) {
            return true;
        }
        return false;
    }


    public static function isRejectedConditionalOffer(
        $applicantUserId,
        $verifiedApplications
    ) {
        $currentApplication =
        ApplicationModel::getCurrentApplication(
            $applicantUserId,
            $verifiedApplications
        );

        if ($currentApplication == true
        && $currentApplication->applicationstatusid == 10) {
            return true;
        }
        return false;
    }


    public static function isAbandoned($applicantUserId, $verifiedApplications)
    {
        $currentApplication =
        ApplicationModel::getCurrentApplication(
            $applicantUserId,
            $verifiedApplications
        );

        if ($currentApplication == true
        && $currentApplication->applicationstatusid == 11) {
            return true;
        }
        return false;
    }


    public static function applicantHasBeenIssuedRejection(
        $applicantUserId,
        $verifiedApplications
    ) {
        $applicationIds = array();
        foreach ($verifiedApplications as $application) {
            $applicationIds[] = $application->applicationid;
        }

        $rejections =
        Rejection::find()
        ->innerJoin(
            'rejection_applications',
            '`rejection`.`rejectionid` = `rejection_applications`.`rejectionid`'
        )
        ->where([
            'rejection.ispublished' => 1,
            'rejection.isactive' => 1,
            'rejection.isdeleted' => 0,
            'rejection_applications.applicationid' => $applicationIds,
            'rejection_applications.isdeleted' => 0
        ])
        ->all();

        if ($rejections == true) {
            return true;
        }

        return false;
    }


    public static function getProcessedApplicantNotification(
        $applicantUserId,
        $currentUser
    ) {
        $currentUserRole =
        AuthorizationModel::getUserRoleName($currentUser->personid);

        $currentUserDivisionId = UserModel::getUserDivision($currentUser);

        $verifiedApplications =
        ApplicationModel::getVerifiedApplicationsByPersonId($applicantUserId);

        if ($applicantUserId == false
        || $currentUser == false
        || $currentUserDivisionId == false
        || $verifiedApplications == false) {
            return false;
        }

        $targetApplication =
        ApplicationModel::getCurrentApplication(
            $applicantUserId,
            $verifiedApplications
        );

        if (self::isRejected($applicantUserId, $verifiedApplications) == true
        && ApplicationModel::hasPublishedRejection($verifiedApplications)
        == false) {
            return "This applicant has been rejected from all of their programme"
            . " choices.  Divisional processing restrictions have therefore been"
            . " removed from this application. The Registrar and any Deans/Deputy "
            . " Deans are permitted to issue a Custom Offer to this applicant.";
        } elseif (self::isRejected($applicantUserId, $verifiedApplications) == true
          && ApplicationModel::hasPublishedRejection($verifiedApplications)
          == true
          && $currentUserRole == "Registrar") {
            return "This applicant has been rejected from all of their programme"
            . " choices and has been issued a rejection response. However as"
            . " Registrar you are still permitted to issue a Custom Offer to this"
            . " applicant.";
        } elseif (self::isRejected($applicantUserId, $verifiedApplications) == true
          && ApplicationModel::hasPublishedRejection($verifiedApplications)
          == true
          && $currentUserRole != "Registrar") {
            return "This applicant has been rejected from all of their programme"
            . " choices and has been issued a rejection response. Only the"
            . " Registrar is authorized to issue a Custom Offer to this applicant"
            . " at this time.";
        } elseif (ApplicationModel::hasPublishedOffer($verifiedApplications)
        == false
          && (
              $currentUserDivisionId == 1
              || ($currentUserDivisionId != 1 && $targetApplication->divisionid == $currentUserDivisionId)
          )
        ) {
            // return "You are permitted to process applicant";
            return null;
        } elseif (ApplicationModel::hasPublishedOffer($verifiedApplications)
        == true) {
            return "  This applicant would have already been sent an Acceptance"
            . " Package; therefore no further action can be taken on this"
            . " application. If you do wish to change programme choice, please"
            . " submit transfer request to Registrar.";
        } elseif (ApplicationModel::hasPublishedOffer($verifiedApplications) == false
          && $currentUserDivisionId != 1
          && $targetApplication->divisionid != $currentUserDivisionId) {
            return "You currently have 'View-Only' access to this applicant because"
            . " the current programme choice under selection is being offered by"
            . " another division.";
        }
        return null;
    }
    public static function hasMiddleName($applicant)
    {
        if ($applicant->middlename == true) {
            return true;
        }
        return false;
    }

    public static function getNameWithMiddleName($applicant)
    {
        return "{$applicant->title} "
        . "{$applicant->firstname} "
        . "{$applicant->middlename} "
        . "{$applicant->lastname}";
    }

    public static function getNameWithoutMiddleName($applicant)
    {
        return "{$applicant->title} "
        . "{$applicant->firstname} "
        . "{$applicant->lastname}";
    }

    public static function getApplicantFullName($applicant)
    {
        if ($applicant == false) {
            return null;
        } elseif ($applicant == true && self::hasMiddleName($applicant) == true) {
            return self::getNameWithMiddleName($applicant);
        } elseif ($applicant == true && self::hasMiddleName($applicant) == false) {
            return self::getNameWithoutMiddleName($applicant);
        }
    }

    public static function hasApplicationsForActiveApplicationPeriod($applicant)
    {
        $inScopeApplicationPeriods =
        ApplicationPeriod::find()
        ->innerJoin(
            'academic_offering',
            '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`'
        )
        ->innerJoin(
            'application',
            '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`'
        )
        ->where([
            'application.personid' => $applicant->personid,
            'application.isactive' => 1,
            'application.isdeleted' => 0,
            'academic_offering.isactive' => 1,
            'academic_offering.isdeleted' => 0,
            'application_period.isactive' => 1,
            'application_period.isdeleted' => 0,
            'application_period.iscomplete' => 0
        ])
        ->all();

        if ($inScopeApplicationPeriods == true) {
            return true;
        }
        return false;
    }
}
