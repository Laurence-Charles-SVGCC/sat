<?php
    use yii\helpers\Url;
?>

<div class="dte_conditional_offer_email">
    <?php if (stripos(Url::home(true), "localhost") == false) :?>
        <img src="http://www.svgcc.vc/subdomains/sat/frontend/images/header.png" alt="header" class="img-rounded" style="width:100%; height:175px;">
    <?php else: ?>
        <img src="http://localhost/sat_dev/frontend/images/header.png" alt="header" class="img-rounded" style="width:100%; height:175px;">
    <?php endif; ?>

    <div id="introduction">
        <p><?= date("l F j, Y"); ?></p>
        <p>Dear <?= $first_name . ' ' . $last_name ?>,</p>
    </div><br/>

    <p>
        You have been shortlisted to interview for entry into the <?= $programme; ?> programme at the 
        <?= $division_name ?>.
    </p>
    
    <span id="body" style="white-space: pre-wrap;">
        <?= $package->emailcontent?>

        <?php if ($offer != false && $offer->appointment == true):?>
            <p><strong>Your interview is scheduled for <?= $offer->appointment;?></strong>.</p>
        <?php endif; ?>
    </span>

    <div id="salutations">
        <p>With warm wishes and kind regards,</p>
        <?php if (stripos(Url::home(true), "localhost") == false) :?>
           <p><img src="http://www.svgcc.vc/subdomains/sat/frontend/images/signature.png" alt="mrs-rouse-signature"></p>
        <?php else: ?>
             <p><img src="http://localhost/sat_dev/frontend/images/signature.jpg" alt="mrs-rouse-signature"></p>
        <?php endif; ?>

        <p>
            Samantha Minors-Rouse
            <br/>Registrar
        </p>
    </div>
</div>