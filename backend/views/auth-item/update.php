<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use backend\models\AuthRule;

    if ($type == 1)
    {
        $this->title = 'Update Role';
        $this->params['breadcrumbs'][] = ['label' => 'Roles Listing', 'url' => ['index', 'type' => 'Roles']];
    }
    elseif ($type == 2)
    {
        $this->title = "Update Permission";
        $this->params['breadcrumbs'][] = ['label' => 'Permissions Listing', 'url' => ['index', 'type' => 'Permissions']];
    }
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/auth-item/index', 'type' => $type]);?>" title="User Management Home">
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
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">Name:</label>
               <?= $form->field($model, 'name')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="description">Description:</label>
               <?= $form->field($model, 'description')->label('')->textarea(['rows' => 6, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
          
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="rule_name">Rule Name :</label>
               <?= $form->field($model, 'rule_name')->label('')->dropDownList(ArrayHelper::map(AuthRule::find()->all(), 'name', 'name'), ['prompt'=>'Select Rule', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="data">Data:</label>
               <?= $form->field($model, 'data')->label('')->textarea(['rows' => 6, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
           </div>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?php if($type == 1): ?>
                <?= Html::a(' Cancel', ['index', 'type' => "Roles"], ['class' => 'btn  btn-danger']);?>
            <?php elseif ($type == 2): ?>
                <?= Html::a(' Cancel', ['index', 'type' => "Permissions"], ['class' => 'btn  btn-danger']);?>
            <?php endif;?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>