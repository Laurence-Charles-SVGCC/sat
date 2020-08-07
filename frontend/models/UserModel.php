<?php

namespace frontend\models;

use Yii;

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
}
