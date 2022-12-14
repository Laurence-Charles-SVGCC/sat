<?php
  use yii\helpers\Url;

?>

<div>
  <div id="introduction">
    <p><?= date("l F j, Y"); ?></p>
    <p>Dear <?= $first_name . ' ' . $last_name ?>,</p>
  </div><br/>

  <span id="body">
    <p>
      You have been shortlisted to interview for entry into the <?= $programme; ?>
      programme at the <?= $division_name ?>.
    </p>

    <?php if ($offer != false && $offer->appointment == true): ?>
      <p>
        <strong>Your interview is scheduled for <?= $offer->appointment;?></strong>.
      </p>
    <?php endif; ?>

    <?= $package->emailcontent?>
  </span>

  <div id="salutations">
    <p>With warm wishes and kind regards,</p>
    <?php if (stripos(Url::home(true), "localhost") == false) :?>
       <p>
         <img src="https://sat.svgcc.vc/images/signature.png"
         alt="mrs-rouse-signature">
       </p>
    <?php else: ?>
         <p>
           <img src="http://localhost/sat_dev/frontend/web/img/signature.png"
           alt="mrs-rouse-signature">
         </p>
    <?php endif; ?>

    <p>
      Samantha Minors-Rouse
      <br/>Registrar
    </p>
  </div>
<div>
