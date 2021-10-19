<?php

namespace common\models;

use Yii;

class StudentHoldModel
{
    public static function getStudentHoldByID($id)
    {
        return StudentHold::find()->where(["studentholdid" => $id])->one();
    }


    public static function getFinancialHoldsByPersonID($id)
    {
        return StudentHold::find()
            ->innerJoin(
                "student_registration",
                "`student_hold`.`studentregistrationid` = `student_registration`.`studentregistrationid`"
            )
            ->innerJoin(
                "hold_type",
                "`student_hold`.`holdtypeid` = `hold_type`.`holdtypeid`"
            )
            ->where(
                [
                    "student_registration.personid" => $id,
                    "hold_type.holdcategoryid" => 1,
                    "student_hold.isactive" => 1,
                    "student_hold.isdeleted" => 0
                ]
            )
            ->all();
    }


    public static function prepareFormattedStudentFinancialHolds(
        $financialHolds
    ) {
        $data = array();
        if ($financialHolds == true) {
            foreach ($financialHolds as $financialHold) {
                $data[] =
                    self::formatStudentHoldDetailsIntoAssociativeArray(
                        $financialHold
                    );
            }
        }
        return $data;
    }


    public static function formatStudentHoldDetailsIntoAssociativeArray($hold)
    {
        $data = array();
        if ($hold == true) {
            $data["id"] = $hold->studentholdid;
            $data["studentRegistrationID"] = $hold->studentregistrationid;

            $appliedBy = EmployeeModel::getEmployeeFullName($hold->appliedby);
            $data["appliedBy"] = $appliedBy;

            $dateApplied = date_format(new \DateTime($hold->dateapplied), "F j, Y");
            $data["dateApplied"] = $dateApplied;

            $data["appliedDetails"] = "{$appliedBy} - {$dateApplied}";

            $resolvedBy = EmployeeModel::getEmployeeFullName($hold->resolvedby);
            if ($resolvedBy == true) {
                $data["resolvedBy"] = $resolvedBy;
            } else {
                $data["resolvedBy"] = "";
            }

            if ($hold->dateresolved == true) {
                $data["dateresolved"] =
                    date_format(new \DateTime($hold->dateresolved), "F j, Y");
            } else {
                $data["dateresolved"] = "";
            }

            $resolvedDetails = "";
            if ($resolvedBy == true) {
                $resolvedDetails .= $resolvedBy;
                if ($hold->dateresolved == true) {
                    $resolvedDetails .=
                        " - "
                        . date_format(new \DateTime($hold->dateresolved), "F j, Y");
                }
            }
            $data["resolvedDetails"] = $resolvedDetails;

            if ($hold->wasnotified == 1) {
                $data["notificationStatus"] = "Sent";
            } else {
                $data["notificationStatus"] = "Not Sent";
            }

            $data["holdTypeID"] = $hold->holdtypeid;
            $data["holdName"] =
                HoldTypeModel::getHoldTypeNameByID($hold->holdtypeid);

            if ($hold->holdstatus == 1) {
                $data["holdStatus"] = "Active";
            } else {
                $data["holdStatus"] = "Resolved";
            }

            $data["holdDetails"] = $hold->details;

            $data["registrationDetails"] =
                StudentRegistrationModel::generateRegistrationDescription(
                    $hold->studentregistrationid
                );
        }
        return $data;
    }


    public static function reactivateHold($studentHold, $userID)
    {
        $studentHold->holdstatus = 1;
        $studentHold->appliedby = $userID;
        $studentHold->dateapplied = date("Y-m-d");
        $studentHold->wasnotified = 0;
        $studentHold->resolvedby = null;
        $studentHold->dateresolved = null;
        return $studentHold->save();
    }


    public static function resolveHold($studentHold, $userID)
    {
        $studentHold->holdstatus = 0;
        $studentHold->resolvedby = $userID;
        $studentHold->dateresolved = date("Y-m-d");
        return $studentHold->save();
    }

    public static function deleteHold($studentHold, $userID)
    {
        $studentHold->isdeleted = 1;
        $studentHold->isactive = 0;
        $studentHold->holdstatus = 0;
        $studentHold->resolvedby = $userID;
        $studentHold->dateresolved = date("Y-m-d");
        return $studentHold->save();
    }


    public static function processPublishingRequest($hold, $notificationForm)
    {
        $holdType = HoldTypeModel::getHoldTypeByID($hold->holdtypeid);

        $studentRegistration =
            StudentRegistrationModel::getStudentRegistrationByID(
                $hold->studentregistrationid
            );

        $user = UserModel::findUserByID($studentRegistration->personid);
        $userFullname = UserModel::getUserFullname($user);

        $feedback = Yii::$app->mailer
            ->compose(
                'holds/financial-hold-notification.php',
                [
                    "notificationForm" => $notificationForm,
                    "holdType" => $holdType,
                    "userFullname" => $userFullname
                ]
            )
            ->setFrom(Yii::$app->params['bursaryEmail'])
            ->setTo($user->email)
            ->setSubject('Financial Hold Notification')
            ->send();

        if ($feedback == true) {
            $hold->wasnotified = 1;
            return $hold->save();
            return true;
        }

        return false;
    }
}
