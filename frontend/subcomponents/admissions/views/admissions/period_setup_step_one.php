<?php
    use yii\widgets\Breadcrumbs;
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
    
    $this->params['breadcrumbs'][] = ['label' => 'Period Listing', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/manage-application-period'])];
    $this->params['breadcrumbs'][] = ['label' => 'Setup Dashboard', 'url' => Url::toRoute(['admissions/initiate-period', 'recordid' => $period->applicationperiodid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/package']);?>" title="Manage Packages">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<h2 class="text-center">Confirm Academic Year Availability</h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em; width:80%; margin: 0 auto; font-size: 20px;">
    <br/>
    <?php $form = ActiveForm::begin(['id' => 'inititate-application-period-form',
                                                            'options' => ['class' => 'form-layout'],]);
    ?>
        <div class="box-body">
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
        </div>
        
         <div class="box-footer" id="buttons" style="display:none">
            <span class = "pull-right">
                <?= Html::submitButton(' Update', ['class' => 'btn btn-success', 'style' => 'margin-right:20px', 'onclick' => 'generateAcademicYearBlanks();']);?>
                <?= Html::a(' Cancel', ['admissions/initiate-period', 'recordid' => $period->applicationperiodid], ['class' => 'btn  btn-danger', ]);?>
            </span>
        </div>
    <?php ActiveForm::end()?><br/>
</div>