<?php

    use yii\widgets\Breadcrumbs;
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

    $this->title = 'Application Details';

    $this->params['breadcrumbs'][] =
    [
      'label' => 'Review Applicants',
      'url' => Url::toRoute(['/subcomponents/admissions/process-applications'])
    ];

    $this->params['breadcrumbs'][] =
    [
      'label' => $application_status_name,
      'url' => Url::to(
          [
            'process-applications/view-by-status',
            'division_id' => $division_id,
            'application_status' => $application_status
          ]
      )
    ];

    $this->params['breadcrumbs'][] = $this->title;
?>


<h2 class="text-center">
  <?= $username . " - " .$applicantFullName;?>
</h2>

<div class="box box-primary">
  <?php if ($application_status > 2):?>
    <!-- Duplicate Flag-->
    <?php if ($duplicate_message):?>
      <br/>
      <p id="offer-message" class="alert alert-warning" role="alert"
      style="width: 95%; margin: 0 auto; font-size:14px;">
        <?= $duplicate_message;?>
      </p>
    <?php endif;?>

    <!-- Offer Flag-->
    <?php if (Offer::hasRecords($applicant->personid) == true):?>
      <br/>
      <p id="offer-message" class="alert alert-info" role="alert"
      style="width: 95%; margin: 0 auto; font-size:14px;">
        <?= "Applicant has " . Offer::getPriorityOffer($applicant->personid) . ".";?>
      </p>
    <?php endif;?>

    <!-- No English Flag-->
    <?php if ($applicantHasCsecEnglish == false):?>
        <p id="offer-message" class="alert alert-warning" style="margin: 10px;">
            <?= "Applicant did not pass CSEC/GCE English Language";?>
        </p>
    <?php endif;?>

    <!-- No Mathematics Flag-->
    <?php if ($applicantHasCsecMathematics == false):?>
        <p id="offer-message" class="alert alert-warning" style="margin: 10px;">
            <?= "Applicant did not pass CSEC/GCE Mathematics";?>
        </p>
    <?php endif;?>

    <!-- Has Less Than 5 Subjects Flag-->
    <?php if ($applicantHasFiveCsecPasses == false):?>
        <p id="offer-message" class="alert alert-warning" style="margin: 10px;">
            <?= "Applicant does not have 5 CSEC passes";?>
        </p>
    <?php endif;?>

    <!-- DTE Relevant Science Subjects Flag-->

    <?php if ($isDteApplicantWithoutRelevantSciences == true):?>
      <p id="offer-message" class="alert alert-warning" style="margin: 10px;">
          <?= "Applicant does not have the necessary passes in relevant science subjects";?>
      </p>
    <?php endif;?>

    <!-- DNE Relevant Science Subjects Flag-->
    <?php if ($isDneApplicantWithoutRelevantSciences == true):?>
      <p id="offer-message" class="alert alert-warning" style="margin: 10px;">
        <?= "Applicant does not have the necessary passes in relevant science subjects";?>
      </p>
    <?php endif;?>
  <?php else:?>
        <!-- Applicant's certificates have not been verified-->
        <br/><p id="offer-message" class="alert alert-warning" style="margin: 10px;">
            <span>Applicant's certificates have not been verified yet.</span>

            <?php if (Yii::$app->user->can('verifyApplicants')) :?>
                <a class="btn btn-danger pull-right"
                    href=<?=Url::toRoute(['/subcomponents/admissions/verify-applicants/view-applicant-qualifications', 'applicantid' => $applicant->personid, 'centrename' => $centrename, 'cseccentreid' => $cseccentreid, 'type' => 'Pending']);?>
                >
                    Click here to verify this applicant's certificates
                </a><br/><br/>
            <?php endif; ?>
        </p>
    <?php endif;?>

    <div class="box-body">
        <div style="margin-left:2.5%"><br/>

            <p style="font-size:20px"><strong>Application Being Considered:</strong><?= $programme; ?></p><br/>
        </div>

        <div>
            <p style="font-size:20px; margin-left:2.5%"><strong>For the current programme under consideration the current intake statistic are as follows:</strong></p>

            <?php if ($cape && Offer::find()->where(['applicationid'=> $target_application->applicationid, 'isactive' => 1, 'isdeleted' => 0])->one()): ?>
                <table class='table table-condensed' style="margin-left:2.5%;">
                    <tr>
                        <th>Subject</th>
                        <th>Offers Made</th>
                        <th>Proposed Intake</th>
                    </tr>

                    <?php foreach ($cape_info as $key =>$ci): ?>
                        <tr>
                            <td> <?= $key ?> </td>
                            <td> <?= $ci['offers_made'] ?> </td>
                            <td> <?= $ci['capacity'] ?> </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            <?php elseif (!$cape): ?>
                <p style="margin-left:5%;"><?= "<ul style='margin-left:2.5%;'> " . "<li>Proposed Intake: " . $spaces . "</li>" . " <li>Conditional Offers given: " . $conditional_offers_made . "</li>" . " <li>Full Offers given: " . $offers_made . "</li>" . "</ul>" ?></p>
            <?php endif; ?>
        </div><br/>

        <div>
            <h2 class="custom_h2">Certificate Information</h2>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                'columns' => [
                    [
                        'format' => 'text',
                        'label' => 'Examination Body',
                        'value' => function ($model) {
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
                        'value' => function ($model) {
                            $exam_proficiency = ExaminationProficiencyType::find()->where(['examinationproficiencytypeid' =>
                                    $model->examinationproficiencytypeid])->one();
                            return $exam_proficiency ? $exam_proficiency->name : "Undefined";
                        }
                    ],
                    [
                        'attribute' => 'subjectid',
                        'format' => 'text',
                        'label' => 'Subject',
                        'value' => function ($model) {
                            $subject = Subject::find()->where(['subjectid' => $model->subjectid])->one();
                            return $subject ? $subject->name : "Undefined";
                        }
                    ],
                    [
                        'attribute' => 'examinationgradeid',
                        'format' => 'text',
                        'label' => 'Grade',
                        'value' => function ($model) {
                            $exam_grade = ExaminationGrade::find()->where(['examinationgradeid' => $model->examinationgradeid])->one();
                            return $exam_grade ? $exam_grade->name : "Undefined";
                        }
                    ],
                ],
            ]); ?>
        </div><br/>

        <fieldset style="width: 95%; margin: 0 auto;">
            <legend><strong>Applicant Supporting Information</strong></legend>
            <p>Select any of the buttons to access the supporting details for each applicant either in "view-only" or "editing" mode.</p>
            <ul>
                <li><a class="btn btn-info" href=<?=Url::toRoute(['/subcomponents/admissions/process-applications/view-applicant-details', 'personid' => $applicant->personid, 'programme' => $programme, 'application_status' => $application_status]);?> role="button">  Preview Applicant Profile</a></li><br/>
                <li><a class="btn btn-success" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'search_status' => 'pending-unlimited', 'applicantusername' => $username]);?> role="button">  View/Edit Applicant Profile</a></li>
            </ul>
        </fieldset><hr>

        <?php if (Applicant::isRejected($applicant->personid) == true  && Applicant::hasBeenIssuedRejection($applicant->personid) == false):?>
        <div>
            <br/><p class="alert alert-error" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;">
                This applicant has been rejected from all of their programme choices.  Divisional processing restrictions have therefore
                been removed from this application.  The Registrar and any Deans/Deputy Deans are permitted to issue a Custom Offer to this applicant.
            </p>
        <?php elseif (Applicant::isRejected($applicant->personid) == true  && Applicant::hasBeenIssuedRejection($applicant->personid) == true  && Yii::$app->user->can('Registrar')):?>
        <div>
            <br/><p class="alert alert-error" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;">
                This applicant has been rejected from all of their programme choices and has been issued a rejection response.
                However as Registrar you are still permitted to issue a Custom Offer to this applicant.
            </p>
        <?php elseif (Applicant::isRejected($applicant->personid) == true  && Applicant::hasBeenIssuedRejection($applicant->personid) == true  && Yii::$app->user->can('Registrar') == false):?>
        <div>
            <br/><p class="alert alert-error" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;">
                This applicant has been rejected from all of their programme choices and has been issued a rejection response.
                Only the Registrar is authorized to issue a Custom Offer to this applicant at this time.
            </p>
        <?php elseif (Applicant::hasBeenIssuedOffer($applicant->personid) == false && ($division_id == 1 || ($division_id != 1 && $target_application->divisionid == $division_id))):?>
        <div>
        <?php else:?>
        <div style="opacity:0.6;">
            <?php if (Applicant::hasBeenIssuedOffer($applicant->personid) == true):?>
                <br/><p class="alert alert-error" role="alert" style="width: 95%; margin: 0 auto; font-size:16px; opacity:1">
                    This applicant would have already been sent an Acceptance Package; therefore no further action can be taken on this application.
                    If you do wish to change programme choice, please submit transfer request to Registrar.
                </p>
            <?php else:?>
                <br/><p  class="alert alert-error" role="alert" style="width: 95%; margin: 0 auto; font-size:16px; opacity:1">
                   You currently have 'View-Only' access to this applicant because the current programme choice under selection
                   is being offered by another division
               </p>
            <?php endif;?>
        <?php endif;?>

            <h2 class="custom_h2">
                Programme Choices
                <?php if (Yii::$app->user->can('System Administrator') == true):?>
                    <div class="pull-right">
                        <?=Html::a(
                'Admin. Reset',
                ['process-applications/full-applicant-reset', 'personid' => $applicant->personid, 'programme' => $programme, 'application_status' => $application_status, 'programme_id' => $programme_id],
                ['class' => 'btn btn-danger', 'data' => ['confirm' => 'Are you sure you want to reset application back to verified status?']]
            );?>
                    </div>
                <?php endif;?>

                <?php if (Applicant::isRejected($applicant->personid) == true  && Applicant::hasBeenIssuedRejection($applicant->personid) == false):?>
                    <div class="pull-right" style="margin-right:10px">
                        <?=Html::a(
                'Custom Offer',
                ['process-applications/custom-offer', 'personid' => $applicant->personid, 'programme' => $programme, 'application_status' => $application_status],
                ['class' => 'btn btn-warning',
                                        'style' => '',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to create a customized offer for student?',
//                                                'method' => 'post',
                                        ],
                                    ]
            );?>
                    </div>

                <?php elseif (Applicant::isRejected($applicant->personid) == true  && Applicant::hasBeenIssuedRejection($applicant->personid) == true  && Yii::$app->user->can('Registrar')):?>
                    <div class="pull-right" style="margin-right:10px">
                        <?=Html::a(
                'Custom Offer',
                ['process-applications/custom-offer', 'personid' => $applicant->personid, 'programme' => $programme, 'application_status' => $application_status],
                ['class' => 'btn btn-warning',
                                        'style' => '',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to create a customized offer for student?',
//                                                'method' => 'post',
                                        ],
                                    ]
            );?>
                    </div>

                <!--feature need not be restricted to DASGS/DTVE-->
                <?php elseif (Applicant::hasBeenIssuedOffer($applicant->personid) == false  && Applicant::isVerified($applicant->personid) == true  /*&& Applicant::getApplicantIntent($applicant->personid)  == 1*/  && ($division_id == 1 || ($division_id != 1 && $target_application->divisionid == $division_id))):?>
                    <?php if (
                                    (count($applications) == 2  && $target_application->ordering == 1  && $target_application->divisionid == $applications[1]->divisionid)
                                    ||
                                    (count($applications) == 3  && ($target_application->ordering == 1  && $target_application->divisionid == $applications[1]->divisionid))
                                    ||
                                    (count($applications) == 3  && ($target_application->ordering == 2  && $target_application->divisionid == $applications[2]->divisionid))
                                ):?>
                        <div class="pull-right" style="margin-right:2.5%">
                            <?=Html::a(
                                    ' Reject All',
                                    ['process-applications/power-rejection',  'personid' => $applicant->personid,  'programme' => $programme, 'application_status' => $application_status, 'programme_id' => $programme_id],
                                    ['class' => 'btn btn-danger',
                                            'style' => '',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to reject all contiguous applications for your division?',
                                            ],
                                        ]
                                );?>
                        </div>
                    <?php endif;?>

                    <div class="pull-right" style="margin-right:10px">
                        <?php if (Yii::$app->user->can('Dean')  ||  Yii::$app->user->can('Deputy Dean') || Yii::$app->user->can('Admission Team Adjuster')):?>
                            <?=Html::a(
                                    ' Custom Offer',
                                    ['process-applications/custom-offer', 'personid' => $applicant->personid, 'programme' => $programme, 'application_status' => $application_status],
                                    ['class' => 'btn btn-warning',
                                            'style' => '',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to create a customized offer for student?',
    //                                                'method' => 'post',
                                            ],
                                        ]
                                );?>
                        </div>
                    <?php endif;?>
                <?php endif;?>
            </h2>

            <table class='table table-condensed' style="width: 95%; margin: 0 auto;">
                <tr>
                    <?php if ($application_status > 2):?>
                        <th>Active</th>
                    <?php endif;?>
                    <th>Priority</th>
                    <th>Division</th>
                    <th>Programme</th>
                    <th>Status</th>
                    <?php if ((Yii::$app->user->can('Dean')  || Yii::$app->user->can('Deputy Dean')) && $application_status > 2): ?>
                        <th>Action</th>
                    <?php endif;?>
                </tr>

                <?php for ($i = 0 ; $i< count($application_container) ; $i++): ?>
                    <tr>
                        <?php if ($application_status > 2):?>
                            <?php if ($application_container[$i]["istarget"] == true):?>
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
                            if ($application_status > 2) {
                                /*Application choice must not be one suggested by Dean/Deputy Dean and;
                                 * User must be a Dean or Deputy Dean to be able to change the status of an applicant's application
                                 */
                                if (Application::getCustomApplications($applicant->personid) == false && (Yii::$app->user->can('Registrar')  || Yii::$app->user->can('Dean')  ||  Yii::$app->user->can('Deputy Dean') || Yii::$app->user->can('Admission Team Adjuster'))) {
                                    /*
                                     * All users that are not 'System Admin' are only allowed to edit application choices that belong to their division
                                     */
                                    if ($division_id != 1 && $target_application->divisionid != $division_id) {
                                        echo "<td> N/A </td>";
                                    } else {
                                        /*
                                         * If user is a member of "All Divisions", "DTE" or "DNE" they have ability to change the application status
                                         * of any application that is the one under current consideration; or any application above it
                                         */
                                        if (EmployeeDepartment::getUserDivision() == 6  || EmployeeDepartment::getUserDivision() == 7  || EmployeeDepartment::getUserDivision() == 1) {
                                            if ($application_container[$i]["application"]->ordering <= $target_application->ordering) {
                                                echo "<td>";
                                                echo "<div class='dropdown'>";
                                                echo "<button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                                echo "Update Status...";
                                                echo "<span class='caret'></span>";
                                                echo "</button>";
                                                echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>";
                                                $statuses = ApplicationStatus::generateAvailableStatuses($application_container[$i]["application"]->applicationid, $application_container[$i]["application"]->applicationstatusid);
                                                $status_count = count($statuses[0]);
                                                for ($k = 0 ; $k < $status_count ; $k++) {
                                                    $hyperlink = Url::toRoute(['/subcomponents/admissions/process-applications/update-application-status',
                                                                                                    'applicationid' => $application_container[$i]["application"]->applicationid,
                                                                                                    'new_status' => $statuses[0][$k],

                                                                                                    //for 'actionViewByStatus($division_id, $application_status)' redirect
                                                                                                    'old_status' => $target_application->applicationstatusid,
                                                                                                    'divisionid' => $application_container[$i]["application"]->divisionid,
                                                                                                    'programme' => $programme,
                                                                                                    'programme_id' => $programme_id
                                                                                                 ]);
                                                    echo "<li><a href='$hyperlink'>{$statuses[1][$k]}</a></li>";
                                                }
                                                echo "</ul>";
                                                echo "</div>";
                                                echo "</td>";
                                            }
                                        } else {
                                            /*
                                            * If user is a member of "DASGS", "DTVE" they have ability to change any application status directly above the one under
                                            * current consideration if the current application is pending and the previous application is a programme offered by their division.
                                            */
                                            if ($application_container[$i]["istarget"] == true
                                                || (
                                                    $target_application->applicationstatusid == 3
                                                        && ($target_application->ordering - $application_container[$i]["application"]->ordering == 1)
                                                        && $application_container[$i]["application"]->divisionid == EmployeeDepartment::getUserDivision()
                                                )
                                               ) {
                                                echo "<td>";
                                                echo "<div class='dropdown'>
                                                        <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                                echo "Update Status...";
                                                echo "<span class='caret'></span>";
                                                echo "</button>";
                                                echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu'>";
                                                $statuses = ApplicationStatus::generateAvailableStatuses($application_container[$i]["application"]->applicationid, $application_container[$i]["application"]->applicationstatusid);
                                                $status_count = count($statuses[0]);
                                                for ($k = 0 ; $k < $status_count ; $k++) {
                                                    $hyperlink = Url::toRoute(['/subcomponents/admissions/process-applications/update-application-status',
                                                                                                    'applicationid' => $application_container[$i]["application"]->applicationid,
                                                                                                    'new_status' => $statuses[0][$k],

                                                                                                    //for 'actionViewByStatus($division_id, $application_status)' redirect
                                                                                                    'old_status' => $target_application->applicationstatusid,
                                                                                                    'divisionid' => $application_container[$i]["application"]->divisionid,
                                                                                                    'programme' => $programme,
                                                                                                    'programme_id' => $programme_id
                                                                                                 ]);
                                                    echo "<li><a href='$hyperlink'>{$statuses[1][$k]}</a></li>";
                                                }
                                                echo "</ul>";
                                                echo "</div>";
                                                echo "</td>";
                                            }
                                        }
                                    }
                                } else {
                                    echo "<td> N/A </td>";
                                }
                            }
                        ?>
                    </tr>
                <?php endfor; ?>
            </table><br/><br/><br/><br/><br/><br/><br/><br/>
        </div>
    </div>
</div>
