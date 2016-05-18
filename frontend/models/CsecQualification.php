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
            [['cseccentreid', 'personid', 'examinationbodyid', 'subjectid', 'examinationproficiencytypeid', 'examinationgradeid', 'isverified', 'isactive', 'isdeleted', 'isqueried'], 'integer'],
            [['year'], 'safe'],
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
    public static function getSubjectsPassedCount($personid)
    {
        return CsecQualification::find()
                    ->innerJoin('examination_grade', '`examination_grade`.`examinationgradeid` = `csec_qualification`.`examinationgradeid`')
                    ->where(['csec_qualification.personid' => $personid, 'csec_qualification.isverified' => 1, 'csec_qualification.isdeleted' => 0,
                            'examination_grade.ordering' => [1, 2, 3]
                            ])
                    ->count();
    }
    
    
    /*
    * Purpose: Gets counts of all csec_subjects an applicants has of a particular grade 
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton | 19/02/2016 Laurence Charles
    */
    public static function getSubjectGradesCount($applicantid, $grade)
    {
        return CsecQualification::find()
                    ->innerJoin('examination_grade', '`examination_grade`.`examinationgradeid` = `csec_qualification`.`examinationgradeid`')
                    ->where(['csec_qualification.personid' => $applicantid, 'csec_qualification.isverified' => 1, 'csec_qualification.isdeleted' => 0,
                            'examination_grade.ordering' => $grade,
                            ])
                    ->count();
    }
    
    
    
    public static function getSecondaryGradesCount($personid, $grade)
    {
        return CsecQualification::find()
                    ->innerJoin('examination_grade', '`examination_grade`.`examinationgradeid` = `csec_qualification`.`examinationgradeid`')
                    ->where(['csec_qualification.personid' => $personid, 'csec_qualification.isverified' => 1, 'csec_qualification.isdeleted' => 0,
                             'csec_qualification.examinationbodyid' => [3,5], 'examination_grade.ordering' => $grade,
                            ])
                    ->count();
    }
    
    public static function getTertiaryGradesCount($personid, $grade)
    {
        return CsecQualification::find()
                    ->innerJoin('examination_grade', '`examination_grade`.`examinationgradeid` = `csec_qualification`.`examinationgradeid`')
                    ->where(['csec_qualification.personid' => $personid, 'csec_qualification.isverified' => 1, 'csec_qualification.isdeleted' => 0,
                             'csec_qualification.examinationbodyid' => 2, 'examination_grade.ordering' => $grade,
                            ])
                    ->count();
    }
    
    
    /*
    * Purpose: Determins if student passed CSEC Math 
    * Created: 4/08/2015 by Gamal Crichton
    * Last Modified: 4/08/2015 by Gamal Crichton
    */
    public static function hasEnglish($certificates)
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
                                return true;
                        }
                    }
                }
            }
        }
        return false;
    }
    
    /*
    * Purpose: Determins if student passed CSEC Math 
    * Created: 4/08/2015 by Gamal Crichton
    * Last Modified: 4/08/2015 by Gamal Crichton
    */
    public static function hasMath($certificates)
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
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }
    
    
    /*
    * Purpose: Gets all csec_subjects an applicants has passed
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton | Laurence Charles 23/02/2016
    */
    public static function getSubjects($personid)
    {
        return CsecQualification::find()
                    ->where(['personid' => $personid, 'isverified' => 1, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
    }
    
    
    /*
    * Purpose: Gets all csec_subjects an applicants has passed
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton
    */
    public static function getPossibleDuplicate($personid, $candidateno, $year)
    {
        try
        {
            $origcandidateno = $candidateno;
            $candidateno = intval($candidateno);
        } catch (Exception $ex) {
            return false;
        } 
        if ($candidateno == 0 || strlen($origcandidateno) != 10 )
            return false;
        $groups = CsecQualification::find()
                    ->where(['candidatenumber' => $candidateno, 'isdeleted' => 0,
                        'year' => $year])
                    ->groupBy('personid')
                    ->all();
        if (count($groups) == 1)
        {
            return false;
        }
        else
        {
            $dups = array();
            foreach ($groups as $group)
            {
                if ($group->personid != $personid)
                {
                    $dups[] = $group->personid;
                }
            }
            return $dups;
        }
    }
    
    
    /**
     * Identifies possible duplicate offer recepients based on csec_qualifications
     * 
     * @param type $personid
     * @param type $candidateno
     * @param type $year
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 09/05/2016
     * Date Last Modified: 09/05/2016
     */
    public static function getPossibleDuplicateOfferee($personid, $candidateno, $year)
    {
        try
        {
            $origcandidateno = $candidateno;
            $candidateno = intval($candidateno);
        } catch (Exception $ex) {
            return false;
        } 
        if ($candidateno == 0 || strlen($origcandidateno) != 10 )
            return false;
        $applicant = Applicant::find()->where(['personid' => $personid])->one();
        $groups = CsecQualification::find()
                ->innerJoin('application', '`application`.`personid` = `csec_qualification`.`personid`')
                ->innerJoin('applicant', '`application`.`personid` = `applicant`.`personid`')
                ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where(['csec_qualification.candidatenumber' => $candidateno, 'csec_qualification.isdeleted' => 0,'csec_qualification.year' => $year,
                        'application.isactive' => 1, 'application.isdeleted' => 0,
                        'application_period.iscomplete' => 0, 'application_period.isactive' => 1,
                        'academic_offering.isdeleted' => 0,
                        'applicant.dateofbirth' => $applicant->dateofbirth, //added check that prevent false positive matches for applicants with same candidateno and examyear but diffeenr DOBs
                        ])
                ->groupBy('csec_qualification.personid')
                ->all();
        if (count($groups) == 1)
        {
            return false;
        }
        else
        {
            $dups = array();
            foreach ($groups as $group)
            {
                if ($group->personid != $personid)
                {
                    $dups[] = $group->personid;
                }
            }
            return $dups;
        }
    }
    
    
    
    
    /*
    * Purpose: Determines if applicant has applied before
    * Created: 27/07/2015 by Gamal Crichton
    * Last Modified: 27/07/2015 by Gamal Crichton
    */
    public static function getPossibleReapplicant($candidateno, $year)
    {
        try{
            $origcandidateno = $candidateno;
            $candidateno = intval($candidateno);
        } catch (Exception $ex) {
            return False;
        } 
        if ($candidateno == 0 || strlen($origcandidateno) != 10 )
        {
            return False;
        }
        
        $cms_reapplicant = Yii::$app->cms_db->createCommand(
                "select certificate_id from applicants_certificates where year = $year and candidate_no = $candidateno")
                ->queryOne();
        
        return $reapplicant ? True : False;
    }
    
    
    /**
     * Determines if student passed CSEC English
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Gamal Crichton | Laurence Charles
     * Date Created: 03/03/2016
     * Date Last Modified: 03/03/2016
     */
    public static function hasCsecEnglish($personid)
    {
        $certificates = self::getSubjects($personid);
        
        $english = Subject::findOne(['name' => 'English Language', 'examinationbodyid' => 3, 'isactive' => 1, 'isdeleted' => 0]);
        $english2 = Subject::findOne(['name' => 'English Language', 'examinationbodyid' => 5, 'isactive' => 1, 'isdeleted' => 0]);
        
        if ($english  && $english2)
        {
            foreach($certificates as $cert)
            {
                if ($cert->subjectid == $english->subjectid)
                {
                    $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                    if (in_array($exam_grade->ordering, array(1,2,3)))
                        return true;
                }
                
                elseif ($cert->subjectid == $english2->subjectid)
                {
                    $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                    if (in_array($exam_grade->ordering, array(1,2,3)))
                        return true;
                }
            }
        }
        return false;
    }
    
    
    /**
     * Determines if student passed CSEC Math 
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Gamal Crichton | Laurence Charles
     * Date Created: 03/03/2016
     * Date Last Modified: 03/03/2016
     */
    public static function hasCsecMathematics($personid)
    {
        $certificates = self::getSubjects($personid);
        
        $math = Subject::findOne(['name' => 'Mathematics', 'examinationbodyid' => 3, 'isactive' => 1, 'isdeleted' => 0]);
        $math2 = Subject::findOne(['name' => 'Mathematics', 'examinationbodyid' => 5, 'isactive' => 1, 'isdeleted' => 0]);
        
        if ($math && $math2)
        {
            foreach($certificates as $cert)
            {                 
                if ($cert->subjectid == $math->subjectid && $cert)
                {
                    $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                    if (in_array($exam_grade->ordering, array(1,2,3)))
                    {
                        return true;
                    }
                }
                
                if ($cert->subjectid == $math2->subjectid && $cert)
                {
                    $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                    if (in_array($exam_grade->ordering, array(1,2,3)))
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    /**
     * Determines if student passed CSEC Social Studies
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Gamal Crichton | Laurence Charles
     * Date Created: 03/03/2016
     * Date Last Modified: 03/03/2016
     */
    public static function hasCsecSocialStudies($personid)
    {
        $certificates = self::getSubjects($personid);
        
        $social_studies = Subject::findOne(['name' => 'Social Studies', 'examinationbodyid' => 3, 'isdeleted' => 0]);
        if ($social_studies)
        {
            foreach($certificates as $cert)
            {                 
                if ($cert->subjectid == $social_studies->subjectid && $cert)
                {
                    $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                    if (in_array($exam_grade->ordering, array(1,2,3)))
                        return true;
                }
            }
        }
        return false;
    }
    
    
    /**
     * Determines if student passed CSEC Social Studies
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Gamal Crichton | Laurence Charles
     * Date Created: 03/03/2016
     * Date Last Modified: 03/03/2016
     */
    public static function hasCsecCaribbeanHistory($personid)
    {
        $certificates = self::getSubjects($personid);
        
        $caribbean_history = Subject::findOne(['name' => 'Caribbean History', 'examinationbodyid' => 3, 'isdeleted' => 0]);
        if ($caribbean_history)
        {
            foreach($certificates as $cert)
            {                 
                if ($cert->subjectid == $caribbean_history->subjectid && $cert)
                {
                    $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                    if (in_array($exam_grade->ordering, array(1,2,3)))
                        return true;
                }
            }
        }
        return false;
    }
    
    
    /**
     * Determines if student passed CSEC Geography
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Gamal Crichton | Laurence Charles
     * Date Created: 03/03/2016
     * Date Last Modified: 03/03/2016
     */
    public static function hasCsecGeography($personid)
    {
        $certificates = self::getSubjects($personid);
        
        $geography = Subject::findOne(['name' => 'Geography', 'examinationbodyid' => 3, 'isdeleted' => 0]);
        $geography2 = Subject::findOne(['name' => 'Geography', 'examinationbodyid' => 5, 'isdeleted' => 0]);
        
        if ($geography && $geography2)
        {
            foreach($certificates as $cert)
            {                 
                if ($cert->subjectid == $geography->subjectid && $cert)
                {
                    $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                    if (in_array($exam_grade->ordering, array(1,2,3)))
                        return true;
                }
                
                if ($cert->subjectid == $geography2->subjectid && $cert)
                {
                    $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                    if (in_array($exam_grade->ordering, array(1,2,3)))
                        return true;
                }
            }
        }
        return false;
    }
    
    
    /**
     * Determines number of csec_subjects an applicants has passed
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Gamal Crichton | Laurence Charles
     * Date Created: 03/03/2016
     * Date Last Modified: 03/03/2016
     */
    public static function hasFiveCsecPasses($personid)
    {
        $record_count = CsecQualification::find()
                    ->innerJoin('examination_grade', '`examination_grade`.`examinationgradeid` = `csec_qualification`.`examinationgradeid`')
                    ->where(['csec_qualification.personid' => $personid, 'csec_qualification.isverified' => 1, 'csec_qualification.isdeleted' => 0,
                            'examination_grade.ordering' => [1, 2, 3]
                            ])
                    ->count();
        if ($record_count >= 5)
            return true;
        return false;
    }
    
    
    /**
     * Determines if applicant satisfied DTE's Relevant Science entry requirement
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 03/03/2016
     * Date Last Modified: 03/03/2016
     */
    public static function hasDteRelevantSciences($personid)
    {
        $certificates = self::getSubjects($personid);
        
        if (count($certificates)>0)
        {
            $has_integrated_science = false;
            
            $has_biology = false;
            $has_biology2 = false;
            
            $has_chemistry = false;
            $has_chemistry2 = false;
            
            $has_physics = false;
            $has_physics2 = false;
            
            $has_agricultural_science1 = false;
            $has_agricultural_science2 = false;

            $integrated_science = Subject::findOne(['name' => 'Integrated Science', 'examinationbodyid' => 3, 'isdeleted' => 0]);
            
            $biology = Subject::findOne(['name' => 'Biology', 'examinationbodyid' => 3, 'isdeleted' => 0]);
            $biology2 = Subject::findOne(['name' => 'Biology', 'examinationbodyid' => 5, 'isdeleted' => 0]);
            
            $chemistry = Subject::findOne(['name' => 'Chemistry', 'examinationbodyid' => 3, 'isdeleted' => 0]);
            $chemistry2 = Subject::findOne(['name' => 'Chemistry', 'examinationbodyid' => 5, 'isdeleted' => 0]);
            
            $physics = Subject::findOne(['name' => 'Physics', 'examinationbodyid' => 3, 'isdeleted' => 0]);
            $physics2 = Subject::findOne(['name' => 'Physics', 'examinationbodyid' => 5, 'isdeleted' => 0]);
            
            $agricultural_science1 = Subject::findOne(['name' => 'Agricultural Science (Double Award)', 'examinationbodyid' => 3, 'isdeleted' => 0]);
            $agricultural_science2 = Subject::findOne(['name' => 'Agricultural Science (Single Award)', 'examinationbodyid' => 3, 'isdeleted' => 0]);

            if($integrated_science == true && $biology == true && $biology2 == true && $chemistry == true && $chemistry2 == true && $physics == true  && $physics2 == true && $agricultural_science1 == true && $agricultural_science2 == true)
            {
                foreach($certificates as $cert)
                {                 
                    if ($cert->subjectid == $integrated_science->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_integrated_science = true;
                    }
                    
                    if ($cert->subjectid == $biology->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_biology = true;
                    }
                    
                    if ($cert->subjectid == $biology2->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_biology2 = true;
                    }
                    
                    if ($cert->subjectid == $chemistry->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_chemistry = true;
                    }
                    
                    if ($cert->subjectid == $chemistry2->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_chemistry2 = true;
                    }
                    
                    if ($cert->subjectid == $physics->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_physics = true;
                    }
                    
                    if ($cert->subjectid == $physics2->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_physics2 = true;
                    }
                    
                    if ($cert->subjectid == $agricultural_science1->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_agricultural_science1 = true;
                    }
                    
                    if ($cert->subjectid == $agricultural_science2->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_agricultural_science2 = true;
                    }
                }
            }
            
            if($has_integrated_science == true || $has_biology == true  || $has_biology2 == true|| $has_chemistry == true || $has_chemistry2 == true || $has_physics == true || $has_physics2 == true || $has_agricultural_science1 == true || $has_agricultural_science2 == true)
                return true;
        }
        return false;
    }
    
    
    
    /**
     * Determines if applicant satisfied DNE's Relevant Science entry requirement
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 03/03/2016
     * Date Last Modified: 03/03/2016
     */
    public static function hasDneRelevantSciences($personid)
    {
        $certificates = self::getSubjects($personid);
        
        if (count($certificates)>0)
        {
            $has_integrated_science = false;
            $has_biology = false;
            $has_biology2 = false;
            $has_chemistry = false;
            $has_chemistry2 = false;
            $has_physics = false;
            $has_physics2 = false;
            $has_human_and_social_biology = false;
            $has_human_and_social_biology2 = false;

            $integrated_science = Subject::findOne(['name' => 'Integrated Science', 'examinationbodyid' => 3, 'isdeleted' => 0]);
            $biology = Subject::findOne(['name' => 'Biology', 'examinationbodyid' => 3, 'isdeleted' => 0]);
            $biology2 = Subject::findOne(['name' => 'Biology', 'examinationbodyid' => 5, 'isdeleted' => 0]);
            $chemistry = Subject::findOne(['name' => 'Chemistry', 'examinationbodyid' => 3, 'isdeleted' => 0]);
            $chemistry2 = Subject::findOne(['name' => 'Chemistry', 'examinationbodyid' => 5, 'isdeleted' => 0]);
            $physics = Subject::findOne(['name' => 'Physics', 'examinationbodyid' => 3, 'isdeleted' => 0]);
            $physics2 = Subject::findOne(['name' => 'Physics', 'examinationbodyid' => 5, 'isdeleted' => 0]);
            $human_and_social_biology = Subject::findOne(['name' => 'Human & Social Biology', 'examinationbodyid' => 3, 'isdeleted' => 0]);
            $human_and_social_biology2 = Subject::findOne(['name' => 'Human and Social Biology', 'examinationbodyid' => 5, 'isdeleted' => 0]);

            if($integrated_science == true && $biology == true && $biology2 == true && $chemistry == true  && $chemistry2 == true && $physics == true  && $physics2 == true && $human_and_social_biology == true  && $human_and_social_biology2 == true)
            {
                foreach($certificates as $cert)
                {                 
                    if ($cert->subjectid == $integrated_science->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_integrated_science = true;
                    }
                    
                    if ($cert->subjectid == $biology->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_biology = true;
                    }
                    
                    if ($cert->subjectid == $biology2->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_biology2 = true;
                    }
                    
                    if ($cert->subjectid == $chemistry->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_chemistry = true;
                    }
                    
                    if ($cert->subjectid == $chemistry2->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_chemistry2 = true;
                    }
                    
                    if ($cert->subjectid == $physics2->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_physics2 = true;
                    }
                    
                    if ($cert->subjectid == $human_and_social_biology->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_human_and_social_biology = true;
                    }
                    
                    if ($cert->subjectid == $human_and_social_biology2->subjectid)
                    {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3)))
                            $has_human_and_social_biology2 = true;
                    }
                }
            }
            
            if($has_integrated_science == true || $has_biology == true  || $has_biology2 == true || $has_chemistry == true ||  $has_chemistry2 == true || $has_physics == true || $has_physics2 == true || $has_human_and_social_biology == true || $has_human_and_social_biology2 == true)
                return true;
        }
        return false;
    }
    
    
    /**
     * Determines if record is eligible for saving
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 03/11/2015
     * Date Last Modified: 19/03/2016
     */
    public function isValid()
    {
        //Determines invalide examinationbodies
        $bodies = ExaminationBody::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
        foreach ($bodies as $key => $body) 
        {
            $subs = Subject::find()
                    ->where(['examinationbodyid' => $body->examinationbodyid , 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
            $profs = ExaminationProficiencyType::find()
                    ->where(['examinationbodyid' => $body->examinationbodyid , 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
            $grds = ExaminationGrade::find()
                    ->where(['examinationbodyid' => $body->examinationbodyid , 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
            if (count($subs)==0 || count($profs)==0 || count($grds)==0)
                unset($bodies[$key]);
        }
        $exam_ids = array();
        foreach ($bodies as $body) 
        {
            array_push($exam_ids, $body->examinationbodyid);
        }
        
        //Retrieves first cseccentreid record - corresponding to value set in javascript function 'generateCsecQualificationBlanks()'
        $centres = CsecCentre::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
        $default_centreid = $centres[0]->cseccentreid;
        
        //Retrieves first subjectid record - corresponding to value set in javascript function 'generateCsecQualificationBlanks()'
        $subjects = Subject::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
        $default_subjectid = $subjects[0]->subjectid;
        
        //Retrieves first examinationproficiencyid record - corresponding to value set in javascript function 'generateCsecQualificationBlanks()'
        $proficiencies = ExaminationProficiencyType::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
        $default_proficiencyid = $proficiencies[0]->examinationproficiencytypeid;
        
        //Retrieves first examinationgradeid record - corresponding to value set in javascript function 'generateCsecQualificationBlanks()'
        $grades = ExaminationGrade::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
        $default_gradeid = $grades[0]->examinationgradeid;
        
        //Retrieves default canadatenumber - corresponding to value set in javascript function 'generateCsecQualificationBlanks()'
        $default_candidatenumber = "00000";
        
        $default_year = "1970";
        
        if (    (in_array($this->examinationbodyid, $exam_ids) == false)
                    ||
                    (
                        ($this->cseccentreid == $default_centreid)
                        && ($this->subjectid==$default_subjectid) 
                        && ($this->examinationproficiencytypeid ==$default_proficiencyid)         
                        && ($this->examinationgradeid == NULL || strcmp($this->examinationgradeid,"")==0 ||  $this->examinationgradeid==$default_gradeid)
                        && (strcmp($this->candidatenumber,"")==0 || $this->candidatenumber==$default_candidatenumber)
                        && ($this->year == NULL || strcmp($this->year,"")==0 || strcmp($this->year,$default_year)==0)      
                    )
                )
                    return false;
        return true;    
    }
    
}
