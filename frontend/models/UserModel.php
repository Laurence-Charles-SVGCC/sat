<?php

namespace frontend\models;

use Yii;

use common\models\User;

class UserModel
{
    public static function getUserDivision($user)
    {
        $employeeDepartment =
        EmployeeDepartment::find()
        ->where(['personid' => $user->personid])
        ->one();

        if ($employeeDepartment) {
            $department = $employeeDepartment->getDepartment()->one();
            if ($department) {
                return $department->divisionid;
            }
        }
        return false;
    }

    public static function getUserById($id)
    {
        return User::find()
        ->where(['personid' => $id])
        ->one();
    }

    public static function getUserFullname($user)
    {
        if ($user->persontypeid == 1) {   // if applicant
            $applicant = ApplicantModel::getApplicantById($user->personid);
            return ApplicantModel::getApplicantFullName($applicant);
        } elseif ($user->persontypeid == 2) {   // if student
            $student = StudentModel::getStudentByPersonid($user->personid);
            return StudentModel::getStudentFullName($student);
        } elseif ($user->persontypeid == 3) {   // if employee
            return EmployeeModel::getEmployeeFullName($user->personid);
        }
    }
}
