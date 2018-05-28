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
  
    $this->title = 'Application Period Setup Step-2';
   
    $this->params['breadcrumbs'][] = ['label' => 'Period Listing', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/manage-application-period'])];
    $this->params['breadcrumbs'][] = ['label' => 'Setup Dashboard', 'url' => Url::toRoute(['admissions/initiate-period', 'recordid' => $period->applicationperiodid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title">Configure Application Period</span>
    </div>
     
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
             <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">Name:</label>
                <?= $form->field($template_period, 'name')->label('', ['class'=> 'form-label'])->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="divisionid">Division:</label>
                <?= $form->field($template_period, 'divisionid')->label('')->dropDownList(ArrayHelper::map(Division::find()->where(['abbreviation' => ["DASGS", "DTVE", "DTE", "DNE"]])->all(), 'divisionid', 'abbreviation'), ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="academicyearid">Academic Year:</label>
                <?= $form->field($template_period, 'academicyearid')->label('')->dropDownList(ArrayHelper::map(AcademicYear::getAllAcademicYears(), 'academicyearid', 'title'), ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="onsitestartdate">On-campus Start Date:</label>
                <?= $form->field($template_period, 'onsitestartdate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="onsiteenddate">On-campus End Date:</label>
                <?= $form->field($template_period, 'onsiteenddate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="offsitestartdate">Off-campus Start Date:</label>
                <?= $form->field($template_period, 'offsitestartdate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="offsiteenddate">Off-campus End Date:</label>
                <?= $form->field($template_period, 'offsiteenddate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="applicationperiodtypeid">Full/PArt Time:</label>
                <?= $form->field($template_period, 'applicationperiodtypeid')->label('')->dropDownList(ArrayHelper::map(ApplicationPeriodType::find()->all(), 'applicationperiodtypeid', 'name'), ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
        </div>
        
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Update', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['admissions/initiate-period', 'recordid' => $period->applicationperiodid], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>