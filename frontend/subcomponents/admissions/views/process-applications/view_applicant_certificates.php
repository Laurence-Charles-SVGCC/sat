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
        
        <?php if ($application_status > 2):?>
            <!-- Offer Flag-->
            <?php if (Offer::hasRecords($applicant->personid) == true):?>
                <br/><p id="offer-message" class="alert alert-info" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;"> 
                    <?= "Applicant has an offer";?>
                </p>
            <?php endif;?>

            <!-- No English Flag-->
            <?php if (CsecQualification::hasCsecEnglish($applicant->personid) == false):?>
                <br/><p id="offer-message" class="alert alert-warning" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;"> 
                    <?= "Applicant did not pass CSEC English Language";?>
                </p>
            <?php endif;?>

            <!-- No Mathematics Flag-->
            <?php if (CsecQualification::hasCsecMathematics($applicant->personid) == false):?>
                <br/><p id="offer-message" class="alert alert-warning" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;"> 
                    <?= "Applicant did not pass CSEC Mathematics";?>
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
        <?php else:?>
            <!-- Applicant's certificates have not been verified-->
            <br/><p id="offer-message" class="alert alert-warning" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;"> 
                <?= "Applicant's certificates have not been verified yet.";?>
            </p>    
        <?php endif;?>
        
        <div class="custom_body">
            <h2 class="custom_h1"><?= Html::encode($this->title) ?></h2>
            
            <div style="margin-left:2.5%">
                <p style="font-size:20px"><strong>Applicant ID:</strong><?= $username; ?></p><br/>

                <p style="font-size:20px"><strong>Applicant Name:</strong><?= $applicant->title . ". " .  $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname ;?></p><br/>

                <p style="font-size:20px"><strong>Application Being Considered:</strong><?= $programme; ?></p><br/>
            </div>
            
            <div>
                <p style="font-size:20px; margin-left:2.5%"><strong>For the current programme under consideration the current intake statistic are as follows:</strong></p>
                
                <?php if($cape): ?>
                    <table class='table table-condensed' style="margin-left:2.5%;">
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
                    <p style="margin-left:5%;"><?= "Offers given: " . $offers_made . ". Proposed Intake: " . $spaces ?></p>
                <?php endif; ?>  
            </div><br/>  
                
            <div>
                <h2 class="custom_h2">Certificate Information</h2>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
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
                <h2 class="custom_h2">Applications</h2>
                <table class='table table-condensed' style="width: 95%; margin: 0 auto;">
                    <tr>
                        <?php if ($application_status > 2):?>
                            <th>Active</th>
                        <?php endif;?>
                        <th>Priority</th>
                        <th>Division</th>
                        <th>Programme</th>
                        <th>Status</th>
                        <?php if (Yii::$app->user->can('Dean')  || Yii::$app->user->can('Deputy Dean') && $application_status > 2): ?>
                            <th>Action</th>
                        <?php endif;?>
                    </tr>
                    
                    <?php for($i = 0 ; $i< count($application_container) ; $i++): ?>
                        <tr>
                            <?php if ($application_status > 2):?>
                                <?php if($application_container[$i]["istarget"] == true):?>
                                    <td> <i class="fa fa-hand-o-right"></i> </td>
                                <?php else:?>
                                    <td></td>
                                <?php endif;?>
                            <?php endif;?>
                            
                            <td> <?= $application_container[$i]["application"]->ordering ?> </td>
                            <td> <?= $application_container[$i]["division"] ?> </td>
                            <td> <?= $application_container[$i]["programme"] ?> </td>
                            <td> <?= $application_container[$i]["status"] ?> </td>
                            
                            <?php
                                // User must be a Dean or Deputy Dean to be able to change the status of an applicant's application
                                if ((Yii::$app->user->can('Dean')  ||  Yii::$app->user->can('Deputy Dean'))  && $application_status > 2)
                                {
                                    /*
                                     * If user is a member of "All Divisions", "DTE" or "DNE" they have ability to change the application status
                                     * of any application that is the one under current consideration; or any application avobe it
                                     */
                                    if (EmployeeDepartment::getUserDivision() == 6  || EmployeeDepartment::getUserDivision() == 7  || EmployeeDepartment::getUserDivision() == 1)
                                    {
                                        if($application_container[$i]["application"]->ordering <= $target_application->ordering)
                                        {
                                            echo "<td>";                                  
                                                echo "<div class='dropdown'>";
                                                    echo "<button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                                        echo "Update Status...";
                                                        echo "<span class='caret'></span>";
                                                    echo "</button>";
                                                    echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>";
                                                        $statuses = ApplicationStatus::generateAvailableStatuses($application_container[$i]["application"]->applicationid, $application_container[$i]["application"]->applicationstatusid);
                                                        $status_count = count($statuses[0]);
                                                        for ($k = 0 ; $k < $status_count ; $k++)
                                                        {
                                                            $hyperlink = Url::toRoute(['/subcomponents/admissions/process-applications/update-application-status', 
                                                                                                'applicationid' => $application_container[$i]["application"]->applicationid, 
                                                                                                'new_status' => $statuses[0][$k],

                                                                                                //for 'actionViewByStatus($division_id, $application_status)' redirect
                                                                                                'old_status' => $target_application->applicationstatusid,
                                                                                                'divisionid' => $application_container[$i]["application"]->divisionid,
                                                                                             ]);
                                                            echo "<li><a href='$hyperlink'>{$statuses[1][$k]}</a></li>";      
                                                        }
                                                    echo "</ul>";
                                                echo "</div>";
                                            echo "</td>";  
                                        }
                                    }
                                    
                                    /*
                                     * If user is a member of "DASGS", "DTVE" they have ability to change any application status directly above the one under
                                     * current consideration if the current application is pending and the previous application is a programme offered by their division.
                                     */
                                    else
                                    {
                                        if(    $application_container[$i]["istarget"] == true 
                                            || ($target_application->applicationstatusid == 3  
                                                    && ($target_application->ordering - $application_container[$i]["application"]->ordering == 1)  
                                                    && $application_container[$i]["application"]->divisionid == EmployeeDepartment::getUserDivision()
                                                )
                                           )
                                        {
                                            echo "<td>";                                  
                                                echo "<div class='dropdown'>
                                                    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                                      echo "Update Status...";
                                                      echo "<span class='caret'></span>";
                                                    echo "</button>";
                                                    echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu'>";
                                                        $statuses = ApplicationStatus::generateAvailableStatuses($application_container[$i]["application"]->applicationid, $application_container[$i]["application"]->applicationstatusid);
                                                        $status_count = count($statuses[0]);
                                                        for ($k = 0 ; $k < $status_count ; $k++)
                                                        {
                                                            $hyperlink = Url::toRoute(['/subcomponents/admissions/process-applications/update-application-status', 
                                                                                                'applicationid' => $application_container[$i]["application"]->applicationid, 
                                                                                                'new_status' => $statuses[0][$k], 

                                                                                                //for 'actionViewByStatus($division_id, $application_status)' redirect
                                                                                                'old_status' => $target_application->applicationstatusid,
                                                                                                'divisionid' => $application_container[$i]["application"]->divisionid,
                                                                                             ]);
                                                            echo "<li><a href='$hyperlink'>{$statuses[1][$k]}</a></li>";
                                                        }
                                                    echo "</ul>";
                                                echo "</div>";
                                            echo "</td>";  
                                        }
                                    }
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
            
            <div><br/>
                <a class="btn btn-success glyphicon glyphicon-folder-open" style="margin-left:2.5%;" href=<?=Url::toRoute(['/subcomponents/admissions/process-applications/view-applicant-details', 'personid' => $applicant->personid, 'programme' => $programme, 'application_status' => $application_status]);?> role="button">  View Application Snapshot</a>
                
                <a class="btn btn-success glyphicon glyphicon-user" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'applicantusername' => $username]);?> role="button">  Modify Applicant Details</a>
            </div>
            
                
        </div>
    </div>
</div>

