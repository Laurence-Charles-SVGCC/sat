<?php
    use yii\helpers\Html;
use yii\web\UrlManager;
use yii\helpers\Url;
use frontend\models\ApplicationPeriod;

?>

    <html>
        <div class="conditional_offer_email">
            <div id="introduction">
                <p><?= date("l F j, Y"); ?></p>
                <p>Dear <?= $first_name . ' ' . $last_name ?>,</p>

                <p>
                    We are pleased to inform you that your application to the St. Vincent and the Grenadines Community College has been successful.
                    You are offered a place in the <?= $programme; ?> at the <?= $division_name ?> commencing on <?= $package->commencementdate?>.<br/>
                    Your Student Number is: <?= $studentno; ?>.
                </p>
            </div>

            <div id="body" style="white-space: pre;">
                <?= $package->emailcontent?>
            </div>

            <div id="salutations">
              <p>With warm wishes and kind regards,</p>
              <?php if (stripos(Url::home(true), "localhost") == false) :?>
                 <p>
                   <img src="https://sat.svgcc.online/images/email_header.png"
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
                </p><br/>

                 <div id="dtve-disclaimer" style="white-space: pre;">
                    <?= $package->disclaimer?>
                </div>
            </div>
        </div>
    </html>
