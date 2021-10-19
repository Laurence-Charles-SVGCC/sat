<?php

namespace common\models;

class ProgrammeCatalogModel
{
    public static function getProgrammeCatalogByID($id)
    {
        return ProgrammeCatalog::find()
            ->where(['programmecatalogid' => $id])
            ->one();;
    }


    public static function getFormattedProgrammeName($programme)
    {
        if ($programme == null) {
            return null;
        }

        $qualificationType =
            QualificationTypeModel::getQualificationAbbreviationByID(
                $programme->qualificationtypeid
            );

        $specialisation = $programme->specialisation;

        if ($programme->programmecatalogid == 10) {      //if CAPE
            return  $programme->name;
        } elseif ($specialisation == true) {
            return "{$qualificationType}. {$programme->name} ({$specialisation})";
        } else {
            return "{$qualificationType}. {$programme->name}";
        }
    }
}
