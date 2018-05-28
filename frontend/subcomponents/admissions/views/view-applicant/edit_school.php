<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
     use yii\bootstrap\ActiveForm;
     use dosamigos\datepicker\DatePicker;

    use frontend\models\Institution;

    $graduated = [
                '' => 'Select..',
                1 => 'Yes',
                0 => 'No'
    ];
    
    $level = NULL;
    if($levelid == 1)
        $level = "Pre School";
    elseif($levelid == 2)
        $level = "Primary School";
    elseif($levelid == 3)
        $level = "Secondary School";
    elseif($levelid == 4)
        $level = "Tertiary School";
    
    $this->title = 'Edit School';
    
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
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="institutionid">Name:</label>
               <?= $form->field($school, 'institutionid')->label('')->dropDownList(Institution::initializeSchoolList($levelid), ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9", "disabled" => true]) ?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="startdate">Start Date:</label>
               <?= $form->field($school, 'startdate')->label(false)->widget(
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
            </div><br/>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="enddate">End Date:</label>
               <?= $form->field($school, 'enddate')->label(false)->widget(
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
            </div><br/>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="employer">Has student graduated from this institution?</label>
               <?= $form->field($school, 'hasgraduated')->label('')->dropDownList($graduated, ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
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