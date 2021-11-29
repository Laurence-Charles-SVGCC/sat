<?php

use yii\helpers\Html;

?>

<div role="tabpanel" class="tab-pane active" id="student-payments">
  <div class="panel panel-default">
    <div class="panel-heading">
      <span>Payments</span>

      <ul class="pull-right">
        <?php foreach ($studentRegistrations as $studentRegistrationId => $programme) : ?>
          <li>
            <?=
              Html::a(
                "Manage {$programme} Scheduled Fees",
                [
                  "student-payments/scheduled-fee-report",
                  "username" => $username,
                  "studentRegistrationId" => $studentRegistrationId
                ]
              );
            ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="panel-body">
      <?php if ($showVoidedReceiptDisplayButton === true) : ?>
        <button id="show-student-voided-receipts-button" type="button" class="pull-right btn btn-xs btn-warning" onclick="showAllVoidedReceiptsAndHideButton()">
          Show Voids
        </button><br />
      <?php endif; ?>

      <div class="row">
        <div class="col-sm-12">
          <?=
            $this->render(
              "student-voided-receipts-listing",
              ["voidedReceiptsDataProvider" => $voidedReceiptsDataProvider]
            );
          ?>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <?=
            $this->render(
              "student-receipt-listing",
              [
                "dataProvider" => $dataProvider,
                "voidedReceiptsDataProvider" => $voidedReceiptsDataProvider,
                "showVoidedReceiptDisplayButton" => $showVoidedReceiptDisplayButton
              ]
            );
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function showAllVoidedReceiptsAndHideButton() {
    const toggleButton =
      document.getElementById("show-student-voided-receipts-button");

    const voidedReceiptListing =
      document.getElementById("student-voided-receipt-listing");

    const elementsExist = toggleButton != null && voidedReceiptListing != null;

    if (elementsExist == true) {
      voidedReceiptListing.style.display = "block";
      toggleButton.style.display = "none";
    }
  }

  function hideStudentVoidedReceipts() {
    const voidedReceiptListing =
      document.getElementById("student-voided-receipt-listing");

    const toggleButton =
      document.getElementById("show-student-voided-receipts-button");

    const elementsExist =
      toggleButton != null &&
      voidedReceiptListing != null;

    if (elementsExist == true) {
      toggleButton.style.display = "block";
      voidedReceiptListing.style.display = "none";
    }
  }
</script>