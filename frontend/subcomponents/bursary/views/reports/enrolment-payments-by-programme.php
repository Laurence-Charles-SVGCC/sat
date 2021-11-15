<?php

use yii\helpers\Html;

$this->title = 'Enrolment Fee Payments';

$this->params["breadcrumbs"][] =
  ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
  ["label" => "Report", "url" => ["reports/index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="enrolment-fees" style="min-height:2000px">
  <div id="application-periods">
    <span class="dropdown">
      <button class="btn btn-default dropdown-toggle btn-block" type="button" data-toggle="dropdown">
        <?php if ($applicationPeriodId == null) : ?>
          Select application period...
        <?php else : ?>
          Change from <?= "{$applicationPeriodName}..." ?>
        <?php endif; ?>

        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <?php foreach ($applicationPeriods as $period) : ?>
          <li>
            <?=
              Html::a(
                $period->name,
                [
                  "enrolment-payments-by-programme",
                  "applicationPeriodId" => $period->applicationperiodid,
                ]
              );
            ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </span><br />
  </div>

  <?php if ($applicationPeriodId != null && !empty($academicOfferings)) : ?>
    <div class="academic-offerings">
      <span class="dropdown">
        <button class="btn btn-default dropdown-toggle btn-block" type="button" data-toggle="dropdown">
          <?php if ($academicOfferingId == null) : ?>
            Select academic offering...
          <?php else : ?>
            Change from <?= "{$academicOfferingName}..." ?>
          <?php endif; ?>
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <?php foreach ($academicOfferings as  $id => $name) : ?>
            <li>
              <?=
                Html::a(
                  $name,
                  [
                    "enrolment-payments-by-programme",
                    "applicationPeriodId" => $applicationPeriodId,
                    "academicOfferingId" => $id,
                  ]
                );
              ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </span><br />
    </div>
  <?php endif; ?>

  <?php if ($applicationPeriodId != null && $academicOfferingId != null) : ?>
    <?=
      $this->render(
        "enrolment-payments-by-programme-report",
        ["dataProvider" => $dataProvider]
      );

    ?>

  <?php endif; ?>
</div>