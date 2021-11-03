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


    public static function prepareSuccessfulApplicantFeeReport(
        $personid,
        $academicOffering
    ) {
        $data = array();
        $applicableBillingCharges =
            BillingChargeModel::getEnrollmentBillingChargesForAcademicOffering(
                $academicOffering
            );

        foreach ($applicableBillingCharges as $billingCharge) {
            $charge = array();
            $customer = UserModel::getUserById($personid);
            $charge["username"] = $customer->username;
            $charge["billingChargeId"] = $billingCharge->id;
            $charge["customerId"] = $personid;

            $billingType =
                BillingTypeModel::getBillingTypeByID(
                    $billingCharge->billing_type_id
                );
            $charge["fee"] = $billingType->name;

            $charge["cost"] = $billingCharge->cost;

            $totalPaid =
                BillingModel::calculateTotalPaidOnBillingCharge(
                    $billingCharge->id,
                    $personid
                );
            $charge["totalPaid"] = $totalPaid;

            $outstanding = $billingCharge->cost - $totalPaid;
            $charge["outstanding"] = $outstanding;

            if ($totalPaid == 0) {
                $charge["status"] = "Unpaid";
            } elseif ($totalPaid == $billingCharge->cost) {
                $charge["status"] = "Paid In Full";
            } elseif ($totalPaid < $billingCharge->cost) {
                $charge["status"] = "Balance = {$outstanding}";
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
}
