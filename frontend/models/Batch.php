<?php

namespace frontend\models;

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
 * @property integer $mdlgroupid
 *
 * @property Assessment[] $assessments
 * @property BatchType $batchtype
 * @property CourseOffering $courseoffering
 * @property BatchStudents[] $batchStudents
 * @property StudentRegistration[] $studentregistrations
 * @property EmployeeBatch[] $employeeBatches
 * @property Person[] $employees
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
            [['batchtypeid', 'courseofferingid', 'name'], 'required'],
            [['batchtypeid', 'courseofferingid', 'assessmentcount', 'isactive', 'isdeleted', 'mdlgroupid'], 'integer'],
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
            'mdlgroupid' => 'Mdlgroupid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessments()
    {
        return $this->hasMany(Assessment::className(), ['batchid' => 'batchid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchtype()
    {
        return $this->hasOne(BatchType::className(), ['batchtypeid' => 'batchtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseoffering()
    {
        return $this->hasOne(CourseOffering::className(), ['courseofferingid' => 'courseofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchStudents()
    {
        return $this->hasMany(BatchStudents::className(), ['batchid' => 'batchid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistrations()
    {
        return $this->hasMany(StudentRegistration::className(), ['studentregistrationid' => 'studentregistrationid'])->viaTable('batch_students', ['batchid' => 'batchid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeBatches()
    {
        return $this->hasMany(EmployeeBatch::className(), ['batchid' => 'batchid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Person::className(), ['personid' => 'employeeid'])->viaTable('employee_batch', ['batchid' => 'batchid']);
    }
    
    
    /**
     * Returns an associative array containing coure details
     * 
     * @param type $batchid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 13/01/2016
     * Date Last Modified: 13/01/2016
     */
    public static function getCourseDetails($batchid)
    {
        $db = Yii::$app->db;
        $course_record = $db->createCommand(
                 "SELECT course_catalog.coursecode AS 'code',"
                . " course_catalog.name AS 'name',"
                . " batch.batchid AS 'batchid',"
                . " course_type.name AS 'type',"
                . " pass_criteria.name AS 'pass_criteria',"
                . " pass_fail_type.description AS 'pass_fail_type',"
                . " course_offering.credits AS 'credits',"
                . " course_offering.courseworkweight AS 'courseworkweight',"
                . " course_offering.examweight AS 'examweight',"
                . " course_offering.passmark AS 'passmark'"
                . " FROM batch"
                . " JOIN course_offering"
                . " ON batch.courseofferingid = course_offering.courseofferingid"
                . " JOIN course_catalog"
                . " ON course_offering.coursecatalogid = course_catalog.coursecatalogid"
                . " JOIN course_type"
                . " ON course_offering.coursetypeid = course_type.coursetypeid"
                . " JOIN pass_criteria"
                . " ON course_offering.passcriteriaid = pass_criteria.passcriteriaid"
                . " JOIN pass_fail_type"
                . " ON course_offering.passfailtypeid = pass_fail_type.passfailtypeid"
                )
                ->queryOne();
        if ($course_record)
            return $course_record;
        return false;
    }
}
