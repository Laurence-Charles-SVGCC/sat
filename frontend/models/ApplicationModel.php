<?php

namespace frontend\models;

class ApplicationModel
{
    public static function getApplicationsByPersonId($id)
    {
        return Application::find()
            ->where(["personid" => $id, "isactive" => 1, "isdeleted" => 0])
            ->orderBy("ordering ASC")
            ->all();
    }

    public static function getVerifiedApplicationsByPersonId($id)
    {
        return Application::find()
            ->where(["personid" => $id, "isactive" => 1, "isdeleted" => 0])
            ->andWhere(['>', 'applicationstatusid', 3])
            ->orderBy("ordering ASC")
            ->all();
    }


    public static function getApplicationsForActivePeriodByPersondId($id)
    {
        return Application::find()
            ->innerJoin(
                'academic_offering',
                '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`'
            )
            ->innerJoin(
                'application_period',
                '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`'
            )
            ->where(
                [
                    'application_period.iscomplete' => 0,
                    'application_period.isactive' => 1,
                    'application_period.isdeleted' => 0,
                    'application.isactive' => 1,
                    'application.isdeleted' => 0,
                    'application.personid' => $id
                ]
            )
            ->orderBy('application.ordering ASC')
            ->all();
    }


    public static function getAdministratorAssignedApplicationsByPersonId($id)
    {
        return Application::find()
            ->innerJoin(
                'academic_offering',
                '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`'
            )
            ->innerJoin(
                'application_period',
                '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`'
            )
            ->where(
                [
                    'application_period.isactive' => 1,
                    'application.isactive' => 1,
                    'application.isdeleted' => 0,
                    'application.personid' => $id,
                    'academic_offering.isactive' => 1,
                    'academic_offering.isdeleted' => 0
                ]
            )
            ->andWhere(['>', 'application.ordering', 3])
            ->orderBy('application.ordering ASC')
            ->all();
    }


    public static function getApplicationUnderConsideration(
        $applications,
        $applicationStatus
    ) {
        $personid = $applications[0]->personid;

        /*******  Algorithm for all applications > August 8th 2020  ***********/

        foreach ($applications as $application) {
            if ($application->iscurrent == true) {
                return $application;
            }
        }

        /*****************  Algoritm for DASGS/DTVE2015  **********************/

        $dasgsDtve2015Applications =
            Application::find()
            ->innerJoin(
                'academic_offering',
                '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`'
            )
            ->innerJoin(
                'application_period',
                '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`'
            )
            ->where(
                [
                    'application.personid' => $personid,
                    'application.isactive' => 1,
                    'application.isdeleted' => 0,
                    'academic_offering.isactive' => 1,
                    'academic_offering.isdeleted' => 0,
                    'application_period.isactive' => 1,
                    'application_period.isdeleted' => 0,
                    'application_period.name' => ['DASGS2015', 'DTVE2015']
                ]
            )
            ->orderBy('application.ordering ASC')
            ->all();

        if ($dasgsDtve2015Applications) {
            return end($dasgsDtve2015Applications);
        }

        /*****  DASGS/DTVE2015 > applications submitted < DASGS/DTVE2019  *****/

        $adminAssignedApplications =
            self::getAdministratorAssignedApplicationsByPersonId($personid);

        // if applicant has custom offers -> get last application
        if ($adminAssignedApplications) {
            return end($adminAssignedApplications);
        } else {
            $targetApplication = null;
            $userAssignedApplications = count($applications);

            // if [rejected,interview-offer-reject] -> get last application
            if (in_array($applicationStatus, [6, 10]) == true) {
                $targetApplication = end($applications);
            }

            // if [unverified,pending,shortlisted,borderlined,interview status,full offer, abandoned] -> get first applicaiton with matching status
            elseif (in_array($applicationStatus, [2, 3, 4, 7, 8, 9]) == true) {
                foreach ($applications as $app) {
                    if ($app->applicationstatusid == $applicationStatus) {
                        return $app;
                    }
                }
            }
        }
        return null;
    }


    public static function isCandidateApplicationUnderConsideration(
        $applications,
        $applicationStatus,
        $candidateApplication
    ) {
        $applicationUnderConsideration =
            self::getApplicationUnderConsideration($applications, $applicationStatus);

        if (
            $applicationUnderConsideration == true
            && $applicationUnderConsideration->applicationid == $candidateApplication->applicationid
        ) {
            return true;
        }
        return false;
    }


    public static function getProgrammeCatalog($application)
    {
        return ProgrammeCatalog::find()
            ->innerJoin(
                'academic_offering',
                '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`'
            )
            ->innerJoin(
                'application',
                '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`'
            )
            ->where(
                [
                    'application.applicationid' => $application->applicationid,
                    'application.isactive' => 1,
                    'application.isdeleted' => 0
                ]
            )
            ->one();
    }


    public static function getProgammeDescription($application)
    {
        $programmeCatalog = self::getProgrammeCatalog($application);
        $qualificationType = $programmeCatalog->getQualificationtype()->one();

        if ($programmeCatalog->qualificationtypeid == false) {
            return "{$programmeCatalog->name} {$programmeCatalog->specialisation}";
        } elseif (
            $programmeCatalog->qualificationtypeid == true
            && $qualificationType->abbreviation == "CAPE"
        ) {
            $capeSubjectsNames = array();

            $applicationCapeSubjects =
                ApplicationCapesubject::find()
                ->innerJoin(
                    'application',
                    '`application_capesubject`.`applicationid` = `application`.`applicationid`'
                )
                ->where(
                    [
                        'application.applicationid' => $application->applicationid,
                        'application.isactive' => 1,
                        'application.isdeleted' => 0
                    ]
                )
                ->all();

            foreach ($applicationCapeSubjects as $cs) {
                $capeSubjectsNames[] = $cs->getCapesubject()->one()->subjectname;
            }

            return $programmeCatalog->name
                . ": "
                . implode(' ,', $capeSubjectsNames);
        } elseif (
            $programmeCatalog->qualificationtypeid == true
            && $qualificationType->abbreviation != "CAPE"
        ) {
            return "{$qualificationType->abbreviation}"
                . " {$programmeCatalog->name}"
                . " {$programmeCatalog->specialisation}";
        }
    }


    public static function getFormattedProgrammeChoices(
        $applications,
        $applicationStatus
    ) {
        $applicationContainer = array();
        $currentApplication = null;

        foreach ($applications as $application) {
            $combined = array();
            $keys = array();
            $values = array();

            array_push($keys, "applicationId");
            array_push($keys, "application");
            array_push($keys, "ordering");
            array_push($keys, "isCurrentApplication");
            array_push($keys, "divisionAbbreviation");
            array_push($keys, "divisionId");
            array_push($keys, "programmeDescription");
            array_push($keys, "status");
            array_push($keys, "availableStatusOptions");

            array_push($values, $application->applicationid);
            array_push($values, $application);
            array_push($values, $application->ordering);

            $currentApplication =
                self::getCurrentApplication($application->personid, $applications);

            if (
                $currentApplication == true
                && $currentApplication->applicationid == $application->applicationid
            ) {
                $isCurrentApplication = true;
            } else {
                $isCurrentApplication = false;
            }
            array_push($values, $isCurrentApplication);

            $divisionAbbreviation =
                DivisionModel::getDivisionAbbreviationById($application->divisionid);
            array_push($values, $divisionAbbreviation);

            array_push($values, $application->divisionid);

            $programmeDescription = self::getProgammeDescription($application);
            array_push($values, $programmeDescription);

            $status =
                ApplicationStatusModel::getApplicationStatusNameById(
                    $application->applicationstatusid
                );
            array_push($values, $status);

            $availableStatusOptions =
                self::generateAvailableStatusOptions($application);
            array_push($values, $availableStatusOptions);

            $combined = array_combine($keys, $values);
            array_push($applicationContainer, $combined);
        }

        return $applicationContainer;
    }


    public static function getExplicitlyLabelledCurrentApplication($id)
    {
        return Application::find()
            ->where([
                "personid" => $id, "isactive" => 1, "isdeleted" => 0, "iscurrent" => 1
            ])
            ->one();
    }

    public static function getAdminCreatedProgrammeChoicesByApplicantUserId(
        $id
    ) {
        return Application::find()
            ->innerJoin(
                'academic_offering',
                '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`'
            )
            ->innerJoin(
                'application_period',
                '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`'
            )
            ->where([
                'application_period.isactive' => 1,
                'application.isactive' => 1,
                'application.isdeleted' => 0,
                'application.personid' => $personid,
                'academic_offering.isactive' => 1,
                'academic_offering.isdeleted' => 0
            ])
            ->andWhere(['>', 'application.ordering', 3])
            ->orderBy('application.ordering ASC')
            ->all();
    }

    public static function getApplicationFromCollectionByStatus(
        $papplications,
        $applicationStatusId
    ) {
        foreach ($applications as $application) {
            if ($application->applicationstatusid == $applicationStatusID) {
                return $application;
            }
        }
        return null;
    }

    public static function getImplicitlyLabelledCurrentApplicationWithKnownStatus(
        $applicantUserId,
        $applications,
        $applicationStatus
    ) {
        /* if alternative application exist the last altenative application is
           the target
        */

        /*
         * Applications from the 2015DASGS and 2015 DTVE application periods must be
         * processed differently as the application handling mechanism was subsequently
         * changed
         */
        $oldApplications =
            Application::find()
            ->innerJoin(
                "academic_offering",
                "`application`.`academicofferingid` = `academic_offering`.`academicofferingid`"
            )
            ->innerJoin(
                "application_period",
                "`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`"
            )
            ->where([
                'application.personid' => $applicantUserId,
                'application.isactive' => 1,
                'application.isdeleted' => 0,
                'academic_offering.isactive' => 1,
                'academic_offering.isdeleted' => 0,
                'application_period.isactive' => 1,
                'application_period.isdeleted' => 0,
                'application_period.name' => ['DASGS2015', 'DTVE2015']
            ])
            ->orderBy('application.ordering ASC')
            ->all();

        if ($oldApplications == true) {
            return end($oldApplications);
        } else {
            $adminCreatedProgrammeChoices =
                self::getAdminCreatedProgrammeChoicesByApplicantUserId(
                    $applicantUserId
                );

            if ($adminCreatedProgrammeChoice == true) {
                return end($adminCreatedProgrammeChoice);
            } else {
                if ($applicationStatus == 0) {
                    return $applications[0];     //chooses default application
                }

                // if rejected -> get last application
                // if interview-offer-reject -> get last application that has interview-offer-reject status
                elseif (in_array($applicationStatus, [6, 10]) == true) {
                    return end($applications);
                }

                // if [unverified|pending|shortlisted|borderline|interview offer|full offer|abandoned -> get first application that has unverified status
                elseif (in_array($applicationStatus, [2, 3, 4, 7, 8, 9, 11]) == true) {
                    return self::getApplicationFromCollectionByStatus(
                        $papplications,
                        $applicationStatus
                    );
                }
            }
        }
        return null;
    }

    public static function getCurrentApplicationWithKnownApplicationStatus(
        $applicantUserId,
        $verifiedApplications,
        $applicationStatus
    ) {
        $explicitlyLabelledCurrentApplication =
            self::getExplicitlyLabelledCurrentApplication($applicantUserId);

        if ($explicitCurrentApplication == true) {
            return $explicitlyLabelledCurrentApplication;
        } else {
            return self::getImplicitlyLabelledCurrentApplicationWithKnownStatus(
                $applicantUserId,
                $verifiedApplications,
                $applicationStatus
            );
        }
    }


    ////////////
    ////////////
    public static function hasDeprecatedApplications($applications)
    {
        foreach ($applications as $application) {
            $academicOffering =
                AcademicOfferingModel::getAcademicOfferingById(
                    $application->academicofferingid
                );

            $applicationPeriod =
                ApplicationPeriodModel::getApplicationPeriodById(
                    $academicOffering->applicationperiodid
                );

            if (in_array($applicationPeriod->name, ['DASGS2015', 'DTVE2015']) == true) {
                return true;
            }
            return false;
        }
    }

    public static function hasAdminCreatedApplication($applications)
    {
        foreach ($applications as $application) {
            if ($application->ordering > 3) {
                return true;
            }
        }
        return false;
    }


    public static function getCurrentOfTwoApplications($applications)
    {
        //if pending
        if (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 3
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 3
            && $applications[1]->applicationstatusid == 3
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 3
        ) {
            return $applications[1];
        }

        //if shortlisted
        elseif (
            $applications[0]->applicationstatusid == 4
            &&  $applications[1]->applicationstatusid == 3
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 6  &&
            $applications[1]->applicationstatusid == 4
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 10
            && $applications[1]->applicationstatusid == 4
        ) {
            return $applications[1];
        }

        //if rejected
        elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 6
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 6
        ) {
            return $applications[1];
        }

        //if borderlined
        elseif (
            $applications[0]->applicationstatusid == 7
            &&  $applications[1]->applicationstatusid == 3
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 7
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 7
        ) {
            return $applications[1];
        }

        //if interview-offer
        elseif (
            $applications[0]->applicationstatusid == 8
            &&  $applications[1]->applicationstatusid == 6
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 6
            && $applications[1]->applicationstatusid == 8
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 8
        ) {
            return $applications[1];
        }

        //if offer
        elseif (
            $applications[0]->applicationstatusid == 9
            &&  $applications[1]->applicationstatusid == 6
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 9
            &&  $applications[1]->applicationstatusid == 10
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 9
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 9
        ) {
            return $applications[0];
        }

        //if reject of interview offer
        elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 10
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 10
        ) {
            return $applications[1];
        }

        //if abandoned
        elseif (
            $applications[0]->applicationstatusid == 11
            &&  $applications[1]->applicationstatusid == 11
        ) {
            return $applications[1];
        }
    }

    public static function getCurrentOfThreeApplications($applications)
    {
        //if pending
        if (
            $applications[0]->applicationstatusid == 3
            &&  $applications[1]->applicationstatusid == 3
            &&  $applications[2]->applicationstatusid == 3
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 3
            &&  $applications[2]->applicationstatusid == 3
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 3
            &&  $applications[2]->applicationstatusid == 3
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 3
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 3
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 3
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 3
        ) {
            return $applications[2];
        }

        //if shortlisted
        elseif (
            $applications[0]->applicationstatusid == 4
            && $applications[1]->applicationstatusid == 3
            && $applications[2]->applicationstatusid == 3
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 4
            &&  $applications[2]->applicationstatusid == 3
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 4
            &&  $applications[2]->applicationstatusid == 3
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 4
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 4
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 4
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 4
        ) {
            return $applications[2];
        }

        //if rejected
        elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 6
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 6
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 6
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 6
        ) {
            return $applications[2];
        }

        //if borderlined
        elseif (
            $applications[0]->applicationstatusid == 7
            &&  $applications[1]->applicationstatusid == 3
            &&  $applications[2]->applicationstatusid == 3
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 7
            &&  $applications[2]->applicationstatusid == 3
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 7
            &&  $applications[2]->applicationstatusid == 3
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 7
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 7
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 7
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 7
        ) {
            return $applications[2];
        }

        //if interview-offer
        elseif (
            $applications[0]->applicationstatusid == 8
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 6
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 8
            &&  $applications[2]->applicationstatusid == 6
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 8
            &&  $applications[2]->applicationstatusid == 6
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 8
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 8
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 8
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 8
        ) {
            return $applications[2];
        }

        //if offer
        elseif (
            $applications[0]->applicationstatusid == 9
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 6
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 9
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 10
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 9
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 6
        ) {
            return $applications[0];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 9
            &&  $applications[2]->applicationstatusid == 6
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 9
            &&  $applications[2]->applicationstatusid == 6
        ) {
            return $applications[1];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 9
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 9
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 9
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 9
        ) {
            return $applications[2];
        }

        //if reject of interview offer
        elseif (
            $applications[0]->applicationstatusid == 10
            &&  $applications[1]->applicationstatusid == 10
            &&  $applications[2]->applicationstatusid == 10
        ) {
            return $applications[2];
        } elseif (
            $applications[0]->applicationstatusid == 6
            &&  $applications[1]->applicationstatusid == 6
            &&  $applications[2]->applicationstatusid == 10
        ) {
            return $applications[2];
        }

        //if abandoned
        elseif (
            $applications[0]->applicationstatusid == 11
            &&  $applications[1]->applicationstatusid == 11
            &&  $applications[2]->applicationstatusid == 11
        ) {
            return $applications[2];
        }
    }


    public static function getImplicitlyLabelledCurrentApplication(
        $verifiedApplications
    ) {
        if (
            self::hasAdminCreatedApplication($verifiedApplications) == true
            || self::hasDeprecatedApplications($verifiedApplications) == true
        ) {
            return end($verifiedApplications);
        }
        // classify collection of applicant defined programme choices
        $numberOfApplications = count($verifiedApplications);
        if ($numberOfApplications == 1) {
            return $verifiedApplications[0];
        } elseif ($numberOfApplications == 2) {
            return self::getCurrentOfTwoApplications($verifiedApplications);
        } elseif ($numberOfApplications == 3) {
            return self::getCurrentOfThreeApplications($verifiedApplications);
        }
        return null;
    }

    public static function getCurrentApplication(
        $applicantUserId,
        $verifiedApplications
    ) {
        $explicitlyLabelledCurrentApplication =
            self::getExplicitlyLabelledCurrentApplication($applicantUserId);

        if ($explicitlyLabelledCurrentApplication == true) {
            return $explicitlyLabelledCurrentApplication;
        } else {
            return self::getImplicitlyLabelledCurrentApplication(
                $verifiedApplications
            );
        }
    }


    public static function hasPublishedRejection($verifiedApplications)
    {
        $ids = array();
        foreach ($verifiedApplications as $application) {
            $ids[] = $application->applicationid;
        }

        $rejections =
            Rejection::find()
            ->innerJoin(
                "rejection_applications",
                "`rejection`.`rejectionid` = `rejection_applications`.`rejectionid`"
            )
            ->where([
                "rejection.ispublished" => 1,
                "rejection.isactive" => 1,
                "rejection.isdeleted" => 0,
                "rejection_applications.applicationid" => $ids,
                "rejection_applications.isdeleted" => 0
            ])
            ->all();

        if ($rejections == true) {
            return true;
        }
        return false;
    }


    public static function hasPublishedOffer($verifiedApplications)
    {
        $ids = array();
        foreach ($verifiedApplications as $application) {
            $ids[] = $application->applicationid;
        }

        $offers =
            Offer::find()
            ->where([
                "applicationid" => $ids,
                "ispublished" => 1,
                "offer.isdeleted" => 0,
                "offertypeid" => 1
            ])
            ->all();

        if ($offers == true) {
            return true;
        }
        return false;
    }

    public static function generateAvailableStatusOptions($application)
    {
        $ids = array();
        $names = array();
        $container = array();

        if ($application->applicationstatusid == 6) {        //if reject before interview
            array_push($ids, 3);
            array_push($ids, 4);
            array_push($ids, 7);
            if (AcademicOfferingModel::requiresInterview($application->applicationid) == true) {
                array_push($ids, 8);
            } else {
                array_push($ids, 9);
            }

            array_push($names, "Pending");
            array_push($names, "Shortlist");
            array_push($names, "Borderline");
            if (AcademicOfferingModel::requiresInterview($application->applicationid) == true) {
                array_push($names, "Interviewee");
            } else {
                array_push($names, "Offer");
            }
        } elseif ($application->applicationstatusid == 3) {        //if pending
            array_push($ids, 4);
            array_push($ids, 7);
            if (AcademicOfferingModel::requiresInterview($application->applicationid) == true) {
                array_push($ids, 8);
            } else {
                array_push($ids, 9);
            }
            array_push($ids, 6);

            array_push($names, "Shortlist");
            array_push($names, "Borderline");
            if (AcademicOfferingModel::requiresInterview($application->applicationid) == true) {
                array_push($names, "Interviewee");
            } else {
                array_push($names, "Offer");
            }
            array_push($names, "Reject");
        } elseif ($application->applicationstatusid == 4) {        //if shortlist
            array_push($ids, 3);
            array_push($ids, 7);
            if (AcademicOfferingModel::requiresInterview($application->applicationid) == true) {
                array_push($ids, 8);
            } else {
                array_push($ids, 9);
            }
            array_push($ids, 6);

            array_push($names, "Pending");
            array_push($names, "Borderline");
            if (AcademicOfferingModel::requiresInterview($application->applicationid) == true) {
                array_push($names, "Interviewee");
            } else {
                array_push($names, "Offer");
            }
            array_push($names, "Reject");
        } elseif ($application->applicationstatusid == 7) {        //if borderline
            array_push($ids, 3);
            array_push($ids, 4);
            if (AcademicOfferingModel::requiresInterview($application->applicationid) == true) {
                array_push($ids, 8);
            } else {
                array_push($ids, 9);
            }
            array_push($ids, 6);
            array_push($names, "Pending");
            array_push($names, "Shortlist");
            if (AcademicOfferingModel::requiresInterview($application->applicationid) == true) {
                array_push($names, "Interviewee");
            } else {
                array_push($names, "Offer");
            }
            array_push($names, "Reject");

            array_push($container, $ids);
            array_push($container, $names);
        } elseif ($application->applicationstatusid == 8) {        //if interview/condition offer
            array_push($ids, 3);
            array_push($ids, 4);
            array_push($ids, 7);
            array_push($ids, 6);        //just incase "Interview/Conditional Offer" was given in error
            array_push($ids, 9);
            array_push($ids, 10);

            array_push($names, "Pending");
            array_push($names, "Shortlist");
            array_push($names, "Borderline");
            array_push($names, "Reject");       //just incase "Interview/Conditional Offer" was given in error
            array_push($names, "Offer");
            array_push($names, "Reject Interviewee");
        } elseif ($application->applicationstatusid == 9) {        //if offer
            /*
             * If it's a programme that requires interview, then once an offer is
             * given the only possible action susequent to this is th rejection
             * of this conditional offer
             */
            if (AcademicOfferingModel::requiresInterview($application->applicationid) == true) {
                array_push($ids, 10);
                array_push($names, "Reject Interviewee");
            }

            /*
             * If it's a programme that doesn't require interview, then more
             * options are available to the user
             */ else {
                array_push($ids, 3);
                array_push($ids, 4);
                array_push($ids, 7);
                array_push($ids, 6);

                array_push($names, "Pending");
                array_push($names, "Shortlist");
                array_push($names, "Borderline");
                array_push($names, "Reject");
            }
        } elseif ($application->applicationstatusid == 10) {        //if reject after interview
            array_push($ids, 9);
            array_push($names, "Offer");
        }
        return array_combine($ids, $names);
    }


    public static function hasMidwiferyApplication($id)
    {
        return $midwiferyApplication =
            Application::find()
            ->innerJoin(
                "academic_offering",
                "`application`.`academicofferingid` = `academic_offering`.`academicofferingid`"
            )
            ->innerJoin(
                "programme_catalog",
                "`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`"
            )
            ->where([
                "application.personid" => $id,
                "application.isactive" => 1,
                "application.isdeleted" => 0,
                "academic_offering.isactive" => 1,
                "academic_offering.isdeleted" => 0,
                "programme_catalog.name" => "Midwifery"
            ])
            ->all();

        if ($midwiferyApplication == true) {
            return true;
        }
        return false;
    }
}
