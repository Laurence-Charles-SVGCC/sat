<?php

namespace frontend\models;

class SubjectModel
{
    public static function getSubjectById($id)
    {
        return Subject::find()
        ->where(['subjectid' => $id])
        ->one();
    }
}
