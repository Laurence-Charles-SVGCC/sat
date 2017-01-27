<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\ApplicationPeriod;

    $this->title = 'Applicant Snapshot Generator';
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/reports/snapshot']);?>" title="Snapshot Reports Home">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/>

<h2 class="text-center"><?=$this->title?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
    </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="alert in alert-block fade alert-info mainButtons" style="width:95%; margin: 0 auto">
                This report generator is intended for uses to generate a snapshot report of Applicant programme choices based on
                the name of the programme and priority of the choice.  Please select the programmes you wish to investigate from the
                checklist and their priority.
            </div>
            
            <div style="width:95%; margin: 0 auto"><br/>
                <fieldset>
                    <legend>1. Select one or more programmes for search:</legend>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= Html::checkboxList('offerings', null, $listing, []);?>
                        </div>
                    </div>
                </fieldset><br/>

                <fieldset>
                    <legend>2. Select priority of programme search:</legend>
                    <div class="row">
                        <div class="col-lg-3">
                            <?= Html::radioList('ordering', null, [1 => 'First Choice', 2 => 'Second Choice', 3 => 'Child Choice'], ['class'=> 'form_field']);?>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div> 
                
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton('Search', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>