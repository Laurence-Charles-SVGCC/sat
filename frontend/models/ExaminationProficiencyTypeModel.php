<?php

namespace frontend\models;

class ExaminationProficiencyTypeModel
{
    public static function getExaminationProficiencyTypeById($id)
    {
        return ExaminationProficiencyType::find()
        ->where(['examinationproficiencytypeid' => $id])
        ->one();
    }
}
