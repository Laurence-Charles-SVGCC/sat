<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use yii\helpers\ArrayHelper;
    use yii\jui\DatePicker;
    
    $this->title = 'Application Period Setup Step-2';
    $this->params['breadcrumbs'][] = ['label' => 'Period Listing', 'url' => Url::toRoute(['/subcomponents/applications/application-periods/view-periods'])];
    $this->params['breadcrumbs'][] = ['label' => 'Setup Dashboard', 'url' => Url::toRoute(['initiate-period', 'id' => $period->applicationperiodid])];
    $this->params['breadcrumbs'][] = $this->title;
?>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title">Configure Application Period</span>
    </div>
     
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
             <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">Name:</label>
                <?= $form->field($template_period, 'name')
                                ->label('')
                                ->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="divisionid">Division:</label>
                <?= $form->field($template_period, 'divisionid')
                                 ->label('')
                                 ->dropDownList(ArrayHelper::map($divisions, 'divisionid', 'abbreviation'), ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="academicyearid">Academic Year:</label>
                <?= $form->field($template_period, 'academicyearid')
                                ->label('')
                                ->dropDownList(ArrayHelper::map($academic_years, 'academicyearid', 'title'), 
                                        ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="onsitestartdate">On-campus Start Date:</label>
                <?= $form->field($template_period, 'onsitestartdate')
                                 ->label('')
                                 ->widget(DatePicker::classname(), 
                                                        ['clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]) ?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="onsiteenddate">On-campus End Date:</label>
                <?= $form->field($template_period, 'onsiteenddate')
                                ->label('')
                                ->widget(DatePicker::classname(), 
                                                        ['clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]) ?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="offsitestartdate">Off-campus Start Date:</label>
                <?= $form->field($template_period, 'offsitestartdate')
                                ->label('')
                                ->widget(DatePicker::classname(), 
                                                        ['clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]) ?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="offsiteenddate">Off-campus End Date:</label>
                <?= $form->field($template_period, 'offsiteenddate')
                                 ->label('')
                                 ->widget(DatePicker::classname(), 
                                                        ['clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]) ?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="applicationperiodtypeid">Full/Part Time:</label>
                <?= $form->field($template_period, 'applicationperiodtypeid')
                                ->label('')
                                ->dropDownList(ArrayHelper::map($application_period_types, 'applicationperiodtypeid', 'name'), 
                                        ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
        </div>
        
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Update', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['initiate-period', 'id' => $period->applicationperiodid], ['class' => 'btn  btn-danger',]);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>