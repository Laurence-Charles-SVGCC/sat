<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\Institution;
    
    $graduated = [
                '' => 'Select..',
                1 => 'Yes',
                0 => 'No'
    ];
    
    $level = NULL;
    if($levelid == 1)
        $level = "Pre School";
    elseif($levelid == 2)
        $level = "Primary School";
    elseif($levelid == 3)
        $level = "Secondary School";
    elseif($levelid == 4)
        $level = "Tertiary School";
    
    $this->title = 'Add Institution';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find An Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = ['label' => 'Student Profile', 'url' => Url::toRoute(['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">
        <h1>Welcome to the Student Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="institutionid">Name:</label>
               <?= $form->field($school, 'institutionid')->label('')->dropDownList(Institution::initializeSchoolList($levelid), ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="startdate">Start Date:</label>
               <?= $form->field($school, 'startdate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true,'format' => 'yyyy-mm-dd', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="enddate">End Date:</label>
               <?= $form->field($school, 'enddate')->label('')->widget(DatePicker::className(), ['inline' => false,'template' => '{addon}{input}','clientOptions' => ['autoclose' => true,'format' => 'yyyy-mm-dd', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="hasgraduated">Has student graduated from this institution?</label>
               <?= $form->field($school, 'hasgraduated')->label('')->dropDownList($graduated, ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn  btn-danger']);?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>