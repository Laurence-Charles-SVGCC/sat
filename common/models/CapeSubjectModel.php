<?php

namespace common\models;

class CapeSubjectModel
{
    public static function getCapeSubjectByID($id)
    {
        return CapeSubject::find()
            ->where(['capesubjectid' => $id])
            ->one();
    }

    public static function getCapeSubjectNameByID($id)
    {
        if ($id == null) {
            return null;
        }

        $capeSubject = self::getCapeSubjectByID($id);
        return $capeSubject->subjectname;
    }
}
