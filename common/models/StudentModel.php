<?php

namespace common\models;

class StudentModel
{
    public static function getStudentByPersonid($id)
    {
        return Student::find()->where(["personid" => $id])->one();
    }


    public static function getNameWithMiddleName($student)
    {
        return "{$student->title} "
            . "{$student->firstname} "
            . "{$student->middlename} "
            . "{$student->lastname}";
    }


    public static function getNameWithoutMiddleName($student)
    {
        return "{$student->title} {$student->firstname} {$student->lastname}";
    }


    public static function getStudentFullName($student)
    {
        if ($student == false) {
            return null;
        } elseif ($student == true && self::hasMiddleName($student) == true) {
            return self::getNameWithMiddleName($student);
        } elseif ($student == true && self::hasMiddleName($student) == false) {
            return self::getNameWithoutMiddleName($student);
        }
    }


    public static function hasMiddleName($student)
    {
        if ($student->middlename == true) {
            return true;
        }
        return false;
    }


    public static function getCurrentProgramme($student)
    {
        $activeRegistration =
            StudentRegistrationModel::getActiveStudentRegistrationByPersonID(
                $student->personid
            );

        $offer = OfferModel::getOfferById($activeRegistration->offerid);
        $currentApplication = OfferModel::getApplication($offer);
        return ApplicationModel::getFormattedProgrammeChoice($currentApplication);
    }

    public static function getActiveStudentByPersonid($id)
    {
        return Student::find()
            ->where(["personid" => $id, "isactive" => 1, "isdeleted" => 0])
            ->one();
    }


    public static function getStudentRegistrations($personid)
    {
        $registrations = array();

        $studentRegistrations =
            StudentRegistrationModel::getRegistrationsByPersonId($personid);

        foreach ($studentRegistrations as $registration) {
            $id = $registration->studentregistrationid;

            $academicOffering =
                AcademicOfferingModel::getAcademicOfferingByID(
                    $registration->academicofferingid
                );

            $programme =
                AcademicOfferingModel::getProgrammeName($academicOffering);

            $registrations[$id] = $programme;
        }

        return $registrations;
    }


    public static function prepareFeePaymentReportByRegistration(
        $studentRegistration
    ) {
        $data = array();

        $application =
            Application::find()
            ->innerJoin(
                'offer',
                '`application`.`applicationid` = `offer`.`applicationid`'
            )
            ->innerJoin(
                'student_registration',
                '`offer`.`offerid` = `student_registration`.`offerid`'
            )
            ->where([
                "application.isactive" => 1,
                "application.isdeleted" => 0,
                "student_registration.studentregistrationid" => $studentRegistration->studentregistrationid
            ])
            ->one();

        $applicableBillingCharges =
            BillingChargeModel::getFirstAndSecondYearBillingChargesForApplication(
                $application
            );

        foreach ($applicableBillingCharges as $billingCharge) {
            $charge = array();
            $customer = UserModel::getUserById($studentRegistration->personid);
            $charge["studentRegistrationId"] = $studentRegistration->studentregistrationid;
            $charge["username"] = $customer->username;
            $charge["billingChargeId"] = $billingCharge->id;
            $charge["customerId"] = $studentRegistration->personid;

            $billingType =
                BillingTypeModel::getBillingTypeByID(
                    $billingCharge->billing_type_id
                );
            $charge["fee"] = $billingType->name;

            $charge["cost"] = $billingCharge->cost;

            $totalPaid =
                BillingModel::calculateTotalPaidOnBillingCharge(
                    $billingCharge->id,
                    $studentRegistration->personid
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
}
