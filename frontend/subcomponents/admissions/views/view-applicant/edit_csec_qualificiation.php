<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\CsecCentre;
    use frontend\models\ExaminationBody;
    use frontend\models\ExaminationProficiencyType;
    use frontend\models\ExaminationGrade;
    use frontend\models\Subject;
    
    $this->title = 'Edit Qualification';
    
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
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="cseccentreid">Examination Centre:</label>
               <?= $form->field($qualification, 'cseccentreid')->label('')->dropDownList(CsecCentre::processCentres(), ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="candidatenumber">Candidate Number:</label>
               <?= $form->field($qualification, 'candidatenumber')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="examinationbodyid">Examination Body:</label>
               <?= $form->field($qualification, 'examinationbodyid')->label('')->dropDownList(ExaminationBody::processExaminationBodies(), ['onchange' => 'AddCsecQualificationAjaxFunction(event);', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="subjectid">Subject:</label>
               <?= $form->field($qualification, 'subjectid')->label('')->dropDownList(Subject::initializeSubjectDropdown($qualification->csecqualificationid), ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="examinationproficiencytypeid">Proficiency:</label>
               <?= $form->field($qualification, 'examinationproficiencytypeid')->label('')->dropDownList(ExaminationProficiencyType::initializeProficiencyDropdown($qualification->csecqualificationid), ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="examinationgradeid">Grade:</label>
               <?= $form->field($qualification, 'examinationgradeid')->label('')->dropDownList(ExaminationGrade::initializeGradesDropdown($qualification->csecqualificationid), ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="year">Examination Year:</label>
               <?= $form->field($qualification, 'year')->label('')->dropDownList(Yii::$app->params['years'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
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