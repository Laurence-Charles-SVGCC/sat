<?php

namespace common\models;

class ApplicationCapesubjectModel
{
    public static function getApplicationCapeSubjectsByApplicationID($id)
    {
        return ApplicationCapesubject::find()
            ->where(['applicationid' => $id])
            ->all();
    }
}
