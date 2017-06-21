<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "applicant_deferral".
 *
 * @property string $applicantdeferralid
 * @property string $applicantid
 * @property string $personid
 * @property string $deferredby
 * @property string $deferraldate
 * @property string $resumedby
 * @property string $dateresumed
 * @property string $details
 * @property integer $isactive
 * @property integer $isdeleted
 */
class ApplicantDeferral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'applicant_deferral';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicantid', 'personid', 'deferredby', 'deferraldate', 'details'], 'required'],
            [['applicantid', 'personid', 'deferredby', 'isactive', 'isdeleted'], 'integer'],
            [['deferraldate'], 'safe'],
            [['details'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicantdeferralid' => 'Applicantdeferralid',
            'applicantid' => 'Applicantid',
            'personid' => 'Personid',
            'deferredby' => 'Deferredby',
            'deferraldate' => 'Deferraldate',
            'details' => 'Details',
            'resumedby' => 'ResumedBy',
            'dateresumed' => 'Date Resumed',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
