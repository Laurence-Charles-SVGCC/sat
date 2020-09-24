<?php

namespace frontend\models;

class DivisionModel
{
    public static function getDivisionById($id)
    {
        return Division::find()
      ->where(['divisionid' => $id])
      ->one();
    }

    public static function getDivisionAbbreviationById($id)
    {
        $division = self::getDivisionById($id);
        if ($division == true) {
            return $division->abbreviation;
        }
        return false;
    }
}
