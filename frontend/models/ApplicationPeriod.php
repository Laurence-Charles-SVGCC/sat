<?php

namespace frontend\models;

use Yii;

use frontend\models\Division;
use frontend\models\AcademicYear;

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
 * @property interger $isdeleted
 * @property interger $iscomplete
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
            [['divisionid', 'personid', 'academicyearid', 'isactive', 'isdeleted', 'iscomplete', 'applicationperiodstatusid', 'applicationperiodtypeid'], 'integer'],
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
        if ($period == true)
        {
            $save_flag = false;
            $period->isdeleted = 0;
            $period->isdeleted = 1;
            $save_flag = $period->save();
            if($save_flag == true)
                return $this->redirect(['admisssions/manage-application-period']);
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');
                return $this->redirect(['admisssions/manage-application-period']);
            }
        }

        else
        {
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
                ->where(['isactive' => 1, 'isdeleted' => 1 ,'personid' => $id, 'applicationperiodstatusid' => [1,2,3,4]])
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
                ->where(['isactive' => 1, 'isdeleted' => 1 ,'personid' => $id, 'applicationperiodstatusid' => [1,2,3,4]])
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
                ->where(['isactive' => 1, 'isdeleted' => 1 ,'personid' => $id, 'applicationperiodstatusid' => [1,2,3,4]])
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
                ->where(['isactive' => 1, 'isdeleted' => 0, 'iscomplete' => 0 ])
                ->all();
        if (count($periods) > 0)
        {
            $ids = array();
            foreach($periods as $period)
            {
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
     * Author: Laurence Charles
     * Date Created: 06/03/2016
     * Date Last Modified: 06/03/2016
     */
    public function getDivisionName()
    {
        $division = Division::find()
                ->where(['divisionid' => $this->divisionid])
                ->one();
        if($division)
            return $division->name;
        return false;
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
        if ($periods)
        {
            foreach($periods as $period)
            {
                if($period->divisionid == $divisionid)
                {
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
        
        if ($divisionid == 1)
        {
            $records = ApplicationPeriod::find()
                        ->innerJoin('academic_offering', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->innerJoin('application' , '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->where(['application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                'application.isactive' => 1, 'application.isdeleted' => 0
                                ])
                        ->andWhere(['>=', 'applicationperiodstatusid', 5])
                        ->all();
        }
        else
        {
            $records = ApplicationPeriod::find()
                        ->innerJoin('academic_offering', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->innerJoin('application' , '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->where(['application_period.divisionid' => $divisionid, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                        'application.isactive' => 1, 'application.isdeleted' => 0
                                ])
                        ->andWhere(['>=', 'applicationperiodstatusid', 5])
                        ->all();
        }
        
        if (count($records) > 0)
        {
            $keys = array();
            array_push($keys, '');

            $values = array();
            array_push($values, 'Select...');

            foreach($records as $record)
            {
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
                    ->innerJoin('academic_year' , '`application_period`.`academicyearid` = `academic_year`.`academicyearid`')
                    ->innerJoin('academic_offering', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->innerJoin('application' , '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                    ->where(['application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                                   'academic_year.iscurrent' => 0, 'academic_year.isactive' => 1, 'academic_year.isdeleted' => 0,
                                    'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                    'application.isactive' => 1, 'application.isdeleted' => 0
                                ])
                    ->andWhere(['>=', 'applicationperiodstatusid', 5])
                    ->all();
        if (count($records) > 0)
        {
            $keys = array();
            array_push($keys, '');

            $values = array();
            array_push($values, 'Select...');

            foreach($records as $record)
            {
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
        
        if ($period)
        {
            $year = AcademicYear::find()
                ->where(['academicyearid' => $period->academicyearid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
            if ($year == true  && $year->iscurrent == 0)
                return true;
        }
        
        return false;
    }
        
        
        
        
        
        
        
        
       
        
        
        
        
        
}
