<?php

namespace app\subcomponents\reports\controllers;

use common\models\AcademicYear;
use common\models\BatchStudents;
use common\models\BatchStudentCape;
use common\models\CapeCourse;
use common\models\CourseCatalog;
use common\models\CourseOffering;
use common\models\Division;
use common\models\ProgrammeCatalog;
use common\models\QualificationTypeModel;
use common\models\Semester;
use yii\data\ArrayDataProvider;

class CoursePassRateController extends \yii\web\Controller
{
    public function actionIndex(
        $divisionId = null,
        $academicYearId = null,
        $semesterId = null
    ) {
        $divisionName = null;
        $academicYearName = null;
        $academicYears = array();
        $semesterName = null;
        $semesters = array();

        $divisions =
            Division::find()
            ->where([
                "divisionid" => [4, 5, 6, 7],
                "isactive" => 1,
                "isdeleted" => 0
            ])
            ->all();

        if ($divisionId == true) {
            $division =
                Division::find()
                ->where(["divisionid" => $divisionId])
                ->one();
            $divisionName = $division->name;

            if ($divisionId == 4 || $divisionId == 5) {
                $academicYears =
                    AcademicYear::find()
                    ->where([
                        "applicantintentid" => 1,
                        "isactive" => 1,
                        "isdeleted" => 0
                    ])
                    ->all();
            } elseif ($divisionId == 6) {
                $academicYears =
                    AcademicYear::find()
                    ->where([
                        "applicantintentid" => 4,
                        "isactive" => 1,
                        "isdeleted" => 0
                    ])
                    ->all();
            } elseif ($divisionId == 7) {
                $academicYears =
                    AcademicYear::find()
                    ->where([
                        "applicantintentid" => 6,
                        "isactive" => 1,
                        "isdeleted" => 0
                    ])
                    ->all();
            }
        }

        if ($academicYearId == true) {
            $academicYear =
                AcademicYear::find()
                ->where(["academicyearid" => $academicYearId])
                ->one();
            $academicYearName = $academicYear->title;

            $semesters =
                Semester::find()
                ->where([
                    "academicyearid" => $academicYearId,
                    "isactive" => 1,
                    "isdeleted" => 0
                ])
                ->all();
        }

        return $this->render(
            "index",
            [
                "divisionId" => $divisionId,
                "academicYearId" => $academicYearId,
                "semesterId" => $semesterId,
                "divisionName" => $divisionName,
                "academicYearName" => $academicYearName,
                "academicYears" => $academicYears,
                "semesterName" => $semesterName,
                "semesters" => $semesters,
                "divisions" => $divisions,
            ]
        );
    }


    // public function actionGenerateReport(
    //     $divisionId = null,
    //     $academicYearId = null,
    //     $semesterId = null
    // ) {
    //     $dataProvider = null;
    //     $data = array();

    //     if ($semesterId == true) {
    //         if ($divisionId == 4) { // if DASGS associate and cape courses must be considered
    //         } else {
    //             $courseOfferings =
    //                 CourseOffering::find()
    //                 ->innerJoin(
    //                     'academic_offering',
    //                     '`course_offering`.`academicofferingid` = `academic_offering`.`academicofferingid`'
    //                 )
    //                 ->innerJoin(
    //                     'application_period',
    //                     '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`'
    //                 )
    //                 ->where([
    //                     "course_offering.semesterid" => $semesterId,
    //                     "course_offering.isactive" => 1,
    //                     "course_offering.isdeleted" => 0,
    //                     "academic_offering.isactive" => 1,
    //                     "academic_offering.isdeleted" => 0,
    //                     "application_period.isactive" => 1,
    //                     "application_period.isdeleted" => 0,
    //                     "application_period.divisionid" => $divisionId,
    //                 ])
    //                 ->all();

    //             $uniqueCourseCatalogIdCollection = array();
    //             if (!empty($courseOfferings)) {
    //                 foreach ($courseOfferings as $courseOffering) {
    //                     if (
    //                         in_array(
    //                             $courseOffering->coursecatalogid,
    //                             $uniqueCourseCatalogIdCollection
    //                         )
    //                         == false
    //                     ) {
    //                         $uniqueCourseCatalogIdCollection[] = $courseOffering->coursecatalogid;
    //                     }
    //                 }
    //             }

    //             if (!empty($uniqueCourseCatalogIdCollection)) {
    //                 foreach ($uniqueCourseCatalogIdCollection as $courseCatalogId) {
    //                     $row = array();
    //                     $division =
    //                         Division::find()
    //                         ->where(["divisionid" => $divisionId])
    //                         ->one();
    //                     $row["division"] = $division->abbreviation;

    //                     $academicYear =
    //                         AcademicYear::find()
    //                         ->where(["academicyearid" => $academicYearId])
    //                         ->one();
    //                     $row["academicYear"] = $academicYear->title;

    //                     $semester =
    //                         Semester::find()
    //                         ->where(["semesterid" => $semesterId])
    //                         ->one();
    //                     $row["semester"] = $semester->title;

    //                     $courseCatalog =
    //                         CourseCatalog::find()
    //                         ->where(["coursecatalogid" => $courseCatalogId])
    //                         ->one();
    //                     $row["courseCode"] = $courseCatalog->coursecode;
    //                     $row["courseName"] = $courseCatalog->name;

    //                     $programmes =
    //                         ProgrammeCatalog::find()
    //                         ->innerJoin(
    //                             'academic_offering',
    //                             '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`'
    //                         )
    //                         ->innerJoin(
    //                             'course_offering',
    //                             '`academic_offering`.`academicofferingid` = `course_offering`.`academicofferingid`'
    //                         )
    //                         ->innerJoin(
    //                             'application_period',
    //                             '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`'
    //                         )
    //                         ->where([
    //                             "programme_catalog.isactive" => 1,
    //                             "programme_catalog.isdeleted" => 0,
    //                             "academic_offering.isactive" => 1,
    //                             "academic_offering.isdeleted" => 0,
    //                             "course_offering.isactive" => 1,
    //                             "course_offering.isdeleted" => 0,
    //                             "course_offering.coursecatalogid" => $courseCatalogId,
    //                             "application_period.isactive" => 1,
    //                             "application_period.isdeleted" => 0,
    //                             "application_period.divisionid" => $divisionId,
    //                         ])
    //                         ->all();
    //                     $programme = $programmes[0];

    //                     $qualificationType =
    //                         QualificationTypeModel::getQualificationAbbreviationByID(
    //                             $programme->qualificationtypeid
    //                         );
    //                     $specialisation = $programme->specialisation;

    //                     if ($programme->programmecatalogid == 10) {    //if CAPE
    //                         return  $programme->name;
    //                     } elseif ($specialisation == true) {
    //                         $row["programme"] =
    //                             "{$qualificationType} {$programme->name} ({$specialisation})";
    //                     } else {
    //                         $row["programme"] =
    //                             "{$qualificationType}. {$programme->name}";
    //                     }

    //                     $failingStudent =
    //                         BatchStudents::find()
    //                         ->innerJoin(
    //                             'batch',
    //                             '`batch_students`.`batchid` = `batch`.`batchid`'
    //                         )
    //                         ->innerJoin(
    //                             'course_offering',
    //                             '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`'
    //                         )
    //                         ->where([
    //                             "batch_students.grade" => "F",
    //                             "batch_students.isactive" => 1,
    //                             "batch_students.isdeleted" => 0,
    //                             "batch.isactive" => 1,
    //                             "batch.isdeleted" => 0,
    //                             "course_offering.isactive" => 1,
    //                             "course_offering.isdeleted" => 0,
    //                             "course_offering.coursecatalogid" => $courseCatalogId,
    //                             "course_offering.semesterid" => $semesterId
    //                         ])
    //                         ->groupBy("batch_students.studentregistrationid")
    //                         ->all();
    //                     $fails = count($failingStudent);

    //                     $passingStudents =
    //                         BatchStudents::find()
    //                         ->innerJoin(
    //                             'batch',
    //                             '`batch_students`.`batchid` = `batch`.`batchid`'
    //                         )
    //                         ->innerJoin(
    //                             'course_offering',
    //                             '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`'
    //                         )
    //                         ->where([
    //                             "batch_students.isactive" => 1,
    //                             "batch_students.isdeleted" => 0,
    //                             "batch.isactive" => 1,
    //                             "batch.isdeleted" => 0,
    //                             "course_offering.isactive" => 1,
    //                             "course_offering.isdeleted" => 0,
    //                             "course_offering.coursecatalogid" => $courseCatalogId,
    //                             "course_offering.semesterid" => $semesterId
    //                         ])
    //                         ->andWhere(["not", ["batch_students.grade" => "F"]])
    //                         ->groupBy("batch_students.studentregistrationid")
    //                         ->all();
    //                     $passes = count($passingStudents);

    //                     $row["noOfPasses"] = $passes;
    //                     $row["noOfFails"] = $fails;
    //                     $totalStudents = $passes + $fails;
    //                     $row["totalStudents"] = $totalStudents;

    //                     if ($totalStudents == 0) {
    //                         $row["passRate"] = "N/A";
    //                     } else {
    //                         $rawPassRate = $passes / ($totalStudents * 1.0) * 100;
    //                         $row["passRate"] = round($rawPassRate, 2);
    //                     }
    //                     $data[] = $row;
    //                 }
    //             }
    //         }
    //     } elseif ($semesterId == null && $academicYearId == true) {
    //         //constrain courses by academic year
    //     } elseif (
    //         $semesterId == null
    //         && $academicYearId == null
    //         && $divisionId == true
    //     ) {
    //         //constrain courses by division
    //     }

    //     $dataProvider = new ArrayDataProvider([
    //         'allModels' => $data,
    //         'pagination' => [
    //             'pageSize' => 200,
    //         ],
    //         'sort' => [
    //             'attributes' => [
    //                 'division',
    //                 'academicYear',
    //                 'semester',
    //                 'courseCode',
    //                 'courseName'
    //             ],
    //         ],
    //     ]);

    //     return $this->render(
    //         "generate-report",
    //         [
    //             "divisionId" => $divisionId,
    //             "academicYearId" => $academicYearId,
    //             "semesterId" => $semesterId,
    //             "dataProvider" => $dataProvider,
    //         ]
    //     );
    // }




    public function actionGenerateReport(
        $divisionId = null,
        $academicYearId = null,
        $semesterId = null
    ) {
        $dataProvider = null;
        $data = array();

        if ($semesterId == true) {
            $division =
                Division::find()
                ->where(["divisionid" => $divisionId])
                ->one();
            $divisionTitle = $division->abbreviation;

            $academicYear =
                AcademicYear::find()
                ->where(["academicyearid" => $academicYearId])
                ->one();
            $academicYearTitle = $academicYear->title;

            $semester =
                Semester::find()
                ->where(["semesterid" => $semesterId])
                ->one();
            $semesterTitle = $semester->title;

            $associateCourseOfferings =
                CourseOffering::find()
                ->innerJoin(
                    'academic_offering',
                    '`course_offering`.`academicofferingid` = `academic_offering`.`academicofferingid`'
                )
                ->innerJoin(
                    'application_period',
                    '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`'
                )
                ->where([
                    "course_offering.semesterid" => $semesterId,
                    "course_offering.isactive" => 1,
                    "course_offering.isdeleted" => 0,
                    "academic_offering.isactive" => 1,
                    "academic_offering.isdeleted" => 0,
                    "application_period.isactive" => 1,
                    "application_period.isdeleted" => 0,
                    "application_period.divisionid" => $divisionId,
                ])
                ->all();

            $uniqueAssociateCourseCatalogIdCollection = array();
            if (!empty($associateCourseOfferings)) {
                foreach ($associateCourseOfferings as $courseOffering) {
                    if (
                        in_array(
                            $courseOffering->coursecatalogid,
                            $uniqueAssociateCourseCatalogIdCollection
                        )
                        == false
                    ) {
                        $uniqueAssociateCourseCatalogIdCollection[] = $courseOffering->coursecatalogid;
                    }
                }
            }

            if (!empty($uniqueAssociateCourseCatalogIdCollection)) {
                foreach ($uniqueAssociateCourseCatalogIdCollection as $courseCatalogId) {
                    $row = array();
                    $row["division"] = $divisionTitle;
                    $row["academicYear"] = $academicYearTitle;
                    $row["semester"] = $semesterTitle;

                    $courseCatalog =
                        CourseCatalog::find()
                        ->where(["coursecatalogid" => $courseCatalogId])
                        ->one();
                    $row["courseCode"] = $courseCatalog->coursecode;
                    $row["courseName"] = $courseCatalog->name;

                    $programmes =
                        ProgrammeCatalog::find()
                        ->innerJoin(
                            'academic_offering',
                            '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`'
                        )
                        ->innerJoin(
                            'course_offering',
                            '`academic_offering`.`academicofferingid` = `course_offering`.`academicofferingid`'
                        )
                        ->innerJoin(
                            'application_period',
                            '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`'
                        )
                        ->where([
                            "programme_catalog.isactive" => 1,
                            "programme_catalog.isdeleted" => 0,
                            "academic_offering.isactive" => 1,
                            "academic_offering.isdeleted" => 0,
                            "course_offering.isactive" => 1,
                            "course_offering.isdeleted" => 0,
                            "course_offering.coursecatalogid" => $courseCatalogId,
                            "application_period.isactive" => 1,
                            "application_period.isdeleted" => 0,
                            "application_period.divisionid" => $divisionId,
                        ])
                        ->all();
                    $programme = $programmes[0];

                    $qualificationType =
                        QualificationTypeModel::getQualificationAbbreviationByID(
                            $programme->qualificationtypeid
                        );
                    $specialisation = $programme->specialisation;

                    if ($programme->programmecatalogid == 10) {    //if CAPE
                        return  $programme->name;
                    } elseif ($specialisation == true) {
                        $row["programme"] =
                            "{$qualificationType} {$programme->name} ({$specialisation})";
                    } else {
                        $row["programme"] =
                            "{$qualificationType}. {$programme->name}";
                    }

                    $failingStudent =
                        BatchStudents::find()
                        ->innerJoin(
                            'batch',
                            '`batch_students`.`batchid` = `batch`.`batchid`'
                        )
                        ->innerJoin(
                            'course_offering',
                            '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`'
                        )
                        ->where([
                            "batch_students.grade" => "F",
                            "batch_students.isactive" => 1,
                            "batch_students.isdeleted" => 0,
                            "batch.isactive" => 1,
                            "batch.isdeleted" => 0,
                            "course_offering.isactive" => 1,
                            "course_offering.isdeleted" => 0,
                            "course_offering.coursecatalogid" => $courseCatalogId,
                            "course_offering.semesterid" => $semesterId
                        ])
                        ->groupBy("batch_students.studentregistrationid")
                        ->all();
                    $fails = count($failingStudent);

                    $passingStudents =
                        BatchStudents::find()
                        ->innerJoin(
                            'batch',
                            '`batch_students`.`batchid` = `batch`.`batchid`'
                        )
                        ->innerJoin(
                            'course_offering',
                            '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`'
                        )
                        ->where([
                            "batch_students.isactive" => 1,
                            "batch_students.isdeleted" => 0,
                            "batch.isactive" => 1,
                            "batch.isdeleted" => 0,
                            "course_offering.isactive" => 1,
                            "course_offering.isdeleted" => 0,
                            "course_offering.coursecatalogid" => $courseCatalogId,
                            "course_offering.semesterid" => $semesterId
                        ])
                        ->andWhere(["not", ["batch_students.grade" => "F"]])
                        ->groupBy("batch_students.studentregistrationid")
                        ->all();
                    $passes = count($passingStudents);

                    $row["noOfPasses"] = $passes;
                    $row["noOfFails"] = $fails;
                    $totalStudents = $passes + $fails;
                    $row["totalStudents"] = $totalStudents;

                    if ($totalStudents == 0) {
                        $row["passRate"] = "N/A";
                    } else {
                        $rawPassRate = $passes / ($totalStudents * 1.0) * 100;
                        $row["passRate"] = round($rawPassRate, 2);
                    }
                    $data[] = $row;
                }
            }

            //CAPE COURSES IF APPLICABLE
            if ($divisionId == 4) {
                $capeCourseOfferings =
                    CapeCourse::find()
                    ->where([
                        "semesterid" => $semesterId,
                        "isactive" => 1,
                        "isdeleted" => 0
                    ])
                    ->all();

                $uniqueCapeCourseCatalogIdCollection = array();
                if (!empty($capeCourseOfferings)) {
                    foreach ($capeCourseOfferings as $courseOffering) {
                        if (
                            in_array(
                                $courseOffering->coursecode,
                                $uniqueCapeCourseCatalogIdCollection
                            )
                            == false
                        ) {
                            $uniqueCapeCourseCatalogIdCollection[] = $courseOffering->coursecode;
                        }
                    }
                }

                if (!empty($uniqueCapeCourseCatalogIdCollection)) {
                    foreach ($uniqueCapeCourseCatalogIdCollection as $courseCode) {
                        $row = array();
                        $row["division"] = $divisionTitle;
                        $row["academicYear"] = $academicYearTitle;
                        $row["semester"] = $semesterTitle;

                        $capeCourse =
                            CapeCourse::find()
                            ->where(["coursecode" => $courseCode])
                            ->one();
                        $row["courseCode"] = $capeCourse->coursecode;
                        $row["courseName"] = $capeCourse->name;
                        $row["programme"] = "CAPE";

                        $failingStudent =
                            BatchStudentCape::find()
                            ->innerJoin(
                                'batch_cape',
                                '`batch_student_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`'
                            )
                            ->innerJoin(
                                'cape_course',
                                '`batch_cape`.`capecourseid` = `cape_course`.`capecourseid`'
                            )
                            ->where([
                                "batch_student_cape.isactive" => 1,
                                "batch_student_cape.isdeleted" => 0,
                                "batch_cape.isactive" => 1,
                                "batch_cape.isdeleted" => 0,
                                "cape_course.isactive" => 1,
                                "cape_course.isdeleted" => 0,
                                "cape_course.coursecode" => $courseCode,
                                "cape_course.semesterid" => $semesterId
                            ])
                            ->andWhere(['<',  'batch_student_cape.final', 40])
                            ->groupBy("batch_student_cape.studentregistrationid")
                            ->all();
                        $fails = count($failingStudent);

                        $passingStudents =
                            BatchStudentCape::find()
                            ->innerJoin(
                                'batch_cape',
                                '`batch_student_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`'
                            )
                            ->innerJoin(
                                'cape_course',
                                '`batch_cape`.`capecourseid` = `cape_course`.`capecourseid`'
                            )
                            ->where([
                                "batch_student_cape.isactive" => 1,
                                "batch_student_cape.isdeleted" => 0,
                                "batch_cape.isactive" => 1,
                                "batch_cape.isdeleted" => 0,
                                "cape_course.isactive" => 1,
                                "cape_course.isdeleted" => 0,
                                "cape_course.coursecode" => $courseCode,
                                "cape_course.semesterid" => $semesterId
                            ])
                            ->andWhere(['>=',  'batch_student_cape.final', 40])
                            ->groupBy("batch_student_cape.studentregistrationid")
                            ->all();
                        $passes = count($passingStudents);

                        $row["noOfPasses"] = $passes;
                        $row["noOfFails"] = $fails;
                        $totalStudents = $passes + $fails;
                        $row["totalStudents"] = $totalStudents;

                        if ($totalStudents == 0) {
                            $row["passRate"] = "N/A";
                        } else {
                            $rawPassRate = $passes / ($totalStudents * 1.0) * 100;
                            $row["passRate"] = round($rawPassRate, 2);
                        }
                        $data[] = $row;
                    }
                }
            }
        } elseif ($semesterId == null && $academicYearId == true) {
            //constrain courses by academic year
        } elseif (
            $semesterId == null
            && $academicYearId == null
            && $divisionId == true
        ) {
            //constrain courses by division
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 200,
            ],
            'sort' => [
                'attributes' => [
                    'division',
                    'academicYear',
                    'semester',
                    'courseCode',
                    'courseName'
                ],
            ],
        ]);

        return $this->render(
            "generate-report",
            [
                "divisionId" => $divisionId,
                "academicYearId" => $academicYearId,
                "semesterId" => $semesterId,
                "dataProvider" => $dataProvider,
            ]
        );
    }
}
