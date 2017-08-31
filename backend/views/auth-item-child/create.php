<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use backend\models\AuthItem;
    
    $this->title = $title;
    $this->params['breadcrumbs'][] = ['label' => 'Role Assignments', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/auth-item-child/index']);?>" title="Access Management Home">
        <h1>Welcome to the Access Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:99%; margin: 0 auto;">
    <div class="box-header with-border">
        <?php  if ($type == "assign-role-to-role"):?>
           <span class="box-title"> Build Role Hierarchy</span>
        <?php elseif ($type == "assign-permission-to-role"):?>
            <span class="box-title"> Increase Role Responsibilities</span>
        <?php endif;?>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="parent">Parent:</label>
               <?= $form->field($model, 'parent')->label('')->dropDownList(ArrayHelper::map($parents, 'name', 'name'), ['prompt'=>'Select Parent..', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="child">Child:</label>
               <?= $form->field($model, 'child')->label('')->dropDownList(ArrayHelper::map($children, 'name', 'name'), ['prompt'=>'Select Child..', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['index'], ['class' => 'btn  btn-danger']);?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>
