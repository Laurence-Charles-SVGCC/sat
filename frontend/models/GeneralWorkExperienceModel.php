<?php

namespace frontend\models;

class GeneralWorkExperienceModel
{
    public static function getGeneralWorkExperiencesByPersonId($id)
    {
        return GeneralWorkExperience::find()
        ->where(['personid'=> $id, 'isactive' => 1, 'isdeleted' => 0])
        ->all();
    }
}
