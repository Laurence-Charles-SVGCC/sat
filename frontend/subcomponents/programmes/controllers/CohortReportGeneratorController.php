<?php

namespace app\subcomponents\programmes\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use frontend\models\Division;
use frontend\models\AcademicYear;
use frontend\models\Employee;
use frontend\models\StudentRegistration;
use frontend\models\ProgrammeCatalog;

class CohortReportGeneratorController extends \yii\web\Controller
{

  public function actionIndex()
  {
    return $this->render('index');
  }


  public function actionCumulativeGpaReport($division_id = NULL, $cohort_id = NULL,
  $minimum_gpa = NULL)
  {
    $data_provider = NULL;
    $info = array();
    $container = array();

    $selected_division = NULL;
    $cohorts = NULL;
    $selected_cohort = NULL;
    $gpas = NULL;
    $gpas_size = NULL;

    $divisions =   Division::find()
      ->where(['divisionid' => [4,5,6,7], 'isdeleted' => 0])
      ->all();

    if ( in_array($division_id, [4,5]) == true ) {
      $cohorts = AcademicYear::find()
      ->where(['applicantintentid' => [1,2], 'isdeleted' => 0])
      ->all();
      $selected_division = Division::getDivisionAbbreviation($division_id);
    }
    elseif ( $division_id == 6 ) {
      $cohorts = AcademicYear::find()
      ->where(['applicantintentid' => [4,5], 'isdeleted' => 0])
      ->all();
      $selected_division = Division::getDivisionName($division_id);
    }
    elseif ( $division_id == 7 ) {
      $cohorts = AcademicYear::find()
      ->where(['applicantintentid' => [6,7], 'isdeleted' => 0])
      ->all();
      $selected_division = Division::getDivisionName($division_id);
    }

    if ( $cohort_id != NULL ) {
      $gpas = array();
      $gpas[] = ["grade" => "A+", "quality_points" => 4.0];
      $gpas[] = ["grade" => "A", "quality_points" => 3.75];
      $gpas[] = ["grade" => "A-", "quality_points" => 3.5];
      $gpas[] = ["grade" => "B+", "quality_points" => 3.25];
      $gpas[] = ["grade" => "B", "quality_points" => 3.0];
      $gpas[] = ["grade" => "B-", "quality_points" => 2.75];
      $gpas[] = ["grade" => "C+", "quality_points" => 2.5];
      $gpas[] = ["grade" => "C", "quality_points" => 2.25];
      $gpas[] = ["grade" => "C-", "quality_points" => 2.0];
      $gpas[] = ["grade" => "D", "quality_points" => 1.0];
      $gpas[] = ["grade" => "F", "quality_points" => 0.0];
      $gpas_size = count($gpas);

      $selected_cohort = AcademicYear::getYear($cohort_id)->title;
    }

    if ($minimum_gpa != NULL)
    {
      $db = Yii::$app->db;

      $students =
      $db->createCommand(
       "SELECT person.username AS 'studentid',
        student_registration.studentregistrationid AS 'studentregistrationid',
        student_registration.academicofferingid AS 'academicofferingid',
        student.title AS 'title',
        student.firstname AS 'firstname',
        student.lastname AS 'lastname',
        student.gender AS 'gender',
        student.email AS 'institution_email',
        email.email AS 'personal_email',
        phone.homephone AS 'homephone',
        phone.cellphone AS 'cellphone',
        phone.workphone AS 'workphone'
        FROM student_registration
        JOIN person
        ON student_registration.personid = person.personid
        JOIN student
        ON person.personid = student.personid
        JOIN academic_offering
        ON student_registration.academicofferingid = academic_offering.academicofferingid
        JOIN programme_catalog
        ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid
        JOIN application_period
        ON academic_offering.applicationperiodid = application_period.applicationperiodid
        JOIN email
        ON student.personid = email.personid
        JOIN phone
        ON email.personid = phone.personid
        WHERE student_registration.isactive = 1
        AND student_registration.isdeleted = 0
        AND student_registration.registrationtypeid = 1
        AND programme_catalog.programmecatalogid <> 10
        AND application_period.divisionid = {$division_id}
        AND academic_offering.academicyearid = {$cohort_id};"
        )
       ->queryAll();

      $valid_records_exist = false;

      foreach($students as $student)
      {
        $cumulative_gpa =
        StudentRegistration::calculateCumulativeGPA($student['studentregistrationid']);

        if ( $minimum_gpa != -1  &&  ($cumulative_gpa < $minimum_gpa || $cumulative_gpa == "Pending")) {
            continue;
        }

        $valid_records_exist = true;

        $info['final'] = $cumulative_gpa;
        $info['studentid'] = $student['studentid'];
        $info['firstname'] = $student['firstname'];
        $info['lastname'] = $student['lastname'];
        $info['gender'] = $student['gender'];
        $info['institution_email'] = $student['institution_email'];
        $info['personal_email'] = $student['personal_email'];
        $info['phone'] = "{$student['homephone']} / {$student['cellphone']} / {$student['workphone']}";
        $info['division'] = $selected_division;
        $info['cohort'] = $selected_cohort;

        $info['programme'] =
        ProgrammeCatalog::getProgrammeName($student['academicofferingid']);

        $container[] =  $info;
      }

      $data_provider  = new ArrayDataProvider(
        ['allModels' => $container,
        'pagination' => ['pageSize' => 1000],
        'sort' =>
        [
          'defaultOrder' => ['final' => SORT_DESC],
           'attributes' => ['final']
        ]
      ]);

      $title = "{$selected_division} {$selected_cohort} Cohort GPA Report_";
      $date =  date('Y-m-d') . "_";
      $officer = Employee::getEmployeeName(Yii::$app->user->identity->personid);
      $filename = $title . $date . $officer;

      if ($valid_records_exist == true){
        return $this->renderPartial('export-cumulative-gpa-report', [
        'data_provider' => $data_provider,
        'filename' => $filename
          ]);
      }
      else {
        Yii::$app->getSession()->setFlash('error', 'No records found.');
        return $this->redirect(['cumulative-gpa-report',
        'division_id' => $division_id,
        'cohort_id' => $cohort_id,
        ]);
      }
    }

    return $this->render('cumulative-gpa-report', [
      'divisions' => $divisions,
      'division_id' => $division_id,
      'selected_division' => $selected_division,
      'cohorts' => $cohorts,
      'cohort_id' => $cohort_id,
      'selected_cohort' => $selected_cohort,
      'gpas' => $gpas,
      'gpas_size' => $gpas_size,
      'minimum_gpa' => $minimum_gpa,
    ]);
  }






}
