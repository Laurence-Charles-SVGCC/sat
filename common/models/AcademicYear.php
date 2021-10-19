<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "academic_year".
 *
 * @property int $academicyearid
 * @property string $title
 * @property int $applicantintentid
 * @property int $iscurrent
 * @property string $startdate
 * @property string $enddate
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property AcademicOffering[] $academicOfferings
 * @property ApplicantIntent $applicantintent
 * @property ApplicationPeriod[] $applicationPeriods
 * @property CapeUnit[] $capeUnits
 * @property Cordinator[] $cordinators
 * @property Semester[] $semesters
 */
class AcademicYear extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'academic_year';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'iscurrent', 'startdate'], 'required'],
            [['applicantintentid', 'iscurrent', 'isactive', 'isdeleted'], 'integer'],
            [['startdate', 'enddate'], 'safe'],
            [['title'], 'string', 'max' => 45],
            [['applicantintentid'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantIntent::class, 'targetAttribute' => ['applicantintentid' => 'applicantintentid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'academicyearid' => 'Academicyearid',
            'title' => 'Title',
            'applicantintentid' => 'Applicantintentid',
            'iscurrent' => 'Iscurrent',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicOfferings()
    {
        return $this->hasMany(AcademicOffering::class, ['academicyearid' => 'academicyearid']);
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
    public function getApplicationPeriods()
    {
        return $this->hasMany(ApplicationPeriod::class, ['academicyearid' => 'academicyearid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapeUnits()
    {
        return $this->hasMany(CapeUnit::class, ['academicyearid' => 'academicyearid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCordinators()
    {
        return $this->hasMany(Cordinator::class, ['academicyearid' => 'academicyearid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemesters()
    {
        return $this->hasMany(Semester::class, ['academicyearid' => 'academicyearid']);
    }
}
