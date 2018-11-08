<?php
  // use yii\helpers\Html;
  use yii\helpers\Url;
  // use yii\widgets\ActiveForm;
  // use kartik\grid\GridView;
  // use kartik\export\ExportMenu;
  //
  // use frontend\models\Division;
  // use frontend\models\AcademicYear;
  // use frontend\models\Semester;
  // use frontend\models\Department;
  // use frontend\models\ProgrammeCatalog;
  // use frontend\models\AcademicOffering;

  $this->title = 'Cohort Cumulative Report';
  $this->params['breadcrumbs'][] = $this->title;
?>

<h2 class="text-center"><?= $this->title ?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
  <div class="box-header with-border">
    <h3 class="box-title"><?= $this->title ?></h3>
  </div>

  <div class="box-body">
    <h4>
      Select from the list below which report you wish to generate:
    </h4>

    <ol>
      <li>
        <a href="<?=Url::toRoute(['/subcomponents/programmes/cohort-report-generator/cumulative-gpa-report']);?>">
          Cumulative GPA Report
        </a>
      </li>
    </ol>
  </div>

</div>
