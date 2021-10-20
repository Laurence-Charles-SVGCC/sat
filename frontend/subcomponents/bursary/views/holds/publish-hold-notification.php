<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Email Hold Notification";

$this->params["breadcrumbs"][] =
  ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
  ["label" => "Find Account", "url" => ["profiles/search"]];

$this->params["breadcrumbs"][] =
  [
    "label" => "{$userFullname}",
    "url" => ["profiles/user-profile", "username" => $username]
  ];

$this->params["breadcrumbs"][] =
  [
    "label" => "{$holdDescription}",
    "url" => ["view", "id" => $hold->studentholdid]
  ];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="box box-primary table-responsive no-padding">
  <div class="box-body">
    <?php $form = ActiveForm::begin(["id" => "publish-hold-notification-form"]); ?>
    <div class="box-body">
      <div style="font-size:1.2em">
        <p>Dear <?= "{$userFullname}" ?>,</p><br />

        <p>
          Please take note that a
          <span style="font-weight:bold"><?= $holdType->name ?></span> has
          been applied to your record. <?= $holdType->displaymessage; ?>.
        </p>
      </div><br />

      <?=
        $form->field($model, "content")
          ->label(false)
          ->textArea(["rows" => 10]);
      ?>

      <?=
        Html::submitButton(
          "Send",
          [
            "id" => "publish-hold-notification-submit-button",
            "class" => "btn btn-success pull-right"
          ]
        );
      ?>
    </div>
    <?php ActiveForm::end(); ?>
  </div>
</div>