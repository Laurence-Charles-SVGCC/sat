<?php

use yii\widgets\Breadcrumbs;
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

$this->params['breadcrumbs'][] = ['label' => 'Enroll Students', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => 'successful'])];
$this->params['breadcrumbs'][] = $this->title;
?>

div class="panel panel-default">
<div class="panel-heading">
    <h2 class="panel-title">
        <span>Enrollment Fee Report</span>

    </h2>
</div>
<div class="panel-body">
    <?=
        GridView::widget(
            [
                "dataProvider" => $dataProvider,
                "columns" => [
                    [
                        "attribute" => "fee",
                        "format" => "text",
                        "label" => "Fee"
                    ],
                    [
                        "attribute" => "cost",
                        "format" => "text",
                        "label" => "Cost"
                    ],
                    [
                        "attribute" => "status",
                        "format" => "text",
                        "label" => "Status"
                    ],
                    [
                        "label" => "Action",
                        "format" => "raw",
                        "value" => function ($row) {
                            if ($row["status"] == "Paid In Full") {
                                return "";
                            } else {
                                return Html::a(
                                    "Pay",
                                    Url::toRoute([
                                        "make-fee-payment",
                                        "username" => $row["username"],
                                        "billingChargeId" => $row["billingChargeId"]
                                    ]),
                                    ["class" => "btn btn-success"]
                                );
                            }
                        }
                    ],
                ],
            ]
        );
    ?>
</div>
</div>
<div class="box box-primary table-responsive no-padding" style="font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title; ?></span>
    </div>

    <?php $form = ActiveForm::begin(
        ['action' => Url::to(['register-student/enroll-student', 'personid' => $personid, 'programme' => $programme]),]
    );
    ?>

    <?= Html::hiddenInput('applicantid', $applicant->applicantid); ?>
    <?= Html::hiddenInput('offerid', $offerid); ?>
    <?= Html::hiddenInput('applicationid', $applicationid); ?>

    <div class="box-body" style="width:98%; margin: 0 auto;">
        <div>
            <p><strong>Applicant ID:</strong><?= $username; ?></p>
            <p><strong>Applicant Name:</strong><?= $applicant->title . ". " .  $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname; ?></p>
            <p><strong>Programme Under Offer:</strong><?= $programme; ?></p><br />
        </div>

        <fieldset>
            <legend><strong>Submitted Applications<strong></legend>
            <table class='table table-condensed'>
                <tr>
                    <th>Priority</th>
                    <th>Division</th>
                    <th>Programme</th>
                    <th>Status</th>
                </tr>

                <?php for ($i = 0; $i < count($application_container); $i++) : ?>
                    <tr>
                        <td> <?= $application_container[$i]["application"]->ordering ?> </td>
                        <td> <?= $application_container[$i]["division"] ?> </td>
                        <td> <?= $application_container[$i]["programme"] ?> </td>

                        <?php if ($application_container[$i]["istarget"] == true) : ?>
                            <td> <i class="glyphicon glyphicon-ok"></i> </td>
                        <?php else : ?>
                            <td><i class="glyphicon glyphicon-remove"></td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        </fieldset><br />

        <fieldset>
            <legend><strong>Review Information</strong></legend>
            <p>
                Would you like to review the applicant's profile?
                <?= Html::radioList('review-applicant', null, ["Yes" => "Yes", "No" => "No"], ['class' => 'form_field', 'onclick' => 'toggleProfileButton();']); ?>
            </p>

            <div id="profile-button" style="display:none">
                <a target="_blank" class="btn btn-info" href=<?= Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'search_status' => 'pending-unlimited', 'applicantusername' => $username]); ?> role="button"> View Applicant Profile</a>
            </div>
        </fieldset><br />

        <fieldset>
            <legend><strong>Enrollment Documents Checklist</strong></legend>
            <p>Select from the following list which documents the applicant presented on enrollment.</p>
            <div class="row">
                <div class="col-lg-3">
                    <?= Html::checkboxList(
                        'documents',
                        $selections,
                        ArrayHelper::map(
                            DocumentType::findAll(['isdeleted' => 0]),
                            'documenttypeid',
                            'name'
                        )
                    );
                    ?>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="box-footer">
        <span class="pull-right">
            <?php if (Yii::$app->user->can('registerStudent')) : ?>
                <?= Html::submitButton(' Enroll Student', ['class' => 'btn  btn-success']) ?>
            <?php endif; ?>
        </span>
    </div>
    <?php ActiveForm::end(); ?>
</div>