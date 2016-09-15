<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "rejection".
 *
 * @property integer $rejectionid
 * @property integer $rejectiontypeid
 * @property integer $personid
 * @property integer $issuedby
 * @property string $issuedate
 * @property integer $revokedby
 * @property string $revokedate
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $ispublished
 * @property integer $packageid
 *
 * @property Person $issuedby0
 * @property Person $revokedby0
 * @property Rejectiontype $rejectiontype
 * @property Person $person
 * @property RejectionApplications[] $rejectionApplications
 * @property Application[] $applications
 */
class Rejection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rejection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rejectiontypeid', 'personid', 'issuedby', 'issuedate'], 'required'],
            [['rejectiontypeid', 'personid', 'issuedby', 'revokedby', 'isactive', 'isdeleted', 'ispublished', 'packageid'], 'integer'],
            [['issuedate', 'revokedate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rejectionid' => 'Rejectionid',
            'rejectiontypeid' => 'Rejectiontypeid',
            'personid' => 'Personid',
            'issuedby' => 'Issuedby',
            'issuedate' => 'Issuedate',
            'revokedby' => 'Revokedby',
            'revokedate' => 'Revokedate',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'ispublished' => 'Ispublished',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssuedby0()
    {
        return $this->hasOne(Person::className(), ['personid' => 'issuedby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevokedby0()
    {
        return $this->hasOne(Person::className(), ['personid' => 'revokedby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRejectiontype()
    {
        return $this->hasOne(Rejectiontype::className(), ['rejectiontypeid' => 'rejectiontypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRejectionApplications()
    {
        return $this->hasMany(RejectionApplications::className(), ['rejectionid' => 'rejectionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::className(), ['applicationid' => 'applicationid'])->viaTable('rejection_applications', ['rejectionid' => 'rejectionid']);
    }
    
    
    
    /**
     * Rescinds an existing rejection.
     * If rejection was already published, the record is made inactive;
     * If it has not been published, the record is deleted.
     * 
     * @param string $id
     * @return mixed
     * 
     * Author: Laurence Charles
     * Date Created: 01/04/2016
     * Date Last Modified: 01/04/2016
     */
    public static function rescindRejection($personid)
    {
        $save_flag_1 = false;
        $save_flag_2 = false;
        
        $rejection = Rejection::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        
        if ($rejection)
        {
            if($rejection->ispublished == 1)
            {
                $rejection->isactive = 0;
                $rejection->isdeleted = 0;
                $rejection->revokedby = Yii::$app->user->getId();
                $rejection->revokedate = date('Y-m-d');
            }
            else
            {
                $rejection->isactive = 0;
                $rejection->isdeleted = 1;
                $rejection->revokedby = Yii::$app->user->getId();
                $rejection->revokedate = date('Y-m-d');
                
                //remove 'RejectionApplication' records
                $rej_applications = RejectionApplications::find()
                                ->where(['rejectionid' => $rejection->rejectionid, 'isactive' => 1, 'isdeleted' => 0])
                                ->all();
                
                foreach($rej_applications as $record)
                {
                    if($rejection->ispublished == 1)
                    {
                        $record->isactive = 0;
                        $record->isdeleted = 0;
                    }
                    else
                    {
                        $record->isactive = 0;
                        $record->isdeleted = 1;
                    }
                    $save_flag_1 = $record->save();
                    if ($save_flag_1 == false)
                        return false;
                }
            }
           
            $save_flag_2 = $rejection->save();
            if ( $save_flag_2 == true)
                return true;
            return false;
        }
        return false;
    }
    
    
    
    /**
     * Returns true if there is a rejection for the stated application period exists
     * 
     * @param type $applicationperiodid
     * @param type $rejectiontype
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 18/04/2016
     * Date Last Modified: 18/04/2016
     */
    public static function rejectionExists($applicationperiodid, $rejectiontype)
    {
        $rejection_cond['application_period.applicationperiodid'] = $applicationperiodid;
        $rejection_cond['application_period.isactive'] = 1;
        $rejection_cond['application_period.iscomplete'] = 0;
        $rejection_cond['rejection.rejectiontypeid'] = $rejectiontype;
        $rejection_cond['rejection.isdeleted'] = 0;
        $rejection_cond['rejection.ispublished'] = 0;
        $rejection_cond['rejection.isactive'] = 1;
        $rejection_cond['rejection_applications.isactive'] = 1;
        $rejection_cond['rejection_applications.isdeleted'] = 0;
        
        $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($rejection_cond)
                ->groupby('rejection.rejectionid')
                ->all();
        if($rejections)
            return true;
        return false;
    }
    
    
    
    /**
     * Returns true if there is a pending application for the stated application periods exists
     * 
     * @param type $applicationperiodid
     * @param type $rejectiontype
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 18/04/2016
     * Date Last Modified: 18/04/2016
     */
    public static function anyRejectionExists($applicationperiods, $rejectiontype)
    {
        $periodids = array();
        foreach($applicationperiods as $period)
            $periodids[]=$period->applicationperiodid;
        
        $rejection_cond['application_period.applicationperiodid'] = $periodids;
        $rejection_cond['application_period.isactive'] = 1;
        $rejection_cond['application_period.iscomplete'] = 0;
        $rejection_cond['rejection.rejectiontypeid'] = $rejectiontype;
        $rejection_cond['rejection.isdeleted'] = 0;
        $rejection_cond['rejection.ispublished'] = 0;
        $rejection_cond['rejection.isactive'] = 1;
        $rejection_cond['rejection_applications.isactive'] = 1;
        $rejection_cond['rejection_applications.isdeleted'] = 0;
        
        $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($rejection_cond)
                ->groupby('rejection.rejectionid')
                ->all();
        if($rejections)
            return true;
        return false;
    }
    
    
    /**
     * Returns true is pending rejections exist
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 24/05/2016
     * Date Last Modified: 24/05/2016
     */
    public static function hasPendingRejections()
    {
        $rejection_cond['rejection.isactive'] = 1;
        $rejection_cond['rejection.isdeleted'] = 0;
        $rejection_cond['rejection.ispublished'] = 0;
        $rejection_cond['rejection.revokedby'] = null;
        $rejection_cond['rejection_applications.isactive'] = 1;
        $rejection_cond['rejection_applications.isdeleted'] = 0;
        $rejection_cond['application.isactive'] = 1;
        $rejection_cond['application.isdeleted'] = 0;
        $rejection_cond['academic_offering.isactive'] = 1;
        $rejection_cond['academic_offering.isdeleted'] = 0;
        $rejection_cond['application_period.isactive'] = 1;
        $rejection_cond['application_period.isdeleted'] = 0;
        $rejection_cond['application_period.iscomplete'] = 0;
        
        $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($rejection_cond)
                ->all();
        if($rejections)
            return true;
        return false;
    }
    
    
    /**
     * Returns true is published rejections exist
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 24/05/2016
     * Date Last Modified: 24/05/2016
     */
    public static function hasPublishedRejections()
    {
        $rejection_cond['rejection.isactive'] = 1;
        $rejection_cond['rejection.isdeleted'] = 0;
        $rejection_cond['rejection.ispublished'] = 1;
        $rejection_cond['rejection.revokedby'] = null;
        $rejection_cond['rejection_applications.isactive'] = 1;
        $rejection_cond['rejection_applications.isdeleted'] = 0;
        $rejection_cond['application.isactive'] = 1;
        $rejection_cond['application.isdeleted'] = 0;
        $rejection_cond['academic_offering.isactive'] = 1;
        $rejection_cond['academic_offering.isdeleted'] = 0;
        $rejection_cond['application_period.isactive'] = 1;
        $rejection_cond['application_period.isdeleted'] = 0;
        $rejection_cond['application_period.iscomplete'] = 0;
        
        $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($rejection_cond)
                ->all();
        if($rejections)
            return true;
        return false;
    }
    
    
    /**
     * Returns true is revoked rejections exist
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 24/05/2016
     * Date Last Modified: 24/05/2016
     */
    public static function hasRevokededRejections()
    {
        $rejection_cond['rejection.isdeleted'] = 0;
        $rejection_cond['rejection_applications.isactive'] = 1;
        $rejection_cond['rejection_applications.isdeleted'] = 0;
        $rejection_cond['application.isactive'] = 1;
        $rejection_cond['application.isdeleted'] = 0;
        $rejection_cond['academic_offering.isactive'] = 1;
        $rejection_cond['academic_offering.isdeleted'] = 0;
        $rejection_cond['application_period.isactive'] = 1;
        $rejection_cond['application_period.isdeleted'] = 0;
        $rejection_cond['application_period.iscomplete'] = 0;
        
        $rejections = Rejection::find()
                ->innerJoin('`rejection_applications`', '`rejection_applications`.`rejectionid` = `rejection`.`rejectionid`')
                ->innerJoin('`application`', '`application`.`applicationid` = `rejection_applications`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($rejection_cond)
                ->andWhere(['not', ['rejection.revokedby' => null]])
                ->all();
        if($rejections)
            return true;
        return false;
    }
   
    
    
}
