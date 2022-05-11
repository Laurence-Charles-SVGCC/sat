<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "batch_students".
 *
 * @property integer $studentregistrationid
 * @property integer $batchid
 * @property integer $coursestatusid
 * @property string $courseworktotal
 * @property string $examtotal
 * @property integer $final
 * @property string $grade
 * @property string $qualitypoints
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property StudentRegistration $studentregistration
 * @property CourseStatus $coursestatus
 * @property Batch $batch
 */
class BatchStudents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'batch_students';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentregistrationid', 'batchid', 'coursestatusid'], 'required'],
            [['studentregistrationid', 'batchid', 'coursestatusid', 'final', 'isactive', 'isdeleted'], 'integer'],
            [['courseworktotal', 'examtotal', 'qualitypoints'], 'number'],
            [['grade'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studentregistrationid' => 'Studentregistrationid',
            'batchid' => 'Batchid',
            'coursestatusid' => 'Coursestatusid',
            'courseworktotal' => 'Courseworktotal',
            'examtotal' => 'Examtotal',
            'final' => 'Final',
            'grade' => 'Grade',
            'qualitypoints' => 'Qualitypoints',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::class, ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoursestatus()
    {
        return $this->hasOne(CourseStatus::class, ['coursestatusid' => 'coursestatusid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatch()
    {
        return $this->hasOne(Batch::class, ['batchid' => 'batchid']);
    }
}
