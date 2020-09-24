<?php

namespace frontend\models;

class MedicalConditionModel
{
    public static function getMedicalConditionsById($id)
    {
        return MedicalCondition::find()
        ->where(['personid'=> $id])
        ->all();
    }
}
