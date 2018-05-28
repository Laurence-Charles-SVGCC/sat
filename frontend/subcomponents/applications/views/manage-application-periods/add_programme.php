<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    
    $this->title = 'Add Programme';
    $this->params['breadcrumbs'][] = ['label' => 'Application Periods', 'url' => Url::toRoute(['/subcomponents/applications/application-periods/view-periods'])];
    $this->params['breadcrumbs'][] = ['label' => $period->name, 'url' => Url::toRoute(['/subcomponents/applications/manage-application-periods/view-application-period', 'id' => $period->applicationperiodid])];
    
    if ($id == NULL)
    {
         $this->params['breadcrumbs'][] = ['label' => 'Manage Programmes', 'url' => Url::toRoute(['/subcomponents/applications/manage-application-periods/period-setup-step-three'])];
     }
     else
     {
         $this->params['breadcrumbs'][] = ['label' => 'Manage Programmes', 'url' => Url::toRoute(['/subcomponents/applications/manage-application-periods/manage-programme-offerings', 'id' => $period->applicationperiodid])];
     }
    
//    $this->params['breadcrumbs'][] = ['label' => 'Manage Programmes', 'url' => Url::toRoute(['/subcomponents/applications/manage-application-periods/manage-programme-offerings', 'id' => $period->applicationperiodid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body"> 
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='name'>Name:</label>
                <?= $form->field($programme, 'name')
                        ->label('')
                        ->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='specialisation'>Specialisation:</label>
                <?= $form->field($programme, 'specialisation')
                        ->label('')
                        ->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='programmetypeid'>Programme Type:</label>
                <?= $form->field($programme, 'programmetypeid')
                        ->label('')
                        ->dropDownList(ArrayHelper::map($intent_types, 'intenttypeid', 'description'), 
                                ['prompt'=>'Select Programme Type', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='qualificationtypeid'>Qualification Type:</label>
                <?= $form->field($programme, 'qualificationtypeid')->label('')->dropDownList(ArrayHelper::map($qualification_types, 'qualificationtypeid', 'name'), 
                        ['prompt'=>'Select Qualification Type', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
             <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='examinationbodyid'>Examination Body:</label>
                <?= $form->field($programme, 'examinationbodyid')
                        ->label('')
                        ->dropDownList(ArrayHelper::map($examination_bodies, 'examinationbodyid', 'name'), 
                                ['prompt'=>'Select Examination Body', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='departmentid'>Department:</label>
                <?= $form->field($programme, 'departmentid')
                        ->label('')
                        ->dropDownList(ArrayHelper::map($departments, 'departmentid', 'name'), 
                                ['prompt'=>'Select Department', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);
                ?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='duration'>Duration:</label>
                <?= $form->field($programme, 'duration')
                        ->label('', ['class'=> 'form-label'])
                        ->dropDownList($duration, ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
        </div>
   
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                
                 <?php if ($id == NULL): ?>
                    <?= Html::a(' Cancel',['manage-application-periods/period-setup-step-three'], ['class' => 'btn btn-danger']);?>
                <?php else: ?>
                    <?= Html::a(' Cancel',['manage-application-periods/manage-programme-offerings', 'id' => $period->applicationperiodid], ['class' => 'btn btn-danger']);?>
                <?php endif;?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>