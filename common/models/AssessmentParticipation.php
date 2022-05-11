<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "assessment_participation".
 *
 * @property integer $assessmentparticipationid
 * @property string $name
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Assessment[] $assessments
 * @property AssessmentCape[] $assessmentCapes
 */
class AssessmentParticipation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assessment_participation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assessmentparticipationid' => 'Assessmentparticipationid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessments()
    {
        return $this->hasMany(Assessment::class, ['assessmentparticipationid' => 'assessmentparticipationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentCapes()
    {
        return $this->hasMany(AssessmentCape::class, ['assessmentparticipationid' => 'assessmentparticipationid']);
    }
}
