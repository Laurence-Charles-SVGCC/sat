<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

     $this->title = 'Create Academic Year';
     $this->params['breadcrumbs'][] = ['label' => 'Academic Year Listing', 'url' => ['index']];
     $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/legacy/year/index']);?>" title="Legacy Years">
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
            <div class="alert in alert-block fade alert-info text-center">
                <strong>N.B : The academic year names must conform to the following format YYYY/YYYY e.g 1990/1991</strong>
            </div>
                
            <?php if($saved_years):?>
                <p>
                    Please ensure that you do not create a duplicate academic year record.  Please 
                    find below a list of all the academic years that have been created thus far;
                </p>

                <ol>
                    <?php foreach($saved_years as $record):?>
                        <li><?=$record;?></li>
                    <?php endforeach;?>
                </ol>
            <?php endif;?>
                        
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">Year Title:</label>
               <span><?=$form->field($year, 'name')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></span>
           </div>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['year/index'],  ['class' => 'btn btn-danger'] );?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>