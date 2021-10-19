<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "application_period".
 *
 * @property int $applicationperiodid
 * @property int $applicationperiodstatusid
 * @property int $divisionid
 * @property int $personid
 * @property int $academicyearid
 * @property string $name
 * @property string $onsitestartdate
 * @property string $onsiteenddate
 * @property string $offsitestartdate
 * @property string $offsiteenddate
 * @property int $isactive
 * @property int $isdeleted
 * @property int $applicationperiodtypeid
 * @property int $iscomplete
 * @property int $catalog_approved
 * @property int $programmes_added
 * @property int $cape_subjects_added
 *
 * @property AcademicOffering[] $academicOfferings
 * @property Division $division
 * @property User $person
 * @property AcademicYear $academicyear
 * @property ApplicationPeriodType $applicationperiodtype
 * @property ApplicationperiodStatus $applicationperiodstatus
 * @property Package[] $packages
 */
class ApplicationPeriod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_period';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicationperiodstatusid', 'divisionid', 'personid', 'academicyearid', 'name', 'onsitestartdate', 'offsitestartdate', 'applicationperiodtypeid'], 'required'],
            [['applicationperiodstatusid', 'divisionid', 'personid', 'academicyearid', 'isactive', 'isdeleted', 'applicationperiodtypeid', 'iscomplete', 'catalog_approved', 'programmes_added', 'cape_subjects_added'], 'integer'],
            [['onsitestartdate', 'onsiteenddate', 'offsitestartdate', 'offsiteenddate'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['divisionid'], 'exist', 'skipOnError' => true, 'targetClass' => Division::class, 'targetAttribute' => ['divisionid' => 'divisionid']],
            [['personid'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['personid' => 'personid']],
            [['academicyearid'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicYear::class, 'targetAttribute' => ['academicyearid' => 'academicyearid']],
            [['applicationperiodtypeid'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationPeriodType::class, 'targetAttribute' => ['applicationperiodtypeid' => 'applicationperiodtypeid']],
            [['applicationperiodstatusid'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationperiodStatus::class, 'targetAttribute' => ['applicationperiodstatusid' => 'applicationperiodstatusid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'applicationperiodid' => 'Applicationperiodid',
            'applicationperiodstatusid' => 'Applicationperiodstatusid',
            'divisionid' => 'Divisionid',
            'personid' => 'Personid',
            'academicyearid' => 'Academicyearid',
            'name' => 'Name',
            'onsitestartdate' => 'Onsitestartdate',
            'onsiteenddate' => 'Onsiteenddate',
            'offsitestartdate' => 'Offsitestartdate',
            'offsiteenddate' => 'Offsiteenddate',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'applicationperiodtypeid' => 'Applicationperiodtypeid',
            'iscomplete' => 'Iscomplete',
            'catalog_approved' => 'Catalog Approved',
            'programmes_added' => 'Programmes Added',
            'cape_subjects_added' => 'Cape Subjects Added',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicOfferings()
    {
        return $this->hasMany(AcademicOffering::class, ['applicationperiodid' => 'applicationperiodid']);
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
    public function getPerson()
    {
        return $this->hasOne(User::class, ['personid' => 'personid']);
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
    public function getApplicationperiodtype()
    {
        return $this->hasOne(ApplicationPeriodType::class, ['applicationperiodtypeid' => 'applicationperiodtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationperiodstatus()
    {
        return $this->hasOne(ApplicationperiodStatus::class, ['applicationperiodstatusid' => 'applicationperiodstatusid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackages()
    {
        return $this->hasMany(Package::class, ['applicationperiodid' => 'applicationperiodid']);
    }
}
