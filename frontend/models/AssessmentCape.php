<?php

namespace frontend\models;

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
 * @property AssessmentParticipation $assessmentparticipation
 * @property AssessmentType $assessmenttype
 * @property Person $lecturer
 * @property BatchCape $batchcape
 * @property AssessmentStudentCape[] $assessmentStudentCapes
 * @property StudentRegistration[] $studentregistrations
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
    public function getAssessmentparticipation()
    {
        return $this->hasOne(AssessmentParticipation::className(), ['assessmentparticipationid' => 'assessmentparticipationid']);
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
    public function getLecturer()
    {
        return $this->hasOne(Person::className(), ['personid' => 'lecturerid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchcape()
    {
        return $this->hasOne(BatchCape::className(), ['batchcapeid' => 'batchcapeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentStudentCapes()
    {
        return $this->hasMany(AssessmentStudentCape::className(), ['assessmentcapeid' => 'assessmentcapeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistrations()
    {
        return $this->hasMany(StudentRegistration::className(), ['studentregistrationid' => 'studentregistrationid'])->viaTable('assessment_student_cape', ['assessmentcapeid' => 'assessmentcapeid']);
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
                        "SELECT assessment_student_cape.assessmentcapeid AS 'assessmentid',"
                        . " assessment_student_cape.studentregistrationid AS 'registrationid',"
                        . " assessment_cape.description AS 'name',"
                        . " assessment_category.name AS 'category', "
                        . " assessment_type.name AS 'type',"
                        . " assessment_participation.name AS 'participation',"
                        . " CONCAT(employee.title, '. ' , employee.firstname , ' ' , employee.lastname) AS 'lecturer',"
                        . " assessment_cape.weight AS 'weight', assessment_cape.dateadministered AS 'date',"
                        . " assessment_student_cape.marksattained AS 'marks_attained',"
                        . " assessment_cape.totalmarks AS 'total_marks',"
                        . " assessment_cape.questionsfilelocation AS 'questions',"
                        . " assessment_cape.markschemelocation AS 'markscheme'"
                        . " FROM assessment_cape"
                        . " JOIN assessment_type"
                        . " ON assessment_cape.assessmenttypeid = assessment_type.assessmenttypeid"
                        . " JOIN assessment_category"
                        . " ON assessment_type.assessmentcategoryid = assessment_category.assessmentcategoryid"
                        . " JOIN assessment_participation"
                        . " ON assessment_cape.assessmentparticipationid = assessment_participation.assessmentparticipationid"
                        . " JOIN assessment_student_cape"
                        . " ON assessment_cape.assessmentcapeid = assessment_student_cape.assessmentcapeid"
                        . " JOIN employee"
                        . " ON assessment_cape.lecturerid = employee.personid"
                        . " WHERE assessment_student_cape.studentregistrationid = " . $studentregistrationid
                        . " AND assessment_cape.batchcapeid = " . $batchid
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
                        "SELECT assessment_student_cape.studentregistrationid AS 'studentregistrationid',"
                        . " assessment_cape.batchcapeid AS 'batchid',"
                        . " assessment_cape.assessmentcapeid AS 'assessmentid',"
                        . " assessment_cape.description AS 'name',"
                        . " assessment_category.name AS 'category', "
                        . " assessment_type.name AS 'type',"
                        . " assessment_participation.name AS 'participation',"
                        . " CONCAT(employee.title, '. ' , employee.firstname , ' ' , employee.lastname) AS 'lecturer',"
                        . " assessment_cape.weight AS 'weight', assessment_cape.dateadministered AS 'date',"
                        . " assessment_student_cape.marksattained AS 'marks_attained',"
                        . " assessment_cape.totalmarks AS 'total_marks',"
                        . " assessment_cape.questionsfilelocation AS 'questions',"
                        . " assessment_cape.markschemelocation AS 'markscheme'"
                        . " FROM assessment_cape"
                        . " JOIN assessment_type"
                        . " ON assessment_cape.assessmenttypeid = assessment_type.assessmenttypeid"
                        . " JOIN assessment_category"
                        . " ON assessment_type.assessmentcategoryid = assessment_category.assessmentcategoryid"
                        . " JOIN assessment_participation"
                        . " ON assessment_cape.assessmentparticipationid = assessment_participation.assessmentparticipationid"
                        . " JOIN assessment_student_cape"
                        . " ON assessment_cape.assessmentcapeid = assessment_student_cape.assessmentcapeid"
                        . " JOIN employee"
                        . " ON assessment_cape.lecturerid = employee.personid"
                        . " WHERE assessment_student_cape.studentregistrationid = " . $studentregistrationid
                        . " AND assessment_cape.assessmentcapeid = " . $assessmentid
                        . ";"
                    )
                    ->queryOne();
        if ($assessment)
            return $assessment;
        return false; 
    }
}
