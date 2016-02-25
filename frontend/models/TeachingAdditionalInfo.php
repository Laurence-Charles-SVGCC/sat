<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "teaching_additional_info".
 *
 * @property integer $teachingadditionalinfoid
 * @property integer $personid
 * @property integer $childcount
 * @property string $childages
 * @property integer $brothercount
 * @property integer $sistercount
 * @property string $yearcompletedschool
 * @property integer $hasworked
 * @property integer $isworking
 * @property integer $hasteachingexperience
 * @property integer $hascriminalrecord
 * @property string $applicationmotivation
 * @property string $additionalcomments
 * @property string $benefactor
 * @property string $benefactordetails
 * @property integer $appliedforloan
 * @property string $sponsorship
 * @property string $sponsorname
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 */
class TeachingAdditionalInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teaching_additional_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'childcount', 'brothercount', 'sistercount', 'yearcompletedschool', 'hasworked', 'isworking', 'hasteachingexperience', 'hascriminalrecord', 'applicationmotivation', 'benefactor', 'appliedforloan', 'sponsorship'], 'required'],
            [['personid', 'childcount', 'brothercount', 'sistercount', 'hasworked', 'isworking', 'hasteachingexperience', 'hascriminalrecord', 'appliedforloan', 'isactive', 'isdeleted'], 'integer'],
            [['applicationmotivation', 'additionalcomments'], 'string'],
            ['yearcompletedschool', 'match', 'pattern' => '/^\d{4}$/'],
            [['childages', 'sponsorship'], 'string', 'max' => 45],
            [['benefactor'], 'string', 'max' => 10],
            [['benefactordetails', 'sponsorname'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'teachingadditionalinfoid' => 'Teachingadditionalinfoid',
            'personid' => 'Personid',
            'childcount' => 'Childcount',
            'childages' => 'Childages',
            'brothercount' => 'Brothercount',
            'sistercount' => 'Sistercount',
            'yearcompletedschool' => 'Year Completed School',
            'hasworked' => 'Hasworked',
            'isworking' => 'Isworking',
            'hasteachingexperience' => 'Hasteachingexperience',
            'hascriminalrecord' => 'Hascriminalrecord',
            'applicationmotivation' => 'Applicationmotivation',
            'additionalcomments' => 'Additionalcomments',
            'benefactor' => 'Benefactor',
            'benefactordetails' => 'Benefactordetails',
            'appliedforloan' => 'Appliedforloan',
            'sponsorship' => 'Sponsorship',
            'sponsorname' => 'Sponsorname',
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
     * Creates default values for model
     * 
     * @param type $personModel
     * 
     * Author: Laurence Charles
     * Date Created: 31/01/2016
     * Date Last Modified: 31/01/2016
     */
    public function initiateTeachingInfo($personid)
    {
        $this->personid = $personid;
        $this->childcount = 0;
        $this->childages = NULL;
        $this->brothercount = 0;
        $this->sistercount = 0;
        $this->yearcompletedschool = "1999";
        $this->hasworked = 0;
        $this->isworking = 0;
        $this->hasteachingexperience = 0;
        $this->hascriminalrecord = 0;
        $this->applicantmotivation = "Enter Motivation";
        $this->additionalcomments = "Anything else?";
        $this->benefactor= "Self";
        $this->benefactoretails = NULL;
        $this->appliedforlaon = 0;
        $this->sponsoship = "Neither";
        $this->sponsorame = NULL;
    }
    
    
    /**
     * Determines if all manadatory field of the model have data.
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 01/02/2016
     * Date Last Modified: 01/02/2016
     */
    public static function checkTeachingInformation($id){
        $model= TeachingAdditionalInfo::find()
                ->where(['personid' => $id, 'isactive' => 1 , 'isdeleted' => 0])
                ->one();
        if ($model){
            if(
               $model->yearcompletedschool !== NULL && strcmp($model->yearcompletedschool,"Enter Year") != 0
               && $model->applicationmotivation !== NULL && strcmp($model->applicationmotivation,"Enter Motivation") != 0
               && Reference::checkReferences($id) == true       
              )
            {
                return true;
            }
        }    
        return false;
    }
    
    
     /**
     * Returns instance of model 
     * 
     * @param type $id          An applicant's personid
     * @return boolean
     * 
     * Author: Laurence Chalres 
     * Date Created: 01/02/2016
     * Last Date Modified: 01/02/2016
     */
    public static function getTeachingInfo($id)
    {
        $model = TeachingAdditionalInfo::find()
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
     * Author: Laurene Charles
     * Date reated: 31/01/2016
     * Last Date Modified: 31/01/2016
     */
    public static function hasChildren($id)
    {
        $model = TeachingAdditionalInfo::find()
                 ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                 ->one();
        if ($model)
        {
            if ($model->childcount > 0)
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
     * Author: Laurene Charles
     * Date reated: 31/01/2016
     * Last Date Modified: 31/01/2016
     */
    public static function hasBrothers($id)
    {
        $model = NursingAdditionalInfo::find()
                 ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
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
     * Author: Laurene Charles
     * Date reated: 31/01/2016
     * Last Date Modified: 31/01/2016
     */
    public static function hasSisters($id)
    {
        $model = NursingAdditionalInfo::find()
                 ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
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
     * Author: Laurene Charles
     * Date reated: 31/01/2016
     * Last Date Modified: 31/01/2016
     */
    public static function hasWorked($id)
    {
        $model = NursingAdditionalInfo::find()
                 ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
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
     * Author: Laurene Charles
     * Date reated: 31/01/2016
     * Last Date Modified: 31/01/2016
     */
    public static function isWorking($id)
    {
        $model = NursingAdditionalInfo::find()
                 ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                 ->one();
        if ($model)
        {
            if ($model->isworking == 1)
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
     * Date reated: 31/01/2016
     * Last Date Modified: 31/01/2016
     */
    public static function hasCriminalRecord($id)
    {
        $teaching_information = TeachingAdditionalInfo::find()
                 ->where(['personid' => $id, 'isactive'=> 1 , 'isdeleted'=> 0])
                 ->one();
        if ($teaching_information)
        {
            if ($teaching_information->hascriminalrecord == 1)
                return true;     
        }
        return false;
    }
    
}
