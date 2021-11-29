<?php

use yii\helpers\Html;

?>

<div role="tabpanel" class="tab-pane active" id="successful-applicant-payments">
  <div class="panel panel-default">
    <div class="panel-heading">
      <span>Payments</span>

      <div class="btn-group pull-right">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Select action...
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <?php if ($showVoidedReceiptDisplayButton === true) : ?>
            <li id="successful-applicant-voided-receipts-visibility-toggle-button">
              <button type="button" class="btn btn-default" onclick="showVoidedReceiptsAndHideSuccessfulApplicantButton()" style="width:100%; background-color: transparent; border: none; text-align: left; color: #777; padding-left:20px">
                Show Voids
              </button>
            </li>
          <?php endif; ?>
          <li>
            <?=
              Html::a(
                "Add application payment",
                [
                  "successful-applicant-payments/add-application-payment",
                  "username" => $username,
                ]
              );
            ?>
          </li>
          <li>
            <?=
              Html::a(
                "Enrollment payments",
                [
                  "successful-applicant-payments/enrollment-payments-report",
                  "username" => $username,
                ]
              );
            ?>
          </li>
        </ul>
      </div><br /><br />
    </div>

    <div class="panel-body">
      <div class="row">
        <div class="col-sm-12">
          <?=
            $this->render(
              "successful-applicant-voided-receipts-listing",
              ["voidedReceiptsDataProvider" => $voidedReceiptsDataProvider]
            );
          ?>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <?=
            $this->render(
              "successful-applicant-receipt-listing",
              ["dataProvider" => $dataProvider]
            );
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function showVoidedReceiptsAndHideSuccessfulApplicantButton() {
    const toggleButton =
      document.getElementById(
        "successful-applicant-voided-receipts-visibility-toggle-button"
      );

    const voidedReceiptListing =
      document.getElementById("successful-applicant-voided-receipt-listing");

    const elementsExist = toggleButton != null && voidedReceiptListing != null;

    if (elementsExist == true) {
      voidedReceiptListing.style.display = "block";
      toggleButton.style.display = "none";
    }
  }
</script>