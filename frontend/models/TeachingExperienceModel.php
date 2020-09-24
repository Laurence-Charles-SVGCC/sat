<?php

namespace frontend\models;

class TeachingExperienceModel
{
    public static function getTeachingExperiencesByPersonId($id)
    {
        return TeachingExperience::find()
        ->where(['personid'=> $id, 'isactive' => 1, 'isdeleted' => 0])
        ->all();
    }
}
