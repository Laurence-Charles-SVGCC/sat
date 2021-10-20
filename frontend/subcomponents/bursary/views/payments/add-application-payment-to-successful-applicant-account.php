<?php

use yii\helpers\Html;
?>

<div class="panel-heading">
  <span>Payments</span>

  <?php if (
    $showMissingApplicationSubmissionBillingChargeNotification == false
    && $showMissingApplicationAmendmentBillingChargeNotification == false
    && $showApplicantSubmissionPaymentForm == false
    && $showApplicantAmendmentPaymentForm == false
  ) : ?>
    <span class="pull-right">
      <?=
        Html::a(
          "Add Subsequent Ammendment",
          ["payments/add-subsequent-amendment-payment-to-applicant-profile", "username" => $username],
          ["class" => "btn btn-success"]
        );
      ?>
    </span><br /><br />
  <?php endif; ?>
</div>

<div class="panel-body">
  <?php if (
    $showMissingApplicationSubmissionBillingChargeNotification == true
    && $showMissingApplicationAmendmentBillingChargeNotification == true
  ) : ?>
    <div class="jumbotron">
      <h2>Fees Missing</h2>
      <p>
        Application fees for the application period corresponding to
        applicant"s submission does not exist. Please contact Bursar.
      </p>
    </div>
  <?php elseif (
    $showMissingApplicationSubmissionBillingChargeNotification == true
    && $showMissingApplicationAmendmentBillingChargeNotification == false
  ) : ?>
    <div class="jumbotron">
      <h2>Fees Missing</h2>
      <p>
        Application submission fees for the application period
        corresponding to applicant"s submission does not exist.
        Please contact Bursar.
      </p>
    </div>
  <?php elseif (
    $showMissingApplicationSubmissionBillingChargeNotification == false
    && $showMissingApplicationAmendmentBillingChargeNotification == true
  ) : ?>
    <div class="jumbotron">
      <h2>Fees Missing</h2>
      <p>
        Application ammendment fees for the application period
        corresponding to applicant"s submission does not exist.
        Please contact Bursar.
      </p>
    </div>

  <?php elseif (
    $showMissingApplicationSubmissionBillingChargeNotification == false
    && $showMissingApplicationAmendmentBillingChargeNotification == false
    && $showApplicantSubmissionPaymentForm == true
  ) : ?>
    <div class="row">
      <div class="col-sm-6">
        <?=
          $this->render(
            "completed-applicant-receipt-listing",
            ["dataProvider" => $dataProvider]
          );
        ?>
      </div>

      <div class="col-sm-6">
        <?=
          $this->render(
            "applicant-submission-payment-form",
            [
              "username" => $username,
              "applicantSubmissionPaymentForm" => $applicantSubmissionPaymentForm,
              "paymentMethods" => $paymentMethods
            ]
          );
        ?>
      </div>
    </div>
  <?php elseif (
    $showMissingApplicationSubmissionBillingChargeNotification == false
    && $showMissingApplicationAmendmentBillingChargeNotification == false
    && $showApplicantSubmissionPaymentForm == false
    && $showApplicantAmendmentPaymentForm == true
  ) : ?>
    <div class="row">
      <div class="col-sm-6">
        <?=
          $this->render(
            "completed-applicant-receipt-listing",
            ["dataProvider" => $dataProvider]
          );
        ?>
      </div>

      <div class="col-sm-6">
        <?=
          $this->render(
            "applicant-amendment-payment-form",
            [
              "username" => $username,
              "applicantAmendmentPaymentForm" => $applicantAmendmentPaymentForm,
              "paymentMethods" => $paymentMethods
            ]
          );
        ?>
      </div>
    </div>
  <?php elseif (
    $showMissingApplicationSubmissionBillingChargeNotification == false
    && $showMissingApplicationAmendmentBillingChargeNotification == false
    && $showApplicantSubmissionPaymentForm == false
    && $showApplicantAmendmentPaymentForm == false
  ) : ?>
    <?=
      $this->render(
        "completed-applicant-receipt-listing",
        ["dataProvider" => $dataProvider]
      );
    ?>
  <?php endif; ?>
</div>