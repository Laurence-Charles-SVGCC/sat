<?php

namespace frontend\models;

use Yii;

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
            [['divisionid', 'personid', 'academicyearid', 'isactive', 'isdeleted', 'applicationperiodstatusid', 'applicationperiodtypeid'], 'integer'],
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
        
        
       
        
        
        
        
        
}
