<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use backend\models\AuthItem;
    
    $this->title = 'Assign Role';
    $this->params['breadcrumbs'][] = ['label' => 'Role Assignments', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/auth-assignment/index']);?>" title="Access Management Home">
        <h1>Welcome to the Access Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:99%; margin: 0 auto;">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title;?></span>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="item_name">Role:</label>
               <?= $form->field($model, 'item_name')->label('')->dropDownList(ArrayHelper::map(AuthItem::find()->where(['type' => 1])->all(), 'name', 'name'), ['prompt'=>'Select Role..', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
          </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="user_id">Employee Name:</label>
               <?= $form->field($model, 'user_id')->label('')->dropDownList($employees, ['prompt'=>'Select employee..', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['index'], ['class' => 'btn  btn-danger']);?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>




