<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    
    $this->title = 'Edit';
    $this->params['breadcrumbs'][] = ['label' => 'Application Periods', 'url' => Url::toRoute(['/subcomponents/applications/application-periods/view-periods'])];
    $this->params['breadcrumbs'][] = ['label' => $period->name, 'url' => Url::toRoute(['/subcomponents/applications/manage-application-periods/view-application-period', 'id' => $period->applicationperiodid])];
    $this->params['breadcrumbs'][] = $this->title;
?>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
    </div>
     
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
             <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">Title:</label>
               <?= $form->field($period, 'name')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="divisionid">Division:</label>
               <?= $form->field($period, 'divisionid')
                       ->label('')->dropDownList(ArrayHelper::map($divisions, 'divisionid', 'name'),       
                                                                    ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="academicyearid">Year:</label>
               <?= $form->field($period, 'academicyearid')
                      ->label('')->dropDownList(ArrayHelper::map($academic_years, 'academicyearid', 'title'),       
                                                                 ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="onsitestartdate">On-site Start Date:</label>
               <?= $form->field($period, 'onsitestartdate')->label(false)->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?>
            </div><br/>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="onsiteenddate">On-site End Date:</label>
               <?= $form->field($period, 'onsiteenddate')->label(false)->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?>
            </div><br/>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="offsitestartdate">Off-site Start Date:</label>
               <?= $form->field($period, 'offsitestartdate')->label(false)->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?>
            </div><br/>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="offsiteenddate">Off-site End Date:</label>
               <?= $form->field($period, 'offsiteenddate')->label(false)->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?>
            </div><br/>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="applicationperiodtypeid">Type:</label>
               <?= $form->field($period, 'applicationperiodtypeid')->label('')->dropDownList([1 => 'Full-time Enrollment', 2 => 'Part-time Enrollment'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="applicationperiodstatusid">Status:</label>
               <?= $form->field($period, 'applicationperiodstatusid')->label('')->dropDownList([ 5 => 'open',  6 => 'closed'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="iscomplete">Applicants Visible:</label>
               <?= $form->field($period, 'iscomplete')->label('')->dropDownList([1 => 'No', 0 => 'Yes'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
        </div>
    
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Update', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['/subcomponents/applications/manage-application-periods/view-application-period', 'id' => $period->applicationperiodid], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>