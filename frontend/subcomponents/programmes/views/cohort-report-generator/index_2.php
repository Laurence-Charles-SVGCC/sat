<?php
  use yii\widgets\Breadcrumbs;
  use yii\helpers\Html;
  use yii\helpers\Url;
  use yii\widgets\ActiveForm;
  use kartik\grid\GridView;
  use kartik\export\ExportMenu;

  use frontend\models\Division;
  use frontend\models\AcademicYear;
  use frontend\models\Semester;
  use frontend\models\Department;
  use frontend\models\ProgrammeCatalog;
  use frontend\models\AcademicOffering;

  $this->title = 'Cohort Report Generator';
  $this->params['breadcrumbs'][] = $this->title;
?>

<h2 class="text-center"><?= $this->title ?></h2>

<div class="box box-primary table-responsive no-padding">
  <div>
    Select from the list below which report you wish to generate:
  </div>

  <ol>
    <li>Cumulative GPA Report</li>
  </ol>


  <div>
         <?php if($broadsheet_dataprovider  && $iscape == false):?>
                 <p><strong>1.</strong>Click on the following links to download a detailed ASc. programme broadsheet in the format of your choice</p>
                 <?= ExportMenu::widget([
                         'dataProvider' => $broadsheet_dataprovider,
                         'columns' => [
                                 [
                                     'attribute' => 'studentid',
                                     'format' => 'text',
                                     'label' => 'Student ID'
                                 ],
                                 [
                                     'attribute' => 'title',
                                     'format' => 'text',
                                     'label' => 'Title'
                                 ],
                                 [
                                     'attribute' => 'firstname',
                                     'format' => 'text',
                                     'label' => 'First Name'
                                 ],
                                 [
                                     'attribute' => 'lastname',
                                     'format' => 'text',
                                     'label' => 'Last Name'
                                 ],
                                 [
                                     'attribute' => 'coursecode',
                                     'format' => 'text',
                                     'label' => 'Course Code'
                                 ],
                                 [
                                     'attribute' => 'coursename',
                                     'format' => 'text',
                                     'label' => 'Course Name'
                                 ],
                                 [
                                     'attribute' => 'semester',
                                     'format' => 'text',
                                     'label' => 'Semester'
                                 ],
                                 [
                                     'attribute' => 'lecturer',
                                     'format' => 'text',
                                     'label' => 'Lecturer'
                                 ],
                                 [
                                     'attribute' => 'courseworkweight',
                                     'format' => 'text',
                                     'label' => 'CW. Weight'
                                 ],
                                 [
                                     'attribute' => 'examweight',
                                     'format' => 'text',
                                     'label' => 'Exam Weight'
                                 ],
                                 [
                                     'attribute' => 'coursework',
                                     'format' => 'text',
                                     'label' => 'Cousework'
                                 ],
                                 [
                                     'attribute' => 'exam',
                                     'format' => 'text',
                                     'label' => 'Exam'
                                 ],
                                 [
                                     'attribute' => 'final',
                                     'format' => 'text',
                                     'label' => 'Final'
                                 ],
                                 [
                                     'attribute' => 'grade',
                                     'format' => 'text',
                                     'label' => 'Grade'
                                 ],
                                 [
                                     'attribute' => 'status',
                                     'format' => 'text',
                                     'label' => 'Status'
                                 ],
                                 [
                                     'attribute' => 'programme',
                                     'format' => 'text',
                                     'label' => 'Programme'
                                 ],
                             ],
                         'fontAwesome' => true,
                         'dropdownOptions' => [
                             'label' => 'Select Export Type',
                             'class' => 'btn btn-default'
                         ],
                         'asDropdown' => false,
                         'showColumnSelector' => false,
                         'filename' => $filename,
                         'exportConfig' => [
                              ExportMenu::FORMAT_PDF => false,
                             ExportMenu::FORMAT_TEXT => false,
                             ExportMenu::FORMAT_HTML => false,
                             ExportMenu::FORMAT_EXCEL => false,
  //                                                    ExportMenu::FORMAT_EXCEL_X => false
                         ],
                     ]);
                 ?>
         <?php elseif($broadsheet_dataprovider  && $iscape == true):?>
             <p><strong>1.</strong>Click on the following links to download a detailed CAPE programme broadsheet in the format of your choice</p>
             <?= ExportMenu::widget([
                     'dataProvider' => $broadsheet_dataprovider,
                     'columns' => [
                             [
                                 'attribute' => 'studentid',
                                 'format' => 'text',
                                 'label' => 'Student ID'
                             ],
                             [
                                 'attribute' => 'title',
                                 'format' => 'text',
                                 'label' => 'Title'
                             ],
                             [
                                 'attribute' => 'firstname',
                                 'format' => 'text',
                                 'label' => 'First Name'
                             ],
                             [
                                 'attribute' => 'lastname',
                                 'format' => 'text',
                                 'label' => 'Last Name'
                             ],
                             [
                                 'attribute' => 'coursecode',
                                 'format' => 'text',
                                 'label' => 'Course Code'
                             ],
                             [
                                 'attribute' => 'coursename',
                                 'format' => 'text',
                                 'label' => 'Course Name'
                             ],
                             [
                                 'attribute' => 'subject',
                                 'format' => 'text',
                                 'label' => 'Subject'
                             ],
                             [
                                 'attribute' => 'semester',
                                 'format' => 'text',
                                 'label' => 'Semester'
                             ],
                             [
                                 'attribute' => 'lecturer',
                                 'format' => 'text',
                                 'label' => 'Lecturer'
                             ],
                             [
                                 'attribute' => 'courseworkweight',
                                 'format' => 'text',
                                 'label' => 'CW. Weight'
                             ],
                             [
                                 'attribute' => 'examweight',
                                 'format' => 'text',
                                 'label' => 'Exam Weight'
                             ],
                             [
                                 'attribute' => 'coursework',
                                 'format' => 'text',
                                 'label' => 'Cousework'
                             ],
                             [
                                 'attribute' => 'exam',
                                 'format' => 'text',
                                 'label' => 'Exam'
                             ],
                             [
                                 'attribute' => 'final',
                                 'format' => 'text',
                                 'label' => 'Final'
                             ],
                             [
                                 'attribute' => 'programme',
                                 'format' => 'text',
                                 'label' => 'Programme'
                             ],
                         ],
                     'fontAwesome' => true,
                     'dropdownOptions' => [
                         'label' => 'Select Export Type',
                         'class' => 'btn btn-default'
                     ],
                     'asDropdown' => false,
                     'showColumnSelector' => false,
                     'filename' => $filename,
                     'exportConfig' => [
                          ExportMenu::FORMAT_PDF => false,
                         ExportMenu::FORMAT_TEXT => false,
                         ExportMenu::FORMAT_HTML => false,
                         ExportMenu::FORMAT_EXCEL => false,
  //                                                    ExportMenu::FORMAT_EXCEL_X => false
                     ],
                 ]);
             ?>
         <?php endif;?>


         <?php if($cumulative_grade_dataprovider  && $iscape == false):?>
                 <br/><br/>
                 <p>
                     <strong>2.</strong>Click on any of the following links to download a student listing of all the enrolled
                     students within this ASc. programme. The primary focus of this report is a student's current cumulative
                     academic performance.
                 </p>
                 <?= ExportMenu::widget([
                         'dataProvider' => $cumulative_grade_dataprovider,
                         'columns' => [
                                 [
                                     'attribute' => 'studentid',
                                     'format' => 'text',
                                     'label' => 'Student ID'
                                 ],
                                 [
                                     'attribute' => 'title',
                                     'format' => 'text',
                                     'label' => 'Title'
                                 ],
                                 [
                                     'attribute' => 'firstname',
                                     'format' => 'text',
                                     'label' => 'First Name'
                                 ],
                                 [
                                     'attribute' => 'lastname',
                                     'format' => 'text',
                                     'label' => 'Last Name'
                                 ],
                                 [
                                     'attribute' => 'final',
                                     'format' => 'text',
                                     'label' => 'Cumulative GPA'
                                 ],
                             ],
                         'fontAwesome' => true,
                         'dropdownOptions' => [
                             'label' => 'Select Export Type',
                             'class' => 'btn btn-default'
                         ],
                         'asDropdown' => false,
                         'showColumnSelector' => false,
                         'filename' => $cumulative_grade_filename,
                         'exportConfig' => [
                              ExportMenu::FORMAT_PDF => false,
                             ExportMenu::FORMAT_TEXT => false,
                             ExportMenu::FORMAT_HTML => false,
                             ExportMenu::FORMAT_EXCEL => false,
  //                                                    ExportMenu::FORMAT_EXCEL_X => false
                         ],
                     ]);
                 ?>
             <?php endif;?>


             <?php if($programme_comparison_dataprovider  && $iscape == false):?>
                 <br/><br/>
                 <p>
                     <strong>3.</strong>Click on any of the following links to download a report showing the current top
                     performers from each ASc. Programme.
                 </p>
                 <?= ExportMenu::widget([
                         'dataProvider' => $programme_comparison_dataprovider,
                         'columns' => [
                                  [
                                     'attribute' => 'division',
                                     'format' => 'text',
                                     'label' => 'Division'
                                 ],
                                 [
                                     'attribute' => 'programme',
                                     'format' => 'text',
                                     'label' => 'Programme'
                                 ],
                                 [
                                     'attribute' => 'studentid',
                                     'format' => 'text',
                                     'label' => 'Student ID'
                                 ],
                                 [
                                     'attribute' => 'title',
                                     'format' => 'text',
                                     'label' => 'Title'
                                 ],
                                 [
                                     'attribute' => 'firstname',
                                     'format' => 'text',
                                     'label' => 'First Name'
                                 ],
                                 [
                                     'attribute' => 'lastname',
                                     'format' => 'text',
                                     'label' => 'Last Name'
                                 ],
                                 [
                                     'attribute' => 'final',
                                     'format' => 'text',
                                     'label' => 'Cumulative GPA'
                                 ],
                             ],
                         'fontAwesome' => true,
                         'dropdownOptions' => [
                             'label' => 'Select Export Type',
                             'class' => 'btn btn-default'
                         ],
                         'asDropdown' => false,
                         'showColumnSelector' => false,
                         'filename' => $cumulative_grade_filename,
                         'exportConfig' => [
  //                                                    ExportMenu::FORMAT_PDF => false,
                             ExportMenu::FORMAT_TEXT => false,
                             ExportMenu::FORMAT_HTML => false,
                             ExportMenu::FORMAT_EXCEL => false,
  //                                                    ExportMenu::FORMAT_EXCEL_X => false
                         ],
                     ]);
                 ?>
             <?php endif;?><br/><br/>
             <?php if (Yii::$app->user->can('viewPerformanceReports')): ?>
                  <p>
                       <strong>4.</strong> Select the button below to generate a report that summaries the overall performance of students
                       enrolled in a particular course.
                   </p>
                   <a class="btn btn-success"
                       href=<?=Url::toRoute(['/subcomponents/programmes/programmes/generate-programme-broadsheet',
                                                               'academicofferingid' => $academicofferingid
                                                           ]);
                                   ?> role="button"> Generate Programme Summary
                   </a><br/><br/><br/>
               <?php endif;?>
  </div>
</div>
