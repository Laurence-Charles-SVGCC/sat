<?php

$this->title = $userFullname;

$this->params["breadcrumbs"][] =
  ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
  ["label" => "Find Account", "url" => ["profiles/search"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<span class="label label-info pull-right">
  <h5><?= $status ?></h5>
</span>
<div class="box box-primary table-responsive no-padding">
  <div class="box-body">
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation">
        <a href="#completed-applicant-personal" aria-controls="completed-applicant-personal" role="tab" data-toggle="tab">
          Profile
        </a>
      </li>
      <li role="presentation" class="active">
        <a href="#completed-applicant-payments" aria-controls="completed-applicant-payments" role="tab" data-toggle="tab">
          Payments
        </a>
      </li>
    </ul>

    <div class="tab-content">
      <?=
        $this->render(
          "completed-applicant-profile-tab-personal",
          [
            "username" => $username,
            "applicant" => $applicant,
            "displayPicture" => $displayPicture,
            "phone" => $phone,
            "personalEmail" => $personalEmail,
            "institutionalEmail" => $institutionalEmail,
            "beneficiaryDetails" => $beneficiaryDetails,
            "applicationDetails" => $applicationDetails,
          ]
        );
      ?>

      <?=
        $this->render(
          "completed-applicant-profile-tab-payments",
          [
            "applicant" => $applicant,
            "username" => $username,
            "showMissingApplicationSubmissionBillingChargeNotification" => $showMissingApplicationSubmissionBillingChargeNotification,
            "showMissingApplicationAmendmentBillingChargeNotification" => $showMissingApplicationAmendmentBillingChargeNotification,
            "showApplicantSubmissionPaymentForm" => $showApplicantSubmissionPaymentForm,
            "showApplicantAmendmentPaymentForm" => $showApplicantAmendmentPaymentForm,
            "applicantSubmissionPaymentForm" =>  $applicantSubmissionPaymentForm,
            "applicantAmendmentPaymentForm" =>  $applicantAmendmentPaymentForm,
            "paymentMethods" => $paymentMethods,
            "dataProvider" => $dataProvider
          ]
        );
      ?>
    </div>
  </div>
</div>