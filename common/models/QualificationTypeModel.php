<?php

namespace common\models;

class QualificationTypeModel
{
    public static function getQualificationByID($id)
    {
        return QualificationType::find()
            ->where(['qualificationtypeid' => $id, 'isactive' => 1, 'isdeleted' => 0])
            ->one();
    }


    public static function getQualificationAbbreviationByID($id)
    {
        $qualification = self::getQualificationByID($id);
        return $qualification->abbreviation;
    }
}
