<?php

namespace frontend\models;

use Yii;
use frontend\models\Reference;



/**
 * This is the model class for table "nursing_additional_info".
 *
 * @property integer $applicantadditionalinfoid
 * @property integer $personid
 * @property integer $childcount
 * @property string $childages
 * @property integer $brothercount
 * @property integer $sistercount
 * @property string $yearcompletedschool
 * @property integer $hasworked
 * @property integer $isworking
 * @property integer $hasnursingexperience
 * @property integer $hasotherapplications
 * @property string $otherapplicationsinfo
 * @property integer $hascriminalrecord
 * @property string $applicantmotivation1
 * @property string $applicantmotivation2
 * @property string $additionalcomments
 * @property string $memberorganisations
 * @property string $exclusionreason
 * @property string $repeatapplicant
 * @property string $previousyears
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 */
class NursingAdditionalInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nursing_additional_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'childcount', 'brothercount', 'sistercount', 'yearcompletedschool', 'hasworked', 'isworking', 'hasnursingexperience', 'hasotherapplications', 'hascriminalrecord', 'applicantmotivation1', 'applicantmotivation2'], 'required'],
            [['personid', 'childcount', 'brothercount', 'sistercount', 'hasworked', 'isworking', 'hasnursingexperience', 'hasotherapplications', 'hascriminalrecord', 'ismember', 'repeatapplicant'], 'integer'],
            ['yearcompletedschool', 'match', 'pattern' => '/^\d{4}$/'],
            [['otherapplicationsinfo', 'applicantmotivation1', 'applicantmotivation2', 'additionalcomments', 'memberorganisations', 'exclusionreason'], 'string'],
            [['childages', 'yearcompletedschool', 'previousyears'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicantadditionalinfoid' => 'Applicantadditionalinfoid',
            'personid' => 'Personid',
            'childcount' => 'Childcount',
            'childages' => 'Childages',
            'brothercount' => 'Brothercount',
            'sistercount' => 'Sistercount',
            'yearcompletedschool' => 'Yearcompletedschool',
            'hasworked' => 'Hasworked',
            'isworking' => 'Isworking',
            'hasnursingexperience' => 'Hasnursingexperience',
            'hasotherapplications' => 'Hasotherapplications',
            'otherapplicationsinfo' => 'Otherapplicationsinfo',
            'hascriminalrecord' => 'Hascriminalrecord',
            'applicantmotivation1' => 'Applicantmotivation1',
            'applicantmotivation2' => 'Applicantmotivation2',
            'additionalcomments' => 'Additionalcomments',
            'ismember' => 'Is Member',
            'memberorganisations' => 'Member Organisations',
            'exclusionreason' => 'Exclusion Reason',
            'repeatapplicant' => 'Repeat Applicant',
            'previousyears' => 'Previous Years',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['personid' => 'personid']);
    }
    
    
    /**
     * Creates default values for NursingAdditionalInformation model
     * 
     * @param type $personModel
     * 
     * Author: Laurence Charles
     * Date Created: 07/10/2015
     * Date Last Modified: 07/10/2015
     */
    public function initiateNursingInfo($personid)
    {
        $this->personid = $personid;
        $this->childcount = 0;
        $this->childages = NULL;
        $this->brothercount = 0;
        $this->sistercount = 0;
        $this->yearcompletedschool = "1999";
        $this->hasworked = 0;
        $this->isworking = 0;
        $this->hasnursingexperience = 0;
        $this->hasotherapplications = 0;
        $this->otherapplicationsinfo = NULL;
        $this->hascriminalrecord = 0;
        $this->applicantmotivation1 = "Enter Motivation";
        $this->applicantmotivation2 = "Enter Motivation";
        $this->additionalcomments = "Anything else?";
    }
    
    
    /**
     * Returns instance of model 
     * 
     * @param type $id          An applicant's personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 31/01/2016
     * Last Date Modified: 31/01/2016
     */
    public static function getNursingInfo($id)
    {
        $model = NursingAdditionalInfo::find()
                 ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                 ->one();
        if ($model)
            return $model;
        return false;
    }
    
    
    /**
     * Determines if applicant has any children
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date reated: 29/09/2015
     * Last Date Modified: 29/09/2015
     */
    public static function hasChildren($id)
    {
        $model = NursingAdditionalInfo::find()
                 ->where(['personid' => $id])
                 ->one();
        if ($model)
        {
            if ($model->childcount>0)
                return true;  
        }
        return false;
    }
    
    
    /**
     * Determines if applicant has any brothers
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date reated: 29/09/2015
     * Last Date Modified: 29/09/2015 | 05/02/2016
     */
    public static function hasBrothers($id)
    {
        $model = NursingAdditionalInfo::find()
                 ->where(['personid' => $id])
                 ->one();
        if ($model)
        {
            if ($model->brothercount > 0)
                return true;   
        }
        return false;
    }
    
    
    /**
     * Determines if applicant has any sisters
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date reated: 29/09/2015
     * Last Date Modified: 29/09/2015 | 05/02/2016
     */
    public static function hasSisters($id)
    {
        $model = NursingAdditionalInfo::find()
                 ->where(['personid' => $id])
                 ->one();
        if ($model)
        {
            if ($model->sistercount > 0)
                return true; 
        }
        return false;
    }
    
    
    /**
     * Determines if applicant has worked in the past
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date reated: 29/09/2015
     * Last Date Modified: 29/09/2015 | 05/02/2016
     */
    public static function hasWorked($id)
    {
        $model = NursingAdditionalInfo::find()
                 ->where(['personid' => $id])
                 ->one();
        if ($model)
        {
            if ($model->hasworked == 1)
                return true;       
        }
        return false;
    }
    
    
    /**
     * Determines if applicant is currently working
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date reated: 29/09/2015
     * Last Date Modified: 29/09/2015 | 05/02/2016
     */
    public static function isWorking($id)
    {
        $model = NursingAdditionalInfo::find()
                 ->where(['personid' => $id])
                 ->one();
        if ($model)
        {
            if ($model->isworking == 1)
                return true;
        }
        return false;
    }
    
    
    /**
     * Determines if applicant is awaiting any application responses
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date reated: 30/09/2015
     * Last Date Modified: 30/09/2015 | 05/02/2016
     */
    public static function hasOtherApplications($id)
    {
        $model = NursingAdditionalInfo::find()
                 ->where(['personid' => $id])
                 ->one();
        if ($model)
        {
            if ($model->hasotherapplications == 1)
                return true;
        }
        return false;
    }
    
    
    /**
     * Determines if applicant has criminal record
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date reated: 29/09/2015
     * Last Date Modified: 29/09/2015 | 05/02/2016
     */
    public static function hasCriminalRecord($id)
    {
        $model = NursingAdditionalInfo::find()
                 ->where(['personid' => $id, 'isactive'=>1 , 'isdeleted'=>0])
                 ->one();
        if ($model)
        {
            if ($model->hascriminalrecord == 1)
                return true;     
        }
        return false;
    }
    
    
    /**
     * Determines if applicant has indicated a previous nursing record
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date reated: 05/10/2015
     * Last Date Modified: 05/10/2015 | 05/02/2016
     */
    public static function hasPreviousNurseExperience($id)
    {
        $model = NursingAdditionalInfo::find()
                 ->where(['personid' => $id])
                 ->one();
        if ($model)
        {
            if ($model->hasnursingexperience == 1)
                return true; 
        }
        return false;
    }
    
    
    /**
     * Determines if all manadatory field of the model have data.
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 29/09/2015
     * Date Last Modified: 29/0/2015 | 05/02/2016
     */
    public static function checkNursingInformation($id){
        $model= NursingAdditionalInfo::find()
                ->where(['personid' => $id, 'isactive' =>1 , 'isdeleted' => 0])
                ->one();
        if ($model){
            if(
               $model->childcount !== NULL && strcmp($model->childcount,"") != 0
               && $model->brothercount !== NULL  && strcmp($model->brothercount,"") != 0 
               && $model->sistercount !== NULL && strcmp($model->sistercount,"") != 0  
               && $model->yearcompletedschool !== NULL && strcmp($model->yearcompletedschool,"") != 0
               && $model->hasworked !== NULL && strcmp($model->hasworked,"") != 0
               && $model->isworking !== NULL && strcmp($model->isworking,"") != 0
               && $model->hascriminalrecord !== NULL && strcmp($model->hascriminalrecord,"") != 0
               && $model->applicantmotivation1 !== NULL && strcmp($model->applicantmotivation1,"") != 0
               && $model->applicantmotivation2 !== NULL && strcmp($model->applicantmotivation2,"") != 0
               && Reference::checkReferences($id) == true       
              )
                return true;
        }    
        return false;
    }
    
    
    /**
     * Returns true is nursing midwifery applicant indicated they had applied previously
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 05/02/2016
     * Date Last Modified: 05/02/2016
     */
    public static function hasPreviousApplication($personid)
    {
        $nursing_info = NursingAdditionalInfo::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        if($nursing_info)
        {
            if($nursing_info->repeatapplicant == 1)
                return true;
        }
        return false;
    }
    
    
    /**
     * Returns true is nursing midwifery applicant indicated they are a member of a professional organisation
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 05/02/2016
     * Date Last Modified: 05/02/2016
     */
    public static function isMember($personid)
    {
        $nursing_info = NursingAdditionalInfo::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        if($nursing_info)
        {
            if($nursing_info->ismember == 1)
                return true;
        }
        return false;
    }       
    
    
}
