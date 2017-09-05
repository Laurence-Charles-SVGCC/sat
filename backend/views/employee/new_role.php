<?php
    use yii\widgets\Breadcrumbs;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use backend\models\AuthItem;
    
    $this->title = "Assign New Role";
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
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="employee_full_name">Employee Name:</label>
               <span><?= $employee_full_name; ?></span>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">New Role:</label>
                <?= $form->field($new_role, 'item_name')->label('')->dropDownList(ArrayHelper::map($roles, 'name', 'name'), ['prompt'=>'Select Role', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['employee/employee-profile', 'personid' => $employee->personid], ['class' => 'btn  btn-danger']);?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>