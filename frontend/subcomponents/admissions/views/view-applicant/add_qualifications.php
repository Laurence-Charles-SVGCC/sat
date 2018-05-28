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
    use yii\bootstrap\Modal;
    
    
    $this->title = 'Add Qualification(s)';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find Applicant', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => $search_status])];
    $this->params['breadcrumbs'][] = ['label' => 'Applicant Profile', 'url' => Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'search_status' => $search_status, 'applicantusername' => $applicantusername])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
    </div>
    
    <div class="box-body">
        <?php
            Modal::begin([
                    'header' => '<h2>Notification</h2>',
                    'id' => 'modal-no-more-qualifications',
                    'size' => 'modal-md',
                ]);
                echo "<p><strong>No records are present to be removed. If you would have previously deleted records, click "
                . "save to finalize the operation</strong>.</p>";
            Modal::end();
        ?>

        <?php
            Modal::begin([
                    'header' => '<h2>Notification</h2>',
                    'id' => 'modal-too-many-qualifications',
                    'size' => 'modal-md',
                ]);
                echo "<p><strong>You have reached your record limit. No more records can be entered.</strong>.</p>";
            Modal::end();
        ?>

       <fieldset style="width:100%">
            <p style="font-size:18px;"><strong>You may enter as much as ten (10) records at a time.</strong></p>
            <?php $form = ActiveForm::begin(['action' => Url::to(['view-applicant/save-new-qualifications', 'search_status' => $search_status, 'personid' => $personid,  'applicantusername' => $applicantusername])]) ?>
                <?= Html::hiddenInput('viewApplicantQualifications_baseUrl', Url::home(true)); ?>
            
                <div id="add-certiifcates" class="panel panel-default" style="width:100%; margin: 0 auto;">
                    <div class="panel-heading">
                        <h4>
                            <i class="glyphicon glyphicon-education"></i> New Certificates
                            <?= Html::button(' ', ['style'=>'margin-left:10px', 'class' => 'btn btn-danger btn-sm pull-right glyphicon glyphicon-minus', 'onclick'=>'removeQualification();']);?>
                            <?= Html::button('Add', ['class' => 'btn btn-success btn-sm pull-right glyphicon glyphicon-plus', 'onclick'=>'addQualification();']);?>                         
                        </h4>
                    </div>

                    <div class="panel-body">
                        <div class="container-items">
                            <table id="certificate_table" class="table table-bordered table-striped" style="width:100%; margin: 0 auto;">
                                <?php for ($i = 0 ; $i < count($csecqualifications)  ; $i++):?>
                                    <tr id="<?= "qualification[" . $i . "]" ;?>" style="display:none">
                                        <td  width='15%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]cseccentreid")->label("")->dropDownList(CsecCentre::processCentres(), ['style'=> 'font-size:14px;']);?>
                                        </td>
                                        <td width='12%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]examinationbodyid")->label("")->dropDownList(ExaminationBody::processExaminationBodies(), ['onchange' => 'ProcessExaminationBody(event);', 'style'=> 'font-size:14px;']);?>
                                        </td>
                                        <td width='15%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]candidatenumber")->label("")->textInput(['maxlength' => true, 'style'=> 'font-size:14px;']) ?>
                                        </td>
                                        <td width='17%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]subjectid")->label("")->dropDownList(Subject::initializeSubjectDropdown($i), ['style'=> 'font-size:14px;']); ?>
                                        </td>
                                        <td width='16%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]examinationproficiencytypeid")->label("")->dropDownList(ExaminationProficiencyType::initializeProficiencyDropdown($i), ['style'=> 'font-size:14px;']);?>
                                        </td>
                                        <td width='13%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]examinationgradeid")->label("")->dropDownList(ExaminationGrade::initializeGradesDropdown($i), ['style'=> 'font-size:14px;']);?>
                                        </td>
                                        <td width='12%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]year")->label("")->dropDownList(Yii::$app->params['years'], ['style'=> 'font-size:14px;']);?>
                                        </td>
                                    </tr>
                                <?php endfor;?>

                            </table>
                        </div>

                        </br>
                        <div id="save-new-certifcates" class="form-group" style="display:none">
                            <?= Html::submitButton('Save New Certificates', ['class' => 'btn btn-primary pull-right', 'onclick'=>'fillBlanks();']);?>
                        </div>

                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </fieldset>
    </div>
</div>