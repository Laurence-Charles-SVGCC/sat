<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Request;
use yii\helpers\Json;
use yii\base\Model;

use common\models\User;
use frontend\models\Applicant;
use frontend\models\DocumentSubmitted;
use frontend\models\CsecQualification;
use frontend\models\ApplicationStatus;
use frontend\models\Application;
use frontend\models\CsecCentre;
use frontend\models\PostSecondaryQualification;
use frontend\models\Subject;
use frontend\models\ExaminationProficiencyType;
use frontend\models\ExaminationGrade;
use frontend\models\ExaminationBody;
use frontend\models\Division;
use frontend\models\ExternalQualification;
use frontend\models\Employee;

use common\models\CsecCentreModel;


class VerifyApplicantsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $data = array();

        $has_external = count(Application::getExternal());
        if ($has_external > 0) {
            $amt_received = count(Application::getExternal());
            $external_amt_verified =
                CsecCentreModel::centreApplicantsVerifiedCount(NULL, true);
            $centre_row = array();
            $centre_row['centre_name'] = "External";
            $centre_row['centre_id'] = '00000';
            $centre_row['status'] =
                ($amt_received - $external_amt_verified) <= 0 ? "Complete" : "Incomplete";
            $centre_row['applicants_verified'] = $external_amt_verified;
            $centre_row['total_received'] = $amt_received;
            $centre_row['percentage_completed'] =
                $amt_received == 0 ? 0 : round(($external_amt_verified / $amt_received) * 100, 2);
            array_push($data, $centre_row);
        }

        $current_centres = CsecCentre::getCurrentCentres();

        /*
         *  If there is an active application the associated csec centres are retreived
         */
        if ($current_centres == true) {
            foreach ($current_centres as $centre) {
                $amt_received = Application::centreApplicantsReceivedCount($centre->cseccentreid);
                $amt_verified = Application::centreApplicantsVerifiedCount($centre->cseccentreid);

                $centre_row = array();
                $centre_row['centre_name'] = $centre->name;
                $centre_row['centre_id'] = $centre->cseccentreid;
                $centre_row['status'] = ($amt_received - $amt_verified) <= 0 ? "Complete" : "Incomplete";
                $centre_row['applicants_verified'] = $amt_verified;
                $centre_row['total_received'] = $amt_received;
                $centre_row['percentage_completed'] = $amt_received == 0 ? 0 : round(($amt_verified / $amt_received) * 100, 2);
                array_push($data, $centre_row);
            }
        }

        if (!empty($data)) {
            $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 30,
                ],
                'sort' => [
                    'defaultOrder' => ['centre_name' => SORT_ASC],
                    'attributes' => ['centre_name', 'status', 'applicants_verified', 'total_received', 'percentage_completed'],
                ],
            ]);
        } else
            $dataProvider = false;

        return $this->render(
            'index',
            ['dataProvider' => $dataProvider]
        );
    }



    /**
     * Renders the "Abandoned Application Index"
     *
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 04/05/2016
     * Date Last Modified: 04/05/2016
     */
    public function actionIndexAbandoned()
    {
        $data = array();
        $current_centres = CsecCentre::getAbandonedCurrentCentres();

        /*
         *  If there is an abanadoned applications the associated csec centres are retreived
         */
        if ($current_centres == true) {
            foreach ($current_centres as $centre) {
                $amt_received = Application::centreAbandonedApplicantsReceivedCount($centre->cseccentreid);
                if ($amt_received > 0) {
                    $centre_row = array();
                    $centre_row['centre_name'] = $centre->name;
                    $centre_row['centre_id'] = $centre->cseccentreid;
                    $centre_row['total_received'] = $amt_received;
                    array_push($data, $centre_row);
                }
            }

            //For external applicants
            $amt_received = count(Application::getAbandonedExternal());
            $centre_row = array();
            $centre_row['centre_name'] = "External";
            $centre_row['centre_id'] = '00000';
            $centre_row['total_received'] = $amt_received;
            array_push($data, $centre_row);

            $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 30,
                ],
                'sort' => [
                    'defaultOrder' => ['centre_name' => SORT_ASC],
                    'attributes' => ['centre_name', 'total_received'],
                ],
            ]);
        } else
            $dataProvider = false;

        return $this->render(
            'index_abandoned',
            ['dataProvider' => $dataProvider]
        );
    }


    public function actionCentreDetails($centre_id, $centre_name)
    {
        if (strcasecmp($centre_name, "external") == 0) {
            $amtReceived = count(Application::getExternal());
            $amtVerified =
                CsecCentreModel::centreApplicantsVerifiedCount($centre_id, true);
            $amtQueried =
                CsecCentreModel::centreApplicantsQueriedCount($centre_id, true);
            $amtPending =
                CsecCentreModel::centreApplicantsPendingCount($centre_id, true);
        } else {
            $amtReceived =
                Application::centreApplicantsReceivedCount($centre_id);
            $amtVerified =
                Application::centreApplicantsVerifiedCount($centre_id);
            $amtQueried =
                Application::centreApplicantsQueriedCount($centre_id);
            $amtPending =
                Application::centreApplicantsPendingCount($centre_id);
        }

        return $this->render(
            'centre-details',
            [
                'centre_name' => $centre_name,
                'centre_id' => $centre_id,
                'pending' => $amtPending,
                'verified' => $amtVerified,
                'queried' => $amtQueried,
                'total' => $amtReceived,
            ]
        );
    }


    /**
     * Displays verification dashboard of a centre
     *
     * @param type $centre_id
     * @param type $centre_name
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 04/05/2016
     * Date Last Modified: 04/05/2016
     */
    public function actionAbandonedCentreDetails($centreid, $centrename)
    {
        if (strcasecmp($centrename, "external") == 0) {
            $applicants = Application::getAbandonedExternal();
            $data = array();

            foreach ($applicants as $applicant) {
                $container = array();

                $container["personid"] = $applicant->personid;
                $container["firstname"] = $applicant->firstname;
                $container["middlename"] = $applicant->middlename;
                $container["lastname"] = $applicant->lastname;
                $container["gender"] = $applicant->gender;

                /**************     flags possible duplicates    **************/
                $duplicate_message = false;
                $possible_applicant_matches = Applicant::find()
                    ->where(['firstname' => $applicant->firstname, 'lastname' => $applicant->lastname])
                    ->all();

                if (empty($possible_applicant_matches) == true) {
                    $duplicate_message = "N/A";
                } else {
                    foreach ($possible_applicant_matches as $match) {
                        $matches_application = Application::find()
                            ->where(['personid' => $match->personid, 'applicationstatusid' => [2, 3, 4, 5, 6, 7, 8, 9, 10]])
                            ->all();
                        if (empty($matches_application) == false && $match->personid != $applicant->personid) {
                            $user = User::findOne(['personid' => $match->personid, 'isdeleted' => 0]);
                            $duplicate_message .= ' ' . $user->username . ', ';
                        }
                    }
                }

                $container["related_accounts"] = $duplicate_message;
                /**************************************************************/

                $container["verifier"] = "N/A";

                $applications = Application::getApplications($applicant->personid);
                $divisionid = $applications[0]->divisionid;
                $division = Division::getDivisionAbbreviation($divisionid);

                /*
                 * If division is DTE or DNE then all applications refer to one division
                 */
                if ($divisionid == 6  || $divisionid == 7) {
                    $container["division"] = $division;
                }

                /*
                 * If division is DASGS or DTVE then applications may refer to multiple divisions
                 */
                if ($divisionid == 4  || $divisionid == 5) {
                    foreach ($applications as $application) {
                        $divID = $application->divisionid;
                        $div = Division::getDivisionAbbreviation($divID);
                        $divisions = " " . $div . " ";
                    }
                    $container["division"] = $divisions;
                }
                $data[] = $container;
            }
        } else {
            $data = array();

            foreach (Application::centreAbandonedApplicantsReceived($centreid) as $application) {
                $container = array();

                $applicant = Applicant::find()->where(['personid' => $application->personid])->one();

                if ($applicant->isexternal == 0) {
                    $container["personid"] = $applicant->personid;
                    $container["firstname"] = $applicant->firstname;
                    $container["middlename"] = $applicant->middlename;
                    $container["lastname"] = $applicant->lastname;
                    $container["gender"] = $applicant->gender;

                    /**************     flags possible duplicates    **************/
                    $duplicate_message = false;
                    $certificates = CsecQualification::getSubjects($application->personid);
                    if (empty($certificates) == true) {
                        $duplicate_message = "N/A";
                    } else {
                        $dups = CsecQualification::getPossibleDuplicate($application->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                        $message = '';
                        if ($dups) {
                            $dupes = '';
                            foreach ($dups as $dup) {
                                $user = User::findOne(['personid' => $dup, 'isdeleted' => 0]);
                                $dupes = $user ? $dupes . ' ' . $user->username : $dupes;
                            }
                            $message .= $dupes;
                        }
                        $reapp = CsecQualification::getPossibleReapplicant($application->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                        if ($reapp) {
                            $message .= ' pre-2015/2016 applicant';
                        }
                        if ($dups || $reapp) {
                            $duplicate_message = $message;
                        }
                    }
                    $container["related_accounts"] = $duplicate_message;
                    /**************************************************************/

                    $container["verifier"] = "N/A";

                    $applications = Application::getApplications($applicant->personid);
                    $divisionid = $applications[0]->divisionid;
                    $division = Division::getDivisionAbbreviation($divisionid);

                    /*
                     * If division is DTE or DNE then all applications refer to one division
                     */
                    if ($divisionid == 6  || $divisionid == 7) {
                        $container["division"] = $division;
                    }

                    /*
                     * If division is DASGS or DTVE then applications may refer to multiple divisions
                     */
                    if ($divisionid == 4  || $divisionid == 5) {
                        foreach ($applications as $application) {
                            $divID = $application->divisionid;
                            $div = Division::getDivisionAbbreviation($divID);
                            $divisions = " " . $div . " ";
                        }
                        $container["division"] = $divisions;
                    }
                    $data[] = $container;
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                'attributes' => ['personid', 'firstname', 'lastname', 'gender', 'division'],
            ],
        ]);


        return $this->render(
            'view-applicant',
            [
                'dataProvider' => $dataProvider,
                'type' => 'Abandoned',
                'centrename' => $centrename,
                'centreid' => $centreid,
            ]
        );
    }


    /*
    * Purpose: Displays all applicants from a given centre
    * Created: 15/07/2015 by Gii
    * Last Modified: 16/07/2015 by Gamal Crichton
    */
    public function actionViewAll($cseccentreid, $centrename)
    {
        //        if (strcasecmp($centrename, "external") == 0)
        //        {
        //            $data = Application::getExternal();
        //        }
        //        else
        //        {
        //            $data = array();
        //            foreach(Application::centreApplicantsReceived($cseccentreid) as $application)
        //            {
        //                $data[] = Applicant::find()
        //                        ->where(['personid' => $application->personid])
        //                        ->one();
        //            }
        //        }
        //        $dataProvider = new ArrayDataProvider([
        //            'allModels' => $data,
        //            'pagination' => [
        //                'pageSize' => 20,
        //            ],
        //            'sort' => [
        //                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
        //                'attributes' => ['personid', 'firstname', 'middlenames', 'lastname', 'gender'],
        //            ],
        //        ]);


        if (strcasecmp($centrename, "external") == 0) {
            $applicants = Application::getExternal();
            $data = array();

            foreach ($applicants as $applicant) {
                $container = array();

                $container["personid"] = $applicant->personid;
                $container["firstname"] = $applicant->firstname;
                $container["middlename"] = $applicant->middlename;
                $container["lastname"] = $applicant->lastname;
                $container["gender"] = $applicant->gender;

                /**************     flags possible duplicates    **************/
                $duplicate_message = false;
                $possible_applicant_matches = Applicant::find()
                    ->where(['firstname' => $applicant->firstname, 'lastname' => $applicant->lastname])
                    ->all();

                if (empty($possible_applicant_matches) == true) {
                    $duplicate_message = "N/A";
                } else {
                    foreach ($possible_applicant_matches as $match) {
                        $matches_application = Application::find()
                            ->where(['personid' => $match->personid, 'applicationstatusid' => [2, 3, 4, 5, 6, 7, 8, 9, 10]])
                            ->all();
                        if (empty($matches_application) == false && $match->personid != $applicant->personid) {
                            $user = User::findOne(['personid' => $match->personid, 'isdeleted' => 0]);
                            $duplicate_message .= ' ' . $user->username . ', ';
                        }
                    }
                }

                $container["related_accounts"] = $duplicate_message;
                /**************************************************************/

                if (!Applicant::isVerified($applicant->personid)) {
                    $verifier = "N/A";
                } else {
                    //                     if (!$applicant->verifier)
                    //                     {
                    //                         $verifier = "Unknown";
                    //                     }
                    //                     else
                    //                     {
                    //                         $verifier = Employee::getEmployeeName($applicant->verifier);
                    //                     }
                    $verifier = Employee::getEmployeeName($applicant->verifier);
                }
                $container["verifier"] = $verifier;

                $applications = Application::getApplications($applicant->personid);
                $divisionid = $applications[0]->divisionid;
                $division = Division::getDivisionAbbreviation($divisionid);

                /*
                 * If division is DTE or DNE then all applications refer to one division
                 */
                if ($divisionid == 6  || $divisionid == 7) {
                    $container["division"] = $division;
                }

                /*
                 * If division is DASGS or DTVE then applications may refer to multiple divisions
                 */
                if ($divisionid == 4  || $divisionid == 5) {
                    foreach ($applications as $application) {
                        $divID = $application->divisionid;
                        $div = Division::getDivisionAbbreviation($divID);
                        $divisions = " " . $div . " ";
                    }
                    $container["division"] = $divisions;
                }
                $data[] = $container;
            }
        } else {
            $data = array();

            foreach (Application::centreApplicantsReceived($cseccentreid) as $application) {
                $container = array();

                $applicant = Applicant::find()->where(['personid' => $application->personid])->one();

                if ($applicant->isexternal == 0) {
                    $container["personid"] = $applicant->personid;
                    $container["firstname"] = $applicant->firstname;
                    $container["middlename"] = $applicant->middlename;
                    $container["lastname"] = $applicant->lastname;
                    $container["gender"] = $applicant->gender;

                    /**************     flags possible duplicates    **************/
                    $duplicate_message = false;
                    $certificates = CsecQualification::getSubjects($application->personid);
                    if (empty($certificates) == true) {
                        $duplicate_message = "N/A";
                    } else {
                        $dups = CsecQualification::getPossibleDuplicate($application->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                        $message = '';
                        if ($dups) {
                            $dupes = '';
                            foreach ($dups as $dup) {
                                $user = User::findOne(['personid' => $dup, 'isdeleted' => 0]);
                                $dupes = $user ? $dupes . ' ' . $user->username : $dupes;
                            }
                            $message .= $dupes;
                        }
                        $reapp = CsecQualification::getPossibleReapplicant($application->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                        if ($reapp) {
                            $message .= ' pre-2015/2016 applicant';
                        }
                        if ($dups || $reapp) {
                            $duplicate_message = $message;
                        }
                    }
                    $container["related_accounts"] = $duplicate_message;
                    /**************************************************************/

                    if (!Applicant::isVerified($applicant->personid)) {
                        $verifier = "N/A";
                    } else {
                        if (!$applicant->verifier) {
                            $verifier = "Unknown";
                        } else {
                            $verifier = Employee::getEmployeeName($applicant->verifier);
                        }
                    }
                    $container["verifier"] = $verifier;

                    $applications = Application::getApplications($applicant->personid);
                    $divisionid = $applications[0]->divisionid;
                    $division = Division::getDivisionAbbreviation($divisionid);

                    /*
                     * If division is DTE or DNE then all applications refer to one division
                     */
                    if ($divisionid == 6  || $divisionid == 7) {
                        $container["division"] = $division;
                    }

                    /*
                     * If division is DASGS or DTVE then applications may refer to multiple divisions
                     */
                    if ($divisionid == 4  || $divisionid == 5) {
                        foreach ($applications as $application) {
                            $divID = $application->divisionid;
                            $div = Division::getDivisionAbbreviation($divID);
                            $divisions = " " . $div . " ";
                        }
                        $container["division"] = $divisions;
                    }
                    $data[] = $container;
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                'attributes' => ['personid', 'firstname', 'lastname', 'gender', 'division'],
            ],
        ]);


        return $this->render(
            'view-applicant',
            [
                'dataProvider' => $dataProvider,
                'type' => 'All',
                'centrename' => $centrename,
                'centreid' => $cseccentreid,
            ]
        );
    }


    public function actionViewPending($cseccentreid, $centrename)
    {
        if (strcasecmp($centrename, "external") == 0) {
            $data = array();

            foreach (CsecCentreModel::centreApplicantsPending($cseccentreid, true) as $applicant) {
                $container = array();
                $container["personid"] = $applicant->personid;
                $container["firstname"] = $applicant->firstname;
                $container["middlename"] = $applicant->middlename;
                $container["lastname"] = $applicant->lastname;
                $container["gender"] = $applicant->gender;

                /**************     flags possible duplicates    **************/
                $duplicate_message = false;
                $possible_applicant_matches = Applicant::find()
                    ->where(['firstname' => $applicant->firstname, 'lastname' => $applicant->lastname])
                    ->all();

                if (empty($possible_applicant_matches) == true) {
                    $duplicate_message = "N/A";
                } else {
                    foreach ($possible_applicant_matches as $match) {
                        $matches_application = Application::find()
                            ->where(['personid' => $match->personid, 'applicationstatusid' => [2, 3, 4, 5, 6, 7, 8, 9, 10]])
                            ->all();
                        if (empty($matches_application) == false && $match->personid != $applicant->personid) {
                            $user = User::findOne(['personid' => $match->personid, 'isdeleted' => 0]);
                            $duplicate_message .= ' ' . $user->username . ', ';
                        }
                    }
                }

                $container["related_accounts"] = $duplicate_message;
                /**************************************************************/

                $container["verifier"] = "N/A";

                $applications = Application::getApplications($applicant->personid);
                $divisionid = $applications[0]->divisionid;
                $division = Division::getDivisionAbbreviation($divisionid);

                /*
                 * If division is DTE or DNE then all applications refer to one division
                 */
                if ($divisionid == 6  || $divisionid == 7) {
                    $container["division"] = $division;
                }

                /*
                 * If division is DASGS or DTVE then applications may refer to multiple divisions
                 */
                if ($divisionid == 4  || $divisionid == 5) {
                    $divisions = " ";
                    foreach ($applications as $application) {
                        $divID = $application->divisionid;
                        $div = Division::getDivisionAbbreviation($divID);
                        $divisions .= $div . ",  ";
                    }
                    $container["division"] = $divisions;
                }
                $data[] = $container;
            }
        } else {
            $data = array();

            foreach (Application::centreApplicantsPending($cseccentreid) as $application) {
                $container = array();

                $applicant = Applicant::find()->where(['personid' => $application->personid])->one();

                if ($applicant->isexternal == 0) {
                    $container["personid"] = $applicant->personid;
                    $container["firstname"] = $applicant->firstname;
                    $container["middlename"] = $applicant->middlename;
                    $container["lastname"] = $applicant->lastname;
                    $container["gender"] = $applicant->gender;

                    /**************     flags possible duplicates    **************/
                    $duplicate_message = false;
                    $certificates = CsecQualification::getSubjects($application->personid);
                    if (empty($certificates) == true) {
                        $duplicate_message = "N/A";
                    } else {
                        $dups = CsecQualification::getPossibleDuplicate($application->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                        $message = '';
                        if ($dups) {
                            $dupes = '';
                            foreach ($dups as $dup) {
                                $user = User::findOne(['personid' => $dup, 'isdeleted' => 0]);
                                $dupes = $user ? $dupes . ' ' . $user->username : $dupes;
                            }
                            $message .= $dupes;
                        }
                        $reapp = CsecQualification::getPossibleReapplicant($application->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                        if ($reapp) {
                            $message .= ' pre-2015/2016 applicant';
                        }
                        if ($dups || $reapp) {
                            $duplicate_message = $message;
                        }
                    }
                    $container["related_accounts"] = $duplicate_message;
                    /**************************************************************/

                    $container["verifier"] = "N/A";

                    $applications = Application::getApplications($applicant->personid);
                    $divisionid = $applications[0]->divisionid;
                    $division = Division::getDivisionAbbreviation($divisionid);

                    /*
                     * If division is DTE or DNE then all applications refer to one division
                     */
                    if ($divisionid == 6  || $divisionid == 7) {
                        $container["division"] = $division;
                    }

                    /*
                     * If division is DASGS or DTVE then applications may refer to multiple divisions
                     */
                    if ($divisionid == 4  || $divisionid == 5) {
                        $divisions = "  ";
                        foreach ($applications as $application) {
                            $divID = $application->divisionid;
                            $div = Division::getDivisionAbbreviation($divID);
                            $divisions .= $div . ",  ";
                        }
                        $container["division"] = $divisions;
                    }
                    $data[] = $container;
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                'attributes' => ['personid', 'firstname', 'lastname', 'gender', 'division'],
            ],
        ]);

        return $this->render(
            'view-applicant',
            [
                'dataProvider' => $dataProvider,
                'type' => 'Pending',
                'centrename' => $centrename,
                'centreid' => $cseccentreid,
            ]
        );
    }


    public function actionViewVerified($cseccentreid, $centrename)
    {
        if (strcasecmp($centrename, "external") == 0) {
            $data = array();

            foreach (CsecCentreModel::centreApplicantsVerified($cseccentreid, true) as $applicant) {
                $container = array();

                $container["personid"] = $applicant->personid;
                $container["firstname"] = $applicant->firstname;
                $container["middlename"] = $applicant->middlename;
                $container["lastname"] = $applicant->lastname;
                $container["gender"] = $applicant->gender;

                /**************     flags possible duplicates    **************/
                $duplicate_message = false;
                $possible_applicant_matches = Applicant::find()
                    ->where(['firstname' => $applicant->firstname, 'lastname' => $applicant->lastname])
                    ->all();

                if (empty($possible_applicant_matches) == true) {
                    $duplicate_message = "N/A";
                } else {
                    foreach ($possible_applicant_matches as $match) {
                        $matches_application = Application::find()
                            ->where(['personid' => $match->personid, 'applicationstatusid' => [2, 3, 4, 5, 6, 7, 8, 9, 10]])
                            ->all();
                        if (empty($matches_application) == false && $match->personid != $applicant->personid) {
                            $user = User::findOne(['personid' => $match->personid, 'isdeleted' => 0]);
                            $duplicate_message .= ' ' . $user->username . ', ';
                        }
                    }
                }

                $container["related_accounts"] = $duplicate_message;
                /**************************************************************/

                if (!$applicant->verifier) {
                    $verifier = "Unknown";
                } else {
                    $verifier = Employee::getEmployeeName($applicant->verifier);
                }
                $container["verifier"] = $verifier;

                $applications = Application::getApplications($applicant->personid);
                $divisionid = $applications[0]->divisionid;
                $division = Division::getDivisionAbbreviation($divisionid);

                /*
                 * If division is DTE or DNE then all applications refer to one division
                 */
                if ($divisionid == 6  || $divisionid == 7) {
                    $container["division"] = $division;
                }

                /*
                 * If division is DASGS or DTVE then applications may refer to multiple divisions
                 */
                if ($divisionid == 4  || $divisionid == 5) {
                    foreach ($applications as $application) {
                        $divID = $application->divisionid;
                        $div = Division::getDivisionAbbreviation($divID);
                        $divisions = " " . $div . " ";
                    }
                    $container["division"] = $divisions;
                }
                $data[] = $container;
            }
        } else {
            $data = array();
            foreach (Application::centreApplicantsVerified($cseccentreid) as $application) {
                $container = array();

                $applicant = Applicant::find()->where(['personid' => $application->personid])->one();

                if ($applicant->isexternal == 0) {
                    $container["personid"] = $applicant->personid;
                    $container["firstname"] = $applicant->firstname;
                    $container["middlename"] = $applicant->middlename;
                    $container["lastname"] = $applicant->lastname;
                    $container["gender"] = $applicant->gender;

                    /**************     flags possible duplicates    **************/
                    $duplicate_message = false;
                    $certificates = CsecQualification::getSubjects($applicant->personid);
                    if (empty($certificates) == true) {
                        $duplicate_message = "N/A";
                    } else {
                        $dups = CsecQualification::getPossibleDuplicate($applicant->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                        $message = '';
                        if ($dups) {
                            $dupes = '';
                            foreach ($dups as $dup) {
                                $user = User::findOne(['personid' => $dup, 'isdeleted' => 0]);
                                $dupes = $user ? $dupes . ' ' . $user->username . ', ' : $dupes;
                            }
                            $message .= $dupes;
                        }
                        $reapp = CsecQualification::getPossibleReapplicant($applicant->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                        if ($reapp) {
                            $message .= ' pre-2015/2016 applicant';
                        }
                        if ($dups || $reapp) {
                            $duplicate_message = $message;
                        }
                    }
                    $container["related_accounts"] = $duplicate_message;
                    /**************************************************************/

                    if (!$applicant->verifier) {
                        $verifier = "Unknown";
                    } else {
                        $verifier = Employee::getEmployeeName($applicant->verifier);
                    }
                    $container["verifier"] = $verifier;


                    $applications = Application::getApplications($applicant->personid);
                    $divisionid = $applications[0]->divisionid;
                    $division = Division::getDivisionAbbreviation($divisionid);

                    /*
                     * If division is DTE or DNE then all applications refer to one division
                     */
                    if ($divisionid == 6  || $divisionid == 7) {
                        $container["division"] = $division;
                    }

                    /*
                     * If division is DASGS or DTVE then applications may refer to multiple divisions
                     */
                    if ($divisionid == 4  || $divisionid == 5) {
                        foreach ($applications as $application) {
                            $divID = $application->divisionid;
                            $div = Division::getDivisionAbbreviation($divID);
                            $divisions = " " . $div . " ";
                        }
                        $container["division"] = $divisions;
                    }
                    $data[] = $container;
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                'attributes' => ['personid', 'firstname', 'lastname', 'gender', 'division'],
            ],
        ]);

        return $this->render(
            'view-applicant',
            [
                'dataProvider' => $dataProvider,
                'type' => 'Verified',
                'centrename' => $centrename,
                'centreid' => $cseccentreid,
            ]
        );
    }


    public function actionViewQueried($cseccentreid, $centrename)
    {
        if (strcasecmp($centrename, "external") == 0) {
            $data = array();

            // foreach (Application::centreApplicantsQueried($cseccentreid, true) as $application) {
            foreach (Application::centreApplicantsQueried($cseccentreid, true) as $application) {
                $container = array();

                $applicant = Applicant::find()->where(['personid' => $application->personid])->one();

                $container["personid"] = $applicant->personid;
                $container["firstname"] = $applicant->firstname;
                $container["middlename"] = $applicant->middlename;
                $container["lastname"] = $applicant->lastname;
                $container["gender"] = $applicant->gender;

                /**************     flags possible duplicates    **************/
                $duplicate_message = false;
                $possible_applicant_matches = Applicant::find()
                    ->where(['firstname' => $applicant->firstname, 'lastname' => $applicant->lastname])
                    ->all();

                if (empty($possible_applicant_matches) == true) {
                    $duplicate_message = "N/A";
                } else {
                    foreach ($possible_applicant_matches as $match) {
                        $matches_application = Application::find()
                            ->where(['personid' => $match->personid, 'applicationstatusid' => [2, 3, 4, 5, 6, 7, 8, 9, 10]])
                            ->all();
                        if (empty($matches_application) == false && $match->personid != $applicant->personid) {
                            $user = User::findOne(['personid' => $match->personid, 'isdeleted' => 0]);
                            $duplicate_message .= ' ' . $user->username . ', ';
                        }
                    }
                }

                $container["related_accounts"] = $duplicate_message;
                /**************************************************************/

                $container["verifier"] = "N/A";

                $applications = Application::getApplications($applicant->personid);
                $divisionid = $applications[0]->divisionid;
                $division = Division::getDivisionAbbreviation($divisionid);

                /*
                 * If division is DTE or DNE then all applications refer to one division
                 */
                if ($divisionid == 6  || $divisionid == 7) {
                    $container["division"] = $division;
                }

                /*
                 * If division is DASGS or DTVE then applications may refer to multiple divisions
                 */
                if ($divisionid == 4  || $divisionid == 5) {
                    foreach ($applications as $application) {
                        $divID = $application->divisionid;
                        $div = Division::getDivisionAbbreviation($divID);
                        $divisions = " " . $div . " ";
                    }
                    $container["division"] = $divisions;
                }
                $data[] = $container;
            }
        } else {
            $data = array();

            foreach (Application::centreApplicantsQueried($cseccentreid) as $application) {
                $container = array();

                $applicant = Applicant::find()->where(['personid' => $application->personid])->one();

                if ($applicant->isexternal == 0) {
                    $container["personid"] = $applicant->personid;
                    $container["firstname"] = $applicant->firstname;
                    $container["middlename"] = $applicant->middlename;
                    $container["lastname"] = $applicant->lastname;
                    $container["gender"] = $applicant->gender;

                    /**************     flags possible duplicates    **************/
                    $duplicate_message = false;
                    $certificates = CsecQualification::getSubjects($applicant->personid);
                    if (empty($certificates) == true) {
                        $duplicate_message = "N/A";
                    } else {
                        $dups = CsecQualification::getPossibleDuplicate($applicant->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                        $message = '';
                        if ($dups) {
                            $dupes = '';
                            foreach ($dups as $dup) {
                                $user = User::findOne(['personid' => $dup, 'isdeleted' => 0]);
                                $dupes = $user ? $dupes . ' ' . $user->username . ', ' : $dupes;
                            }
                            $message .= $dupes;
                        }
                        $reapp = CsecQualification::getPossibleReapplicant($applicant->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
                        if ($reapp) {
                            $message .= ' pre-2015/2016 applicant';
                        }
                        if ($dups || $reapp) {
                            $duplicate_message = $message;
                        }
                    }
                    $container["related_accounts"] = $duplicate_message;
                    /**************************************************************/

                    $container["verifier"] = "N/A";

                    $applications = Application::getApplications($applicant->personid);
                    $divisionid = $applications[0]->divisionid;
                    $division = Division::getDivisionAbbreviation($divisionid);

                    /*
                     * If division is DTE or DNE then all applications refer to one division
                     */
                    if ($divisionid == 6  || $divisionid == 7) {
                        $container["division"] = $division;
                    }

                    /*
                     * If division is DASGS or DTVE then applications may refer to multiple divisions
                     */
                    if ($divisionid == 4  || $divisionid == 5) {
                        foreach ($applications as $application) {
                            $divID = $application->divisionid;
                            $div = Division::getDivisionAbbreviation($divID);
                            $divisions = " " . $div . " ";
                        }
                        $container["division"] = $divisions;
                    }
                    $data[] = $container;
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                'attributes' => ['personid', 'firstname', 'lastname', 'gender', 'division'],
            ],
        ]);

        return $this->render(
            'view-applicant',
            [
                'dataProvider' => $dataProvider,
                'type' => 'Queried',
                'centrename' => $centrename,
                'centreid' => $cseccentreid,
            ]
        );
    }


    /*
    * Purpose: Displays all certificates for a given applicant from a given centre.
     * Handles actions from the display: add more certificates, save all as verified
     * and save changes.
    * Created: 15/07/2015 by Gamal Crichton
    * Last Modified: 18/07/2015 by Gamal Crichton | 20/03/2016 by L.Charles
    */
    public function actionViewApplicantQualifications($applicantid, $centrename, $cseccentreid, $type)
    {
        $request = Yii::$app->request;

        if ($post_data = Yii::$app->request->post()) {
            $post_qualification = PostSecondaryQualification::find()
                ->where(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();

            /*
             * Updates Post Qualification Record
             */
            if ($post_qualification == true)        //if post secondary qualification record exists
            {
                $post_secondary_save_flag = false;
                $post_secondary_load_flag = false;

                $post_secondary_load_flag = $post_qualification->load($post_data);
                if ($post_secondary_load_flag == true) {
                    $post_secondary_save_flag = $post_qualification->save();
                    if ($post_secondary_save_flag == false) {
                        Yii::$app->getSession()->setFlash('error', 'Error updating post secondary qualification record.');
                        return $this->redirect(\Yii::$app->request->getReferrer());
                    }
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Error loading post secondary qualification recrord.');
                    return $this->redirect(\Yii::$app->request->getReferrer());
                }
            }

            $external_qualification = ExternalQualification::find()
                ->where(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();

            /*
             * Updates ExternalQualification Record if it exists
             */
            if ($external_qualification == true)        //if external qualification record exists
            {
                $external_save_flag = false;
                $external_load_flag = false;

                $external_load_flag = $external_qualification->load($post_data);
                if ($external_load_flag == true) {
                    $external_save_flag = $external_qualification->save();
                    if ($external_save_flag == false) {
                        Yii::$app->getSession()->setFlash('error', 'Error updating external qualification record.');
                        return $this->redirect(\Yii::$app->request->getReferrer());
                    }
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Error loading external qualification recrord.');
                    return $this->redirect(\Yii::$app->request->getReferrer());
                }
            }

            $qualifications = CsecQualification::find()
                ->where(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0])
                ->all();


            /*
             * For applicants with external qualifications, their status is updated to pending
             * i. if they only have external_qualifications and it is verified
             * ii. if they have external_qualification + post_secondary both must be verified
             * iii. if they have external_qualification + post_secondary + csecqualification all three must be verified
             */
            if ($external_qualification == false &&  $post_qualification == false  && $qualifications == false) {
                //do nothing
            }
            //            elseif ($external_qualification == false &&  $post_qualification == true  && $qualifications == false)
            //            {
            //
            //            }
            elseif ($external_qualification == true &&  $post_qualification == false  && $qualifications == false) {
                if ($external_qualification->isverified == 1  && $external_qualification->isqueried == 0) {
                    $applications = Application::findAll(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0]);
                    foreach ($applications as $application) {
                        $application->applicationstatusid = 3;
                        $application->save();
                    }
                }
            } elseif ($external_qualification == true &&  $post_qualification == true  && $qualifications == false) {
                if (
                    $external_qualification->isverified == 1  && $external_qualification->isqueried == 0
                    && $post_qualification->isverified == 1  && $post_qualification->isqueried == 0
                ) {
                    $applications = Application::findAll(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0]);
                    foreach ($applications as $application) {
                        $application->applicationstatusid = 3;
                        $application->save();
                    }
                }
            } elseif ($request->post('CsecQualification')) {
                $verify_all = $request->post('verified') === '' ? true : false;

                //                $qualifications = CsecQualification::find()
                //                            ->where(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0])
                //                            ->all();
                $current_count = count($qualifications);
                $loaded_count = count($request->post('CsecQualification'));

                for ($j = 0; $j < $loaded_count; $j++) {
                    if ($j >= $current_count) {
                        $temp = new CsecQualification();
                        $qualifications[] = $temp;
                    }
                }

                $load_flag = false;

                $load_flag = Model::loadMultiple($qualifications, $post_data);
                if ($load_flag == false) {
                    Yii::$app->getSession()->setFlash('error', 'Error laoding records.');
                    return $this->redirect(\Yii::$app->request->getReferrer());
                } else {
                    foreach ($qualifications as $qual) {
                        if ($qual->personid == true) {
                            if ($verify_all == true) {
                                //Save as verified submit button
                                $qual->isverified = 1;
                                $qual->isqueried = 0;
                                if ($post_qualification == true) {
                                    $post_qualification->isverified = 1;
                                    $post_qualification->isqueried = 0;
                                    $post_qualification->save();
                                }
                                if ($external_qualification == true) {
                                    $external_qualification->isverified = 1;
                                    $external_qualification->isqueried = 0;
                                    $external_qualification->save();
                                }
                            }

                            if (!$qual->save()) {
                                Yii::$app->getSession()->setFlash('error', 'Could not add Certificate: ' . $qual['subjectid'] . ' ' . $qual['grade']);
                            }
                        }
                    }

                    $all_certs = count(CsecQualification::findAll(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0]));
                    $verified_certs = count(CsecQualification::findAll(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0, 'isverified' => 1]));

                    /*
                     * If post secondary qualification exists then both previous counts must take it into consideration
                     */
                    if ($post_qualification) {
                        $all_certs++;
                        if ($post_qualification->isverified == 1  && $post_qualification->isqueried == 0)
                            $verified_certs++;
                    }

                    /*
                     * If post secondary qualification exists then both previous counts must take it into consideration
                     */
                    if ($external_qualification) {
                        $all_certs++;
                        if ($external_qualification->isverified == 1 && $external_qualification->isqueried == 0)
                            $verified_certs++;
                    }


                    /*
                     * If all certifications are verified then all application statuses are set to "Pending'
                     */
                    if ($all_certs == $verified_certs) {
                        $target_applicant = Applicant::find()
                            ->where(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                        $target_applicant->verifier = Yii::$app->user->identity->personid;
                        $target_applicant->save();

                        $pending = ApplicationStatus::findOne(['name' => 'Pending']);
                        if ($pending) {
                            $applications = Application::findAll(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0]);
                            foreach ($applications as $application) {
                                $application->applicationstatusid = $pending->applicationstatusid;
                                $application->save();
                            }
                        }
                    }
                    /*
                     * If all certifications are not verified then all application statuses are reset to "Unverified'
                     */ else {
                        $unverified = ApplicationStatus::findOne(['name' => 'Unverified']);
                        if ($unverified) {
                            $applications = Application::findAll(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0]);
                            foreach ($applications as $application) {
                                $application->applicationstatusid = $unverified->applicationstatusid;
                                $application->save();
                            }
                        }
                    }
                }
            }

            //redirect
            if (strcasecmp($type, "pending") == 0) {
                return self::actionViewPending($cseccentreid, $centrename);
            } elseif (strcasecmp($type, "queried") == 0) {
                return self::actionViewQueried($cseccentreid, $centrename);
            } elseif (strcasecmp($type, "all") == 0) {
                return self::actionViewAll($cseccentreid, $centrename);
            } elseif (strcasecmp($type, "verified") == 0) {
                return self::actionViewVerified($cseccentreid, $centrename);
            }
        } else {
            $post_qualification = PostSecondaryQualification::find()
                ->where(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();


            $qualifications = CsecQualification::find()
                ->where(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
            $record_count = count($qualifications);

            for ($k = 0; $k < 10; $k++) {
                $temp = new CsecQualification();
                $temp->cseccentreid = "";
                $temp->candidatenumber = "";
                $temp->examinationbodyid = "";
                $temp->subjectid = "";
                $temp->examinationproficiencytypeid = "";
                $temp->examinationgradeid = "";
                $temp->year = "";
                $qualifications[] = $temp;
                $temp = NULL;
            }

            $applicant_model = Applicant::find()
                ->where(['personid' => $applicantid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();

            $username = $applicant_model->getPerson()->one()->username;

            $external_qualification = ExternalQualification::getExternalQualifications($applicant_model->personid);

            return $this->render(
                'view-applicant-qualifications',
                [
                    'csecqualifications' => $qualifications,
                    'applicant' => $applicant_model,
                    'username' => $username,
                    'applicantid' => $applicantid,
                    'centrename' => $centrename,
                    'centreid' => $cseccentreid,
                    'type' => $type,
                    'isexternal' => $applicant_model->isexternal,
                    'post_qualification' => $post_qualification,
                    'record_count' => $record_count,
                    'external_qualification' => $external_qualification
                ]
            );
        }
    }


    /*
    * Purpose: Soft deletes a given certificate.
    * Created: 18/07/2015 by Gamal Crichton
    * Last Modified: 18/07/2015 by Gamal Crichton
    */
    public function actionDeleteCertificate($certificate_id)
    {
        $save_flag = false;
        $cert = CsecQualification::find()
            ->where(['csecqualificationid' => $certificate_id])
            ->one();
        if ($cert == true) {
            $cert->isdeleted = 1;
            $cert->isactive = 0;
            $save_flag = $cert->save();

            if ($save_flag == false)
                Yii::$app->session->setFlash('error', 'Certificate could not be deleted');
        } else
            Yii::$app->session->setFlash('error', 'Error retrieving certificate');

        return $this->redirect(\Yii::$app->request->getReferrer());
    }


    /**
     * Deletes 'Post Secondary Qualification' qualification
     *
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 02/03/2016
     * Date Last Modified: 10/03/2016
     */
    public function actionDeletePostSecondaryQualification($recordid)
    {
        $save_flag = false;

        $qualification = PostSecondaryQualification::find()
            ->where(['postsecondaryqualificationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();
        if ($qualification) {
            $qualification->isactive = 0;
            $qualification->isdeleted = 1;
            $save_flag = $qualification->save();
            if ($save_flag == false)
                Yii::$app->session->setFlash('error', 'Post Secondary Qualification could not be deleted');
        } else
            Yii::$app->session->setFlash('error', 'Error retrieving certificate');

        return $this->redirect(\Yii::$app->request->getReferrer());
    }


    /**
     * Saves "PostSecondaryQualification' record
     *
     * @param type $personid
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 15/03/2016
     * Date Last Modified: 25/05/2016
     */
    public function actionAddPostSecondaryQualification($personid, $cseccentreid, $centrename, $type)
    {
        $user = User::find()
            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();

        $applicant = Applicant::find()
            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();

        $qualification = new PostSecondaryQualification();

        if ($post_data = Yii::$app->request->post()) {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $qualification->load($post_data);
            if ($load_flag == true) {

                $qualification->personid = $user->personid;
                $validation_flag = $qualification->validate();

                if ($validation_flag == true) {
                    $save_flag = $qualification->save();
                    if ($save_flag == true) {
                        //                        return self::actionViewApplicantQualifications($user->personid, $centrename, $cseccentreid, $type);
                        //redirect
                        if (strcasecmp($type, "pending") == 0) {
                            return self::actionViewPending($cseccentreid, $centrename);
                        } elseif (strcasecmp($type, "queried") == 0) {
                            return self::actionViewQueried($cseccentreid, $centrename);
                        } elseif (strcasecmp($type, "all") == 0) {
                            return self::actionViewAll($cseccentreid, $centrename);
                        } elseif (strcasecmp($type, "verified") == 0) {
                            return self::actionViewVerified($cseccentreid, $centrename);
                        }
                    } else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save qualification record. Please try again.');
                } else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate qualification  record. Please try again.');
            } else
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load qualification  record. Please try again.');
        }

        return $this->render('add_post_secondary_qualificiation', [
            'user' => $user,
            'qualification' => $qualification,
            'applicant' => $applicant,
            'centrename' => $centrename,
            'centreid' => $cseccentreid,
            'type' => $type
        ]);
    }


    /**
     * Handles 'examination_body' dropdownlist of 'add_csecqualification' view
     *
     * @param type $exam_body_id
     *
     * Author: Laurence Charles
     * Date Created: 18/03/2016
     * Date Last Modified: 18/03/2016
     */
    public function actionExaminationBodyDependants($exam_body_id, $index)
    {
        $subjects = Subject::getSubjectList($exam_body_id);
        $proficiencies = ExaminationProficiencyType::getExaminationProficiencyList($exam_body_id);
        $grades = ExaminationGrade::getExaminationGradeList($exam_body_id);
        $pass = NULL;

        if (count($subjects) > 0  && count($proficiencies) > 0  && count($grades) > 0)    //if subjects related to examination body exist
        {
            $pass = 1;
            echo Json::encode(['recordid' => $index, 'subjects' => $subjects, 'proficiencies' => $proficiencies, 'grades' => $grades, 'pass' => $pass]);       //return json encoded array of subjects
        } else {
            $pass = 0;
            echo Json::encode(['recordid' => $index, 'pass' => $pass]);
        }
    }


    /**
     * Saves CsecQualifcation
     *
     * @param type $personid
     * @param type $centrename
     * @param type $centreid
     * @param type $type
     * @param type $record_count
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 18/03/2016
     * Date Last Modified: 20/03/2016 | 26/03/2016
     */
    public function actionSaveNewQualifications($personid, $centrename, $centreid, $type, $record_count, $qual_limit)
    {
        $all_qualifications = array();

        //creates CsecQualification record containers
        for ($i = 0; $i < $qual_limit; $i++) {
            $temp = new CsecQualification();
            array_push($all_qualifications, $temp);
        }

        if ($post_data = Yii::$app->request->post()) {
            $load_flag = false;

            $load_flag = Model::loadMultiple($all_qualifications, $post_data);
            if ($load_flag == true) {
                /* Removes all the previously saved records.
                 * Ensures only additional are save by this operation
                 */
                for ($i = 0; $i < $record_count; $i++) {
                    unset($all_qualifications[$i]);
                }

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    foreach ($all_qualifications as $qualification) {

                        $save_flag = false;
                        if ($qualification->isValid() == true) {
                            $qualification->personid = $personid;
                            if ($qualification->validate() == false) {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error validating certificates. Please try again');
                                return $this->redirect(\Yii::$app->request->getReferrer());
                            } else {
                                $save_flag = $qualification->save();
                                if ($save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error saving certificates. Please try again');
                                    return $this->redirect(\Yii::$app->request->getReferrer());
                                }
                            }
                        }
                    }
                    $transaction->commit();
                } catch (Exception $ex) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing your request. Please try again');
                }
            } else {
                Yii::$app->getSession()->setFlash('error', 'Error occured loading records. Please try again');
            }
        }
        return $this->redirect(\Yii::$app->request->getReferrer());
    }


    /**
     * Adds/Edits/Deletes "ExternalQualification' record
     *
     * @param type $personid
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 03/04/2016
     * Date Last Modified: 03/04/2016 | 30/01/2017
     */
    public function actionExternalQualifications($personid, $action, $cseccentreid, $centrename, $type)
    {
        $user = User::find()
            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();

        $applicant = Applicant::find()
            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();

        if ($action == "delete") {
            $qualification = ExternalQualification::getExternalQualifications($personid);
            if ($qualification == true) {
                $save_flag = false;
                $qualification->isdeleted = 1;
                $qualification->isactive = 0;
                $save_flag = $qualification->save();
                if ($save_flag == true) {
                    return $this->redirect(\Yii::$app->request->getReferrer());
                }
            } else {
                Yii::$app->getSession()->setFlash('error', 'Error occured when deleting External Qualification. Please try again.');
                return $this->redirect(\Yii::$app->request->getReferrer());
            }
        } elseif ($action == "add")
            $qualification = new ExternalQualification();

        if ($post_data = Yii::$app->request->post()) {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $qualification->load($post_data);
            if ($load_flag == true) {
                $qualification->personid = $user->personid;
                $validation_flag = $qualification->validate();

                if ($validation_flag == true) {
                    $save_flag = $qualification->save();
                    if ($save_flag == true) {
                        //redirect
                        if (strcasecmp($type, "pending") == 0) {
                            return self::actionViewPending($cseccentreid, $centrename);
                        } elseif (strcasecmp($type, "queried") == 0) {
                            return self::actionViewQueried($cseccentreid, $centrename);
                        } elseif (strcasecmp($type, "all") == 0) {
                            return self::actionViewAll($cseccentreid, $centrename);
                        } elseif (strcasecmp($type, "verified") == 0) {
                            return self::actionViewVerified($cseccentreid, $centrename);
                        }
                    } else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save qualification record. Please try again.');
                } else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate qualification  record. Please try again.');
            } else
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load qualification  record. Please try again.');
        }

        return $this->render('external_qualification', [
            'user' => $user,
            'qualification' => $qualification,
            'applicant' => $applicant,
            'centrename' => $centrename,
            'centreid' => $cseccentreid,
            'type' => $type
        ]);
    }


    /**
     * Set all the applications of a particular applicant to "Abandoned"
     *
     * @param type $personid
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 04/05/2016
     * Date Last Modified: 04/05/2016
     */
    public function actionAbandonApplication($personid, $centrename, $centreid)
    {
        $applications = Application::getApplicantApplications($personid);
        $save_flag = true;
        $test_flag = true;

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($applications as $application) {
                $application->applicationstatusid = 11;
                $test_flag = $application->save();
                if ($test_flag == false) {
                    $save_flag = false;
                    break;
                }
            }
            if ($save_flag == false) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', 'Error occured processing your request. Please try again');
            } else {
                $transaction->commit();
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            Yii::$app->getSession()->setFlash('error', 'Error occured processing your request. Please try again');
        }
        return self::actionViewPending($centreid, $centrename);
    }


    /**
     * Set all the applications of a particular applicant to "Unverified"
     *
     * @param type $personid
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 04/05/2016
     * Date Last Modified: 04/05/2016
     */
    public function actionReactivateApplication($personid, $centrename, $centreid)
    {
        $save_flag = true;
        $test_flag = true;
        $i = -1;

        $applications = Application::getAbandonedApplicantApplications($personid);

        if ($applications == false) {
            Yii::$app->getSession()->setFlash('error', 'Error retrieving records. Please try again');
        } else {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                foreach ($applications as $key => $application) {
                    $application->applicationstatusid = 2;
                    $test_flag = $application->save();

                    if ($test_flag == false) {
                        $i = $key;
                        $save_flag = false;
                        break;
                    }
                }

                if ($save_flag == false) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured updating record. Please try again');
                } else {
                    $transaction->commit();
                }
            } catch (Exception $ex) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', 'Error occured processing your request. Please try again');
            }
        }
        return self::actionIndexAbandoned();
    }



    public function actionViewDocuments($applicantid, $centrename,  $cseccentreid, $type,  $personid)
    {
        $applicant = Applicant::find()
            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();

        $username = $applicant->getPerson()->one()->username;

        //Get documents already submitted
        $selections = array();
        foreach (DocumentSubmitted::findAll(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0]) as $doc) {
            array_push($selections, $doc->documenttypeid);
        }

        return $this->render(
            'prospective_student',
            [
                'username' => $username,
                'applicant' => $applicant,

                'applicantid' => $applicantid,
                'centrename' => $centrename,
                'centreid' => $cseccentreid,
                'type' => $type,
                'selections' => $selections,

                'personid' => $personid,
            ]
        );
    }



    public function actionVerifyDocuments($applicantid, $centrename, $cseccentreid, $type, $personid)
    {
        $document_save_flag = false;

        if (Yii::$app->request->post()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $request = Yii::$app->request;
                //Update document submission
                $submitted = $request->post('documents');

                $docs = DocumentSubmitted::findAll(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0]);

                //if form has non selected then any documents that were prevously selected must be deleted
                if (!$submitted) {
                    foreach ($docs as $doc) {
                        $doc->isactive = 0;
                        $doc->isdeleted = 1;
                        $document_save_flag = $doc->save();
                        if ($document_save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error deleting document record.');
                            return self::actionViewDocuments($applicantid, $centrename,  $cseccentreid, $type,  $personid);
                        }
                    }
                    $transaction->commit();
                    //                    return self::actionViewApplicantQualifications($applicantid, $centrename, $cseccentreid, $type);
                    Yii::$app->session->setFlash('success', 'Document update was successful.  You can now return to Applicant Certificate using Breadcrumbs or Back button.');
                    return self::actionViewDocuments($applicantid, $centrename,  $cseccentreid, $type,  $personid);
                }


                $docs_arr = array();
                if ($docs) {
                    foreach ($docs as $doc) {
                        $docs_arr[] = $doc->documenttypeid;
                    }

                    //                    foreach ($docs as $doc)
                    foreach ($docs as $doc) {
                        if (!in_array($doc->documenttypeid, $submitted)) {
                            //Document has been unchecked
                            $doc->isactive = 0;
                            $doc->isdeleted = 1;
                            $document_save_flag = $doc->save();
                            if ($document_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error deleting document record.');
                                return self::actionViewDocuments($applicantid, $centrename,  $cseccentreid, $type,  $personid);
                            }
                        }
                    }
                }

                if ($submitted) {
                    foreach ($submitted as $sub) {
                        if (!in_array($sub, $docs_arr)) {
                            $doc = new DocumentSubmitted();
                            $doc->documenttypeid = $sub;
                            $doc->personid = $personid;
                            $doc->recepientid = Yii::$app->user->getId();
                            $doc->documentintentid = 1;
                            $document_save_flag = $doc->save();
                            if ($document_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Document could not be added');
                                return self::actionViewDocuments($applicantid, $centrename,  $cseccentreid, $type,  $personid);
                            }
                        }
                    }
                }
                $transaction->commit();
                //                return self::actionViewApplicantQualifications($applicantid, $centrename, $cseccentreid, $type);
                Yii::$app->session->setFlash('success', 'Document update was successful.  You can now return to Applicant Certificate using Breadcrumbs or Back button.');
                return self::actionViewDocuments($applicantid, $centrename,  $cseccentreid, $type,  $personid);
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Error occured processing request.');
                return self::actionViewDocuments($applicantid, $centrename,  $cseccentreid, $type,  $personid);
            }
        }
    }
}
