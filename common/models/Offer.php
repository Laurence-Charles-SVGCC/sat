<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "offer".
 *
 * @property int $offerid
 * @property int $applicationid
 * @property int $offertypeid
 * @property int $issuedby
 * @property string $issuedate
 * @property int $revokedby
 * @property string $revokedate
 * @property int $isactive
 * @property int $isdeleted
 * @property int $ispublished
 * @property int $packageid
 * @property string $appointment
 *
 * @property Application $application
 * @property OfferType $offertype
 * @property Package $package
 * @property StudentTransfer[] $studentTransfers
 * @property StudentTransfer[] $studentTransfers0
 */
class Offer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'offer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicationid', 'issuedby', 'issuedate'], 'required'],
            [['applicationid', 'offertypeid', 'issuedby', 'revokedby', 'isactive', 'isdeleted', 'ispublished', 'packageid'], 'integer'],
            [['issuedate', 'revokedate'], 'safe'],
            [['appointment'], 'string'],
            [['applicationid'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['applicationid' => 'applicationid']],
            [['offertypeid'], 'exist', 'skipOnError' => true, 'targetClass' => OfferType::class, 'targetAttribute' => ['offertypeid' => 'offertypeid']],
            [['packageid'], 'exist', 'skipOnError' => true, 'targetClass' => Package::class, 'targetAttribute' => ['packageid' => 'packageid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'offerid' => 'Offerid',
            'applicationid' => 'Applicationid',
            'offertypeid' => 'Offertypeid',
            'issuedby' => 'Issuedby',
            'issuedate' => 'Issuedate',
            'revokedby' => 'Revokedby',
            'revokedate' => 'Revokedate',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'ispublished' => 'Ispublished',
            'packageid' => 'Packageid',
            'appointment' => 'Appointment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::class, ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffertype()
    {
        return $this->hasOne(OfferType::class, ['offertypeid' => 'offertypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackage()
    {
        return $this->hasOne(Package::class, ['packageid' => 'packageid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentTransfers()
    {
        return $this->hasMany(StudentTransfer::class, ['offerto' => 'offerid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentTransfers0()
    {
        return $this->hasMany(StudentTransfer::class, ['offerfrom' => 'offerid']);
    }
}
