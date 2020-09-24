<?php

namespace frontend\models;

class NursePriorCertificationModel
{
    public static function getNursePriorCertificationsByPersonId($id)
    {
        return NursePriorCertification::find()
        ->where(['personid'=> $id, 'isactive' => 1, 'isdeleted' => 0])
        ->all();
    }
}
