<?php

namespace frontend\models;

use Yii;

use common\models\User;

class CsecQualificationModel
{
    public static function getVerifiedCsecQualificationsByPersonId($personId)
    {
        return CsecQualification::find()
        ->where(
            [
              'personid' => $personId,
              'isverified' => 1,
              'isactive' => 1,
              'isdeleted' => 0
            ]
        )
        ->all();
    }

    public static function getAllCsecQualificationsByPersonId($personId)
    {
        return CsecQualification::find()
        ->where(
            [
              'personid' => $personId,
              'isactive' => 1,
              'isdeleted' => 0
            ]
        )
        ->all();
    }

    public static function hasVerifiedCsecQualifications($csecQualifications)
    {
        foreach ($csecQualifications as $csecQualification) {
            if ($csecQualification->isverified == true) {
                return true;
            }
        }
        return false;
    }


    public static function getCentreDetails($csecQualification)
    {
        return CsecCentre::find()
        ->where(['cseccentreid' => $csecQualification->cseccentreid])
        ->one();
    }

    public static function getVerifiedCsecQualificationsDataProvider(
        $csecQualifications
    ) {
        $qualifications = array();
        foreach ($csecQualifications as $csecQualification) {
            if ($csecQualification->isverified == true) {
                $qualifications[] = $csecQualification;
            }
        }
        return $qualifications;
    }

    public static function formatCsecQualificationIntoAssociativeArray(
        $csecQualification
    ) {
        $data = array();
        $data['id'] = $csecQualification->csecqualificationid;
        $data['examinationBody'] =
        ExaminationBodyModel::getExaminationBodyById(
            $csecQualification->examinationbodyid
        )
        ->name;

        $data['examinationBodyAbbreviation'] =
        ExaminationBodyModel::getExaminationBodyById(
            $csecQualification->examinationbodyid
        )
        ->abbreviation;

        $data['year'] = $csecQualification->year;

        $data['proficiency'] =
        ExaminationProficiencyTypeModel::getExaminationProficiencyTypeById(
            $csecQualification->examinationproficiencytypeid
        )
        ->name;

        $data['subject'] =
        SubjectModel::getSubjectById($csecQualification->subjectid)->name;

        $data['grade'] =
        ExaminationGradeModel::getExaminationGradeById(
            $csecQualification->examinationgradeid
        )
         ->name;

        $data['centre'] =
        CsecCentreModel::getCsecCentreById($csecQualification->cseccentreid)
        ->name;

        return $data;
    }

    public static function prepareFormattedVerifiedCsecQualificationListing(
        $csecQualifications
    ) {
        $data = array();

        foreach ($csecQualifications as $csecQualification) {
            if ($csecQualification->isverified == true) {
                $data[] =
                self::formatCsecQualificationIntoAssociativeArray($csecQualification);
            }
        }
        return $data;
    }


    public static function hasCsecEnglish($personid)
    {
        $englishCertificates =
        CsecQualification::find()
        ->innerJoin(
            'subject',
            '`csec_qualification`.`subjectid` = `subject`.`subjectid`'
        )
        ->innerJoin(
            'examination_grade',
            '`csec_qualification`.`examinationgradeid` = `examination_grade`.`examinationgradeid`'
        )
        ->where([
            'csec_qualification.personid' => $personid,
            'csec_qualification.isverified' => 1,
            'csec_qualification.isactive' => 1,
            'csec_qualification.isdeleted' => 0,
            'csec_qualification.examinationbodyid' => 3,
            'subject.name' => 'English Language',
            'examination_grade.ordering' => [1,2,3]
        ])
        ->orWhere([
            'csec_qualification.personid' => $personid,
            'csec_qualification.isverified' => 1,
            'csec_qualification.isactive' => 1,
            'csec_qualification.isdeleted' => 0,
            'csec_qualification.examinationbodyid' => 5,
            'subject.name' => 'English idLanguage',
            'examination_grade.ordering' => [1,2,3]
        ])
        ->all();

        if ($englishCertificates == true) {
            return true;
        }
        return false;
    }


    public static function hasCsecMathematics($personid)
    {
        $mathsCertificates =
        CsecQualification::find()
        ->innerJoin(
            'subject',
            '`csec_qualification`.`subjectid` = `subject`.`subjectid`'
        )
        ->innerJoin(
            'examination_grade',
            '`csec_qualification`.`examinationgradeid` = `examination_grade`.`examinationgradeid`'
        )
        ->where([
            'csec_qualification.personid' => $personid,
            'csec_qualification.isverified' => 1,
            'csec_qualification.isactive' => 1,
            'csec_qualification.isdeleted' => 0,
            'csec_qualification.examinationbodyid' => 3,
            'subject.name' => 'Mathematics',
            'examination_grade.ordering' => [1,2,3]
        ])
        ->orWhere([
            'csec_qualification.personid' => $personid,
            'csec_qualification.isverified' => 1,
            'csec_qualification.isactive' => 1,
            'csec_qualification.isdeleted' => 0,
            'csec_qualification.examinationbodyid' => 5,
            'subject.name' => 'Mathematics',
            'examination_grade.ordering' => [1,2,3]
        ])
        ->all();

        if ($mathsCertificates == true) {
            return true;
        }
        return false;
    }


    public static function hasFiveCsecPasses($personid)
    {
        $passingCertificates =
        CsecQualification::find()
        ->innerJoin(
            'examination_grade',
            '`examination_grade`.`examinationgradeid` = `csec_qualification`.`examinationgradeid`'
        )
        ->where([
          'csec_qualification.personid' => $personid,
          'csec_qualification.isverified' => 1,
          'csec_qualification.isdeleted' => 0,
          'examination_grade.ordering' => [1, 2, 3]
        ])
        ->all();

        if (count($passingCertificates) >= 5) {
            return true;
        }
        return false;
    }


    public static function hasDteRelevantSciences($personid)
    {
        $passingCertificates =
        CsecQualification::find()
        ->innerJoin(
            'examination_grade',
            '`examination_grade`.`examinationgradeid` = `csec_qualification`.`examinationgradeid`'
        )
        ->where([
          'csec_qualification.personid' => $personid,
          'csec_qualification.isverified' => 1,
          'csec_qualification.isdeleted' => 0,
          'examination_grade.ordering' => [1, 2, 3]
        ])
        ->all();

        if (count($passingCertificates)>0) {
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

            if ($integrated_science == true && $biology == true && $biology2 == true && $chemistry == true && $chemistry2 == true && $physics == true  && $physics2 == true && $agricultural_science1 == true && $agricultural_science2 == true) {
                foreach ($passingCertificates as $cert) {
                    if ($cert->subjectid == $integrated_science->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_integrated_science = true;
                        }
                    }

                    if ($cert->subjectid == $biology->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_biology = true;
                        }
                    }

                    if ($cert->subjectid == $biology2->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_biology2 = true;
                        }
                    }

                    if ($cert->subjectid == $chemistry->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_chemistry = true;
                        }
                    }

                    if ($cert->subjectid == $chemistry2->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_chemistry2 = true;
                        }
                    }

                    if ($cert->subjectid == $physics->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_physics = true;
                        }
                    }

                    if ($cert->subjectid == $physics2->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_physics2 = true;
                        }
                    }

                    if ($cert->subjectid == $agricultural_science1->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_agricultural_science1 = true;
                        }
                    }

                    if ($cert->subjectid == $agricultural_science2->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_agricultural_science2 = true;
                        }
                    }
                }
            }

            if ($has_integrated_science == true || $has_biology == true  || $has_biology2 == true|| $has_chemistry == true || $has_chemistry2 == true || $has_physics == true || $has_physics2 == true || $has_agricultural_science1 == true || $has_agricultural_science2 == true) {
                return true;
            }
        }
        return false;
    }


    public static function hasDneRelevantSciences($personid)
    {
        $passingCertificates =
        CsecQualification::find()
        ->innerJoin(
            'examination_grade',
            '`examination_grade`.`examinationgradeid` = `csec_qualification`.`examinationgradeid`'
        )
        ->where([
          'csec_qualification.personid' => $personid,
          'csec_qualification.isverified' => 1,
          'csec_qualification.isdeleted' => 0,
          'examination_grade.ordering' => [1, 2, 3]
        ])
        ->all();

        if (count($passingCertificates) > 0) {
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

            if ($integrated_science == true && $biology == true && $biology2 == true && $chemistry == true  && $chemistry2 == true && $physics == true  && $physics2 == true && $human_and_social_biology == true  && $human_and_social_biology2 == true) {
                foreach ($passingCertificates as $cert) {
                    if ($cert->subjectid == $integrated_science->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_integrated_science = true;
                        }
                    }

                    if ($cert->subjectid == $biology->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_biology = true;
                        }
                    }

                    if ($cert->subjectid == $biology2->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_biology2 = true;
                        }
                    }

                    if ($cert->subjectid == $chemistry->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_chemistry = true;
                        }
                    }

                    if ($cert->subjectid == $chemistry2->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_chemistry2 = true;
                        }
                    }

                    if ($cert->subjectid == $physics2->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_physics2 = true;
                        }
                    }

                    if ($cert->subjectid == $human_and_social_biology->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_human_and_social_biology = true;
                        }
                    }

                    if ($cert->subjectid == $human_and_social_biology2->subjectid) {
                        $exam_grade = ExaminationGrade::findOne(['examinationgradeid' => $cert->examinationgradeid]);
                        if (in_array($exam_grade->ordering, array(1,2,3))) {
                            $has_human_and_social_biology2 = true;
                        }
                    }
                }
            }

            if ($has_integrated_science == true || $has_biology == true  || $has_biology2 == true || $has_chemistry == true ||  $has_chemistry2 == true || $has_physics == true || $has_physics2 == true || $has_human_and_social_biology == true || $has_human_and_social_biology2 == true) {
                return true;
            }
        }

        return false;
    }

    public static function generateApplicantHistoryFeedback(
        $applicantPersonId,
        $verifiedCertificate
    ) {
        $dups =
        self::getPossibleDuplicate(
            $applicantPersonId,
            $verifiedCertificate->candidatenumber,
            $verifiedCertificate->year
        );

        $message = "";
        if ($dups == true) {
            $dupes = "";
            foreach ($dups as $dup) {
                $user = User::findOne(['personid' => $dup, 'isdeleted' => 0]);
                $dupes = $user ? $dupes . ' ' . $user->username : $dupes;
            }
            $message = "Possible Duplicate of applicant(s) {$dupes}";
        }

        $reapp =
        self::getPossibleReapplicant(
            $applicantPersonId,
            $verifiedCertificate->candidatenumber,
            $verifiedCertificate->year
        );
        if ($reapp) {
            $message =
            $message . ' Applicant applied to College in academic year prior to 2015/2016.';
        }

        if ($dups == true || $reapp == true) {
            return $message;
        }
        return null;
    }


    public static function getPossibleReapplicant($candidateno, $year)
    {
        try {
            $origcandidateno = $candidateno;
            $candidateno = intval($candidateno);
        } catch (Exception $ex) {
            return false;
        }
        if ($candidateno == 0 || strlen($origcandidateno) != 10) {
            return false;
        }

        $cms_reapplicant =
        Yii::$app->cms_db->createCommand(
            "select certificate_id from applicants_certificates where year = $year and candidate_no = $candidateno"
        )
        ->queryOne();

        return $reapplicant ? true : false;
    }


    public static function getPossibleDuplicate($personid, $candidateno, $year)
    {
        try {
            $origcandidateno = $candidateno;
            $candidateno = intval($candidateno);
        } catch (Exception $ex) {
            return false;
        }
        if ($candidateno == 0 || strlen($origcandidateno) != 10) {
            return false;
        }
        $groups =
        CsecQualification::find()
        ->where([
          'candidatenumber' => $candidateno, 'isdeleted' => 0, 'year' => $year
        ])
        ->groupBy('personid')
        ->all();

        if (count($groups) == 1) {
            return false;
        } else {
            $dups = array();
            foreach ($groups as $group) {
                if ($group->personid != $personid) {
                    $dups[] = $group->personid;
                }
            }
            return $dups;
        }
    }
}
