<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Fee Catalog';

$this->params["breadcrumbs"][] =
  ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div>
  <ul>
    <li>
      <?=
        Html::a(
          'Manage Application Fees',
          Url::toRoute(['application-fees/view-fee-listing'])
        );
      ?>
    </li>
    <li>
      <?=
        Html::a(
          'Manage Student Fees',
          Url::toRoute(['student-fees/view-fee-listing'])
        );
      ?>
    </li>
  </ul>


</div>