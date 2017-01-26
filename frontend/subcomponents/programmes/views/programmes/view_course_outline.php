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
    $this->params['breadcrumbs'][] = $this->title;
    
    $levels = [
        "1" => "1",
        "2" => "2",
        "3" => "3",
    ];
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/registry/withdrawal/index']);?>" title="Withdrawl Controller">
        <h1>Welcome to the Withdrawal Management</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Course Code:</label>
                <?=$form->field($outline, 'code')->label('')->textInput(['disabled' => true, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
            </div>
            
             <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Course Name:</label>
                 <?=$form->field($outline, 'name')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'disabled' => true]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Credits:</label>
                 <?=$form->field($outline, 'credits')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'disabled' => true]);?>
             </div>
            
             <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Level</label>
                 <?=$form->field($outline, 'level')->label('')->dropDownList(($levels), ['prompt'=>'Select Course Level', 'disabled' => true, 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
             </div>

            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Pre-requisites:</label>
                 <?=$form->field($outline, 'prerequisites')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'disabled' => true]);?>
             </div>, 
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Co-requisities:</label>
                 <?=$form->field($outline, 'corequisites')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'disabled' => true]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Semesters Delivered:</label>
                 <td><?=$form->field($outline, 'deliveryperiod')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'disabled' => true]);?></td>
            </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Course Provider:</label>
                 <?=$form->field($outline, 'courseprovider')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'disabled' => true]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Total Study Hours:</label>
                 <?=$form->field($outline, 'totalstudyhours')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 5, 'disabled' => true]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Course Description:</label>
                 <?=$form->field($outline, 'description')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 7, 'disabled' => true]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Course Rationale:</label>
                 <?=$form->field($outline, 'rational')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 7, 'disabled' => true]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Learning Outcomes:</label>
                 <?=$form->field($outline, 'outcomes')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 10, 'disabled' => true]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Content:</label>
                 <?=$form->field($outline, 'content')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 10, 'disabled' => true]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Teaching Methodology:</label>
                <?=$form->field($outline, 'teachingmethod')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 3, 'disabled' => true]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Assessment Method:</label>
                 <?=$form->field($outline, 'assessmentmethod')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 12, 'disabled' => true]);?>
             </div>
            
            <div class="form-group">
                 <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Learning Resources:</label>
                 <?=$form->field($outline, 'resources')->label('')->textArea(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'rows' => 12, 'disabled' => true]);?>
             </div>
        </div>
   
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::a(' Back',
                            ['programmes/programme-overview', 'programmecatalogid' => $programmecatalogid],
                            ['class' => 'btn btn-danger']
                            );
                ?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>