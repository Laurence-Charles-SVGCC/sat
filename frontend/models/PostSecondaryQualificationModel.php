<?php

namespace frontend\models;

class PostSecondaryQualificationModel
{
    public static function getPostSecondaryQualificationsById($id)
    {
        return PostSecondaryQualification::find()
        ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
        ->one();
    }
}
