<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cordinator".
 *
 * @property integer $cordinatorid
 * @property integer $cordinatortypeid
 * @property integer $personid
 * @property integer $academicyearid
 * @property integer $departmentid
 * @property integer $academicofferingid
 * @property integer $courseofferingid
 * @property integer $capesubjectid
 * @property string $dateassigned
 * @property integer $assignedby
 * @property string $daterevoked
 * @property integer $revokedby
 * @property integer $isserving
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property CordinatorType $cordinatortype
 * @property User $person
 * @property Department $department
 * @property AcademicOffering $academicoffering
 * @property CourseOffering $courseoffering
 * @property CapeSubject $capesubject
 * @property User $assignedby0
 * @property User $revokedby0
 * @property AcademicYear $academicyear
 */
class Cordinator extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cordinator';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cordinatortypeid', 'personid', 'academicyearid', 'dateassigned', 'assignedby'], 'required'],
            [['cordinatortypeid', 'personid', 'academicyearid', 'departmentid', 'academicofferingid', 'courseofferingid', 'capesubjectid', 'assignedby', 'revokedby', 'isserving', 'isactive', 'isdeleted'], 'integer'],
            [['dateassigned', 'daterevoked'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cordinatorid' => 'Cordinatorid',
            'cordinatortypeid' => 'Cordinatortypeid',
            'personid' => 'Personid',
            'academicyearid' => 'Academicyearid',
            'departmentid' => 'Departmentid',
            'academicofferingid' => 'Academicofferingid',
            'courseofferingid' => 'Courseofferingid',
            'capesubjectid' => 'Capesubjectid',
            'dateassigned' => 'Dateassigned',
            'assignedby' => 'Assignedby',
            'daterevoked' => 'Daterevoked',
            'revokedby' => 'Revokedby',
            'isserving' => 'Isserving',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCordinatortype()
    {
        return $this->hasOne(CordinatorType::class, ['cordinatortypeid' => 'cordinatortypeid']);
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
    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['departmentid' => 'departmentid']);
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
    public function getCourseoffering()
    {
        return $this->hasOne(CourseOffering::class, ['courseofferingid' => 'courseofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapesubject()
    {
        return $this->hasOne(CapeSubject::class, ['capesubjectid' => 'capesubjectid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedby0()
    {
        return $this->hasOne(User::class, ['personid' => 'assignedby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevokedby0()
    {
        return $this->hasOne(User::class, ['personid' => 'revokedby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicyear()
    {
        return $this->hasOne(AcademicYear::class, ['academicyearid' => 'academicyearid']);
    }
}
