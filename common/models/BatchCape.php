<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "batch_cape".
 *
 * @property integer $batchcapeid
 * @property integer $capecourseid
 * @property string $name
 * @property integer $assessmentcount
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $opentostudent
 * @property integer $mdlgroupid
 *
 * @property AssessmentCape[] $assessmentCapes
 * @property CapeCourse $capecourse
 * @property BatchStudentCape[] $batchStudentCapes
 * @property StudentRegistration[] $studentregistrations
 * @property EmployeeBatchCape[] $employeeBatchCapes
 * @property User[] $people
 */
class BatchCape extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'batch_cape';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['capecourseid', 'name'], 'required'],
            [['capecourseid', 'assessmentcount', 'isactive', 'isdeleted', 'opentostudent', 'mdlgroupid'], 'integer'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'batchcapeid' => 'Batchcapeid',
            'capecourseid' => 'Capecourseid',
            'name' => 'Name',
            'assessmentcount' => 'Assessmentcount',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'opentostudent' => 'Opentostudent',
            'mdlgroupid' => 'Mdlgroupid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentCapes()
    {
        return $this->hasMany(AssessmentCape::class, ['batchcapeid' => 'batchcapeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapecourse()
    {
        return $this->hasOne(CapeCourse::class, ['capecourseid' => 'capecourseid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchStudentCapes()
    {
        return $this->hasMany(BatchStudentCape::class, ['batchcapeid' => 'batchcapeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistrations()
    {
        return $this->hasMany(StudentRegistration::class, ['studentregistrationid' => 'studentregistrationid'])->viaTable('batch_student_cape', ['batchcapeid' => 'batchcapeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeBatchCapes()
    {
        return $this->hasMany(EmployeeBatchCape::class, ['batchcapeid' => 'batchcapeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasMany(User::class, ['personid' => 'personid'])->viaTable('employee_batch_cape', ['batchcapeid' => 'batchcapeid']);
    }
}
