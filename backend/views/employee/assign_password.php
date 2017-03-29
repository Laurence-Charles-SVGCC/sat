<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use yii\widgets\ActiveForm;
    
    $this->title = 'Password Assignment';
    
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
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="userid">Employee:</label>
               <?=$form->field($model, 'userid')->label('')->dropDownList($employees, ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]); ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="password">Password :</label>
               <?= $form->field($model, 'password')->label('')->passwordInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="confirm_password">Confirm Password:</label>
               <?= $form->field($model, 'confirm_password')->label('')->passwordInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
        </div>
            
        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['user/index'], ['class' => 'btn  btn-danger']);?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>