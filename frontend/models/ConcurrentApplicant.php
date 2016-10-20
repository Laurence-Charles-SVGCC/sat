<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "concurrent_applicant".
 *
 * @property string $concurrentapplicantid
 * @property string $primaryapplicantid
 * @property string $secondaryapplicantid
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Applicant $primaryapplicant
 * @property Applicant $secondaryapplicant
 */
class ConcurrentApplicant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'concurrent_applicant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['primaryapplicantid', 'secondaryapplicantid'], 'required'],
            [['primaryapplicantid', 'secondaryapplicantid', 'isactive', 'isdeleted'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'concurrentapplicantid' => 'Concurrentapplicantid',
            'primaryapplicantid' => 'Primaryapplicantid',
            'secondaryapplicantid' => 'Secondaryapplicantid',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrimaryapplicant()
    {
        return $this->hasOne(Applicant::className(), ['applicantid' => 'primaryapplicantid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSecondaryapplicant()
    {
        return $this->hasOne(Applicant::className(), ['applicantid' => 'secondaryapplicantid']);
    }
    
    
    /**
     * Returns true is applicantid corresponds with primaryapplicantid of any ConcurrentApplicant.
     * 
     * @param type $applicantid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 18/10/2016
     * Date Last Modified: 20/10/2016
     */
    public static function isPrimary($applicantid)
    {
        $records = ConcurrentApplicant::find()
                ->where(['primaryapplicantid' => $applicantid, 'isdeleted' => 0])
                ->all();
        if ($records == true)
            return true;
        return false;
    }
    
    
    /**
     * Returns true is applicantid corresponds with secondaryapplicantid of any ConcurrentApplicant.
     * 
     * @param type $applicantid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 18/10/2016
     * Date Last Modified: 20/10/2016
     */
    public static function isSecondary($applicantid)
    {
        $records = ConcurrentApplicant::find()
                ->where(['secondaryapplicantid' => $applicantid, 'isdeleted' => 0])
                ->all();
        if ($records == true)
            return true;
        return false;
    }
    
    
     /**
     * Returns all associatred ConcurrentApplicant.= records
     * 
     * @param type $applicantid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 18/10/2016
     * Date Last Modified: 18/10/2016
     */
    public static function getAssociatedApplicants($applicantid)
    {
        $related_records = ConcurrentApplicant::find()
                ->where(['primaryapplicantid' => $applicantid, 'isdeleted' => 0])
                ->orWhere(['secondaryapplicantid' => $applicantid, 'isdeleted' => 0])
                ->all();
        if ($related_records == true)
        {
            return $related_records;
        }
        return false;
    }
}
