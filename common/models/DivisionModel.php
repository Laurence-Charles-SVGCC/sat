<?php

namespace common\models;

class DivisionModel
{
    public static function getMainDivisions()
    {
        return Division::find()
            ->where(['divisionid' => [4, 5, 6, 7], 'isactive' => 1, 'isdeleted' => 0])
            ->all();
    }


    public static function getDivisionByID($divisionid)
    {
        return Division::find()
            ->where(['divisionid' => $divisionid, 'isdeleted' => 0])
            ->one();
    }


    public static function getDivisionAbbreviationByID($divisionid)
    {
        $model =  self::getDivisionByID($divisionid);
        if ($model == true) {
            return $model->abbreviation;
        }
        return null;
    }
}
