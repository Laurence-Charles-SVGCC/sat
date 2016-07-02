<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\ApplicationPeriod;
    
    $this->title = 'Unregistered Dashboard';
    
?>

<div class="report-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
            
            <?php
                $form = ActiveForm::begin(
                    [
                        'action' => Url::to(['reports/get-unregistered-applicants']),
                    ]); 
            ?>
            
                <div style="margin-left:2.5%"><br/>
                    <div id="unregistered-applicant-application-period">
                        <?= Html::label('Please select the application period you wish to investigate: ', 'unregistered_period_label'); ?>
                        <?= Html::dropDownList('applicationperiod',  "Select...", $periods, ['id' => 'unregistered_period_field', 'onchange' => 'toggleUnregisteredSearchButton();']) ; ?>
                    </div></br>
                    
                    <div id="unregistered-applicant-submit-button"  style="display:none">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: left']) ?>
                    </div>
                
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

    

