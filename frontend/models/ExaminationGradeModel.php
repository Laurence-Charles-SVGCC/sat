<?php

namespace frontend\models;

class ExaminationGradeModel
{
    public static function getExaminationGradeById($id)
    {
        return ExaminationGrade::find()
        ->where(['examinationgradeid' => $id])
        ->one();
    }
}
