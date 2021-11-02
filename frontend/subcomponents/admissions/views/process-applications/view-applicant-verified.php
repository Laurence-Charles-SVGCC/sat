<?php

use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Application Details';

$this->params['breadcrumbs'][] =
    [
        'label' => 'Review Applicants',
        'url' => Url::toRoute(['/subcomponents/admissions/process-applications'])
    ];

$this->params['breadcrumbs'][] =
    [
        'label' => $applicationStatusName,
        'url' => Url::to(
            [
                'process-applications/view-by-status',
                'division_id' => $userDivisionId,
                'application_status' => $applicationStatus
            ]
        )
    ];

$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="text-center">
    <?= $applicantUsername . " - " . $applicantFullName; ?>
</h1>

<!-- Duplicate Flag-->
<?php if ($duplicateMessage == true) : ?>
    <br />
    <p id="offer-message" class="alert alert-info">
        <?= $duplicateMessage; ?>
    </p>
<?php endif; ?>

<!-- Offer Flag-->
<?php if ($applicantHasOffer == true) : ?>
    <br />
    <p id="offer-message" class="alert alert-info">
        <?= $offerDescription; ?>
    </p>
<?php endif; ?>

<!-- No English Flag-->
<?php if ($applicantHasCsecEnglish == false) : ?>
    <p id="offer-message" class="alert alert-info">
        <?= "Applicant did not pass CSEC/GCE English Language"; ?>
    </p>
<?php endif; ?>

<!-- No Mathematics Flag-->
<?php if ($applicantHasCsecMathematics == false) : ?>
    <p id="offer-message" class="alert alert-info">
        <?= "Applicant did not pass CSEC/GCE Mathematics"; ?>
    </p>
<?php endif; ?>

<!-- Has Less Than 5 Subjects Flag-->
<?php if ($applicantHasFiveCsecPasses == false) : ?>
    <p id="offer-message" class="alert alert-info">
        <?= "Applicant does not have 5 CSEC passes"; ?>
    </p>
<?php endif; ?>

<!-- DTE Relevant Science Subjects Flag-->

<?php if ($isDteApplicantWithoutRelevantSciences == true) : ?>
    <p id="offer-message" class="alert alert-info">
        <?= "Applicant does not have the necessary passes in relevant science subjects"; ?>
    </p>
<?php endif; ?>

<!-- DNE Relevant Science Subjects Flag-->
<?php if ($isDneApplicantWithoutRelevantSciences == true) : ?>
    <p id="offer-message" class="alert alert-info">
        <?= "Applicant does not have the necessary passes in relevant science subjects"; ?>
    </p>
<?php endif; ?>

<?=
    $this->render(
        'view-applicant-verified-programme-stats',
        [
            'currentApplication' => $currentApplication,
            'programme' => $programme,
            'conditionalOffersMade' => $conditionalOffersMade,
            'fullOffersMade' => $fullOffersMade,
            'programmeExpectedIntake' => $programmeExpectedIntake,
            'cape' => $cape,
            'capeInfo' => $capeInfo,
        ]
    );
?>

<div class="box box-primary">
    <?php if ($applicantProgressMessage == true) : ?>
        <p class="alert alert-error" style="margin:5px">
            <?= $applicantProgressMessage ?>
        </p>
    <?php endif; ?>

    <div class="box-header">
        <h3>
            <span>Programme Choices</span>
            <span class="pull-right">
                <?php
                if ($userCanIssueUserDefinedOffer == true) {
                    echo Html::a(
                        'Issue Custom Offer',
                        [
                            'process-applications/custom-offer',
                            'personid' => $applicant->personid,
                            'programme' => $programme,
                            'application_status' => $application_status
                        ],
                        [
                            'style' => 'margin: 5px',
                            'class' => 'btn btn-primary',
                            'data' => [
                                'confirm' => 'Are you sure you want to create a customized offer for student?',
                            ],
                        ]
                    );
                }

                if ($userCanPerformAdminReset == true) {
                    echo Html::a(
                        "Reset All",
                        [
                            'process-applications/full-applicant-reset',
                            'personid' => $applicant->personid,
                            'programme' => $programme,
                            'application_status' => $application_status,
                            'programme_id' => $programme_id
                        ],
                        [
                            'title' => 'Remove any offers or rejections associated with programme choices and set all statuses back to "Verified"',
                            'class' => 'btn btn-warning',
                            'style' => 'margin: 5px',
                            'data' => [
                                'confirm' => 'Are you sure you want to reset application back to verified status?'
                            ]
                        ]
                    );
                }

                if ($userCanPerformPowerRejection == true) {
                    echo Html::a(
                        'Reject All',
                        ['process-applications/power-rejection',  'personid' => $applicant->personid,  'programme' => $programme, 'application_status' => $application_status, 'programme_id' => $programme_id],
                        [
                            'class' => 'btn btn-danger',
                            'title' => 'Reject all applications under your authority',
                            'class' => 'btn btn-danger',
                            'style' => 'margin: 5px',
                            'data' => [
                                'confirm' => 'Are you sure you want to reject all contiguous applications for your division?',
                            ],
                        ]
                    );
                }
                ?>
            </span>
        </h3>
    </div>

    <div class="box-body">
        <table class="table table-condensed" style="margin-bottom: 50px">
            <tr>
                <th></th>
                <th>Priority</th>
                <th>Division</th>
                <th>Programme</th>
                <th>Status</th>
                <?php if ($userCanAccessActionColumn == true) : ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>

            <?php foreach ($programmeChoices as $programmeChoice) : ?>
                <tr>
                    <?php if ($programmeChoice["isCurrentApplication"] == true) : ?>
                        <td><i class="fa fa-hand-o-right"></i> </td>
                    <?php else : ?>
                        <td></td>
                    <?php endif; ?>
                    <td> <?= $programmeChoice["ordering"] ?> </td>
                    <td> <?= $programmeChoice["divisionAbbreviation"] ?> </td>
                    <td> <?= $programmeChoice["programmeDescription"] ?> </td>
                    <td> <?= $programmeChoice["status"] ?> </td>

                    <?php if ($userCanUpdateApplicationStatus == false) : ?>
                        <td></td>
                    <?php else : ?>
                        <td>
                            <div class='dropdown'>
                                <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                                    Update Status...
                                    <span class='caret'></span>
                                </button>
                                <ul class='dropdown-menu' aria-labelledby='dropdownMenu'>
                                    <?php foreach ($programmeChoice["availableStatusOptions"]  as $id => $name) : ?>
                                        <li>
                                            <?=
                                                Html::a(
                                                    "{$name}",
                                                    [
                                                        'process-applications/update-application-status',
                                                        'applicationid' => $programmeChoice["applicationId"],
                                                        'new_status' => $id,
                                                        'old_status' => $application_status,
                                                        'divisionid' => $programmeChoice["divisionId"],
                                                        'programme' => $programme,
                                                        'programme_id' => $programme_id
                                                    ]
                                                );
                                            ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class='active' role="presentation">
                <a href="#academic-qualifications" aria-controls="academic-qualifications" role="tab" data-toggle="tab">
                    Academic Qualifications
                </a>
            </li>
            <li role="presentation">
                <a href="#personal-information" aria-controls="personal-information" role="tab" data-toggle="tab">
                    Personal Information
                </a>
            </li>
            <li role="presentation">
                <a href="#institutions-attended" aria-controls="institutions-attended" role="tab" data-toggle="tab">
                    Institutions Attended
                </a>
            </li>
            <?php if ($applicant->applicantintentid == 4) : //if DTE Applicant
            ?>
                <li role="presentation">
                    <a href="#dte-additional-details" aria-controls="dte-additional-details" role="tab" data-toggle="tab">
                        Additional Details
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($applicant->applicantintentid == 6) : //if DNE Applicant
            ?>
                <li role="presentation">
                    <a href="#dne-additional-details" aria-controls="dne-additional-details" role="tab" data-toggle="tab">
                        Additional Details
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <?=
                $this->render(
                    'view-applicant-verified-academic-qualifications-tab',
                    [
                        'applicant' => $applicant,
                        'centreName' => $centreName,
                        'cseccentreid' => $cseccentreid,
                        'verifiedCsecQualificationsDataProvider' =>
                        $verifiedCsecQualificationsDataProvider,
                        'postSecondaryQualification' => $postSecondaryQualification,
                        'externalQualification' => $externalQualification,
                    ]
                );
            ?>

            <?=
                $this->render(
                    'view-applicant-verified-personal-information-tab',
                    [
                        'programme' => $programme,
                        'application_status' => $application_status,
                        'programme_id' => $programme_id,
                        'applicant' => $applicant,
                        'phone' => $phone,
                        'email' => $email,
                        'permanentaddress' => $permanentaddress,
                        'residentaladdress' => $residentaladdress,
                        'postaladdress' => $postaladdress,
                        'old_beneficiary' => $old_beneficiary,
                        'new_beneficiary' => $new_beneficiary,
                        'mother' => $mother,
                        'father' => $father,
                        'nextofkin' => $nextofkin,
                        'old_emergencycontact' => $old_emergencycontact,
                        'new_emergencycontact' => $new_emergencycontact,
                        'guardian' =>  $guardian,
                        'spouse' => $spouse,
                    ]
                );
            ?>

            <?=
                $this->render(
                    'view-applicant-verified-academic-institutions-tab',
                    ['secondaryAttendances' => $secondaryAttendances]
                );
            ?>

            <?php if ($applicant->applicantintentid == 4) : ?>
                <?=
                    $this->render(
                        'view-applicant-verified-dte-additional-information-tab',
                        [
                            'general_work_experience' => $general_work_experience,
                            'references' => $references,
                            'teaching' => $teaching,
                            'teachinginfo' => $teachinginfo,
                            'criminalrecord' => $criminalrecord,
                            'teachingApplicantHasChildren' =>
                            $teachingApplicantHasChildren,
                        ]
                    );
                ?>
            <?php endif; ?>

            <?php if ($applicant->applicantintentid == 6) : ?>
                <?=
                    $this->render(
                        'view-applicant-verified-dne-additional-information-tab',
                        [
                            'applicant' => $applicant,
                            'general_work_experience' => $general_work_experience,
                            'references' => $references,
                            'nursing' => $nursing,
                            'nursing_certification' => $nursing_certification,
                            'nursinginfo' => $nursinginfo,
                            'criminalrecord' => $criminalrecord,
                            'aplicantHasMidwiferyApplication' => $aplicantHasMidwiferyApplication,
                            'nursingApplicantHasChildren' => $nursingApplicantHasChildren,
                            'nursingApplicantIsMember' => $nursingApplicantIsMember,
                            'nursingApplicantHasOtherApplications' => $nursingApplicantHasOtherApplications,
                            'nursingApplicantHasPreviousApplication' => $nursingApplicantHasPreviousApplication,
                        ]
                    );
                ?>
            <?php endif; ?>
        </div>
    </div>
</div>