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


    public static function isCape($academicOffering)
    {
        $programmeCatalog =
            ProgrammeCatalogModel::getProgrammeCatalogByID(
                $academicOffering->programmecatalogid
            );

        if ($programmeCatalog->name === "CAPE") {
            return true;
        }

        return false;
    }


    public static function getSuccessfulApplications(
        $academicOffering
    ) {
        return Application::find()
            ->innerJoin(
                'offer',
                '`application`.`applicationid` = `offer`.`applicationid`'
            )
            ->innerJoin(
                'academic_offering',
                '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`'
            )
            ->where([
                "application.academicofferingid" => $academicOffering->academicofferingid,
                "application.applicationstatusid" => 9,
                "application.isactive" => 1,
                "application.isdeleted" => 0,
                "offer.ispublished" => 1,
                "offer.isactive" => 1,
                "offer.isdeleted" => 0,

            ])
            ->all();
    }
}
