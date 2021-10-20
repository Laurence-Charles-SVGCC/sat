<?php

use yii\helpers\Html;
?>

<div style="font-size:1.2em">
  <p>Dear <?= Html::encode($userFullname) ?>,</p><br />

  <p>
    Please take note that a
    <span style="font-weight:bold"><?= Html::encode($holdType->name) ?></span>
    has been applied to your record. <?= $holdType->displaymessage; ?>.
  </p>

  <p style="white-space: pre-line;">
    <?= $notificationForm->content ?>
  </p>
</div>