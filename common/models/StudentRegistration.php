<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "student_registration".
 *
 * @property int $studentregistrationid
 * @property int $offerid
 * @property int $personid
 * @property int $academicofferingid
 * @property int $registrationtypeid
 * @property int $studentstatusid
 * @property int $academicstatusid
 * @property int $currentlevel
 * @property string $registrationdate
 * @property int $receivedpicture
 * @property int $cardready
 * @property int $cardcollected
 * @property int $isactive
 * @property int $isdeleted
 * @property string $statuschangedate
 *
 * @property AssessmentStudent[] $assessmentStudents
 * @property AssessmentStudentCape[] $assessmentStudentCapes
 * @property BatchStudentCape[] $batchStudentCapes
 * @property BatchCape[] $batchcapes
 * @property BatchStudents[] $batchStudents
 * @property Batch[] $batches
 * @property ClubMemberHistory[] $clubMemberHistories
 * @property DisciplinaryAction[] $disciplinaryActions
 * @property Event[] $events
 * @property GraduationReport[] $graduationReports
 * @property MaternityLeave[] $maternityLeaves
 * @property MiscellaneousEvent[] $miscellaneousEvents
 * @property PersonAward[] $personAwards
 * @property SickLeave[] $sickLeaves
 * @property StatusHistory[] $statusHistories
 * @property StudentDeferral[] $studentDeferrals
 * @property StudentDeferral[] $studentDeferrals0
 * @property StudentHold[] $studentHolds
 * @property User $person
 * @property AcademicOffering $academicoffering
 * @property RegistrationType $registrationtype
 * @property StudentStatus $studentstatus
 * @property AcademicStatus $academicstatus
 * @property StudentTransfer[] $studentTransfers
 */
class StudentRegistration extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_registration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['offerid', 'personid', 'academicofferingid', 'registrationtypeid', 'studentstatusid', 'academicstatusid', 'currentlevel', 'receivedpicture', 'cardready', 'cardcollected', 'isactive', 'isdeleted'], 'integer'],
            [['personid', 'academicofferingid', 'registrationtypeid', 'currentlevel', 'registrationdate'], 'required'],
            [['registrationdate', 'statuschangedate'], 'safe'],
            [['personid'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['personid' => 'personid']],
            [['academicofferingid'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicOffering::class, 'targetAttribute' => ['academicofferingid' => 'academicofferingid']],
            [['registrationtypeid'], 'exist', 'skipOnError' => true, 'targetClass' => RegistrationType::class, 'targetAttribute' => ['registrationtypeid' => 'registrationtypeid']],
            [['studentstatusid'], 'exist', 'skipOnError' => true, 'targetClass' => StudentStatus::class, 'targetAttribute' => ['studentstatusid' => 'studentstatusid']],
            [['academicstatusid'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicStatus::class, 'targetAttribute' => ['academicstatusid' => 'academicstatusid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'studentregistrationid' => 'Studentregistrationid',
            'offerid' => 'Offerid',
            'personid' => 'Personid',
            'academicofferingid' => 'Academicofferingid',
            'registrationtypeid' => 'Registrationtypeid',
            'studentstatusid' => 'Studentstatusid',
            'academicstatusid' => 'Academicstatusid',
            'currentlevel' => 'Currentlevel',
            'registrationdate' => 'Registrationdate',
            'receivedpicture' => 'Receivedpicture',
            'cardready' => 'Cardready',
            'cardcollected' => 'Cardcollected',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'statuschangedate' => 'Statuschangedate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentStudents()
    {
        return $this->hasMany(AssessmentStudent::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentStudentCapes()
    {
        return $this->hasMany(AssessmentStudentCape::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchStudentCapes()
    {
        return $this->hasMany(BatchStudentCape::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchcapes()
    {
        return $this->hasMany(BatchCape::class, ['batchcapeid' => 'batchcapeid'])->viaTable('batch_student_cape', ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchStudents()
    {
        return $this->hasMany(BatchStudents::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatches()
    {
        return $this->hasMany(Batch::class, ['batchid' => 'batchid'])->viaTable('batch_students', ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubMemberHistories()
    {
        return $this->hasMany(ClubMemberHistory::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisciplinaryActions()
    {
        return $this->hasMany(DisciplinaryAction::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGraduationReports()
    {
        return $this->hasMany(GraduationReport::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaternityLeaves()
    {
        return $this->hasMany(MaternityLeave::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMiscellaneousEvents()
    {
        return $this->hasMany(MiscellaneousEvent::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonAwards()
    {
        return $this->hasMany(PersonAward::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSickLeaves()
    {
        return $this->hasMany(SickLeave::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusHistories()
    {
        return $this->hasMany(StatusHistory::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentDeferrals()
    {
        return $this->hasMany(StudentDeferral::class, ['registrationfrom' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentDeferrals0()
    {
        return $this->hasMany(StudentDeferral::class, ['registrationto' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentHolds()
    {
        return $this->hasMany(StudentHold::class, ['studentregistrationid' => 'studentregistrationid']);
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
    public function getRegistrationtype()
    {
        return $this->hasOne(RegistrationType::class, ['registrationtypeid' => 'registrationtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentstatus()
    {
        return $this->hasOne(StudentStatus::class, ['studentstatusid' => 'studentstatusid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicstatus()
    {
        return $this->hasOne(AcademicStatus::class, ['academicstatusid' => 'academicstatusid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentTransfers()
    {
        return $this->hasMany(StudentTransfer::class, ['studentregistrationid' => 'studentregistrationid']);
    }
}
