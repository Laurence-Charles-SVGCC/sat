<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "batch".
 *
 * @property integer $batchid
 * @property integer $batchtypeid
 * @property integer $courseofferingid
 * @property string $name
 * @property integer $assessmentcount
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $opentostudent
 *
 * @property Assessment[] $assessments
 * @property BatchType $batchtype
 * @property CourseOffering $courseoffering
 * @property BatchStudents[] $batchStudents
 * @property StudentRegistration[] $studentregistrations
 * @property EmployeeBatch[] $employeeBatches
 * @property User[] $people
 */
class Batch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'batch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batchtypeid', 'courseofferingid', 'name', 'assessmentcount'], 'required'],
            [['batchtypeid', 'courseofferingid', 'assessmentcount', 'isactive', 'isdeleted', 'opentostudent'], 'integer'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'batchid' => 'Batchid',
            'batchtypeid' => 'Batchtypeid',
            'courseofferingid' => 'Courseofferingid',
            'name' => 'Name',
            'assessmentcount' => 'Assessmentcount',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'opentostudent' => 'Opentostudent',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessments()
    {
        return $this->hasMany(Assessment::class, ['batchid' => 'batchid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchtype()
    {
        return $this->hasOne(BatchType::class, ['batchtypeid' => 'batchtypeid']);
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
    public function getBatchStudents()
    {
        return $this->hasMany(BatchStudents::class, ['batchid' => 'batchid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistrations()
    {
        return $this->hasMany(StudentRegistration::class, ['studentregistrationid' => 'studentregistrationid'])->viaTable('batch_students', ['batchid' => 'batchid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeBatches()
    {
        return $this->hasMany(EmployeeBatch::class, ['batchid' => 'batchid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasMany(User::class, ['personid' => 'personid'])->viaTable('employee_batch', ['batchid' => 'batchid']);
    }
}
