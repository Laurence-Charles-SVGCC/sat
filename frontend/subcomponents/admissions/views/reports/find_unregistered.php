<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\ApplicationPeriod;
    
    $this->title = 'Unregistered Dashboard';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find A Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin(['action' => Url::to(['reports/get-unregistered-applicants'])]);?>
        <div class="box-body">
            <div class="form-group">
               <?= Html::label('Please select the application period you wish to investigate: ', 'unregistered_period_label'); ?>
               <?= Html::dropDownList('applicationperiod',  "Select...", $periods, ['id' => 'unregistered_period_field', 'onchange' => 'toggleUnregisteredSearchButton();']) ; ?>                              
           </div>
         </div>
   
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton('Generate', ['class' => 'btn btn-md btn-success', "id" => "unregistered-applicant-submit-button",  "style" => "display:none"]) ?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>