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


$this->title = 'Successful Applicant  Review Dashboard';
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
                <p style="font-size:20px"><strong>Applicant ID:</strong><?= $username; ?></p><br/>

                <p style="font-size:20px"><strong>Applicant Name:</strong><?= $applicant->title . ". " .  $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname ;?></p><br/>

                <p style="font-size:20px"><strong>Programme Under Offer:</strong><?= $programme; ?></p><br/>
            </div>
            
            
            <fieldset>
                <legend class="custom_h2" style="margin-left: 2.5%;">Submitted Applications</legend>
                <table class='table table-condensed' style="width: 95%; margin: 0 auto;">
                    <tr>
                        <th>Priority</th>
                        <th>Division</th>
                        <th>Programme</th>
                        <th>Status</th>
                    </tr>
                    
                    <?php for($i = 0 ; $i< count($application_container) ; $i++): ?>
                        <tr>
                            <td> <?= $application_container[$i]["application"]->ordering ?> </td>
                            <td> <?= $application_container[$i]["division"] ?> </td>
                            <td> <?= $application_container[$i]["programme"] ?> </td>
                            
                            <?php if($application_container[$i]["istarget"] == true):?>
                                <td> <i class="glyphicon glyphicon-ok"></i> </td>
                            <?php else:?>
                                <td><i class="glyphicon glyphicon-remove"></td>
                            <?php endif;?>
                        </tr>
                    <?php endfor; ?> 
                </table>
            </fieldset><br/><br/>
            
            
            <fieldset>
                <legend class="custom_h2" style="margin-left: 2.5%;">Registration Panel</legend>
                <div style="margin-left: 2.5%;">
                    <p class="general_text">
                        Would you like to review the applicant's profile?
                        <?= Html::radioList('review-applicant', null, ["Yes" => "Yes", "No" => "No"], ['class'=> 'form_field', 'onclick'=> 'toggleProfileButton();']);?>
                    </p>

                    <div id="profile-button" style="display:none">
                        <a target="_blank" class="btn btn-success glyphicon glyphicon-user" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'search_status' => 'pending-unlimited', 'applicantusername' => $username]);?> role="button">  View Applicant Profile</a>
                    </div>

                    <br/>
                    <div>
                        <?php 
                            $form = ActiveForm::begin(
                                [
                                    'action' => Url::to(['register-student/enroll-student', 'personid' => $personid, 'programme' => $programme]),
                                ]); 
                        ?>
                        
                            <?= Html::hiddenInput('applicantid', $applicant->applicantid); ?>
                            <?= Html::hiddenInput('offerid', $offerid); ?>
                            <?= Html::hiddenInput('applicationid', $applicationid); ?>
                        
                            <p class="general_text">Select from the following list which documents the applicant presented on enrollment.</p>
                            <h3><strong>Enrollment Documents Checklist</strong></h3>
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
                                <?php if (Yii::$app->user->can('registerStudent')): ?>
                                    <?= Html::submitButton(' Enroll Student', ['class' => 'btn btn-lg btn-success pull-left', 'style' => 'width: 30%;']) ?>
                                <?php endif; ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </fieldset><br/><br/>
        </div>
    </div>
</div>
            
