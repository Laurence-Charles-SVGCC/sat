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
    
    $status = [
        '' => 'Select Status',
        5 => 'active',
        6 => 'close'
    ];
    
    $iscomplete = [
        '' => 'Choose Status...',
        0 => 'No',
        1 => 'Yes'
    ];
    
    $type = [
        '' => 'Select Type',
        1 => 'Full-time Enrollment',
        2 => 'Part-time Enrollment'
    ];
    
    $this->title = 'Edit Application Period';
    
    $this->params['breadcrumbs'][] = ['label' => 'Period Listing', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/manage-application-period'])];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/manage-application-period']);?>" title="Manage Application Periods">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

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
               <?= $form->field($period, 'divisionid')->label('')->dropDownList(ArrayHelper::map(Division::find()
                                                                                                                                                                    ->where(['divisionid' => [4,5,6,7]])
                                                                                                                                                                    ->all(), 'divisionid', 'name'),       
                                                                                                                                                                    ['prompt'=>'Select Division',
                                                                                                                                                                        'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9'
                                                                                                                                                                    ]);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="academicyearid">Year:</label>
               <?= $form->field($period, 'academicyearid')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="onsitestartdate">On-site Start Date:</label>
               <?= $form->field($period, 'onsitestartdate')->label(false)->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="onsiteenddate">On-site End Date:</label>
               <?= $form->field($period, 'onsiteenddate')->label(false)->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="offsitestartdate">Off-site Start Date:</label>
               <?= $form->field($period, 'offsitestartdate')->label(false)->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="offsiteenddate">Off-site End Date:</label>
               <?= $form->field($period, 'offsiteenddate')->label(false)->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="applicationperiodtypeid">Type:</label>
               <?= $form->field($period, 'applicationperiodtypeid')->label('')->dropDownList($type, ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="applicationperiodstatusid">Status:</label>
               <?= $form->field($period, 'applicationperiodstatusid')->label('')->dropDownList($status, ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="iscomplete">Hide:</label>
               <?= $form->field($period, 'iscomplete')->label('')->dropDownList($iscomplete, ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
        </div>
    
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Update', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['admissions/manage-application-period'], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>