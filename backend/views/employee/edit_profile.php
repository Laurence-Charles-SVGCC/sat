<?php
    use yii\widgets\Breadcrumbs;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $this->title = "Edit";
    $this->params['breadcrumbs'][] = ['label' => 'User Listing', 'url' => Url::toRoute(['/user/index'])];
    $this->params['breadcrumbs'][] = ['label' => 'Employee Profile', 'url' => Url::toRoute(['/employee/employee-profile', 'personid' => $employee->personid])];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/user/index']);?>" title="User Management Home">
        <h1>Welcome to the User Management System</h1>
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
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="username">Username:</label>
               <span><?= $username;?></span>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Title:</label>
               <?= $form->field($employee, 'title')->label('')->dropDownList(Yii::$app->params['titles'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="firstname">First Name :</label>
               <?= $form->field($employee, 'firstname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="middlename">Middle Name:</label>
               <?= $form->field($employee, 'middlename')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="lastname">Last Name:</label>
               <?= $form->field($employee, 'lastname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="gender">Gender:</label>
               <?= $form->field($employee, 'gender')->label('')->dropDownList(['male' => 'Male', 'female' => 'Female'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="dateofbirth">Date of Birth:</label>
               <?= $form->field($employee, 'dateofbirth')->label(false)->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="maritalstatus">Marital Status:</label>
               <?= $form->field($employee, 'maritalstatus')->label('')->radioList(Yii::$app->params['maritalstatus'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="nationality">Nationality:</label>
               <?= $form->field($employee, 'nationality')->label('')->dropDownList(Yii::$app->params['nationality'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="placeofbirth">Place of Birth:</label>
               <?= $form->field($employee, 'placeofbirth')->label('')->dropDownList(Yii::$app->params['placeofbirth'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="religion">Religion:</label>
               <?= $form->field($employee, 'religion')->label('')->dropDownList(Yii::$app->params['religion'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="nationalidnumber">National ID NUmber:</label>
               <?= $form->field($employee, 'nationalidnumber')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="nationalinsurancenumber">National Insurance Service Number:</label>
               <?= $form->field($employee, 'nationalinsurancenumber')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="inlandrevenuenumber">Inland Revenue Number:</label>
               <?= $form->field($employee, 'inlandrevenuenumber')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['employee/employee-profile', 'personid' => $employee->personid], ['class' => 'btn  btn-danger']);?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>