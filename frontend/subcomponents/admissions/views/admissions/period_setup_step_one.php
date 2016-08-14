<?php

/* 
 * Author: Laurence Charles
 * Date Created 05/08/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\ApplicationPeriodType;

    $this->title = 'Application Period Setup Step-1';
?>

<?php header('Access-Control-Allow-Origin: *'); ?>

<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">  
            <h1 class="custom_h1">Confirm Academic Year Availability </h1>
            
            <br/>
            <div style="width:70%; margin: 0 auto; font-size: 20px;">
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'inititate-application-period-form',
                        'options' => [
                                            'class' => 'form-layout'
                        ],
                    ]);
                ?>
                    <br/>
                     <?= Html::hiddenInput('applicationPeriodCreation_baseUrl', Url::home(true)); ?>
                    <?=$form->field($period, 'divisionid')->label('Division')->dropDownList(ArrayHelper::map(Division::find()->where(['abbreviation' => ["DASGS", "DTVE", "DTE", "DNE"]])->all(), 'divisionid', 'abbreviation'), ['prompt'=>'Select Division', 'onchange' => 'displayPeriodType()']);?>
                    
                    <div id="applicationperiodtypeid-field" style="display:none">
                        <?=$form->field($period, 'applicationperiodtypeid')->label('Period Type')->dropDownList(ArrayHelper::map(ApplicationPeriodType::find()->all(), 'applicationperiodtypeid', 'name'), ['prompt'=>'Select Type', 'onchange' => 'calculateApplicantIntent(event);']);?>
                    </div>
                   
                    <div id="application-period-exists-alert" class="alert in alert-block fade alert-success mainButtons" style = "display:none">
                        An application period matching the selected division and application period type already exists.
                    </div> 
                    
                    <div id="new-year-options" style = "display:none">
                        <br/>
                        <p>Enter academic year record:</p>
                        <?= $form->field($new_year, 'title')->label('Title', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'style' => 'vertical-align:middle']);?>
                        
                        <?= $form->field($new_year, 'startdate')->label('Start Date')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]) ;?>
                        
                        <?= $form->field($new_year, 'enddate')->label('End Date')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?>
                    </div>
                    
                    <div id="buttons" style="display:none">
                        <hr>
                        <?= Html::a(' Cancel',['admissions/initiate-period', 'recordid' => $period->applicationperiodid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-right:15%;']);?>
                        <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-lg btn-success', 'style' => 'width:25%; margin-right:15%;', 'onclick' => 'generateAcademicYearBlanks();']);?>
                    </div>
                <?php ActiveForm::end()?>
            </div>
        </div>
    </div>
</div>



