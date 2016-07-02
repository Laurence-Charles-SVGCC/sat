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


$this->title = 'Update Documents';
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
            
            <fieldset>
                <legend class="custom_h2" style="margin-left: 2.5%;">Registration Panel</legend>
                <div style="margin-left: 2.5%;">
                    <br/>
                    <div>
                        <?php 
                            $form = ActiveForm::begin(
                                [
//                                    'action' => Url::to(['register-student/enroll-student']),
                                ]); 
                        ?>
                        
                            <p class="general_text">Select from the following list which documents the applicant has presented.</p>
                            <h3><strong>Enrollment Documents Checklist</strong></h3>
                            <div class="row">
                                <div class="col-lg-3">
                                    <?= Html::checkboxList('documents', 
                                                            $selections, 
                                                            ArrayHelper::map(DocumentType::findAll(['isactive' => 1, 'isdeleted' => 0]),
                                                            'documenttypeid', 
                                                            'name'));
                                    ?>
                                </div>
                            </div>

                            <div class="form-group"><br/>
                                <?=Html::a(' Cancel',['view-applicant/applicant-profile', 'applicantusername' => $user->username], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);?>
                                <?= Html::submitButton(' Update Documents', ['class' => 'btn btn-lg btn-success pull-right', 'style' => 'width: 25%; margin-right:15%;']) ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </fieldset><br/><br/>
        </div>
    </div>
</div>
            
