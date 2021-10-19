<?php

use yii\helpers\Html;

?>

<div role="tabpanel" class="tab-pane active" id="student-payments">
  <div class="panel panel-default">
    <div class="panel-heading">
      <span>Payments</span>

      <div class="btn-group pull-right">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Select action...
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
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
      </div><br /><br />
    </div>

    <div class="panel-body">
      <div class="row">
        <div class="col-sm-12">
          <?=
            $this->render(
              "student-receipt-listing",
              ["dataProvider" => $dataProvider]
            );
          ?>
        </div>
      </div>
    </div>
  </div>
</div>