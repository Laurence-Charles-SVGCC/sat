<?php
    use dosamigos\datepicker\DatePicker;
    use yii\widgets\Breadcrumbs;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use yii\widgets\ActiveForm;
    use yii\data\ArrayDataProvider;
    
    use frontend\models\PersonType;
    use frontend\models\Department;
    use frontend\models\EmployeeTitle;
    
    $this->title = 'Create Lecturer Account';
    
    $this->params['breadcrumbs'][] = ['label' => 'User Listing', 'url' => Url::toRoute(['/user/index'])];
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
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Title:</label>
               <?= $form->field($model, 'title')->label('')->dropDownList(Yii::$app->params['titles'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="firstname">First Name :</label>
               <?= $form->field($model, 'firstname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="middlename">Middle Name:</label>
               <?= $form->field($model, 'middlename')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="lastname">Last Name:</label>
               <?= $form->field($model, 'lastname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="institutional_email">Institution Email:</label>
               <?= $form->field($model, 'institutional_email')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="personal_email">Personal Email:</label>
               <?= $form->field($model, 'personal_email')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="departmentid">Department:</label>
               <?= $form->field($model, 'departmentid')->label('')->dropDownList(ArrayHelper::map(Department::findAll(['isactive' => 1, 'isdeleted' => 0]), 'departmentid', 'name'), ["prompt" => "Select Department...", "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="employeetitleid">Job Title:</label>
               <?= $form->field($model, 'employeetitleid')->label('')->dropDownList(ArrayHelper::map(EmployeeTitle::findAll(['isactive' => 1, 'isdeleted' => 0]), 'employeetitleid', 'name'), ["prompt" => "Select Job Title...", "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="gender">Gender:</label>
               <?= $form->field($model, 'gender')->label('')->dropDownList(['male' => 'Male', 'female' => 'Female'], ["prompt" => "Select Gender...", "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="dateofbirth">Date of Birth:</label>
               <?= $form->field($model, 'dateofbirth')->label(false)->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="maritalstatus">Marital Status:</label>
               <?= $form->field($model, 'maritalstatus')->label('')->radioList(Yii::$app->params['maritalstatus'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="nationality">Nationality:</label>
               <?= $form->field($model, 'nationality')->label('')->dropDownList(Yii::$app->params['nationality'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="placeofbirth">Place of Birth:</label>
               <?= $form->field($model, 'placeofbirth')->label('')->dropDownList(Yii::$app->params['placeofbirth'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="religion">Religion:</label>
               <?= $form->field($model, 'religion')->label('')->dropDownList(Yii::$app->params['religion'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="nationalidnumber">National ID Number:</label>
               <?= $form->field($model, 'nationalidnumber')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="nationalinsurancenumber">NIS Number:</label>
               <?= $form->field($model, 'nationalinsurancenumber')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="inlandrevenuenumber">Inland Revenue Number:</label>
               <?= $form->field($model, 'inlandrevenuenumber')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="username">Username (if assigned) :</label>
               <?= $form->field($model, 'username')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['user/index'], ['class' => 'btn  btn-danger']);?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>

