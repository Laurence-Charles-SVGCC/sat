<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\BatchStudentCape;
    use frontend\models\StudentRegistration;
    
    $this->title = 'Edit Course Totals';
    
    $this->params['breadcrumbs'][] = ['label' => 'Student Profile', 'url' => Url::to(['gradebook/transcript', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
    </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="course-code">Course Code</label>
               <p class = "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"><?= $course_summary['code']?></p>
           </div>
            
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="course-name">Course Name</label>
               <p class = "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"><?= $course_summary['name']?></p>
           </div>
            
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="credits-unit">Credits Unit</label>
               <p class = "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"><?= $course_summary['unit']?></p>
           </div>
            
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="subject">Subject</label>
               <p class = "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"><?= $course_summary['subject']?></p>
           </div>
            
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="coursework-weight">Coursework (<?=$course_summary['courseworkweight'];?>)</label>
               <?= $form->field($course_record, 'courseworktotal')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="exam-weight">Exam (<?=$course_summary['examweight']?>)</label>
               <?= $form->field($course_record, 'examtotal')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="final">Final</label>
               <p class = "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"><?=$course_summary['final']?></p>
           </div>
        </div>
        
    
         <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['edit-transcript-cancel', 'batchid' => $course_summary['batchid'], 'studentregistrationid' => $course_summary['studentregistrationid']], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>