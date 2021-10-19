<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "academic_offering".
 *
 * @property int $academicofferingid
 * @property int $programmecatalogid
 * @property int $academicyearid
 * @property int $applicationperiodid
 * @property int $spaces
 * @property int $interviewneeded
 * @property int $credits_required
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property ProgrammeCatalog $programmecatalog
 * @property AcademicYear $academicyear
 * @property ApplicationPeriod $applicationperiod
 * @property Application[] $applications
 * @property Award[] $awards
 * @property CapeSubject[] $capeSubjects
 * @property Cordinator[] $cordinators
 * @property CourseOffering[] $courseOfferings
 * @property ProgrammeCordinator[] $programmeCordinators
 * @property StudentRegistration[] $studentRegistrations
 */
class AcademicOffering extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'academic_offering';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['programmecatalogid', 'academicyearid', 'applicationperiodid'], 'required'],
            [['programmecatalogid', 'academicyearid', 'applicationperiodid', 'spaces', 'interviewneeded', 'credits_required', 'isactive', 'isdeleted'], 'integer'],
            [['programmecatalogid'], 'exist', 'skipOnError' => true, 'targetClass' => ProgrammeCatalog::class, 'targetAttribute' => ['programmecatalogid' => 'programmecatalogid']],
            [['academicyearid'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicYear::class, 'targetAttribute' => ['academicyearid' => 'academicyearid']],
            [['applicationperiodid'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationPeriod::class, 'targetAttribute' => ['applicationperiodid' => 'applicationperiodid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'academicofferingid' => 'Academicofferingid',
            'programmecatalogid' => 'Programmecatalogid',
            'academicyearid' => 'Academicyearid',
            'applicationperiodid' => 'Applicationperiodid',
            'spaces' => 'Spaces',
            'interviewneeded' => 'Interviewneeded',
            'credits_required' => 'Credits Required',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammecatalog()
    {
        return $this->hasOne(ProgrammeCatalog::class, ['programmecatalogid' => 'programmecatalogid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicyear()
    {
        return $this->hasOne(AcademicYear::class, ['academicyearid' => 'academicyearid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationperiod()
    {
        return $this->hasOne(ApplicationPeriod::class, ['applicationperiodid' => 'applicationperiodid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::class, ['academicofferingid' => 'academicofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAwards()
    {
        return $this->hasMany(Award::class, ['academicyearid' => 'academicyearid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapeSubjects()
    {
        return $this->hasMany(CapeSubject::class, ['academicofferingid' => 'academicofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCordinators()
    {
        return $this->hasMany(Cordinator::class, ['academicofferingid' => 'academicofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseOfferings()
    {
        return $this->hasMany(CourseOffering::class, ['academicofferingid' => 'academicofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammeCordinators()
    {
        return $this->hasMany(ProgrammeCordinator::class, ['academicofferingid' => 'academicofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentRegistrations()
    {
        return $this->hasMany(StudentRegistration::class, ['academicofferingid' => 'academicofferingid']);
    }
}
