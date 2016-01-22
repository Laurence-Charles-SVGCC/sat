<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "assessment_student".
 *
 * @property integer $studentregistrationid
 * @property integer $assessmentid
 * @property string $marksattained
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property StudentRegistration $studentregistration
 * @property Assessment $assessment
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
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::className(), ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessment()
    {
        return $this->hasOne(Assessment::className(), ['assessmentid' => 'assessmentid']);
    }
}
