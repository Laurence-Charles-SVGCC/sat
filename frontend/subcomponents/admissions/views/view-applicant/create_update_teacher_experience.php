<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use dosamigos\datepicker\DatePicker;
    use yii\widgets\ActiveForm;
    
    if ($action == "create")
        $this->title = 'Create New Teaching Role';
    else
        $this->title = 'Update Teaching Role';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find Applicant', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => $search_status])];
    $this->params['breadcrumbs'][] = ['label' => 'Applicant Profile', 'url' => Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'search_status' => $search_status, 'applicantusername' => $user->username])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="institutionname">Name of Institution:</label>
               <?= $form->field($experience, 'institutionname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
             <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="address">Address:</label>
               <?= $form->field($experience, 'address')->label('')->textArea(['rows' => '3', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="startdate">Start Date:</label>
               <?= $form->field($experience, 'startdate')->label(false)->widget(
                        DatePicker::className(), [
                            'inline' => false,
                            'template' => '{addon}{input}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                                "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"
                            ]
                        ]); 
               ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="dateofappointment">Date of Appointment:</label>
               <?= $form->field($experience, 'dateofappointment')->label(false)->widget(
                        DatePicker::className(), [
                            'inline' => false,
                            'template' => '{addon}{input}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                                "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"
                            ]
                        ]); 
               ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="enddate">End Date:</label>
               <?= $form->field($experience, 'enddate')->label(false)->widget(
                        DatePicker::className(), [
                            'inline' => false,
                            'template' => '{addon}{input}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                                "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"
                            ]
                        ]); 
               ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="classtaught">Class Taught:</label>
               <?= $form->field($experience, 'classtaught')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="subject">Subject(s):</label>
               <?= $form->field($experience, 'subject')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
        </div>

         <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['view-applicant/applicant-profile',  'search_status' => $search_status,  'applicantusername' => $user->username], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>