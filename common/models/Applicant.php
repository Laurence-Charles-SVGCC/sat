<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "applicant".
 *
 * @property int $applicantid
 * @property int $personid
 * @property int $potentialstudentid
 * @property int $applicantintentid
 * @property string $title
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $gender
 * @property string $dateofbirth
 * @property string $photopath
 * @property int $bursarystatus
 * @property string $sponsorname
 * @property string $clubs
 * @property string $otherinterests
 * @property int $isactive
 * @property int $isdeleted
 * @property string $maritalstatus
 * @property string $nationality
 * @property string $religion
 * @property string $placeofbirth
 * @property string $nationalsports
 * @property string $othersports
 * @property string $otheracademics
 * @property int $isexternal
 * @property int $verifier
 * @property int $hasduplicate
 * @property int $isprimary
 * @property int $hasdeferred
 *
 * @property User $user
 * @property ApplicantIntent $applicantintent
 * @property User $verifier0
 * @property ApplicantDeferral[] $applicantDeferrals
 */
class Applicant extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['personid', 'potentialstudentid', 'applicantintentid', 'bursarystatus', 'isactive', 'isdeleted', 'isexternal', 'verifier', 'hasduplicate', 'isprimary', 'hasdeferred'], 'integer'],
            [['dateofbirth'], 'safe'],
            [['clubs', 'otherinterests', 'nationalsports', 'othersports', 'otheracademics'], 'string'],
            [['title'], 'string', 'max' => 4],
            [['firstname', 'middlename', 'lastname', 'nationality', 'religion', 'placeofbirth'], 'string', 'max' => 45],
            [['gender'], 'string', 'max' => 6],
            [['photopath', 'sponsorname'], 'string', 'max' => 100],
            [['maritalstatus'], 'string', 'max' => 15],
            [['personid'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['personid' => 'personid']],
            [['applicantintentid'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantIntent::class, 'targetAttribute' => ['applicantintentid' => 'applicantintentid']],
            [['verifier'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['verifier' => 'personid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'applicantid' => 'Applicantid',
            'personid' => 'Personid',
            'potentialstudentid' => 'Potentialstudentid',
            'applicantintentid' => 'Applicantintentid',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'middlename' => 'Middlename',
            'lastname' => 'Lastname',
            'gender' => 'Gender',
            'dateofbirth' => 'Dateofbirth',
            'photopath' => 'Photopath',
            'bursarystatus' => 'Bursarystatus',
            'sponsorname' => 'Sponsorname',
            'clubs' => 'Clubs',
            'otherinterests' => 'Otherinterests',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'maritalstatus' => 'Maritalstatus',
            'nationality' => 'Nationality',
            'religion' => 'Religion',
            'placeofbirth' => 'Placeofbirth',
            'nationalsports' => 'Nationalsports',
            'othersports' => 'Othersports',
            'otheracademics' => 'Otheracademics',
            'isexternal' => 'Isexternal',
            'verifier' => 'Verifier',
            'hasduplicate' => 'Hasduplicate',
            'isprimary' => 'Isprimary',
            'hasdeferred' => 'Hasdeferred',
        ];
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
    public function getApplicantintent()
    {
        return $this->hasOne(ApplicantIntent::class, ['applicantintentid' => 'applicantintentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVerifier0()
    {
        return $this->hasOne(User::class, ['personid' => 'verifier']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDeferrals()
    {
        return $this->hasMany(ApplicantDeferral::class, ['applicantid' => 'applicantid']);
    }
}
