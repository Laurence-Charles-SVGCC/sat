<?php

namespace frontend\models;

class ExaminationBodyModel
{
    public static function getExaminationBodyById($id)
    {
        return ExaminationBody::find()
        ->where(['examinationbodyid' => $id])
        ->one();
    }
}
