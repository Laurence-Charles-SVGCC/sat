<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cape_subject".
 *
 * @property int $capesubjectid
 * @property int $cordinatorid
 * @property int $academicofferingid
 * @property string $subjectname
 * @property int $unitcount
 * @property int $capacity
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property ApplicationCapesubject[] $applicationCapesubjects
 * @property User $cordinator
 * @property AcademicOffering $academicoffering
 * @property CapeSubjectGroup[] $capeSubjectGroups
 * @property CapeGroup[] $capegroups
 * @property CapeUnit[] $capeUnits
 * @property Cordinator[] $cordinators
 */
class CapeSubject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cape_subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cordinatorid', 'academicofferingid', 'unitcount', 'capacity', 'isactive', 'isdeleted'], 'integer'],
            [['academicofferingid', 'subjectname'], 'required'],
            [['subjectname'], 'string', 'max' => 100],
            [['cordinatorid'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['cordinatorid' => 'personid']],
            [['academicofferingid'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicOffering::class, 'targetAttribute' => ['academicofferingid' => 'academicofferingid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'capesubjectid' => 'Capesubjectid',
            'cordinatorid' => 'Cordinatorid',
            'academicofferingid' => 'Academicofferingid',
            'subjectname' => 'Subjectname',
            'unitcount' => 'Unitcount',
            'capacity' => 'Capacity',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationCapesubjects()
    {
        return $this->hasMany(ApplicationCapesubject::class, ['capesubjectid' => 'capesubjectid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCordinator()
    {
        return $this->hasOne(User::class, ['personid' => 'cordinatorid']);
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
    public function getCapeSubjectGroups()
    {
        return $this->hasMany(CapeSubjectGroup::class, ['capesubjectid' => 'capesubjectid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapegroups()
    {
        return $this->hasMany(CapeGroup::class, ['capegroupid' => 'capegroupid'])->viaTable('cape_subject_group', ['capesubjectid' => 'capesubjectid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapeUnits()
    {
        return $this->hasMany(CapeUnit::class, ['capesubjectid' => 'capesubjectid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCordinators()
    {
        return $this->hasMany(Cordinator::class, ['capesubjectid' => 'capesubjectid']);
    }
}
