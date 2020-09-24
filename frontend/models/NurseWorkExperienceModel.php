<?php

namespace frontend\models;

class NurseWorkExperienceModel
{
    public static function getNurseWorkExperienceByPersonId($id)
    {
        return NurseWorkExperience::find()
        ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
        ->one();
    }
}
