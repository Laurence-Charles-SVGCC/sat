<?php

namespace common\models;

class ApplicationPeriodModel
{
    public static function makeApplicantsOfPeriodVisible($period)
    {
        $period->iscomplete = 0;
        return $period->save();
    }


    public static function excludeApplicantsOfPeriod($period)
    {
        $period->iscomplete = 1;
        return $period->save();
    }


    public static function closePeriod($period)
    {
        $period->applicationperiodstatusid = 6;
        return $period->save();
    }


    public static function openPeriod($period)
    {
        $period->applicationperiodstatusid = 5;
        return $period->save();
    }


    public static function formatApplicationPeriodDetailsIntoAssociativeArray(
        $period
    ) {
        $formattedApplicationPeriod = array();
        $formattedApplicationPeriod['id'] = $period->applicationperiodid;

        $formattedApplicationPeriod['status'] =
            ApplicationperiodStatusModel::getApplicationPeriodStatusNameByID(
                $period->applicationperiodstatusid
            );

        $formattedApplicationPeriod['type'] =
            ApplicationPeriodTypeModel::getApplicationPeriodTypeNmeByID(
                $period->applicationperiodtypeid
            );

        $formattedApplicationPeriod['division'] =
            DivisionModel::getDivisionAbbreviationByID($period->divisionid);

        $formattedApplicationPeriod['year'] =
            AcademicYearModel::getAcademicYearTitleByID($period->academicyearid);

        $formattedApplicationPeriod['name'] =  $period->name;

        $formattedApplicationPeriod['iscomplete'] =
            self::getApplicantVisibility($period);

        $formattedApplicationPeriod['applicationsExist']  =
            self::applicationsExistForPeriod($period);

        return $formattedApplicationPeriod;
    }


    public static function prepareFormattedApplicationPeriodListing()
    {
        $data = array();
        $periods = self::getActiveApplicationPeriods();

        foreach ($periods as $period) {
            $data[] =
                self::formatApplicationPeriodDetailsIntoAssociativeArray($period);
        }
        return $data;
    }


    public static function formatDate($date)
    {
        return date_format(date_create($date), "d/m/Y");
    }


    public static function getApplicantVisibility($period)
    {
        return $period->iscomplete == 1 ? "Excluded" : "Visible";
    }


    public static function getApplicationPeriodID($applicationPeriodID)
    {
        return ApplicationPeriod::find()
            ->where(['applicationperiodid' => $applicationPeriodID])
            ->one();
    }


    public static function getActiveApplicationPeriods()
    {
        return ApplicationPeriod::find()
            ->where(['isactive' => 1,  'isdeleted' => 0])
            ->orderBy('applicationperiodid DESC')
            ->all();
    }


    public static function getPendingApplicationPeriod()
    {
        return ApplicationPeriod::find()
            ->where(
                [
                    'isactive' => 0, 'isdeleted' => 0,
                    'applicationperiodstatusid' => [1, 2, 3, 4]
                ]
            )
            ->one();
    }


    public static function getApplicantIntent($applicationPeriod)
    {
        $applicantintentid = null;
        if ($applicationPeriod->divisionid == 4) {
            if ($applicationPeriod->applicationperiodtypeid == 1) {
                $applicantintentid = 1;
            } elseif ($applicationPeriod->applicationperiodtypeid == 2) {
                $applicantintentid = 2;
            }
        } elseif ($applicationPeriod->divisionid == 5) {
            if ($applicationPeriod->applicationperiodtypeid == 1) {
                $applicantintentid = 1;
            } elseif ($applicationPeriod->applicationperiodtypeid == 2) {
                $applicantintentid = 3;
            }
        } elseif ($applicationPeriod->divisionid == 6) {
            if ($applicationPeriod->applicationperiodtypeid == 1) {
                $applicantintentid = 4;
            } elseif ($applicationPeriod->applicationperiodtypeid == 2) {
                $applicantintentid = 5;
            }
        } elseif ($applicationPeriod->divisionid == 7) {
            if ($applicationPeriod->applicationperiodtypeid == 1) {
                $applicantintentid = 6;
            } elseif ($applicationPeriod->applicationperiodtypeid == 2) {
                $applicantintentid = 7;
            } elseif ($applicationPeriod->applicationperiodtypeid == 3) {
                $applicantintentid = 10;
            }
        }
        return $applicantintentid;
    }


    public static function createDefaultApplicationPeriod($userID)
    {
        $period = new ApplicationPeriod();
        $period->applicationperiodtypeid = 1;
        $period->applicationperiodstatusid = 1;
        $period->divisionid = 4;
        $period->personid = $userID;
        $period->academicyearid = 4;
        $period->name = strval(date('Y'));
        $period->onsitestartdate = date('Y-m-d');
        $period->onsiteenddate = date('Y-m-d');
        $period->offsitestartdate = date('Y-m-d');
        $period->offsiteenddate =  date('Y-m-d');
        $period->isactive = 0;
        $period->isdeleted = 0;
        if ($period->save() == true) {
            return $period;
        }
        return false;
    }


    public static function getUnconfiguredAppplicationPeriod()
    {
        return ApplicationPeriod::find()
            ->where([
                'isactive' => 0,
                'isdeleted' => 0,
                'applicationperiodstatusid' => [1, 2, 3, 4]
            ])
            ->one();
    }


    public static function hasCapeOffering($applicationPeriod)
    {
        if ($applicationPeriod == true) {
            return AcademicOffering::find()
                ->where([
                    'applicationperiodid' => $applicationPeriod->applicationperiodid,
                    'programmecatalogid' => 10, 'isactive' => 1, 'isdeleted' => 0
                ])
                ->one();
        }
        return false;
    }


    public static function determineApplicantIntent(
        $divisionid,
        $applicationperiodtypeid
    ) {
        $applicantintentid = null;
        if ($divisionid == 4) {
            if ($applicationperiodtypeid == 1) {
                $applicantintentid = 1;
            } elseif ($applicationperiodtypeid == 2) {
                $applicantintentid = 2;
            }
        } elseif ($divisionid == 5) {
            if ($applicationperiodtypeid == 1) {
                $applicantintentid = 1;
            } elseif ($applicationperiodtypeid == 2) {
                $applicantintentid = 3;
            }
        } elseif ($divisionid == 6) {
            if ($applicationperiodtypeid == 1) {
                $applicantintentid = 4;
            } elseif ($applicationperiodtypeid == 2) {
                $applicantintentid = 5;
            }
        } elseif ($divisionid == 7) {
            if ($applicationperiodtypeid == 1) {
                $applicantintentid = 6;
            } elseif ($applicationperiodtypeid == 2) {
                $applicantintentid = 7;
            } elseif ($applicationperiodtypeid == 3) {
                $applicantintentid = 10;
            }
        }
        return $applicantintentid;
    }


    /**
     * Determines existence of academic year and application period for Application Period configuration
     * Currently only take full time application periods into account
     */
    public static function processApplicantIntentID(
        $divisionid,
        $applicationperiodtypeid
    ) {
        $resultSet = array();
        $academicYearExists = 0;
        $applicationPeriodExists = 0;

        $applicantintentid =
            self::determineApplicantIntent(
                $divisionid,
                $applicationperiodtypeid
            );

        if ($applicantintentid == 1) {
            $academicYear =
                AcademicYear::find()
                ->where([
                    'applicantintentid' => $applicantintentid,
                    'iscurrent' => 1,
                    'isactive' => 1,
                    'isdeleted' => 0
                ])
                ->one();
            if ($academicYear == true) {
                $academicYearExists = 1;
                $period =
                    ApplicationPeriod::find()
                    ->where([
                        'divisionid' => $divisionid,
                        'isactive' => 1,
                        'isdeleted' => 0
                    ])
                    ->one();
                if ($period == true) {
                    $applicationPeriodExists = 1;
                }
            }
        }

        array_push($resultSet, $academicYearExists);
        array_push($resultSet, $applicationPeriodExists);
        return $resultSet;
    }


    public static function updatePeriodName(
        $divisionID,
        $academicYearID,
        $applicationPeriodTypeID
    ) {
        $division =
            Division::find()
            ->where(['divisionid' => $divisionID])
            ->one();

        $academicYear =
            AcademicYear::find()
            ->where(['academicyearid' => $academicYearID])
            ->one();

        $applicationPeriodType =
            ApplicationPeriodType::find()
            ->where(['applicationperiodtypeid' => $applicationPeriodTypeID])
            ->one();

        $name = "";
        $divisionAbbreviation = $division->abbreviation;
        $academicYearTitle = substr($academicYear->title, 0, 4);
        $applicationPeriodTypeName = $applicationPeriodType->name;

        if ($applicationPeriodTypeID == 1) {
            return "{$divisionAbbreviation}{$academicYearTitle}";
        } elseif ($applicationPeriodTypeID == 2) {
            return "{$divisionAbbreviation}{$academicYearTitle}{$applicationPeriodTypeName}";
        }
        return "Error";
    }

    public static function getCapeSubjects($period)
    {
        return CapeSubject::find()
            ->innerJoin(
                'academic_offering',
                '`cape_subject`.`academicofferingid` = `academic_offering`.`academicofferingid`'
            )
            ->where([
                'cape_subject.isactive' => 1,
                'cape_subject.isdeleted' => 0,
                'academic_offering.applicationperiodid' => $period->applicationperiodid,
                'academic_offering.isactive' => 1,
                'academic_offering.isdeleted' => 0
            ])
            ->all();
    }


    public static function getCurrentCapeAcademicOffering($period)
    {
        return AcademicOffering::find()
            ->where([
                'applicationperiodid' => $period->applicationperiodid,
                'programmecatalogid' => 10,
                'isactive' => 1,
                'isdeleted' => 0
            ])
            ->one();
    }


    public static function getAllApplicationsForPeriod($period)
    {
        return Application::find()
            ->innerJoin(
                'academic_offering',
                '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`'
            )
            ->where([
                'application.isactive' => 1,
                'application.isdeleted' => 0,
                'academic_offering.applicationperiodid' => $period->applicationperiodid,
                'academic_offering.isactive' => 1,
                'academic_offering.isdeleted' => 0
            ])
            ->all();
    }


    public static function applicationsExistForPeriod($period)
    {
        $applicationsForPeriod = self::getAllApplicationsForPeriod($period);
        if (!empty($applicationsForPeriod)) {
            return true;
        } else {
            return false;
        }
    }


    public static function deletePeriod($period)
    {
        $academicOfferings =
            AcademicOffering::find()
            ->where([
                'applicationperiodid' => $period->applicationperiodid,
                'isactive' => 1,
                'isdeleted' => 0
            ])
            ->all();

        foreach ($academicOfferings as $offering) {
            $offering->isactive = 0;
            $offering->isdeleted = 1;
            if ($offering->save() == false) {
                return false;
            }
        }

        $period->isactive = 0;
        $period->isdeleted = 1;
        return $period->save();
    }


    public static function getApplicationPeriodNameByID($applicationPeriodID)
    {
        if ($applicationPeriodID == null) {
            return null;
        }
        $period =
            ApplicationPeriod::find()
            ->where(['applicationperiodid' => $applicationPeriodID])
            ->one();

        if ($period == true) {
            return $period->name;
        }
    }


    public static function getVisibleApplicationPeriods()
    {
        return ApplicationPeriod::find()
            ->where(
                [
                    "applicationperiodstatusid" => 5,
                    "iscomplete" => 0,
                    "isactive" => 1,
                    "isdeleted" => 0
                ]
            )
            ->all();
    }


    public static function getApplicationPeriodIds($applicationPeriods)
    {
        $ids = array();
        foreach ($applicationPeriods as $applicationPeriod) {
            $ids[] = $applicationPeriod->applicationperiodid;
        }
        return $ids;
    }


    public static function getApplicationPeriodIdsForVisibleApplicationPeriods()
    {
        $periods = self::getVisibleApplicationPeriods();
        return self::getApplicationPeriodIds($periods);
    }


    public static function getApplicationPeriodByID($applicationPeriodID)
    {
        return ApplicationPeriod::find()
            ->where(['applicationperiodid' => $applicationPeriodID])
            ->one();
    }


    public static function getRelevantApplicationPeriodForApplicant(
        $personid
    ) {
        $activeApplications =
            ApplicationModel::getActiveApplicationsByPersonID($personid);

        if ($activeApplications == true) {
            $application = $activeApplications[0];

            $offering =
                AcademicOfferingModel::getAcademicOfferingByID(
                    $application->academicofferingid
                );

            if ($offering == true) {
                return ApplicationPeriodModel::getApplicationPeriodByID(
                    $offering->applicationperiodid
                );
            }
            return null;
        }
    }


    public static function getAssociatedProgrammes($applicationPeriodId)
    {
        return ProgrammeCatalog::find()
            ->innerJoin(
                'academic_offering',
                '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`'
            )
            ->where([
                "programme_catalog.isactive" => 1,
                "programme_catalog.isdeleted" => 0,
                "academic_offering.applicationperiodid" => $applicationPeriodId
            ])
            ->orderBy("name Asc")
            ->all();
    }


    public static function getAssociatedAcademicOfferings($applicationPeriodId)
    {
        return AcademicOffering::find()
            ->innerJoin(
                'programme_catalog',
                '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`'
            )
            ->where([
                "academic_offering.isactive" => 1,
                "academic_offering.isdeleted" => 0,
                "academic_offering.applicationperiodid" => $applicationPeriodId
            ])
            ->orderBy("programme_catalog.name Asc")
            ->all();
    }


    public static function generateProgrammeDropdownList($applicationPeriodId)
    {
        $data = array();

        $academicOfferings = self::getAssociatedAcademicOfferings(
            $applicationPeriodId
        );

        foreach ($academicOfferings as $academicOffering) {
            $programme =
                ProgrammeCatalogModel::getProgrammeCatalogByID(
                    $academicOffering->programmecatalogid
                );

            $data[$academicOffering->academicofferingid] =
                ProgrammeCatalogModel::getFormattedProgrammeName($programme);
        }
        return $data;
    }


    public static function hasOffers($applicationPeriod)
    {
        $offers =
            Offer::find()
            ->innerJoin('application', '`offer`.`applicationid` = `application`.`applicationid`')
            ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
            ->where([
                'offer.ispublished' => 0,
                'offer.isactive' => 1,
                'offer.isdeleted' => 0,
                'application.isactive' => 1,
                'application.isdeleted' => 0,
                'academic_offering.applicationperiodid' => $applicationPeriod->applicationperiodid,
                'academic_offering.isactive' => 1,
                'academic_offering.isdeleted' => 0
            ])
            ->all();
        if (empty($offers)) {
            return false;
        }
        return true;
    }
}
