<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "applicant_intent".
 *
 * @property int $applicantintentid
 * @property int $intenttypeid
 * @property string $name
 * @property string $description
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property AcademicYear[] $academicYears
 * @property Applicant[] $applicants
 * @property IntentType $intenttype
 * @property ApplicantRegistration[] $applicantRegistrations
 */
class ApplicantIntent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_intent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['intenttypeid'], 'required'],
            [['intenttypeid', 'isactive', 'isdeleted'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['intenttypeid'], 'exist', 'skipOnError' => true, 'targetClass' => IntentType::class, 'targetAttribute' => ['intenttypeid' => 'intenttypeid']],
        ];
    }

    /**
     * {@inheritdoc}
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
        return $this->hasMany(AcademicYear::class, ['applicantintentid' => 'applicantintentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicants()
    {
        return $this->hasMany(Applicant::class, ['applicantintentid' => 'applicantintentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIntenttype()
    {
        return $this->hasOne(IntentType::class, ['intenttypeid' => 'intenttypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantRegistrations()
    {
        return $this->hasMany(ApplicantRegistration::class, ['applicantintentid' => 'applicantintentid']);
    }
}
