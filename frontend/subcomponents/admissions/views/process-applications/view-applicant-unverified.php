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
  <?= $applicantUsername . " - " .$applicantFullName;?>
</h1>

<p id="offer-message" class="alert alert-warning">
    Applicant's certificates have not been verified yet.
</p>

<div class="box box-primary">
    <div class="box-body">
        <div style="margin-bottom: 50px">
            <h3>Programme Choices</h3>

            <table class="table table-condensed">
                <tr>
                    <th>Priority</th>
                    <th>Division</th>
                    <th>Programme</th>
                    <th>Status</th>
                </tr>

                <?php foreach ($programmeChoices as $programmeChoice): ?>
                    <tr>
                        <td> <?= $programmeChoice["ordering"]?> </td>
                        <td> <?= $programmeChoice["divisionAbbreviation"] ?> </td>
                        <td> <?= $programmeChoice["programmeDescription"] ?> </td>
                        <td> <?= $programmeChoice["status"] ?> </td>
                    </tr>
                <?php endforeach;?>
            </table>
        </div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class='active' role="presentation">
                <a href="#certificates" aria-controls="certificates" role="tab"
                data-toggle="tab">
                    Certificates
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <?=
                $this->render(
                    'view-applicant-unverified-certificates-tab',
                    [
                      'applicant' => $applicant,
                      'centreName' => $centreName,
                      'cseccentreid' => $cseccentreid,
                      'verifiedCsecQualificationsDataProvider' =>
                          $verifiedCsecQualificationsDataProvider,
                    ]
                );
            ?>
        </div>
    </div>
</div>
