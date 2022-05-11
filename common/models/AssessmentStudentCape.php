<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "assessment_student_cape".
 *
 * @property integer $assessment cape_studentregistration_id
 * @property integer $studentregistrationid
 * @property integer $assessmentcapeid
 * @property string $marksattained
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property AssessmentCape $assessmentcape
 * @property StudentRegistration $studentregistration
 */
class AssessmentStudentCape extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assessment_student_cape';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentregistrationid', 'assessmentcapeid'], 'required'],
            [['studentregistrationid', 'assessmentcapeid', 'isactive', 'isdeleted'], 'integer'],
            [['marksattained'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assessment cape_studentregistration_id' => 'Assessment Cape Studentregistration ID',
            'studentregistrationid' => 'Studentregistrationid',
            'assessmentcapeid' => 'Assessmentcapeid',
            'marksattained' => 'Marksattained',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentcape()
    {
        return $this->hasOne(AssessmentCape::class, ['assessmentcapeid' => 'assessmentcapeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::class, ['studentregistrationid' => 'studentregistrationid']);
    }
}
