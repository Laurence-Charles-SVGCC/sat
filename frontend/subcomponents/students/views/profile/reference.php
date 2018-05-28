<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use dosamigos\datepicker\DatePicker;
    use yii\widgets\ActiveForm;
    
    $titles = [
            '' => 'Title', 
            'Mr' => 'Mr',
            'Ms' => 'Ms', 
            'Mrs' => 'Mrs'
        ];
    
    if ($action == "add")
        $this->title = 'Add Reference';
    elseif ($action == "edit")
        $this->title = 'Update Reference';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find An Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = ['label' => 'Student Profile', 'url' => Url::toRoute(['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Title:</label>
               <?= $form->field($reference, 'title')->label('')->dropDownList($titles, ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="firstname">First Name:</label>
               <?= $form->field($reference, 'firstname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="lastname">Last Name:</label>
               <?= $form->field($reference, 'lastname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="address">Address:</label>
               <?= $form->field($reference, 'address')->label('')->textArea(['rows' => '3', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="occupation">Occupation:</label>
               <?= $form->field($reference, 'occupation')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="contactnumber">Contact Number:</label>
               <?= $form->field($reference, 'contactnumber')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn  btn-danger']);?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>