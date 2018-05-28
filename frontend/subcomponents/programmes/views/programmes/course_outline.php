<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use common\models\User;
    use frontend\models\CourseOutline;
    
    $this->title = $action . ' Course Outline';
    $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => 'Programme Overview', 'url' => Url::to(['programmes/programme-overview',
                                                            'programmecatalogid' => $programmecatalogid
                                                            ])];
    $this->params['breadcrumbs'][] = ['label' => 'Academic Offering Overview', 'url' => Url::to(['programmes/get-academic-offering',
                                                            'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid
                                                            ])];
    $this->params['breadcrumbs'][] = ['label' => 'Course Management', 'url' => Url::to(['programmes/course-management',
                                                            'iscape' => $iscape, 'programmecatalogid' => $programmecatalogid, 
                                                            'academicofferingid' => $academicofferingid, 'code' => $code
                                                            ])];
    $this->params['breadcrumbs'][] = $this->title;
    
    $levels = [
        "1" => "1",
        "2" => "2",
        "3" => "3",
    ];
?>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>

    <?php $form = ActiveForm::begin();?>
        <div class="box-body"> 
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Course Code:</label>
                <?=$form->field($outline, 'code')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
            </div>
            
             <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Course Name:</label>
                 <?=$form->field($outline, 'name')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Credits:</label>
                 <?=$form->field($outline, 'credits')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
             </div>
            
             <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Level</label>
                 <?=$form->field($outline, 'level')->label('')->dropDownList(($levels), ['prompt'=>'Select Course Level', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
             </div>

            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Pre-requisites:</label>
                 <?=$form->field($outline, 'prerequisites')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
             </div>, 
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Co-requisities:</label>
                 <?=$form->field($outline, 'corequisites')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Semesters Delivered:</label>
                 <td><?=$form->field($outline, 'deliveryperiod')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?></td>
            </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Course Provider:</label>
                 <?=$form->field($outline, 'courseprovider')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Total Study Hours:</label>
                 <?=$form->field($outline, 'totalstudyhours')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 5]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Course Description:</label>
                 <?=$form->field($outline, 'description')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 7]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Course Rationale:</label>
                 <?=$form->field($outline, 'rational')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 7]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Learning Outcomes:</label>
                 <?=$form->field($outline, 'outcomes')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 10]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Content:</label>
                 <?=$form->field($outline, 'content')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 10]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Teaching Methodology:</label>
                <?=$form->field($outline, 'teachingmethod')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 3]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Assessment Method:</label>
                 <?=$form->field($outline, 'assessmentmethod')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 12]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Learning Resources:</label>
                 <?=$form->field($outline, 'resources')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 12]);?>
             </div>
        </div>
   
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['programmes/course-management','iscape' => $iscape,  'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid, 'code' => $code],
                                ['class' => 'btn btn-danger']);
                ?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>