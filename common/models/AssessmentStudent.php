<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "assessment_student".
 *
 * @property integer $assessment_studentregistration_id
 * @property integer $studentregistrationid
 * @property integer $assessmentid
 * @property string $marksattained
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Assessment $assessment
 * @property StudentRegistration $studentregistration
 */
class AssessmentStudent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assessment_student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentregistrationid', 'assessmentid'], 'required'],
            [['studentregistrationid', 'assessmentid', 'isactive', 'isdeleted'], 'integer'],
            [['marksattained'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assessment_studentregistration_id' => 'Assessment Studentregistration ID',
            'studentregistrationid' => 'Studentregistrationid',
            'assessmentid' => 'Assessmentid',
            'marksattained' => 'Marksattained',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessment()
    {
        return $this->hasOne(Assessment::class, ['assessmentid' => 'assessmentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::class, ['studentregistrationid' => 'studentregistrationid']);
    }
}
