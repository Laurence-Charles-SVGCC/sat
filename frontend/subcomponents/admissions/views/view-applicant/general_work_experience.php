<?php
    use yii\widgets\Breadcrumbs;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
     use yii\bootstrap\ActiveForm;
    
    if ($action == "create")
        $this->title = 'Create New Work Experience';
    else
        $this->title = 'Update Work Experience';
    
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
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="role">Role:</label>
               <?= $form->field($experience, 'role')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="natureofduties">Nature Of Duties:</label>
               <?= $form->field($experience, 'natureofduties')->label('')->textArea(['rows' => '5', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="employer">Employer Name:</label>
               <?= $form->field($experience, 'employer')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="employeraddress">Employer Address:</label>
               <?= $form->field($experience, 'employeraddress')->label('')->textArea(['rows' => '3', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="salary">Salary:</label>
               <?= $form->field($experience, 'salary')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
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
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="iscurrentjob">Is current job:</label>
               <?= $form->field($experience, 'iscurrentjob')->label('')->dropDownList([0 => 'No', 1 => 'Yes'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>                                      
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