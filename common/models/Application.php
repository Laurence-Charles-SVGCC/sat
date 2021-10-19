<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "application".
 *
 * @property int $applicationid
 * @property int $personid
 * @property int $divisionid
 * @property int $academicofferingid
 * @property int $applicationstatusid
 * @property string $applicationtimestamp
 * @property string $submissiontimestamp
 * @property int $ordering
 * @property string $ipaddress
 * @property string $browseragent
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property ApplicantDeferral[] $applicantDeferrals
 * @property ApplicantDeferral[] $applicantDeferrals0
 * @property User $person
 * @property AcademicOffering $academicoffering
 * @property ApplicationStatus $applicationstatus
 * @property Division $division
 * @property ApplicationCapesubject[] $applicationCapesubjects
 * @property ApplicationHistory[] $applicationHistories
 * @property Offer[] $offers
 * @property RejectionApplications[] $rejectionApplications
 * @property Rejection[] $rejections
 */
class Application extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['personid', 'divisionid', 'academicofferingid', 'applicationstatusid', 'applicationtimestamp', 'ordering', 'ipaddress', 'browseragent'], 'required'],
            [['personid', 'divisionid', 'academicofferingid', 'applicationstatusid', 'ordering', 'isactive', 'isdeleted'], 'integer'],
            [['applicationtimestamp', 'submissiontimestamp'], 'safe'],
            [['ipaddress', 'browseragent'], 'string'],
            [['personid'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['personid' => 'personid']],
            [['academicofferingid'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicOffering::class, 'targetAttribute' => ['academicofferingid' => 'academicofferingid']],
            [['applicationstatusid'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationStatus::class, 'targetAttribute' => ['applicationstatusid' => 'applicationstatusid']],
            [['divisionid'], 'exist', 'skipOnError' => true, 'targetClass' => Division::class, 'targetAttribute' => ['divisionid' => 'divisionid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'applicationid' => 'Applicationid',
            'personid' => 'Personid',
            'divisionid' => 'Divisionid',
            'academicofferingid' => 'Academicofferingid',
            'applicationstatusid' => 'Applicationstatusid',
            'applicationtimestamp' => 'Applicationtimestamp',
            'submissiontimestamp' => 'Submissiontimestamp',
            'ordering' => 'Ordering',
            'ipaddress' => 'Ipaddress',
            'browseragent' => 'Browseragent',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDeferrals()
    {
        return $this->hasMany(ApplicantDeferral::class, ['from_applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDeferrals0()
    {
        return $this->hasMany(ApplicantDeferral::class, ['to_applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::class, ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicoffering()
    {
        return $this->hasOne(AcademicOffering::class, ['academicofferingid' => 'academicofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationstatus()
    {
        return $this->hasOne(ApplicationStatus::class, ['applicationstatusid' => 'applicationstatusid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::class, ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationCapesubjects()
    {
        return $this->hasMany(ApplicationCapesubject::class, ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationHistories()
    {
        return $this->hasMany(ApplicationHistory::class, ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::class, ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRejectionApplications()
    {
        return $this->hasMany(RejectionApplications::class, ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRejections()
    {
        return $this->hasMany(Rejection::class, ['rejectionid' => 'rejectionid'])->viaTable('rejection_applications', ['applicationid' => 'applicationid']);
    }
}
