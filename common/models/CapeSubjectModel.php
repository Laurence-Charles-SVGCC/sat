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


    public static function getCapeSubjectNames($capeSubjects)
    {
        $names = array();

        if (!empty($capeSubjects)) {
            foreach ($capeSubjects as $capeSubject) {
                $names[] = $capeSubject->subjectname;
            }
        }
        return $names;
    }
}
