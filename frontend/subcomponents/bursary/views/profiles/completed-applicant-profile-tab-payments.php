<?php

use yii\helpers\Html;
?>

<div role="tabpanel" class="tab-pane active" id="completed-applicant-payments">
  <div class="panel panel-default">
    <div class="panel-heading">
      <span>Payments</span>
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
                "voided-receipts-listing",
                ["voidedReceiptsDataProvider" => $voidedReceiptsDataProvider]
              );
            ?>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <?=
              $this->render(
                "completed-applicant-receipt-listing",
                [
                  "dataProvider" => $dataProvider,
                  "showVoidedReceiptDisplayButton" => $showVoidedReceiptDisplayButton
                ]
              );
            ?>
          </div>

          <div class="col-sm-6">
            <?=
              $this->render(
                "completed-applicant-submission-payment-form",
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
          <div class="col-sm-12">
            <?=
              $this->render(
                "voided-receipts-listing",
                ["voidedReceiptsDataProvider" => $voidedReceiptsDataProvider]
              );
            ?>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-7">
            <?=
              $this->render(
                "completed-applicant-receipt-listing",
                [
                  "dataProvider" => $dataProvider,
                  "showVoidedReceiptDisplayButton" => $showVoidedReceiptDisplayButton
                ]
              );
            ?>
          </div>

          <div class="col-sm-5">
            <?=
              $this->render(
                "completed-applicant-amendment-payment-form",
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
        <div class="row">
          <div class="col-sm-12">
            <?=
              $this->render(
                "voided-receipts-listing",
                ["voidedReceiptsDataProvider" => $voidedReceiptsDataProvider]
              );
            ?>
          </div>
        </div>

        <?=
          $this->render(
            "completed-applicant-receipt-listing",
            [
              "dataProvider" => $dataProvider,
              "showVoidedReceiptDisplayButton" => $showVoidedReceiptDisplayButton
            ]
          );
        ?>
      <?php endif; ?>
    </div>
  </div>
</div>