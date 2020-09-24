<?php

namespace app\models;

use Yii;

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
}
