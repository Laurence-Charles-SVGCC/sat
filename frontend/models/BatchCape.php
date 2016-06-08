<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "batch_cape".
 *
 * @property string $batchcapeid
 * @property string $capecourseid
 * @property string $name
 * @property integer $assessmentcount
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $mdlgroupid
 *
 * @property AssessmentCape[] $assessmentCapes
 * @property CapeCourse $capecourse
 * @property BatchStudentCape[] $batchStudentCapes
 * @property StudentRegistration[] $studentregistrations
 * @property EmployeeBatchCape[] $employeeBatchCapes
 * @property Person[] $people
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
            [['capecourseid', 'assessmentcount', 'isactive', 'isdeleted', 'mdlgroupid'], 'integer'],
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
            'mdlgroupid' => 'Mdlgroupid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentCapes()
    {
        return $this->hasMany(AssessmentCape::className(), ['batchcapeid' => 'batchcapeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapecourse()
    {
        return $this->hasOne(CapeCourse::className(), ['capecourseid' => 'capecourseid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchStudentCapes()
    {
        return $this->hasMany(BatchStudentCape::className(), ['batchcapeid' => 'batchcapeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistrations()
    {
        return $this->hasMany(StudentRegistration::className(), ['studentregistrationid' => 'studentregistrationid'])->viaTable('batch_student_cape', ['batchcapeid' => 'batchcapeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeBatchCapes()
    {
        return $this->hasMany(EmployeeBatchCape::className(), ['batchcapeid' => 'batchcapeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasMany(Person::className(), ['personid' => 'personid'])->viaTable('employee_batch_cape', ['batchcapeid' => 'batchcapeid']);
    }
}
