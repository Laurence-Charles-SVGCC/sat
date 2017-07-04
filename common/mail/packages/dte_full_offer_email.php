<?php
    use yii\helpers\Html;
    use yii\web\UrlManager;
    use yii\helpers\Url;
    use frontend\models\ApplicationPeriod;
?>

    <html>
        <div class="conditional_offer_email">
            <img src="http://www.svgcc.vc/subdomains/sat/frontend/images/header.png" alt="header" class="img-rounded" style="width:100%; height:150px;">
            
            <div id="introduction">
                <p><?= date("l F j, Y"); ?></p>
                <p>Dear <?= $first_name . ' ' . $last_name ?>,</p>
                
                <p>
                    We are pleased to inform you that your application to the St. Vincent and the Grenadines Community College has been successful.  
                    You are offered a place in the <?= $programme; ?> Programme at the <?= $division_name ?> commencing on <?= $package->commencementdate?>.<br/>
                    Your Student Number is: <?= $studentno; ?>.
                </p>
            </div>

            <div id="body" style="white-space: pre;">
                <?= $package->emailcontent?>
            </div>

            <div id="salutations">
                <p>With warm wishes and kind regards,</p>
                <p><img src="http://www.svgcc.vc/subdomains/sat/frontend/images/signature.png" alt="mrs-rouse-signature"></p>
                <!--<p><img src="http://localhost/sat_dev/frontend/images/signature.jpg" alt="mrs-rouse-signature"></p>-->
                <p>
                    Samantha Minors-Rouse
                    <br/>Registrar
                </p>
            </div>
        </div>
    </html>
