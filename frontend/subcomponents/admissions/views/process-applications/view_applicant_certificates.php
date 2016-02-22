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


$status_ids = ["3", "7", "4", "8", "9","10", "6"];

$status_names = ["Pending", "Borderline", "Shortlist", "Conditional Offer", "Offer", "Conditional Offer Rejection", "Reject"];

$this->title = 'Application  Review Dashboard';
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
            <h2><?= Html::encode($this->title) ?></h2>
            
            <p style="font-size:20px"><strong>Applicant ID:</strong><?= applicantid; ?></p>
            
            <p style="font-size:20px"><strong>Applicant Name:</strong><?= $applicant->title . ". " .  $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname ;?></p>
            
            <p style="font-size:20px"><strong>Application Being Considered:</strong><?= $programme; ?></p>
            
            <div>
                <p>For the current programme under consideration the current intake statistic are as follows:</p>
                
                <?php if($cape): ?>
                    <table class='table table-condensed'>
                        <tr>
                            <th>Subject</th>
                            <th>Offers Made</th>
                            <th>Proposed Intake</th>
                        </tr>
                        
                        <?php foreach($cape_info as $key =>$ci): ?>
                            <tr>
                                <td> <?= $key ?> </td>
                                <td> <?= $ci['offers_made'] ?> </td>
                                <td> <?= $ci['capacity'] ?> </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    
                <?php else: ?>
                    <h3><?= "Offers given: " . $offers_made . ". Proposed Intake: " . $spaces ?></h3>
                <?php endif; ?>  
            </div><br/>    
                
            <div>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
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
                <table class='table table-condensed'>
                    <tr>
                        <th>Active</th>
                        <th>Division</th>
                        <th>Programme</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    
                    <?php for($i = 0 ; $i< count($application_container) ; $i++): ?>
                        <tr>
                            <?php if($application_container[$i]["istarget"] == true):?>
                                <td> <i class="fa fa-hand-o-right"></i> </td>
                            <?php else:?>
                                <td stlye="opacity: 0.5"> <i class="fa fa-hand-o-right"></i> </td>
                            <?php endif;?>
                            
                            <td> <?= $application_container[$i]["division"] ?> </td>
                            <td> <?= $application_container[$i]["programme"] ?> </td>
                            <td> <?= $application_container[$i]["status"] ?> </td>
                            
                            <?php 
                                if($application_container[$i]["istarget"] == true || ($target_application->applicationstatusid == 3  && ($target_application->ordering - $application_container[$i]["application"]->ordering == 1)))
                                {
                                    echo "<td>";                                  
                                        echo "<div class='dropdown'>
                                            <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                              echo "Select Cohort...";
                                              echo "<span class='caret'></span>";
                                            echo "</button>";
                                            echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu'>";
                                                $status_count = count($status_ids);
                                                for ($k = 0 ; $k < $status_count ; $k++)
                                                {
                                                    $hyperlink = Url::toRoute(['/subcomponents/admissions/process-applications/update-status', 
                                                                                        'applicationid' => $application_container[$k]["application"]->applicationid, 
                                                                                        'new_status' => $status_id[$k], 
                                                                                        
                                                                                        //for 'actionViewByStatus($division_id, $application_status)' redirect
                                                                                        'old_status' => $target_application->applicationstatusid,
                                                                                        'divisionid' => $division_id,
                                                                                     ]);
                                                    echo "<li><a href='$hyperlink'>$status_name[$k]</a></li>";      
                                                }
                                            echo "</ul>";
                                        echo "</div>";
                                    echo "</td>";  
                                }
                                else
                                {
                                    echo "<td> N/A </td>";
                                }
                            ?>
                        </tr>
                    <?php endfor; ?> 
                </table>
            </div>
        
                
        </div>
    </div>
</div>

