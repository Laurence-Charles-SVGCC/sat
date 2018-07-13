<?php
    use yii\helpers\Url;
?>

<div class="post_interview_rejection_email">
    <div id="introduction">
        <p><?= date("l F j, Y"); ?></p>
        <p>Dear <?= $first_name . ' ' . $last_name ?>,</p>
    </div>

    <div id="body" style="white-space: pre-wrap;">
        <?= $package->emailcontent?>
    </div>

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