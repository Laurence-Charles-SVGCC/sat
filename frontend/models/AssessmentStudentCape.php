<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "assessment_student_cape".
 *
 * @property integer $studentregistrationid
 * @property integer $assessmentcapeid
 * @property string $marksattained
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property StudentRegistration $studentregistration
 * @property AssessmentCape $assessmentcape
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
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::className(), ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentcape()
    {
        return $this->hasOne(AssessmentCape::className(), ['assessmentcapeid' => 'assessmentcapeid']);
    }
}
