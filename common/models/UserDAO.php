<?php

namespace common\models;

class UserDAO
{
    public static function getByEmail($email)
    {
        return User::find()->where(["email" => $email])->one();
    }

    public static function getByResetToken($token)
    {
        return User::find()->where(["resettoken" => $token])->one();
    }

    public static function getUserDivision($user)
    {
        $divisions =
            Division::find()
            ->innerJoin(
                'department',
                '`division`.`divisionid` = `department`.`divisionid`'
            )
            ->innerJoin(
                'employee_department',
                '`department`.`departmentid` = `employee_department`.`departmentid`'
            )
            ->where([
                'division.isactive' => 1,
                'division.isdeleted' => 0,
                'department.isactive' => 1,
                'department.isdeleted' => 0,
                'employee_department.personid' => $user->personid,
                'employee_department.isactive' => 1,
                'employee_department.isdeleted' => 0
            ])
            ->all();
        if (!empty($divisions)) {
            return $divisions[0]->divisionid;
        }
        return false;
    }
}
