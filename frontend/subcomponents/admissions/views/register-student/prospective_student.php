<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

use frontend\models\ExaminationBody;
use frontend\models\ExaminationProficiencyType;
use frontend\models\Subject;
use frontend\models\ExaminationGrade;
use frontend\models\ApplicationStatus;
use frontend\models\EmployeeDepartment;


$this->title = 'Successful Applicant  Review Dashboard';
//$this->params['breadcrumbs'][] = ['label' => 'Manage Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verify-applicants-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h2 class="custom_h1"><?= Html::encode($this->title) ?></h2>
            
            <div style="margin-left:2.5%">
                <p style="font-size:20px"><strong>Applicant ID:</strong><?= $username; ?></p><br/>

                <p style="font-size:20px"><strong>Applicant Name:</strong><?= $applicant->title . ". " .  $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname ;?></p><br/>

                <p style="font-size:20px"><strong>Application Being Considered:</strong><?= $programme; ?></p><br/>
            </div>
            
            <div>
                <table class='table table-condensed' style="width: 95%; margin: 0 auto;">
                    <tr>
                        <th>Active</th>
                        <th>Priority</th>
                        <th>Division</th>
                        <th>Programme</th>
                        <th>Status</th>
                    </tr>
                    
                    <?php for($i = 0 ; $i< count($application_container) ; $i++): ?>
                        <tr>
                            <?php if($application_container[$i]["istarget"] == true):?>
                                <td> <i class="fa fa-hand-o-right"></i> </td>
                            <?php else:?>
                                <td></td>
                            <?php endif;?>
                            
                            <td> <?= $application_container[$i]["application"]->ordering ?> </td>
                            <td> <?= $application_container[$i]["division"] ?> </td>
                            <td> <?= $application_container[$i]["programme"] ?> </td>
                        </tr>
                    <?php endfor; ?> 
                </table>
            </div>
            
            
            <?php ActiveForm::begin(['action' => Url::to(['view-applicant/applicant-actions'])]);?>
                
                <?= Html::hiddenInput('applicantusername', $username); ?>
                <?php if (Yii::$app->user->can('registerStudent')): ?>
                    <?= Html::submitButton('Register as Student', ['class' => 'btn btn-success', 'name' => 'register']); ?>
                <?php endif; ?>
               
                <?= Html::submitButton('View Applicant Profile', ['class' => 'btn btn-success', 'name' => 'applicant_profile']); ?>
            
                <?php if(Yii::$app->user->can('publishOffer')): ?>
                    <?= Html::submitButton('Publish Decision', ['class' => 'btn btn-success', 'name' => 'publish_decision']); ?>
                <?php endif; ?>

            <?php ActiveForm::end(); ?>
            
        </div>
    </div>
</div>
            
