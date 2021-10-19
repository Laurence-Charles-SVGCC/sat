<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "student_hold".
 *
 * @property int $studentholdid
 * @property int $studentregistrationid
 * @property int $holdtypeid
 * @property int $appliedby
 * @property int $resolvedby
 * @property int $holdstatus
 * @property string $details
 * @property string $dateapplied
 * @property string $dateresolved
 * @property int $isactive
 * @property int $isdeleted
 * @property int $wasnotified
 * @property int $academicyearid
 *
 * @property HoldType $holdtype
 * @property User $appliedby0
 * @property User $resolvedby0
 * @property StudentRegistration $studentregistration
 */
class StudentHold extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_hold';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['studentregistrationid', 'holdtypeid', 'appliedby', 'dateapplied'], 'required'],
            [['studentregistrationid', 'holdtypeid', 'appliedby', 'resolvedby', 'holdstatus', 'isactive', 'isdeleted', 'wasnotified', 'academicyearid'], 'integer'],
            [['details'], 'string'],
            [['dateapplied', 'dateresolved'], 'safe'],
            [['holdtypeid'], 'exist', 'skipOnError' => true, 'targetClass' => HoldType::class, 'targetAttribute' => ['holdtypeid' => 'holdtypeid']],
            [['appliedby'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['appliedby' => 'personid']],
            [['resolvedby'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['resolvedby' => 'personid']],
            [['studentregistrationid'], 'exist', 'skipOnError' => true, 'targetClass' => StudentRegistration::class, 'targetAttribute' => ['studentregistrationid' => 'studentregistrationid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'studentholdid' => 'Studentholdid',
            'studentregistrationid' => 'Studentregistrationid',
            'holdtypeid' => 'Holdtypeid',
            'appliedby' => 'Appliedby',
            'resolvedby' => 'Resolvedby',
            'holdstatus' => 'Holdstatus',
            'details' => 'Details',
            'dateapplied' => 'Dateapplied',
            'dateresolved' => 'Dateresolved',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'wasnotified' => 'Wasnotified',
            'academicyearid' => 'Academicyearid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHoldtype()
    {
        return $this->hasOne(HoldType::class, ['holdtypeid' => 'holdtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppliedby0()
    {
        return $this->hasOne(User::class, ['personid' => 'appliedby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResolvedby0()
    {
        return $this->hasOne(User::class, ['personid' => 'resolvedby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::class, ['studentregistrationid' => 'studentregistrationid']);
    }
}
