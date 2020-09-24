<?php

namespace frontend\models;

class ExternalQualificationModel
{
    public static function getExternalQualificationById($id)
    {
        return ExternalQualification::find()
        ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
        ->one();
    }
}
