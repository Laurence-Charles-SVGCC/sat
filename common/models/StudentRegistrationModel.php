<?php

namespace common\models;

use Yii;

class StudentRegistrationModel
{
    public static function generateRegistrationDescription($id)
    {
        $registration = Yii::$app->db->createCommand(
            "SELECT student_registration.studentregistrationid AS 'studentregistrationid',"
                . " qualification_type.abbreviation AS 'qualification',"
                . " programme_catalog.name AS 'programmename',"
                . " programme_catalog.specialisation AS 'specialisation',"
                . " academic_year.title AS 'year-title',"
                . " applicant_intent.name AS 'applicant-intent-name'"
                . " FROM student_registration"
                . " JOIN academic_offering"
                . " ON student_registration.academicofferingid = academic_offering.academicofferingid"
                . " JOIN programme_catalog"
                . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                . " JOIN qualification_type"
                . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                . " JOIN academic_year"
                . " ON academic_offering.academicyearid = academic_year.academicyearid"
                . " JOIN applicant_intent"
                . " ON academic_year.applicantintentid = applicant_intent.applicantintentid"
                . " WHERE student_registration.studentregistrationid = {$id};"
        )
            ->queryOne();

        if ($registration == true) {
            $qualification = $registration["qualification"];
            $programmeName = $registration["programmename"];
            $specialisation = $registration["specialisation"];
            $applicantIntentName = $registration["applicant-intent-name"];
            $yearTitle = $registration["year-title"];
            if ($qualification == "CAPE") {
                return "{$applicantIntentName} ({$yearTitle})- {$programmeName}";
            } elseif ($qualification != "CAPE" && $specialisation == true) {
                return "{$applicantIntentName} ({$yearTitle})- {$qualification} {$programmeName} ({$specialisation})";
            } elseif ($qualification != "CAPE" && $specialisation == false) {
                return "{$applicantIntentName} ({$yearTitle})- {$qualification} {$programmeName}";
            }
        }
        return null;
    }


    public static function getStudentRegistrationByID($id)
    {
        return StudentRegistration::find()
            ->where(["studentregistrationid" => $id])
            ->one();
    }


    public static function getActiveStudentRegistrationByPersonID($personID)
    {
        return StudentRegistration::find()
            ->where(["personid" => $personID, "isactive" => 1])
            ->one();
    }


    public static function getStudentRegistrationsByPersonID($personID)
    {
        return StudentRegistration::find()
            ->where(["personid" => $personID, "isdeleted" => 0])
            ->all();
    }


    public static function formatStudentRegistrationsIntoAssociativeArray(
        $studentRegistrations
    ) {
        $listing = array();
        foreach ($studentRegistrations as $studentRegistration) {
            $item = array();
            $item["id"] = $studentRegistration->studentregistrationid;

            $item["name"] =
                self::generateRegistrationDescription(
                    $studentRegistration->studentregistrationid
                );

            $listing[] = $item;
        }
        return $listing;
    }


    public static function getRegistrationsByPersonId($id)
    {
        return StudentRegistration::find()
            ->where(["personid" => $id, "isdeleted" => 0])
            ->all();
    }


    public static function getApplication($studentRegistration)
    {
        return
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
    }
}
