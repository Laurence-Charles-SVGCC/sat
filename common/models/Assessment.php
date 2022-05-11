<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "assessment".
 *
 * @property integer $assessmentid
 * @property integer $batchid
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
 * @property Batch $batch
 * @property AssessmentType $assessmenttype
 * @property AssessmentParticipation $assessmentparticipation
 * @property User $lecturer
 * @property AssessmentStudent[] $assessmentStudents
 */
class Assessment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assessment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batchid', 'assessmenttypeid', 'assessmentparticipationid', 'lecturerid', 'description', 'totalmarks', 'weight', 'dateadministered'], 'required'],
            [['batchid', 'assessmenttypeid', 'assessmentparticipationid', 'lecturerid', 'totalmarks', 'gradeentrycompleted', 'gradepublished', 'isactive', 'isdeleted'], 'integer'],
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
            'assessmentid' => 'Assessmentid',
            'batchid' => 'Batchid',
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
    public function getBatch()
    {
        return $this->hasOne(Batch::class, ['batchid' => 'batchid']);
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
    public function getAssessmentparticipation()
    {
        return $this->hasOne(AssessmentParticipation::class, ['assessmentparticipationid' => 'assessmentparticipationid']);
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
    public function getAssessmentStudents()
    {
        return $this->hasMany(AssessmentStudent::class, ['assessmentid' => 'assessmentid']);
    }
}
