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
              "successful-applicant-receipt-listing",
              ["dataProvider" => $dataProvider]
            );
          ?>
        </div>
      </div>
    </div>
  </div>
</div>