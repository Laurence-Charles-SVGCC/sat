<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use frontend\models\ExaminationBody;
use frontend\models\ExaminationProficiencyType;
use frontend\models\Subject;
use frontend\models\ExaminationGrade;
use frontend\models\ApplicationStatus;
use frontend\models\EmployeeDepartment;
use frontend\models\DocumentType;


$this->title = 'Verify Document Submission';
//$this->params['breadcrumbs'][] = ['label' => 'Manage Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verify-applicants-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h2 class="custom_h1"><?= Html::encode($this->title) ?></h2><br/>
            
            <div style="margin-left:2.5%">
                <p style="font-size:20px"><strong>Applicant ID:</strong><?= $username; ?></p>

                <p style="font-size:20px"><strong>Applicant Name:</strong><?= $applicant->title . ". " .  $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname ;?></p><br/>
            </div>
            
            <fieldset>
                <legend class="custom_h2" style="margin-left: 2.5%;">Verify Document</legend>
                <div style="margin-left: 2.5%;">
                    <div>
                        <?php 
                            $form = ActiveForm::begin(
                                [
                                    'action' => Url::to(['verify-applicants/verify-documents',  'applicantid' => $applicantid,  'centrename' => $centrename, 'cseccentreid' => $centreid, 'type' => $type, 'personid' => $applicant->personid]),
                                ]); 
                        ?>
                        
                            <p class="general_text">Select from the following list which documents the applicant presented on enrollment.</p>
                            <h3><strong>Documents Checklist</strong></h3>
                            <div class="row">
                                <div class="col-lg-3">
                                    <?= Html::checkboxList('documents', 
                                                            $selections, 
                                                            ArrayHelper::map(DocumentType::findAll(['isdeleted' => 0]),
                                                            'documenttypeid', 
                                                            'name'));
                                    ?>
                                </div>
                            </div>

                            <div class="form-group"><br/>
                                    <?=Html::a(' Back', 
                                                        ['verify-applicants/view-applicant-qualifications', 'applicantid' => $applicantid,  'centrename' => $centrename, 'cseccentreid' => $centreid, 'type' => $type], 
                                                        ['class' => 'btn btn-danger glyphicon glyphicon-remove pull-left','style' => 'margin-right:2.5%',]);
                                    ?> 
                                    <?= Html::submitButton(' Verify Selection', ['class' => 'btn btn-success pull-left glyphicon glyphicon-ok']) ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </fieldset><br/><br/>
        </div>
    </div>
</div>
            
