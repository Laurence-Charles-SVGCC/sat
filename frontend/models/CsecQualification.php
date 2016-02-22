<?php

namespace frontend\models;

use frontend\models\ExaminationBody;
use frontend\models\Subject;
use frontend\models\ExaminationGrade;


use Yii;

/**
 * This is the model class for table "csec_qualification".
 *
 * @property string $csecqualificationid
 * @property string $cseccentreid
 * @property string $candidatenumber
 * @property string $personid
 * @property string $examinationbodyid
 * @property string $subjectid
 * @property string $examinationproficiencytypeid
 * @property string $year
 * @property string $examinationgradeid
  * @property integer $isverified
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $isqueried
 *
 * @property CsecCentre $cseccentre
 * @property Person $person
 * @property ExaminationBody $examinationbody
 * @property Subject $subject
 * @property ExaminationProficiencyType $examinationproficiencytype
 * @property ExaminationGrade $examinationgrade
 */
class CsecQualification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'csec_qualification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cseccentreid', 'personid', 'examinationbodyid', 'subjectid', 'examinationproficiencytypeid'], 'required'],
            [['cseccentreid', 'personid', 'examinationbodyid', 'subjectid', 'examinationproficiencytypeid', 'examinationgradeid'], 'integer'],
            [['year'], 'safe'],
            [['isverified', 'isactive', 'isdeleted', 'isqueried'], 'boolean'],
            [['candidatenumber'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'csecqualificationid' => 'Csecqualificationid',
            'cseccentreid' => 'Cseccentreid',
            'candidatenumber' => 'Candidatenumber',
            'personid' => 'Personid',
            'examinationbodyid' => 'Examinationbodyid',
            'subjectid' => 'Subjectid',
            'examinationproficiencytypeid' => 'Examinationproficiencytypeid',
            'year' => 'Year',
            'examinationgradeid' => 'Examinationgradeid',
            'isverified' => 'Isverified',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'isqueried' => 'Isqueried',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCseccentre()
    {
        return $this->hasOne(CsecCentre::className(), ['cseccentreid' => 'cseccentreid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExaminationbody()
    {
        return $this->hasOne(ExaminationBody::className(), ['examinationbodyid' => 'examinationbodyid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subject::className(), ['subjectid' => 'subjectid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExaminationproficiencytype()
    {
        return $this->hasOne(ExaminationProficiencyType::className(), ['examinationproficiencytypeid' => 'examinationproficiencytypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExaminationgrade()
    {
        return $this->hasOne(ExaminationGrade::className(), ['examinationgradeid' => 'examinationgradeid']);
    }
    
    
    /**
     * Retrieves all the csec qualification records, associated with a personid
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 23/12/2015
     * Date Last Modified: 23/12/2015
     */
    public static function getQualifications($id)
    {
        $records = CsecQualification::find()
                 ->where(['personid'=> $id, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();
        
        if(count($records) > 0)
            return $records;
        return false;
    }
    
    
    /*
    * Purpose: Gets all csec_subjects an applicants has passed
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton  | 19/02/2016 Laurence Charles
    */
    public function getSubjectsPassedCount($applicantid)
    {
        return CsecQualification::find()
                    ->innerJoin('examination_grade', '`examination_grade`.`examinationgradeid` = `csec_qualification`.`examinationgradeid`')
                    ->where(['csec_qualification.personid' => $applicantid, 'csec_qualification.isverified' => 1, 'csec_qualification.isdeleted' => 0,
                            'examination_grade.ordering' => [1, 2, 3]
                            ])
                    ->count();
    }
    
    
    /*
    * Purpose: Gets counts of all csec_subjects an applicants has of a particular grade 
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton | 19/02/2016 Laurence Charles
    */
    public function getSubjectGradesCount($applicantid, $grade)
    {
        return CsecQualification::find()
                    ->innerJoin('examination_grade', '`examination_grade`.`examinationgradeid` = `csec_qualification`.`examinationgradeid`')
                    ->where(['csec_qualification.personid' => $applicantid, 'csec_qualification.isverified' => 1, 'csec_qualification.isdeleted' => 0,
                            'examination_grade.ordering' => $grade,
                            ])
                    ->count();
    }
    
    
    /*
    * Purpose: Determins if student passed CSEC Math 
    * Created: 4/08/2015 by Gamal Crichton
    * Last Modified: 4/08/2015 by Gamal Crichton
    */
    private function hasEnglish($certificates)
    {
        $exam_body = ExaminationBody::findOne(['abbreviation' => 'CSEC', 'isdeleted' => 0]);
        if ($exam_body)
        {
            $english = Subject::findOne(['name' => 'english language', 'examinationbodyid' => $exam_body->examinationbodyid, 'isdeleted' => 0]);
            if ($english)
            {
                foreach($certificates as $cert)
                {
                    if ($cert->subjectid == $english->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                        {
                                return True;
                        }
                    }
                }
            }
        }
        return False;
    }
    
    /*
    * Purpose: Determins if student passed CSEC Math 
    * Created: 4/08/2015 by Gamal Crichton
    * Last Modified: 4/08/2015 by Gamal Crichton
    */
    private function hasMath($certificates)
    {
        $exam_body = ExaminationBody::findOne(['abbreviation' => 'CSEC', 'isdeleted' => 0]);
        if ($exam_body)
        {
            $math = Subject::findOne(['name' => 'mathematics', 'examinationbodyid' => $exam_body->examinationbodyid, 'isdeleted' => 0]);
            if ($math)
            {
                foreach($certificates as $cert)
                {                 
                    if ($cert->subjectid == $math->subjectid && $cert)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                        {
                            return True;
                        }
                    }
                }
            }
        }
        return False;
    }
}
