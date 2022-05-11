<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "assessment_cape".
 *
 * @property integer $assessmentcapeid
 * @property integer $batchcapeid
 * @property integer $assessmenttypeid
 * @property integer $assessmentparticipationid
 * @property integer $lecturerid
 * @property string $description
 * @property integer $totalmarks
 * @property string $weight
 * @property string $dateadministered
 * @property integer $gradeentrycompleted
 * @property integer $gradepublished
 * @property string $questionsfilelocation
 * @property string $markschemelocation
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property BatchCape $batchcape
 * @property AssessmentType $assessmenttype
 * @property User $lecturer
 * @property AssessmentParticipation $assessmentparticipation
 * @property AssessmentStudentCape[] $assessmentStudentCapes
 */
class AssessmentCape extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assessment_cape';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batchcapeid', 'assessmenttypeid', 'assessmentparticipationid', 'lecturerid', 'description', 'totalmarks', 'weight', 'dateadministered'], 'required'],
            [['batchcapeid', 'assessmenttypeid', 'assessmentparticipationid', 'lecturerid', 'totalmarks', 'gradeentrycompleted', 'gradepublished', 'isactive', 'isdeleted'], 'integer'],
            [['description'], 'string'],
            [['weight'], 'number'],
            [['dateadministered'], 'safe'],
            [['questionsfilelocation', 'markschemelocation'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assessmentcapeid' => 'Assessmentcapeid',
            'batchcapeid' => 'Batchcapeid',
            'assessmenttypeid' => 'Assessmenttypeid',
            'assessmentparticipationid' => 'Assessmentparticipationid',
            'lecturerid' => 'Lecturerid',
            'description' => 'Description',
            'totalmarks' => 'Totalmarks',
            'weight' => 'Weight',
            'dateadministered' => 'Dateadministered',
            'gradeentrycompleted' => 'Gradeentrycompleted',
            'gradepublished' => 'Gradepublished',
            'questionsfilelocation' => 'Questionsfilelocation',
            'markschemelocation' => 'Markschemelocation',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchcape()
    {
        return $this->hasOne(BatchCape::class, ['batchcapeid' => 'batchcapeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmenttype()
    {
        return $this->hasOne(AssessmentType::class, ['assessmenttypeid' => 'assessmenttypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLecturer()
    {
        return $this->hasOne(User::class, ['personid' => 'lecturerid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentparticipation()
    {
        return $this->hasOne(AssessmentParticipation::class, ['assessmentparticipationid' => 'assessmentparticipationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentStudentCapes()
    {
        return $this->hasMany(AssessmentStudentCape::class, ['assessmentcapeid' => 'assessmentcapeid']);
    }
}
