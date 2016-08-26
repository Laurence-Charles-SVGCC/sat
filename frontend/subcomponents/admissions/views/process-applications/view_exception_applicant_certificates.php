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
    use frontend\models\Offer;
    use frontend\models\CsecQualification;
    use frontend\models\Applicant;
    use frontend\models\Application;

    $this->title = 'Application  Review Dashboard (Exceptions)';
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
        
        <div>
            <!-- Duplicate Flag-->
           <?php if ($duplicate_message):?>
               <br/><p id="offer-message" class="alert alert-warning" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;"> 
                   <?= $duplicate_message;?>
               </p>
           <?php endif;?>

           <!-- Offer Flag-->
           <?php if (Offer::hasRecords($applicant->personid) == true):?>
               <br/><p id="offer-message" class="alert alert-info" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;"> 
                   <?= "Applicant has " . Offer::getPriorityOffer($applicant->personid) . ".";?>
               </p>
           <?php endif;?>

           <!-- No English Flag-->
           <?php if (CsecQualification::hasCsecEnglish($applicant->personid) == false):?>
               <br/><p id="offer-message" class="alert alert-warning" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;"> 
                   <?= "Applicant did not pass CSEC/GCE English Language";?>
               </p>
           <?php endif;?>

           <!-- No Mathematics Flag-->
           <?php if (CsecQualification::hasCsecMathematics($applicant->personid) == false):?>
               <br/><p id="offer-message" class="alert alert-warning" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;"> 
                   <?= "Applicant did not pass CSEC/GCE Mathematics";?>
               </p>
           <?php endif;?>

           <!-- Has Less Than 5 Subjects Flag-->
           <?php if (CsecQualification::hasFiveCsecPasses($applicant->personid) == false):?>
               <br/><p id="offer-message" class="alert alert-warning" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;"> 
                   <?= "Applicant does not have 5 CSEC passes";?>
               </p>
           <?php endif;?>

           <!-- DTE Relevant Science Subjects Flag-->
           <?php if ($applicant->applicantintentid == 4):?>
               <?php if (CsecQualification::hasDteRelevantSciences($applicant->personid) == false):?>
                   <br/><p id="offer-message" class="alert alert-warning" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;"> 
                       <?= "Applicant does not have the necessary passes in relevant science subjects";?>
                   </p>
               <?php endif;?>
           <?php endif;?> 

           <!-- DNE Relevant Science Subjects Flag-->
           <?php if ($applicant->applicantintentid == 6):?>
               <?php if (CsecQualification::hasDneRelevantSciences($applicant->personid) == false):?>
                   <br/><p id="offer-message" class="alert alert-warning" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;"> 
                       <?= "Applicant does not have the necessary passes in relevant science subjects";?>
                   </p>
               <?php endif;?>
           <?php endif;?> 
        </div>
        
        
        <div class="custom_body">
            <h2 class="custom_h1"><?= Html::encode($this->title) ?></h2>
            
            <div style="margin-left:2.5%">
                <p style="font-size:20px"><strong>Applicant ID:</strong><?= $username; ?></p><br/>

                <p style="font-size:20px"><strong>Applicant Name:</strong><?= $applicant->title . ". " .  $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname ;?></p><br/>
            </div> 
            
            <div>
                <h2 class="custom_h2">Certificate Information</h2>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                    'columns' => [
                        [
                            'format' => 'text',
                            'label' => 'Examination Body',
                            'value' => function($model)
                                {
                                    $exam_body = ExaminationBody::find()->where(['examinationbodyid' => $model->examinationbodyid])->one();
                                   return $exam_body ? $exam_body->name : "Undefined";
                                }
                        ],
                        [
                            'attribute' => 'year',
                            'format' => 'text',
                            'label' => 'Year'
                        ],
                        [
                            'attribute' => 'proficiency',
                            'format' => 'text',
                            'label' => 'Proficiency',
                            'value' => function($model)
                                {
                                    $exam_proficiency = ExaminationProficiencyType::find()->where(['examinationproficiencytypeid' => 
                                        $model->examinationproficiencytypeid])->one();
                                   return $exam_proficiency ? $exam_proficiency->name : "Undefined";
                                }
                        ],
                        [
                            'attribute' => 'subjectid',
                            'format' => 'text',
                            'label' => 'Subject',
                            'value' => function($model)
                                {
                                    $subject = Subject::find()->where(['subjectid' => $model->subjectid])->one();
                                   return $subject ? $subject->name : "Undefined";
                                }
                        ],
                        [
                            'attribute' => 'examinationgradeid',
                            'format' => 'text',
                            'label' => 'Grade',
                            'value' => function($model)
                                {
                                    $exam_grade = ExaminationGrade::find()->where(['examinationgradeid' => $model->examinationgradeid])->one();
                                   return $exam_grade ? $exam_grade->name : "Undefined";
                                }
                        ],
                    ],
                ]); ?>
            </div><br/>
            
            <div>
                <h2 class="custom_h2"> Applications</h2>
                
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
                            <td> <?= $application_container[$i]["status"] ?> </td>
                        </tr>
                    <?php endfor; ?> 
                </table><br/><br/>
                
                 <div>
                    <a class="btn btn-success glyphicon glyphicon-user" style="margin-left:2.5%" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'applicantusername' => $username]);?> role="button">  View Applicant Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>

