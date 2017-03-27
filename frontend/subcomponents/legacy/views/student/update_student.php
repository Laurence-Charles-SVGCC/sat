<?php
     use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\LegacyYear;
    use frontend\models\LegacyFaculty;

    $this->title = 'Update Student Record';
    $this->params['breadcrumbs'][] = ['label' => 'Legacy Students', 'url' => ['student/find-a-student']];
    $this->params['breadcrumbs'][] = ['label' => 'Student Profile', 'url' => ['student/view', 'id' => $student->legacystudentid]];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/legacy/student/find-a-student']);?>" title="Legacy Student Home">
        <h1>Welcome to the Legacy Management System</h1>
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
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Title:</label>
               <span><?=$form->field($student, 'title')->label('')->dropDownList(Yii::$app->params['titles'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></span>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="firstname">First Name:</label>
               <span><?=$form->field($student, 'firstname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></span>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="middlename">Middle Name:</label>
               <span><?=$form->field($student, 'middlename')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></span>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="lastname">Last Name:</label>
               <span><?=$form->field($student, 'lastname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></span>
           </div>
            
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="dateofbirth">Date of Birth:</label>
               <span><?=$form->field($student, 'dateofbirth')->label(false)->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?></span>
           </div>
            
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="address">Address:</label>
               <span><?=$form->field($student, 'address')->label('', ['class'=> 'form-label'])->textArea(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9", 'rows' =>3]);?></span>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="gender">Gender:</label>
               <span><?=$form->field($student, 'gender')->label('')->dropDownList(['' => 'Select Gender', 'Male' => 'Male', 'Female' => 'Female'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></span>
           </div>     
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="legacyyearid">Admission Year:</label>
               <span><?=$form->field($student, 'legacyyearid')->label('')->dropDownList(ArrayHelper::map(LegacyYear::find()->all(), 'legacyyearid', 'name'), ['prompt'=>'Select the admission year of student..', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></span>
           </div>   
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="legacyfacultyid">Faculty:</label>
               <span><?=$form->field($student, 'legacyfacultyid')->label('')->dropDownList(ArrayHelper::map(LegacyFaculty::find()->all(), 'legacyfacultyid', 'name'), ['prompt'=>'Select Faculty..', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></span>
           </div>   
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['student/view', 'id' => $student->legacystudentid],  ['class' => 'btn btn-danger'] );?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>