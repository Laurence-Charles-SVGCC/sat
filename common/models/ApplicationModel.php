<?php

namespace common\models;

use Yii;

class ApplicationModel
{
    public static function getApplicationByApplicationID($id)
    {
        return Application::find()->where(["applicationid" => $id])->one();
    }


    public static function getActiveApplicationsByPersonID($id)
    {
        return Application::find()
            ->where(
                [
                    "applicationstatusid" => [2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
                    "ordering" => [1, 2, 3],
                    "personid" => $id,
                    "isactive" => 1,
                    "isdeleted" => 0
                ]
            )
            ->orderBy("ordering ASC")
            ->all();
    }


    public static function hasActiveApplicationsRelatedToVisibleApplicationPeriod(
        $personid
    ) {
        $idsForVisibleApplicationPeriods =
            ApplicationPeriodModel::getApplicationPeriodIdsForVisibleApplicationPeriods();

        $activeApplications = self::getActiveApplicationsByPersonID($personid);
        foreach ($activeApplications as $application) {
            $offering =
                AcademicOfferingModel::getAcademicOfferingByID(
                    $application->academicofferingid
                );

            if (in_array($offering->applicationperiodid, $idsForVisibleApplicationPeriods) == true) {
                return true;
            }
        }
        return false;
    }


    public static function getFormattedProgrammeChoice($application)
    {
        $capeSubjectsNames = array();

        $offering =
            AcademicOfferingModel::getAcademicOfferingByID(
                $application->academicofferingid
            );

        $programme =
            ProgrammeCatalogModel::getProgrammeCatalogByID(
                $offering->programmecatalogid
            );

        $applicationCapeSubjects =
            ApplicationCapesubjectModel::getApplicationCapeSubjectsByApplicationID(
                $application->applicationid
            );

        foreach ($applicationCapeSubjects as $cs) {
            $capeSubjectsNames[] =
                CapeSubjectModel::getCapeSubjectNameByID($cs->capesubjectid);
        }

        if (empty($applicationCapeSubjects) == true) {
            // return ProgrammeCatalogModel::getFormattedProgrammeName($programme);
            return AcademicOfferingModel::getFormattedOfferingName($offering);
        } else {
            $academicYear =
                AcademicYearModel::getAcademicYearByID($offering->academicyearid);

            return $programme->name
                . " ({$academicYear->title}): "
                . implode(' ,', $capeSubjectsNames);
        }
    }


    public static function formatApplicationsInformation($applications)
    {
        $data = array();
        foreach ($applications as $application) {
            $row = array();
            if ($application->ordering === 1) {
                $row["ordering"] = "First Choice";
            } elseif ($application->ordering === 2) {
                $row["ordering"] = "Second Choice";
            } elseif ($application->ordering === 3) {
                $row["ordering"] = "Third Choice";
            }

            $row["name"] = self::getFormattedProgrammeChoice($application);
            $data[] = $row;
        }
        return $data;
    }


    public static function getApplicationPeriodName($application)
    {
        $academicOffering =
            AcademicOfferingModel::getAcademicOfferingByID(
                $application->academicofferingid
            );

        if ($academicOffering == true) {
            $period =
                ApplicationPeriodModel::getApplicationPeriodByID(
                    $academicOffering->applicationperiodid
                );

            return $period->name;
        }
        return null;
    }

    public static function getApplicationPeriod($application)
    {
        $academicOffering =
            AcademicOfferingModel::getAcademicOfferingByID(
                $application->academicofferingid
            );

        if ($academicOffering == true) {
            return ApplicationPeriodModel::getApplicationPeriodByID(
                $academicOffering->applicationperiodid
            );
        }
        return null;
    }


    public static function getSuccessfulApplication($applicant)
    {
        return Application::find()
            ->where([
                "personid" => $applicant->personid,
                "applicationstatusid" => 9,
                "isactive" => 1,
                "isdeleted" => 0
            ])
            ->one();
    }


    public static function getFormattedProgrammeChoices($personId)
    {
        $applications = self::getActiveApplicationsByPersonID($personId);
        return self::formatApplicationsInformation($applications);
    }
}
