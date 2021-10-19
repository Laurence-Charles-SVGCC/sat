<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "programme_catalog".
 *
 * @property int $programmecatalogid
 * @property int $programmetypeid
 * @property int $examinationbodyid
 * @property int $qualificationtypeid
 * @property int $departmentid
 * @property string $creationdate
 * @property string $specialisation
 * @property int $duration
 * @property string $name
 * @property int $credits_required
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property AcademicOffering[] $academicOfferings
 * @property Award[] $awards
 * @property GraduationProgrammeCourse[] $graduationProgrammeCourses
 * @property ExaminationBody $examinationbody
 * @property QualificationType $qualificationtype
 * @property Department $department
 * @property IntentType $programmetype
 */
class ProgrammeCatalog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'programme_catalog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['programmetypeid', 'examinationbodyid', 'qualificationtypeid', 'departmentid', 'creationdate', 'duration'], 'required'],
            [['programmetypeid', 'examinationbodyid', 'qualificationtypeid', 'departmentid', 'duration', 'credits_required', 'isactive', 'isdeleted'], 'integer'],
            [['creationdate'], 'safe'],
            [['specialisation'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 100],
            [['examinationbodyid'], 'exist', 'skipOnError' => true, 'targetClass' => ExaminationBody::class, 'targetAttribute' => ['examinationbodyid' => 'examinationbodyid']],
            [['qualificationtypeid'], 'exist', 'skipOnError' => true, 'targetClass' => QualificationType::class, 'targetAttribute' => ['qualificationtypeid' => 'qualificationtypeid']],
            [['departmentid'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['departmentid' => 'departmentid']],
            [['programmetypeid'], 'exist', 'skipOnError' => true, 'targetClass' => IntentType::class, 'targetAttribute' => ['programmetypeid' => 'intenttypeid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'programmecatalogid' => 'Programmecatalogid',
            'programmetypeid' => 'Programmetypeid',
            'examinationbodyid' => 'Examinationbodyid',
            'qualificationtypeid' => 'Qualificationtypeid',
            'departmentid' => 'Departmentid',
            'creationdate' => 'Creationdate',
            'specialisation' => 'Specialisation',
            'duration' => 'Duration',
            'name' => 'Name',
            'credits_required' => 'Credits Required',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicOfferings()
    {
        return $this->hasMany(AcademicOffering::class, ['programmecatalogid' => 'programmecatalogid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAwards()
    {
        return $this->hasMany(Award::class, ['programmecatalogid' => 'programmecatalogid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGraduationProgrammeCourses()
    {
        return $this->hasMany(GraduationProgrammeCourse::class, ['programmecatalogid' => 'programmecatalogid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExaminationbody()
    {
        return $this->hasOne(ExaminationBody::class, ['examinationbodyid' => 'examinationbodyid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQualificationtype()
    {
        return $this->hasOne(QualificationType::class, ['qualificationtypeid' => 'qualificationtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['departmentid' => 'departmentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammetype()
    {
        return $this->hasOne(IntentType::class, ['intenttypeid' => 'programmetypeid']);
    }
}
