<?php

namespace common\models;

class AcademicOfferingModel extends \yii\base\Model
{
    public static function getAcademicOfferingByID($id)
    {
        return  AcademicOffering::find()
            ->where(['academicofferingid' => $id, 'isdeleted' => 0])
            ->one();
    }


    public static function getProgrammeName($academicOffering)
    {
        $programme =
            ProgrammeCatalog::find()
            ->where(
                [
                    'programmecatalogid' => $academicOffering->programmecatalogid,
                    'isactive' => 1,
                    'isdeleted' => 0
                ]
            )
            ->one();

        if ($programme == true) {
            $qualificationType =
                QualificationType::find()
                ->where(
                    [
                        'qualificationtypeid' => $programme->qualificationtypeid,
                        'isactive' => 1,
                        'isdeleted' => 0
                    ]
                )
                ->one()
                ->abbreviation;

            $specialisation = $programme->specialisation;

            if ($academicOffering->programmecatalogid == 10) {  //if CAPE
                return  $programme->name;
            } elseif ($specialisation == true) {
                return "{$qualificationType} {$programme->name} ({$specialisation})";
            } else {
                return "{$qualificationType} {$programme->name}";
            }
        } else {
            return null;
        }
    }


    public static function getProgrammeNameByStudentRegistrationId(
        $studentRegistrationId
    ) {
        $studentRegistration =
            StudentRegistrationModel::getStudentRegistrationByID(
                $studentRegistrationId
            );

        $academicOffering =
            AcademicOfferingModel::getAcademicOfferingByID($studentRegistration->academicofferingid);

        $programme =
            ProgrammeCatalog::find()
            ->where(
                [
                    'programmecatalogid' => $academicOffering->programmecatalogid,
                    'isactive' => 1,
                    'isdeleted' => 0
                ]
            )
            ->one();

        if ($programme == true) {
            $qualificationType =
                QualificationType::find()
                ->where(
                    [
                        'qualificationtypeid' => $programme->qualificationtypeid,
                        'isactive' => 1,
                        'isdeleted' => 0
                    ]
                )
                ->one()
                ->abbreviation;

            $specialisation = $programme->specialisation;

            if ($academicOffering->programmecatalogid == 10) {  //if CAPE
                return  $programme->name;
            } elseif ($specialisation == true) {
                return "{$qualificationType} {$programme->name} ({$specialisation})";
            } else {
                return "{$qualificationType} {$programme->name}";
            }
        } else {
            return null;
        }
    }


    public static function getFormattedOfferingName($academicOffering)
    {
        $programme =
            ProgrammeCatalogModel::getProgrammeCatalogByID(
                $academicOffering->programmecatalogid
            );

        $academicYear =
            AcademicYearModel::getAcademicYearByID(
                $academicOffering->academicyearid
            );

        $programmeName =
            ProgrammeCatalogModel::getFormattedProgrammeName($programme);

        return $programmeName . " ({$academicYear->title})";
    }
}
