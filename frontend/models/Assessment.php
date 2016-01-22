<?php

namespace frontend\models;

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
 * @property Person $lecturer
 * @property AssessmentStudent[] $assessmentStudents
 * @property StudentRegistration[] $studentregistrations
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
        return $this->hasOne(Batch::className(), ['batchid' => 'batchid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmenttype()
    {
        return $this->hasOne(AssessmentType::className(), ['assessmenttypeid' => 'assessmenttypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentparticipation()
    {
        return $this->hasOne(AssessmentParticipation::className(), ['assessmentparticipationid' => 'assessmentparticipationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLecturer()
    {
        return $this->hasOne(Person::className(), ['personid' => 'lecturerid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentStudents()
    {
        return $this->hasMany(AssessmentStudent::className(), ['assessmentid' => 'assessmentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistrations()
    {
        return $this->hasMany(StudentRegistration::className(), ['studentregistrationid' => 'studentregistrationid'])->viaTable('assessment_student', ['assessmentid' => 'assessmentid']);
    }
    
    
    /**
     * Returns an array of assessment detail records.
     * 
     * @param type $batchid
     * @param type $studentregistrationid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 12/12/2015
     * Date Last Modified: 12/12/2015
     */
    public static function getAssessmentReport($batchid, $studentregistrationid)
    {
        $db = Yii::$app->db;
        $assessment_records = $db->createCommand(
                "SELECT assessment_student.assessmentid AS 'assessmentid',"
                . " assessment_student.studentregistrationid AS 'registrationid',"
                . " assessment.description AS 'name',"
                . " assessment_category.name AS 'category', "
                . " assessment_type.name AS 'type',"
                . " assessment_participation.name AS 'participation',"
                . " CONCAT(employee.title, '. ' , employee.firstname , ' ' , employee.lastname) AS 'lecturer',"
                . " assessment.weight AS 'weight',"
                . " assessment.dateadministered AS 'date',"
                . " assessment_student.marksattained AS 'marks_attained',"
                . " assessment.totalmarks AS 'total_marks',"
                . " assessment.questionsfilelocation AS 'questions',"
                . " assessment.markschemelocation AS 'markscheme'"
                . " FROM assessment"
                . " JOIN assessment_type"
                . " ON assessment.assessmenttypeid = assessment_type.assessmenttypeid"
                . " JOIN assessment_category"
                . " ON assessment_type.assessmentcategoryid = assessment_category.assessmentcategoryid"
                . " JOIN assessment_participation"
                . " ON assessment.assessmentparticipationid = assessment_participation.assessmentparticipationid"
                . " JOIN assessment_student"
                . " ON assessment.assessmentid = assessment_student.assessmentid"
                . " JOIN employee"
                . " ON assessment.lecturerid = employee.personid"
                . " WHERE assessment_student.studentregistrationid = " . $studentregistrationid
                . " AND assessment.batchid = " . $batchid
                . ";"
            )
            ->queryAll();
        if (count($assessment_records) > 0)
            return $assessment_records;
        return false;  
    }
    
    
    /**
     * Returns an assessment detail record.
     * 
     * @param type $assessmentid
     * @param type $studentregistrationid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 15/12/2015
     * Date Last Modified: 15/12/2015
     */
    public static function getAssessmentDetails($assessmentid, $studentregistrationid)
    {
        $db = Yii::$app->db;
        $assessment = $db->createCommand(
                "SELECT assessment_student.studentregistrationid AS 'studentregistrationid',"
                . " assessment.batchid AS 'batchid',"
                . " assessment_student.assessmentid AS 'assessmentid',"
                . " assessment.description AS 'name',"
                . " assessment_category.name AS 'category', "
                . " assessment_type.name AS 'type',"
                . " assessment_participation.name AS 'participation',"
                . " CONCAT(employee.title, '. ' , employee.firstname , ' ' , employee.lastname) AS 'lecturer',"
                . " assessment.weight AS 'weight',"
                . " assessment.dateadministered AS 'date',"
                . " assessment_student.marksattained AS 'marks_attained',"
                . " assessment.totalmarks AS 'total_marks',"
                . " assessment.questionsfilelocation AS 'questions',"
                . " assessment.markschemelocation AS 'markscheme'"
                . " FROM assessment"
                . " JOIN assessment_type"
                . " ON assessment.assessmenttypeid = assessment_type.assessmenttypeid"
                . " JOIN assessment_category"
                . " ON assessment_type.assessmentcategoryid = assessment_category.assessmentcategoryid"
                . " JOIN assessment_participation"
                . " ON assessment.assessmentparticipationid = assessment_participation.assessmentparticipationid"
                . " JOIN assessment_student"
                . " ON assessment.assessmentid = assessment_student.assessmentid"
                . " JOIN employee"
                . " ON assessment.lecturerid = employee.personid"
                . " WHERE assessment_student.studentregistrationid = " . $studentregistrationid
                . " AND assessment.batchid = " . $assessmentid
                . ";"
            )
            ->queryOne();
        if ($assessment)
            return $assessment;
        return false; 
    }
}
