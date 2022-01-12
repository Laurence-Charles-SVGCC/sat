<?php

namespace frontend\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\custom\ModelNotFoundException;

use frontend\models\Division;
use frontend\models\AcademicYear;
use frontend\models\AcademicOffering;
use frontend\models\ProgrammeCatalog;

/**
 * This is the model class for table "application_period".
 *
 * @property string $applicationperiodid
 * @property string $applicationperiodstatusid
 * @property string $applicationperiodtypeid
 * @property string $divisionid
 * @property string $personid
 * @property string $academicyearid
 * @property string $name
 * @property string $onsitestartdate
 * @property string $onsiteenddate
 * @property string $offsitestartdate
 * @property string $offsiteenddate
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $iscomplete
 * @property integer $catalog_approved
 * @property integer $programmes_added
 * @property integer $cape_subjects_added
 */
class ApplicationPeriod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_period';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['divisionid', 'personid', 'academicyearid', 'name', 'onsitestartdate', 'offsitestartdate', 'applicationperiodstatusid', 'applicationperiodtypeid'], 'required'],
            [['divisionid', 'personid', 'academicyearid', 'isactive', 'isdeleted', 'iscomplete', 'catalog_approved',  'programmes_added',  'cape_subjects_added', 'applicationperiodstatusid', 'applicationperiodtypeid'], 'integer'],
            [['onsitestartdate', 'onsiteenddate', 'offsitestartdate', 'offsiteenddate'], 'safe'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'divisionid' => 'Division',
            'academicyearid' => 'Academic Year',
            'name' => 'Application Period Name',
            'onsitestartdate' => 'On-Campus Start Date',
            'onsiteenddate' => 'On-Campus End Date',
            'offsitestartdate' => 'Off-Campus Start Date',
            'offsiteenddate' => 'Off-Campus End Date',
            'applicationperiodstatusid' => 'applicationperiodstatusid',
            'applicationperiodtypeid' => 'applicationperiodtypeid',
            'iscomplete' => 'Is Complete',
        ];
    }




    /**
     * Returns an array of all active (not period status) application period records
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 09/02/2016
     * Date Last Modified: 09/02/2016
     */
    public static function getAllPeriods()
    {
        $periods = ApplicationPeriod::find()
            ->where(['isactive' => 1, 'isdeleted' => 0])
            ->all();
        if (count($periods) > 0)
            return $periods;
        return false;
    }


    /**
     * Deletes an application period record 
     * 
     * @param type $recordid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/02/2016
     * Date Last Modified: 09/02/2016
     */
    public function actionDeleteApplicationPeriod($recordid)
    {
        $period = ApplicationPeriod::find()
            ->where(['applicationperiodid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();
        if ($period == true) {
            $save_flag = false;
            $period->isdeleted = 0;
            $period->isdeleted = 1;
            $save_flag = $period->save();
            if ($save_flag == true)
                return $this->redirect(['admisssions/manage-application-period']);
            else {
                Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');
                return $this->redirect(['admisssions/manage-application-period']);
            }
        } else {
            Yii::$app->getSession()->setFlash('error', 'Error occured locating record. Please try again.');
            return $this->redirect(['admisssions/manage-pplication-period']);
        }
    }


    /**
     * Returns true if it is safe to delete a particular application period;
     * i.e. it is not associated with any applications.
     * 
     * @param type $applicationperiodid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 09/02/2016
     * Date Last Modified: 09/02/2016
     */
    public static function canSafeToDelete($applicationperiodid)
    {
        $db = Yii::$app->db;
        $records = $db->createCommand(
            " SELECT *"
                . " FROM application"
                . " JOIN academic_offering"
                . " ON application.academicofferingid = academic_offering.academicofferingid"
                . " WHERE academic_offering.applicationperiodid = " . $applicationperiodid
                . ";"
        )
            ->queryAll();

        //if no applications are associated with the application period
        if (count($records) == 0)
            return true;
        return false;
    }


    /**
     * Checks if user has an outstanding application period setup
     * i.e. returns true if user has an incomplete period in progress
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 10/02/2016
     * Date Last Modified: 10/02/2016
     */
    public static function hasIncompletePeriod()
    {
        $id = Yii::$app->user->identity->personid;
        $periods = ApplicationPeriod::find()
            ->where(['isactive' => 1, 'isdeleted' => 1, 'personid' => $id, 'applicationperiodstatusid' => [1, 2, 3, 4]])
            ->all();
        if (count($periods) > 0)
            return true;
        return false;
    }


    /**
     * Returns the 'applicationperiodid' of the incomplete period
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 10/02/2016
     * Date Last Modified: 10/02/2016
     */
    public static function getIncompletePeriodID()
    {
        $id = Yii::$app->user->identity->personid;
        $period = ApplicationPeriod::find()
            ->where(['isactive' => 1, 'isdeleted' => 1, 'personid' => $id, 'applicationperiodstatusid' => [1, 2, 3, 4]])
            ->one();
        if ($period)
            return $period->applicationperiodid;
        return false;
    }


    /**
     * Returns the Application Period record of the incomplete period
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 11/02/2016
     * Date Last Modified: 11/02/2016
     */
    public static function getIncompletePeriod()
    {
        $id = Yii::$app->user->identity->personid;
        $period = ApplicationPeriod::find()
            ->where(['isactive' => 1, 'isdeleted' => 1, 'personid' => $id, 'applicationperiodstatusid' => [1, 2, 3, 4]])
            ->one();
        if ($period)
            return $period;
        return false;
    }



    /**
     * Returns an array of active 'application period' records
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 11/02/2016
     * Date Last Modified: 11/02/2016
     */
    public static function getActivePeriods()
    {
        $db = Yii::$app->db;
        $periods = $db->createCommand(
            " SELECT application_period.applicationperiodid AS 'id',"
                . " application_period.name AS 'name',"
                . " division.abbreviation AS 'division',"
                . " academic_year.title AS 'year',"
                . " application_period.onsitestartdate AS 'onsitestartdate',"
                . " application_period.onsiteenddate AS 'onsiteenddate',"
                . " application_period.offsitestartdate AS 'offsitestartdate',"
                . " application_period.offsiteenddate AS 'offsiteenddate',"
                . " application_period_type.name AS 'type',"
                . " applicationperiod_status.name AS 'status',"
                . " employee.title AS 'emptitle',"
                . " employee.firstname AS 'firstname',"
                . " employee.lastname AS 'lastname'"
                . " FROM application_period"
                . " JOIN division"
                . " ON application_period.divisionid = division.divisionid"
                . " JOIN academic_year"
                . " ON application_period.academicyearid = academic_year.academicyearid"
                . " JOIN application_period_type"
                . " ON application_period.applicationperiodtypeid = application_period_type.applicationperiodtypeid"
                . " JOIN applicationperiod_status"
                . " ON application_period.applicationperiodstatusid = applicationperiod_status.applicationperiodstatusid"
                . " JOIN employee"
                . " ON application_period.personid = employee.personid"
                . " WHERE application_period.isactive = 1"
                . " AND application_period.isdeleted = 0"
                . " AND application_period.applicationperiodstatusid = 5;"
        )
            ->queryAll();

        return $periods;
    }



    /**
     * Returns an array of all 'application period' records
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 11/02/2016
     * Date Last Modified: 11/02/2016
     */
    public static function getAllApplicationPeriods()
    {
        $db = Yii::$app->db;
        $periods = $db->createCommand(
            " SELECT application_period.applicationperiodid AS 'id',"
                . " application_period.name AS 'name',"
                . " division.abbreviation AS 'division',"
                . " academic_year.title AS 'year',"
                . " application_period.onsitestartdate AS 'onsitestartdate',"
                . " application_period.onsiteenddate AS 'onsiteenddate',"
                . " application_period.offsitestartdate AS 'offsitestartdate',"
                . " application_period.offsiteenddate AS 'offsiteenddate',"
                . " application_period.iscomplete AS 'iscomplete',"
                . " application_period_type.name AS 'type',"
                . " applicationperiod_status.name AS 'status',"
                . " employee.title AS 'emptitle',"
                . " employee.firstname AS 'firstname',"
                . " employee.lastname AS 'lastname'"
                . " FROM application_period"
                . " JOIN division"
                . " ON application_period.divisionid = division.divisionid"
                . " JOIN academic_year"
                . " ON application_period.academicyearid = academic_year.academicyearid"
                . " JOIN application_period_type"
                . " ON application_period.applicationperiodtypeid = application_period_type.applicationperiodtypeid"
                . " JOIN applicationperiod_status"
                . " ON application_period.applicationperiodstatusid = applicationperiod_status.applicationperiodstatusid"
                . " JOIN employee"
                . " ON application_period.personid = employee.personid"
                . " WHERE application_period.isdeleted = 0;"
        )
            ->queryAll();

        return $periods;
    }


    /**
     * Return true if their is an open application period
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 24/02/2016
     * Date Last Modified: 24/02/2016
     */
    public static function openPeriodExists()
    {
        $periods = ApplicationPeriod::find()
            ->where(['isactive' => 1, 'isdeleted' => 0, 'applicationperiodstatusid' => 5])
            ->all();
        if (count($periods) > 0)
            return true;
        return false;
    }

    /**
     * Return true if their is an incomplete application period
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 24/02/2016
     * Date Last Modified: 24/02/2016
     */
    public static function incompletePeriodExists()
    {
        $periods = ApplicationPeriod::find()
            ->where(['isactive' => 1, 'isdeleted' => 0, 'iscomplete' => 0])
            ->all();
        if (count($periods) > 0)
            return true;
        return false;
    }


    /**
     * Return array of open application period
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 06/03/2016
     * Date Last Modified: 06/03/2016
     */
    public static function getOpenPeriod()
    {
        $periods = ApplicationPeriod::find()
            ->where(['isactive' => 1, 'isdeleted' => 0/*, 'applicationperiodstatusid' => 5*/])
            ->all();
        if (count($periods) > 0)
            return $periods;
        return false;
    }


    /**
     * Return array of open application period IDs
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 06/03/2016
     * Date Last Modified: 06/03/2016 | 26/08/2016
     */
    public static function getOpenPeriodIDs()
    {
        $periods = ApplicationPeriod::find()
            ->where(['isactive' => 1, 'isdeleted' => 0, 'iscomplete' => 0])
            ->all();
        if (count($periods) > 0) {
            $ids = array();
            foreach ($periods as $period) {
                array_push($ids, $period->divisionid);
            }
            return $ids;
        }
        return false;
    }


    /**
     * Returns the name of each division associated ith a particular division
     * 
     * @return boolean
     * 
     * Author: charles_laurence1@gmail.com
     * Created: 2016_03_06
     * Modified: 06/03/2016
     */
    public function getDivisionName()
    {
        $division = Division::find()
            ->where(['divisionid' => $this->divisionid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();
        return ($division == true) ? $division->name : "Unknown Division";
    }



    /**
     * Returns an array of periods that have not been terminated
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 21/03/2016
     * Date Last Modified: 21/03/2016
     */
    public static function periodIncomplete()
    {
        $periods = ApplicationPeriod::find()
            ->where(['isactive' => 1, 'isdeleted' => 0, 'iscomplete' => 0])
            ->all();
        if (count($periods) > 0)
            return $periods;
        return false;
    }


    /**
     * Returns true if an applicationperiod for a particular division is "Incomplete" 
     * 
     * @param type $divisionid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 04/11/2016
     * Date LAst Modified: 04/11/2016
     */
    public static function divisionPeriodIncomplete($divisionid)
    {
        $periods = self::periodIncomplete();
        if ($periods) {
            foreach ($periods as $period) {
                if ($period->divisionid == $divisionid) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Returns the name of a particular application period
     * 
     * @param type $recordid
     * @return string
     * 
     * Author: Laurence Charles
     * Date Created: 21/03/2016
     * Date Last Modified: 21/03/2016
     */
    public static function getPeriodName($recordid)
    {
        $period = ApplicationPeriod::find()
            ->where(['applicationperiodid' => $recordid])
            ->one();
        if ($period)
            return $period->name;
        return "upcoming";
    }

    /**
     * Returns an array of application periods for display in dropdownlist
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 11/05/2016
     * Date Last Modified: 11/05/2016 | 12/09/2016
     */
    public static function preparePeriods()
    {
        $divisionid = EmployeeDepartment::getUserDivision();

        if ($divisionid == 1) {
            $records =
                ApplicationPeriod::find()
                ->where(["isactive" => 1, "isdeleted" => 0])
                ->andWhere(['>=', 'applicationperiodstatusid', 5])
                ->all();
        } else {
            $records =
                ApplicationPeriod::find()
                ->where([
                    "isactive" => 1,
                    "isdeleted" => 0,
                    "divisionid" => $divisionid
                ])
                ->andWhere(['>=', 'applicationperiodstatusid', 5])
                ->all();
        }

        if (count($records) > 0) {
            $keys = array();
            array_push($keys, '');

            $values = array();
            array_push($values, 'Select...');

            foreach ($records as $record) {
                $key = strval($record->applicationperiodid);
                array_push($keys, $key);
                $value = strval($record->name);
                array_push($values, $value);
            }

            $combined = array_combine($keys, $values);
            return $combined;
        }
        return false;
    }

    /**
     * Returns an array of application periods for display in dropdownlist
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 12/09/2016
     * Date Last Modified: 12/09/2016
     */
    public static function preparePastPeriods()
    {
        $records = ApplicationPeriod::find()
            ->innerJoin('academic_year', '`application_period`.`academicyearid` = `academic_year`.`academicyearid`')
            ->innerJoin('academic_offering', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
            ->innerJoin('application', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
            ->where([
                'application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                'academic_year.iscurrent' => 0, 'academic_year.isactive' => 1, 'academic_year.isdeleted' => 0,
                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                'application.isactive' => 1, 'application.isdeleted' => 0
            ])
            ->andWhere(['>=', 'applicationperiodstatusid', 5])
            ->all();
        if (count($records) > 0) {
            $keys = array();
            array_push($keys, '');

            $values = array();
            array_push($values, 'Select...');

            foreach ($records as $record) {
                $key = strval($record->applicationperiodid);
                array_push($keys, $key);
                $value = strval($record->name);
                array_push($values, $value);
            }

            $combined = array_combine($keys, $values);
            return $combined;
        }
        return false;
    }



    public static function prepareWithdrawalReportPeriods()
    {
        $records = ApplicationPeriod::find()
            ->innerJoin('academic_year', '`application_period`.`academicyearid` = `academic_year`.`academicyearid`')
            ->innerJoin('academic_offering', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
            ->innerJoin('application', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
            ->where([
                'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.divisionid' => [4, 5],
                'academic_year.isactive' => 1, 'academic_year.isdeleted' => 0,
                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                'application.isactive' => 1, 'application.isdeleted' => 0
            ])
            ->andWhere(['>=', 'applicationperiodstatusid', 5])
            ->all();
        if (count($records) > 0) {
            $keys = array();
            array_push($keys, '');

            $values = array();
            array_push($values, 'Select...');

            $today = date('Y-m-d');

            foreach ($records as $record) {

                $academic_year = AcademicYear::find()
                    ->where(['academicyearid' => $record->academicyearid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
                $split_date = explode("-", $academic_year->enddate);
                $year = $split_date[0];
                $month = $split_date[1];
                $day = $split_date[2];
                $targetDate = date($year . "-" . ($month - 1) . "-" . $day);

                // if current date equal to or past 1 month prior to end of academic year for application period in question
                if (strtotime($today) > strtotime($targetDate)) {
                    $key = strval($record->applicationperiodid);
                    array_push($keys, $key);
                    $value = strval($record->name);
                    array_push($values, $value);
                }
            }
            $combined = array_combine($keys, $values);
            return $combined;
        }
        return false;
    }


    /**
     * Generates listing of application period for academic warning generation
     * 
     * @return type
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2018_03_09
     * Last Modified: 2018_03_09
     */
    public static function prepareWarningReportPeriods()
    {
        $records = ApplicationPeriod::find()
            ->innerJoin('academic_year', '`application_period`.`academicyearid` = `academic_year`.`academicyearid`')
            ->innerJoin('academic_offering', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
            ->innerJoin('application', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
            ->where([
                'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.divisionid' => [4, 5],
                'academic_year.isactive' => 1, 'academic_year.isdeleted' => 0,
                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                'application.isactive' => 1, 'application.isdeleted' => 0
            ])
            ->andWhere(['>=', 'applicationperiodstatusid', 5])
            ->all();
        if (count($records) > 0) {
            $keys = array();
            array_push($keys, '');

            $values = array();
            array_push($values, 'Select...');

            foreach ($records as $record) {
                $academic_year = AcademicYear::find()
                    ->where(['academicyearid' => $record->academicyearid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();

                $today = date('Y-m-d');
                $split_today_date = explode("-", $today);
                $today_year = $split_today_date[0];
                $today_month = $split_today_date[1];
                $today_day = $split_today_date[2];

                $split_start_date = explode("-", $academic_year->startdate);
                $start_date_year = $split_start_date[0];
                $start_date_month = $split_start_date[1];
                $start_date_day = $split_start_date[2];

                if ($today_year >  $start_date_year) {
                    $key = strval($record->applicationperiodid);
                    array_push($keys, $key);
                    $value = strval($record->name);
                    array_push($values, $value);
                } elseif ($today_year ==  $start_date_year  &&  ($today_month - $start_date_month > 6)) {
                    $key = strval($record->applicationperiodid);
                    array_push($keys, $key);
                    $value = strval($record->name);
                    array_push($values, $value);
                }
                /*
                    $split_date = explode("-", $academic_year->enddate);
                    $year = $split_date[0];
                    $month = $split_date[1];
                    $day = $split_date[2];
                    $targetDate = date($year . "-" . ($month - 1) . "-" . $day);

                    // if current date equal to or past 1 month prior to end of academic year for application period in question
                    if (strtotime($today) > strtotime($targetDate))
                    {
                        $key = strval($record->applicationperiodid);
                        array_push($keys, $key);
                        $value = strval($record->name);
                        array_push($values, $value);
                    }*/
            }
            $combined = array_combine($keys, $values);
            return $combined;
        }
        return false;
    }



    /**
     * Return true if application period is associated with current academic year
     * 
     * @param type $periodid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 12/09/2016
     * Date Last Modified: 12/09/2016
     */
    public static function isCurrent($periodid)
    {
        $period = ApplicationPeriod::find()
            ->where(['applicationperiodid' => $periodid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();

        if ($period) {
            $year = AcademicYear::find()
                ->where(['academicyearid' => $period->academicyearid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
            if ($year == true  && $year->iscurrent == 0)
                return true;
        }

        return false;
    }


    /**
     * Returns true if application period for DNE or DTE is still under review
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     */
    public static function isDteOrDneApplicationPeriodUnderReview()
    {
        $periods_under_review = ApplicationPeriod::find()
            ->where(['iscomplete' => 0, 'divisionid' => [6, 7],  'isactive' => 1, 'isdeleted' => 0])
            ->all();
        if (count($periods_under_review) > 0) {
            return true;
        }
        return false;
    }


    /**
     * Returns true if application period for DNE or DTE is still under review
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     */
    public static function isDasgsOrDtveApplicationPeriodUnderReview()
    {
        $periods_under_review = ApplicationPeriod::find()
            ->where(['iscomplete' => 0, 'divisionid' => [4, 5],  'isactive' => 1, 'isdeleted' => 0])
            ->all();
        if (count($periods_under_review) > 0) {
            return true;
        }
        return false;
    }











    /***************************************************************************************************/
    /**
     * Retrieve all active aplication period records
     * 
     * @return [ApplicationPeriod] | []
     * 
     * Author: charles.laurence1@gmail.com
     *  Created: 2017_08_24
     *  Modified: 2017_08_24
     */
    public static function getAllActivePeriods()
    {
        $application_periods = ApplicationPeriod::find()
            ->where(['isactive' => 1,  'isdeleted' => 0])
            ->all();
        return $application_periods;
    }


    /**
     * Returns the ApplicationPeriod record that is in the process of being configured.
     * Only one ApplicationPeriod is allowed to be in an 'incomplete/unconfigred' 'state at any given time
     * 
     * @return ApplicationPeriod | NULL
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_06
     * Modified: 2017_09_06
     */
    public static function getUnconfiguredAppplicationPeriod()
    {
        return ApplicationPeriod::find()
            ->where(['isactive' => 0, 'isdeleted' => 0, 'applicationperiodstatusid' => [1, 2, 3, 4]])
            ->one();
    }


    /**
     * Returns ApplicationPeriod record
     * 
     * @param type $id
     * @param type $isactive
     * @return type ApplicationPeriod
     *  @throws ModelNotFoundException
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_01
     * Modified: 2017_09_11
     */
    public static function getApplicationPeriod($id, $isactive = true)
    {
        if ($isactive == true) {
            $period = ApplicationPeriod::find()
                ->where(['applicationperiodid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
            if ($period == NULL) {
                $error_message = "No active application period found for ApplicationPeriod ->ID= " . $id;
                throw new ModelNotFoundException($error_message);
            }
        } elseif ($isactive == false) {
            $period = ApplicationPeriod::find()
                ->where(['applicationperiodid' => $id])
                ->one();
            if ($period == NULL) {
                $error_message = "No application period found for ApplicationPeriod ->ID= " . $id;
                throw new ModelNotFoundException($error_message);
            }
        }
        return $period;
    }


    /**
     * Returns true if it is safe to delete a particular application period;
     * i.e. it is not associated with any applications.
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_07
     * Modified: 2017_10_09
     */
    public static function eligibleToDelete($applicationperiodid)
    {
        $applications = Application::find()
            ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
            ->where([
                'application.isactive' => 1, 'application.isdeleted' => 0,
                'academic_offering.applicationperiodid' => $applicationperiodid, 'academic_offering.isactive' => 1,
                'academic_offering.isdeleted' => 0
            ])
            ->all();
        if (empty($applications) == true) {
            return true;
        }
        return false;
    }


    /**
     * Return all  associated AcademicOffering records in sorted order
     * 
     * @return [AcademicOffering] | []
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_08
     * Modified: 2017_09_08
     */
    public function getAcademicOfferings()
    {
        return AcademicOffering::find()
            ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
            ->where([
                'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0,
                'academic_offering.applicationperiodid' => $this->applicationperiodid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0
            ])
            ->orderBy('programme_catalog.name ASC')
            ->all();
    }


    /**
     * Return all  associated ProgrammeCatalog records in sorted order
     * 
     * @return [ProgrammeCatalog] | []
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_11
     * Modified: 2017_09_11
     */
    public function getProgrammes()
    {
        //            return ProgrammeCatalog::find()
        //                    ->innerJoin('department', '`programme_catalog`.`departmentid` = `department`.`departmentid`')
        //                    ->where(['programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0, 
        //                                    'programme_catalog.programmetypeid' => $this->applicationperiodtypeid, 'programme_catalog.name' => 'CAPE',
        //                                    'department.isactive' => 1, 'department.isdeleted' => 0, , 'department.divisionid' => $this->divisionid])
        //                    ->orderBy('programme_catalog.name ASC')
        //                    ->all();
        $programmes = array();
        $programmes = ProgrammeCatalog::find()
            ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
            ->where([
                'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0,
                'academic_offering.applicationperiodid' => $this->applicationperiodid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0
            ])
            ->orderBy('programme_catalog.name ASC')
            ->all();

        if (empty($programmes) == true) {
            $programmes = ProgrammeCatalog::find()
                ->innerJoin('department', '`programme_catalog`.`departmentid` = `department`.`departmentid`')
                ->where([
                    'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0, 'programme_catalog.programmetypeid' => $this->applicationperiodtypeid,
                    'department.isactive' => 1, 'department.isdeleted' => 0, 'department.divisionid' => $this->divisionid
                ])
                ->andWhere(['not', ['programme_catalog.name' => 'CAPE']])
                ->orderBy('programme_catalog.name ASC')
                ->all();
        }
        return $programmes;
    }


    /**
     * Returns all Associate Degree academic offering associated with the application period
     * 
     * @param type $applicationperiodid
     * @return type [AcademicOFfering] || []
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_11
     * Modified: 2017_09_11
     */
    public function getAssociateDegreeOfferings()
    {
        return  AcademicOffering::find()
            ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
            ->where([
                'academic_offering.applicationperiodid' => $this->applicationperiodid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0
            ])
            ->andWhere(['not', ['programme_catalog.name' => 'CAPE']])
            ->all();
    }


    /**
     * Returns academic_offering record for a particular programme if it already exists for this application period
     * 
     * @param type $programmecatalogid
     * @return type boolean
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_11
     * Modified: 2017_09_11
     */
    public function getExistingAcadmeicOffering($programmecatalogid)
    {
        $offering = AcademicOffering::find()
            ->where([
                'programmecatalogid' => $programmecatalogid, 'applicationperiodid' => $this->applicationperiodid,
                'isactive' => 1, 'isdeleted' => 0
            ])
            ->one();
        return $offering;
    }


    /**
     * Returns formatted collection of AcademicOffering records

     * @return array
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_08
     * Modified: 2017_09_08
     */
    public function prepareAcademicOfferingsSummary()
    {
        $academic_offerings = $this->getAcademicOfferings();
        $academic_offering_expected_intake = array();
        foreach ($academic_offerings as $academic_offering) {
            $name_intake_array = array();
            array_push($name_intake_array, $academic_offering->getProgrammeName());
            array_push($name_intake_array, ($academic_offering->spaces == true ? $academic_offering->spaces : "--"));
            array_push($name_intake_array, count($academic_offering->getApplicantIntake()));
            array_push($academic_offering_expected_intake, $name_intake_array);
        }
        return $academic_offering_expected_intake;
    }


    /**
     * Return all  associated CapeOffering records
     * 
     * @return [CapeSubject] | []
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_08
     * Modified: 2017_09_08
     */
    public function getCapeSubjectOfferings()
    {
        return CapeSubject::find()
            ->innerJoin('academic_offering', '`cape_subject`.`academicofferingid` = `academic_offering`.`academicofferingid`')
            ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
            ->where([
                'cape_subject.isactive' => 1, 'cape_subject.isdeleted' => 0,
                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                'application_period.applicationperiodid' => $this->applicationperiodid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0
            ])
            ->orderBy('cape_subject.subjectname ASC')
            ->all();
    }


    /**
     * Returns formatted collection of CapeSubject records
     * 
     * @return array
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_08
     * Modified: 2017_09_08
     */
    public function prepareCapeOfferingSummary()
    {
        $cape_offerings = $this->getCapeSubjectOfferings();
        $cape_offering_intake =  array();
        foreach ($cape_offerings as $cape_offering) {
            $data = array();
            array_push($data, $cape_offering->subjectname);
            array_push($data, $cape_offering->getGroup());
            array_push($data, $cape_offering->capacity);
            array_push($data,  count($cape_offering->getApplicantIntake()));
            array_push($cape_offering_intake, $data);
        }
        return $cape_offering_intake;
    }


    /**
     * Return applicantintent
     * 
     * @return int
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_11
     * Modified: 2017_09_11
     */
    public function getApplicantIntent()
    {
        $applicantintentid = NULL;
        if ($this->divisionid == 4) {
            if ($this->applicationperiodtypeid == 1) {
                $applicantintentid = 1;
            } elseif ($this->applicationperiodtypeid == 2) {
                $applicantintentid = 2;
            }
        } elseif ($this->divisionid == 5) {
            if ($this->applicationperiodtypeid == 1) {
                $applicantintentid = 1;
            } elseif ($this->applicationperiodtypeid == 2) {
                $applicantintentid = 3;
            }
        } elseif ($this->divisionid == 6) {
            if ($this->applicationperiodtypeid == 1) {
                $applicantintentid = 4;
            } elseif ($this->applicationperiodtypeid == 2) {
                $applicantintentid = 5;
            }
        } elseif ($this->divisionid == 7) {
            if ($this->applicationperiodtypeid == 1) {
                $applicantintentid = 6;
            } elseif ($this->applicationperiodtypeid == 2) {
                $applicantintentid = 7;
            } elseif ($this->applicationperiodtypeid == 3) {
                $applicantintentid = 10;
            }
        }
        return $applicantintentid;
    }


    /**
     * Return applicantintent
     * 
     * @return int
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_13
     * Modified: 2017_05_04
     */
    public static function calculateApplicantIntent($divisionid, $applicationperiodtypeid)
    {
        $applicantintentid = NULL;
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
     * Determines existance academic year and application period for Application Period configuration
     * Currently only take full time application periods into account
     * 
     * @param type $divisionid
     * @param type $applicationperiodtypeid
     * @param type $applicantintentid
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_13
     * Modified: 2017_09_13
     */
    public static  function processApplicantIntentid($divisionid, $applicationperiodtypeid)
    {
        $resultant_set = array();
        $academic_year_exists = 0;
        $application_period_exists = 0;
        $applicantintentid = self::calculateApplicantIntent($divisionid, $applicationperiodtypeid);

        if ($applicantintentid == 1) {
            $academicYear = AcademicYear::find()
                ->where(['applicantintentid' => $applicantintentid, 'iscurrent' => 1, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
            if ($academicYear) {
                $academic_year_exists = 1;
                $period = ApplicationPeriod::find()
                    ->where(['divisionid' => $divisionid, 'iscomplete' => 0, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
                if ($period) {
                    $application_period_exists = 1;
                }
            }
        }

        array_push($resultant_set, $academic_year_exists);
        array_push($resultant_set, $application_period_exists);
        return $resultant_set;
    }



    /**
     * Returns the name of the division associated with application_period
     * 
     * @return type
     * @throws ModelNotFoundException
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_11
     * Modified: 2017_09_11
     */
    public function getDivision()
    {
        $division = Division::find()
            ->where(['divisionid' => $this->divisionid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();
        if ($division == NULL) {
            $error_message = "No active application period found for ApplicationPeriod ->ID= " . $id;
            throw new ModelNotFoundException($error_message);
        }
        return $division;
    }


    /**
     * Prepares programme/academic_offering listing for programme listing management
     * 
     * @return array => [[programme_listing], [academic_offering_backup], [offering_listing]]
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_12
     * Modified: 2017_09_12
     */
    public function prepareAcademicOfferings()
    {
        $offerings_result_set = array();

        $programmes = $this->getProgrammes();
        array_push($offerings_result_set, $programmes);
        $offerings = array();
        $saved_offerings = $this->getAssociateDegreeOfferings();
        $offering_copy = array();

        if (empty($saved_offerings) == false) {
            array_push($offerings_result_set, AcademicOffering::backUp($saved_offerings));
            for ($j = 0; $j < count($programmes); $j++) {
                $existing_offering = $this->getExistingAcadmeicOffering($programmes[$j]->programmecatalogid);
                if ($existing_offering == true) {
                    $existing_offering->programmecatalogid = 1;        //done to ensure checkbox is 'checked'
                    array_push($offerings, $existing_offering);
                } else {
                    $offer = new AcademicOffering();
                    array_push($offerings, $offer);
                }
            }
        } else {
            array_push($offerings_result_set, array());
            for ($j = 0; $j < count($programmes); $j++) {
                $offer = new AcademicOffering();
                array_push($offerings, $offer);
            }
        }
        array_push($offerings_result_set, $offerings);
        return $offerings_result_set;
    }


    /**
     * Prepares related records for offerign management
     * 
     * @return array => [[subjects], [group_copy], [cape_offering_copy], [cape_subjects_copy], [cape_subjects],[subject_groups]]
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_12
     * Modified: 2017_09_12
     */
    public function prepareCapeOfferings()
    {
        $offerings_result_set = array();

        // CAPE subjects must be retrieved if its is a DASGS application period
        if ($this->divisionid == 4) {
            $subjects = Subject::find()
                ->where(['examinationbodyid' => 2, 'isactive' => 1, 'isdeleted' => 0])
                ->orderBy('name ASC')
                ->all();
        }

        array_push($offerings_result_set, $subjects);

        $subject_count = count($subjects);
        $cape_check = false;
        $none_cape_check = false;
        $cape_subjects = array();
        $saved_cape_offering = NULL;
        $cape_offering_copy = NULL;
        $saved_cape_subjects = array();
        $cape_subjects_copy = array();

        $subject_groups = array();
        $group_copy = array();
        $saved_subject_groups = array();
        if (AcademicOffering::hasCapeOffering($this->applicationperiodid) == true) {
            $saved_subject_groups = CapeSubjectGroup::getAssociatedCapeGroups($this->applicationperiodid);
            array_push($offerings_result_set, CapeSubjectGroup::backup($saved_subject_groups));

            $saved_cape_offering = AcademicOffering::getCapeOffering($this->applicationperiodid);
            array_push($offerings_result_set, AcademicOffering::backUpSingle($saved_cape_offering));

            $saved_cape_subjects = CapeSubject::getCapeSubjects($saved_cape_offering->academicofferingid);
            array_push($offerings_result_set, CapeSubject::backUp($saved_cape_subjects));

            for ($i = 0; $i < $subject_count; $i++) {
                $subject = CapeSubject::find()
                    ->innerJoin('academic_offering', '`cape_subject`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                    ->where(['cape_subject.subjectname' => $subjects[$i]['name'], 'academic_offering.applicationperiodid' => $this->applicationperiodid])
                    ->one();
                if ($subject == true) {
                    $subject->subjectname = 1;
                    array_push($cape_subjects, $subject);

                    //prepare appropriate associated capesubjectgroup record
                    $subject_group = CapeSubjectGroup::find()
                        ->where(['capesubjectid' => $subject->capesubjectid])
                        ->one();
                    if ($subject_group == true) {
                        array_push($subject_groups, $subject_group);
                    } else {
                        $subject_group = new CapeSubjectGroup();
                        array_push($subject_groups, $subject_group);
                    }
                } else {
                    $subject = new CapeSubject();
                    array_push($cape_subjects, $subject);

                    $subject_group = new CapeSubjectGroup();
                    array_push($subject_groups, $subject_group);
                }
            }
        } else {
            array_push($offerings_result_set, array());
            array_push($offerings_result_set, array());
            array_push($offerings_result_set, array());
            for ($i = 0; $i < $subject_count; $i++) {
                $cape = new CapeSubject();
                array_push($cape_subjects, $cape);

                $subject_group = new CapeSubjectGroup();
                array_push($subject_groups, $subject_group);
            }
        }

        array_push($offerings_result_set, $cape_subjects);
        array_push($offerings_result_set, $subject_groups);
        return $offerings_result_set;
    }


    /**
     * Create and return default ApplicationPeriod record
     * 
     * @return type ApplicationPeriod | false
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_12
     * Modified: 2017_09_12
     */
    public static function createDefaultApplicationPeriod()
    {
        $period = new ApplicationPeriod();
        $period->applicationperiodtypeid = 1;
        $period->applicationperiodstatusid = 1;
        $period->divisionid = 4;
        $period->personid = Yii::$app->user->identity->personid;
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
        return $false;
    }


    /**
     * Toggle the catalog approval status
     * 
     * @param type $status
     * @return type
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_27
     * Modified: 2017_09_28
     */
    public function toggleProgrammeCatalogApproval($status)
    {
        if ($status == "approve") {
            $this->catalog_approved = 1;
        } elseif ($status == "reset") {
            $this->catalog_approved = 0;
        }

        return $this->save();
    }


    /**
     * Generates collection of programmes associated with a division
     * 
     * @return type [ProgrammeCatalog] || []
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_28
     * Modified: 2017_09_28
     */
    public function getAvailableProgrammes()
    {
        return ProgrammeCatalog::find()
            ->innerJoin('department', '`programme_catalog`.`departmentid` = `department`.`departmentid`')
            ->where([
                'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0, 'programme_catalog.programmetypeid' => $this->applicationperiodtypeid,
                'department.isactive' => 1, 'department.isdeleted' => 0, 'department.divisionid' => $this->divisionid
            ])
            ->orderBy('programme_catalog.name ASC')
            ->all();
    }


    /**
     * Prepare academic offerings for display in checklist
     * 
     * @param type $programmes
     * @return AcademicOffering[]
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_28
     * Modified: 2017_09_28
     */
    public function processProgrammes($programmes)
    {
        $offerings = array();
        foreach ($programmes as $programme) {
            $existing_offering = $this->getExistingAcadmeicOffering($programme->programmecatalogid);
            if ($existing_offering == true) {
                array_push($offerings, $existing_offering);
            } else {
                $offer = new AcademicOffering();
                $offer->isactive = 0;
                array_push($offerings, $offer);
            }
        }
        return $offerings;
    }


    /**
     * Return the academic offering of a particular programme associated with the application period
     * 
     * @param type $programmecatalogid
     * @return AcademicOffering
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_29
     * Modified: 2017_09_29
     */
    public function getAcadmeicOffering($programmecatalogid)
    {
        return AcademicOffering::find()
            ->where(['applicationperiodid' => $this->applicationperiodid, 'programmecatalogid' => $programmecatalogid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();
    }


    /**
     * Generates collection of cape subjects
     * 
     * @return  Subject[] || []
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_29
     * Modified: 2017_09_29
     */
    public function getAvailableCapeSubjects()
    {
        return Subject::find()
            ->where(['examinationbodyid' => 2, 'isactive' => 1, 'isdeleted' => 0])
            ->orderBy('name ASC')
            ->all();
    }


    /**
     * Generates collection of cape subjects
     * 
     * @return  Subject[] || []
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_29
     * Modified: 2017_09_29
     */
    public function getAvailableCapeSubjectGroups()
    {
        return CapeGroup::find()
            ->where(['isactive' => 1, 'isdeleted' => 0])
            ->all();
    }


    /**
     * Returns CapeSubject record that is associated with application period
     * 
     * @param Subject $subject
     * @return CapeSubject
     * 
     * Author: charles.laurnce1@gmail.com
     * Created: 2017_09_27
     * Modified: 2017_09_27
     */
    public function getExistingCapeSubjectOffering($subject)
    {
        return CapeSubject::find()
            ->innerJoin('academic_offering', '`cape_subject`.`academicofferingid` = `academic_offering`.`academicofferingid`')
            ->where([
                'cape_subject.subjectname' => $subject->name, 'cape_subject.isactive' => 1, 'cape_subject.isdeleted' => 0,
                'academic_offering.applicationperiodid' => $this->applicationperiodid, 'academic_offering.isactive' => 1, 'cape_subject.isdeleted' => 0
            ])
            ->one();
    }


    /**
     * 
     * @param Subject $subjects
     * @return array [Cape Subject, Cape Subject Group]
     * 
     * Author: charles.laurnce1@gmail.com
     * Created: 2017_09_27
     * Modified: 2017_09_27
     */
    public function processCapeSubjectsAndGroups($subjects)
    {
        $result_set = array();
        $subject_groups = array();
        $subject_offerings = array();

        foreach ($subjects as $subject) {
            $existing_subject_offering = $this->getExistingCapeSubjectOffering($subject);
            if ($existing_subject_offering == true) {
                array_push($subject_offerings, $existing_subject_offering);

                $subject_group = CapeSubjectGroup::find()
                    ->where(['capesubjectid' => $existing_subject_offering->capesubjectid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
                if ($subject_group == true) {
                    array_push($subject_groups, $subject_group);
                } else {
                    $subject_group = new CapeSubjectGroup();
                    array_push($subject_groups, $subject_group);
                }
            } else {
                $cape_subject = new CapeSubject();
                $cape_subject->isactive = 0;
                array_push($subject_offerings, $cape_subject);

                $subject_group = new CapeSubjectGroup();
                array_push($subject_groups, $subject_group);
            }
        }

        array_push($result_set, $subject_offerings);
        array_push($result_set, $subject_groups);
        return $result_set;
    }



    /**
     * Returns the CapeSubjects associated with an academic offering related to the application period
     * 
     * @return AcademicOffering[]
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_29
     * Modified: 2017_09_29
     */
    public function getCapeSubjects()
    {
        return CapeSubject::find()
            ->innerJoin('academic_offering', '`cape_subject`.`academicofferingid` = `academic_offering`.`academicofferingid`')
            ->where([
                'cape_subject.isactive' => 1, 'cape_subject.isdeleted' => 0,
                'academic_offering.applicationperiodid' => $this->applicationperiodid,  'academic_offering.isactive' => 1,
                'academic_offering.isdeleted' => 0
            ])
            ->all();
    }


    /**
     * Returns the CapeSubjects associated with an academic offering related to the application period
     * 
     * @return AcademicOffering
     * 
     * Author: charles.laurence1@gmail.com
     * Created: 2017_09_29
     * Modified: 2017_09_29
     */
    public function getCurrentCapeAcademicOffering()
    {
        return AcademicOffering::find()
            ->where(['applicationperiodid' => $this->applicationperiodid, 'programmecatalogid' => 10, 'isactive' => 1, 'isdeleted' => 0])
            ->one();
    }
}
