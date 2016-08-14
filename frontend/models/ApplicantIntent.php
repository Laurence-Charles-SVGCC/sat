<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "applicant_intent".
 *
 * @property string $applicantintentid
 * @property integer $intenttypeid
 * @property string $name
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property AcademicYear[] $academicYears
 * @property Applicant[] $applicants
 * @property IntentType $intenttype
 * @property ApplicantRegistration[] $applicantRegistrations
 */
class ApplicantIntent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'applicant_intent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intenttypeid'], 'required'],
            [['intenttypeid', 'isactive', 'isdeleted'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicantintentid' => 'Applicantintentid',
            'intenttypeid' => 'Intenttypeid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicYears()
    {
        return $this->hasMany(AcademicYear::className(), ['applicantintentid' => 'applicantintentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicants()
    {
        return $this->hasMany(Applicant::className(), ['applicantintentid' => 'applicantintentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIntenttype()
    {
        return $this->hasOne(IntentType::className(), ['intenttypeid' => 'intenttypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantRegistrations()
    {
        return $this->hasMany(ApplicantRegistration::className(), ['applicantintentid' => 'applicantintentid']);
    }
    
    
    /**
     * Returns the applicant intent  value
     * 
     * @param type $divisionid
     * @param type $applicationperiodtypeid
     * @return int
     * 
     * Author: Laurence Charles
     * Date Created: 12/08/2016
     * Date Last Modifieid: 12/08/2016
     */
    public static function getApplicantIntent($divisionid, $applicationperiodtypeid)
    {
        $applicantintentid = NULL;
        
        if (($divisionid == 4 || $divisionid == 5) && $applicationperiodtypeid == 1 )
        {
            $applicantintentid = 1;
        }
        else if ($divisionid == 4 && $applicationperiodtypeid == 2 )
        {
           $applicantintentid = 2;
        }
        else if ($divisionid == 5 && $applicationperiodtypeid == 2 )
        {
           $applicantintentid = 3;
        }
        else if ($divisionid == 6 && $applicationperiodtypeid == 1 )
        {
           $applicantintentid = 4;
        }
        else if ($divisionid == 6 && $applicationperiodtypeid == 2 )
        {
           $applicantintentid = 5;
        }
            else if ($divisionid == 7 && $applicationperiodtypeid == 1 )
        {
           $applicantintentid = 6;
        }
        else if ($divisionid == 7 && $applicationperiodtypeid == 1 )
        {
           $applicantintentid = 7;
        }
        
        return $applicantintentid;
    }
}
