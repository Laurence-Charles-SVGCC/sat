<?php

namespace common\models;

use Yii;

class ApplicantModel
{
    public static function getApplicantByPersonid($id)
    {
        return Applicant::find()->where(["personid" => $id])->one();
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


    public static function hasMiddleName($applicant)
    {
        if ($applicant->middlename == true) {
            return true;
        }
        return false;
    }


    public static function generateDisplayPicture($applicant)
    {
        if ($applicant->photopath == true) {
            return "img/profile-pictures/students/{$applicant->personid}/{$applicant->photopath}";
        } elseif (
            $applicant->photopath == false
            && $applicant->gender === "male"
        ) {
            return "img/profile-pictures/generic/avatar_male(150_150).png";
        } elseif (
            $applicant->photopath == false
            && $applicant->gender === "female"
        ) {
            return "img/profile-pictures/generic/avatar_male(150_150).png";
        } else {
            return null;
        }
    }


    public static function getApplicantApplicationPeriodID($applicant)
    {
        $currentApplications =
            ApplicationModel::getActiveApplicationsByPersonID($applicant->personid);

        if (!empty($currentApplications) == true) {
            $academicOffering =
                AcademicOfferingModel::getAcademicOfferingByID(
                    $currentApplications[0]->academicofferingid
                );
            if ($academicOffering == true) {
                return $academicOffering->applicationperiodid;
            }
        }
        return false;
    }


    public static function getUnenrolledSuccessfulApplicantProgramme($applicant)
    {
        $acceptedApplication = ApplicationModel::getSuccessfulApplication($applicant);
        return ApplicationModel::getFormattedProgrammeChoice($acceptedApplication);
    }

    public static function getBillingChargeCatalog($application)
    {
        $cohortCharges =
            BillingChargeModel::getCohortBillingChargesPayableOnEnrollment(
                $application
            );

        $programmeCharges =
            BillingChargeModel::getProgrammeBillingChargesPayableOnEnrollment(
                $application
            );

        return array_merge($cohortCharges, $programmeCharges);
    }



    public static function prepareSuccessfulApplicantFeeReport($application)
    {
        $data = array();
        $applicableBillingCharges = self::getBillingChargeCatalog($application);

        foreach ($applicableBillingCharges as $billingCharge) {
            $charge = array();
            $customer = UserModel::getUserById($application->personid);
            $charge["username"] = $customer->username;
            $charge["billingChargeId"] = $billingCharge->id;
            $charge["customerId"] = $application->personid;

            $billingType =
                BillingTypeModel::getBillingTypeByID(
                    $billingCharge->billing_type_id
                );
            $charge["fee"] = $billingType->name;

            $charge["cost"] = number_format($billingCharge->cost, 2);

            $totalPaid =
                BillingModel::calculateTotalPaidOnBillingCharge(
                    $billingCharge->id,
                    $application->personid
                );
            $charge["totalPaid"] = number_format($totalPaid, 2);

            $outstanding = $billingCharge->cost - $totalPaid;
            $charge["outstanding"] = number_format($outstanding, 2);

            if ($totalPaid == 0) {
                $charge["status"] = "Unpaid";
            } elseif ($totalPaid == $billingCharge->cost) {
                $charge["status"] = "Paid In Full";
            } elseif ($totalPaid < $billingCharge->cost) {
                $outstandingFormatted = number_format($outstanding, 2);;
                $charge["status"] = "Balance = {$outstandingFormatted}";
            }
            $data[] = $charge;
        }
        return $data;
    }


    public static function isApplicantExternal($applicant)
    {
        if ($applicant->isexternal == true) {
            return true;
        } else {
            return false;
        }
    }


    public static function allApplicationsVerified($applicant)
    {
        $applications =
            ApplicationModel::getActiveApplicationsByPersonID($applicant->personid);

        if (empty($applications)) {
            return false;
        } else {
            foreach ($applications as $application) {
                if ($application->applicationstatusid == 2) {
                    return false;
                }
            }
            return true;
        }
    }


    public static function isExternalApplicantVerified($applicant)
    {
        $postSecondaryQualifications =
            PostSecondaryQualificationModel::getPostSecondaryQualifications(
                $applicant
            );

        $externalQualifications =
            ExternalQualificationModel::getExternalQualifications(
                $applicant
            );

        if (
            self::isApplicantExternal($applicant) == true
            && empty($postSecondaryQualifications)
            && empty($externalQualifications)
            && self::allApplicationsVerified($applicant) == true
        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == true
            && empty($postSecondaryQualifications)
            && !empty($externalQualifications)
            && ExternalQualificationModel::allQualificationsVerified(
                $externalQualifications
            ) == true
        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == true
            && empty($externalQualifications)
            && !empty($postSecondaryQualifications)
            && PostSecondaryQualificationModel::allQualificationsVerified(
                $postSecondaryQualifications
            ) == true
        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == false
            && !empty($externalQualifications)
            && ExternalQualificationModel::allQualificationsVerified(
                $externalQualifications
            ) == true
            && !empty($postSecondaryQualifications)
            && PostSecondaryQualificationModel::allQualificationsVerified(
                $postSecondaryQualifications
            ) == true
        ) {
            return true;
        } else {
            return false;
        }
    }


    public static function isNonExternalApplicantVerified($applicant)
    {
        $csecQualifications =
            CsecQualificationModel::getCsecQualifications($applicant);

        $postSecondaryQualifications =
            PostSecondaryQualificationModel::getPostSecondaryQualifications(
                $applicant
            );

        $externalQualifications =
            ExternalQualificationModel::getExternalQualifications(
                $applicant
            );

        if (
            self::isApplicantExternal($applicant) == false
            && !empty($csecQualifications)
            && CsecQualificationModel::allQualificationsVerified(
                $externalQualifications
            ) == true
            && empty($postSecondaryQualifications)
            && empty($externalQualifications)

        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == false
            && !empty($csecQualifications)
            && CsecQualificationModel::allQualificationsVerified(
                $externalQualifications
            ) == true
            && empty($postSecondaryQualifications)
            && !empty($externalQualifications)
            && ExternalQualificationModel::allQualificationsVerified(
                $externalQualifications
            ) == true
        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == false
            && !empty($csecQualifications)
            && CsecQualificationModel::allQualificationsVerified(
                $externalQualifications
            ) == true
            && empty($externalQualifications)
            && !empty($postSecondaryQualifications)
            && PostSecondaryQualificationModel::allQualificationsVerified(
                $postSecondaryQualifications
            ) == true
        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == false
            && !empty($csecQualifications)
            && CsecQualificationModel::allQualificationsVerified(
                $externalQualifications
            ) == true
            && !empty($externalQualifications)
            && ExternalQualificationModel::allQualificationsVerified(
                $externalQualifications
            ) == true
            && !empty($postSecondaryQualifications)
            && PostSecondaryQualificationModel::allQualificationsVerified(
                $postSecondaryQualifications
            ) == true
        ) {
            return true;
        } else {
            return false;
        }
    }


    public static function isExternalApplicantPending($applicant)
    {
        $postSecondaryQualifications =
            PostSecondaryQualificationModel::getPostSecondaryQualifications(
                $applicant
            );

        $externalQualifications =
            ExternalQualificationModel::getExternalQualifications(
                $applicant
            );

        if (
            self::isApplicantExternal($applicant) == true
            && empty($postSecondaryQualifications)
            && empty($externalQualifications)
            && self::allApplicationsVerified($applicant) == false
        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == true
            && empty($postSecondaryQualifications)
            && !empty($externalQualifications)
            && ExternalQualificationModel::qualificationsClassifiedAsPending(
                $externalQualifications
            ) == true
        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == true
            && empty($externalQualifications)
            && !empty($postSecondaryQualifications)
            && PostSecondaryQualificationModel::qualificationsClassifiedAsPending(
                $postSecondaryQualifications
            ) == true
        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == false
            && !empty($externalQualifications)
            && ExternalQualificationModel::qualificationsClassifiedAsPending(
                $externalQualifications
            ) == true
            && !empty($postSecondaryQualifications)
            && PostSecondaryQualificationModel::qualificationsClassifiedAsPending(
                $postSecondaryQualifications
            ) == true
        ) {
            return true;
        } else {
            return false;
        }
    }


    public static function isNonExternalApplicantPending($applicant)
    {
        $csecQualifications =
            CsecQualificationModel::getCsecQualifications($applicant);

        $postSecondaryQualifications =
            PostSecondaryQualificationModel::getPostSecondaryQualifications(
                $applicant
            );

        $externalQualifications =
            ExternalQualificationModel::getExternalQualifications(
                $applicant
            );

        if (
            self::isApplicantExternal($applicant) == false
            && !empty($csecQualifications)
            && CsecQualificationModel::qualificationsClassifiedAsPending(
                $externalQualifications
            ) == true
            && empty($postSecondaryQualifications)
            && empty($externalQualifications)

        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == false
            && !empty($csecQualifications)
            && CsecQualificationModel::qualificationsClassifiedAsPending(
                $externalQualifications
            ) == true
            && empty($postSecondaryQualifications)
            && !empty($externalQualifications)
            && ExternalQualificationModel::qualificationsClassifiedAsPending(
                $externalQualifications
            ) == true
        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == false
            && !empty($csecQualifications)
            && CsecQualificationModel::qualificationsClassifiedAsPending(
                $externalQualifications
            ) == true
            && empty($externalQualifications)
            && !empty($postSecondaryQualifications)
            && PostSecondaryQualificationModel::qualificationsClassifiedAsPending(
                $postSecondaryQualifications
            ) == true
        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == false
            && !empty($csecQualifications)
            && CsecQualificationModel::qualificationsClassifiedAsPending(
                $externalQualifications
            ) == true
            && !empty($externalQualifications)
            && ExternalQualificationModel::qualificationsClassifiedAsPending(
                $externalQualifications
            ) == true
            && !empty($postSecondaryQualifications)
            && PostSecondaryQualificationModel::qualificationsClassifiedAsPending(
                $postSecondaryQualifications
            ) == true
        ) {
            return true;
        } else {
            return false;
        }
    }


    public static function getExternalApplicantsForActivePeriods()
    {
        return Applicant::find()
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
    }


    public static function isExternalApplicantQueried($applicant)
    {
        $postSecondaryQualifications =
            PostSecondaryQualificationModel::getPostSecondaryQualifications(
                $applicant
            );

        $externalQualifications =
            ExternalQualificationModel::getExternalQualifications(
                $applicant
            );

        if (
            self::isApplicantExternal($applicant) == true
            && empty($postSecondaryQualifications)
            && !empty($externalQualifications)
            && ExternalQualificationModel::hasQualificationsQueried(
                $externalQualifications
            ) == true
        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == true
            && empty($externalQualifications)
            && !empty($postSecondaryQualifications)
            && PostSecondaryQualificationModel::hasQualificationsQueried(
                $postSecondaryQualifications
            ) == true
        ) {
            return true;
        } elseif (
            self::isApplicantExternal($applicant) == false
            && (!empty($externalQualifications) && ExternalQualificationModel::hasQualificationsQueried(
                $externalQualifications
            ) == true)
            || (!empty($postSecondaryQualifications)
                && PostSecondaryQualificationModel::hasQualificationsQueried(
                    $postSecondaryQualifications
                ) == true)
        ) {
            return true;
        } else {
            return false;
        }
    }


    public static function calculateEnrollmentFeesSummary($application)
    {
        $totalCost = 0;
        $totalPaid = 0;
        $totalDue = 0;
        $summary = array();

        $applicableBillingCharges = self::getBillingChargeCatalog($application);

        foreach ($applicableBillingCharges as $billingCharge) {
            $totalCost += $billingCharge->cost;

            $paidAmount =
                BillingModel::calculateTotalPaidOnBillingCharge(
                    $billingCharge->id,
                    $application->personid
                );
            $totalPaid += $paidAmount;

            $totalDue += $billingCharge->cost - $paidAmount;
        }

        $summary["totalCost"] = "$" . number_format($totalCost, 2);
        $summary["totalPaid"] = "$" . number_format($totalPaid, 2);
        $summary["totalDue"] = "$" . number_format($totalDue, 2);
        return $summary;
    }
}
