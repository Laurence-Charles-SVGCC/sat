<?php
    use yii\helpers\Html;
    use yii\web\UrlManager;
?>

<div>
    <h3>Welcome <?= $model->getFullName() ?> </h3>
    
    <div>
        You have completed your first step of requesting an applicant account.<br/><br/>
        In order to be issued an applicant account you need to follow the instructions below:
        <dl>
            <dt>1. Verify your email address</dt>
            <dd>To verify your email address click the link below labeled <strong>'Click this link to verify your email address'</strong>.</dd>
                <dd>This would lead you to a page where you will complete the application registration process.</dd>
            <dt>2. Complete Registration</dt>
            <dd>Enter your Applicant ID : <strong><?=  $model->applicantname ?></strong></dd>
                <dd>Enter and confirm (re-enter) your password.</dd>
                <dd>Click the 'Sign-up' button.</dd>
        </dl>
    </div>
    
    <p><?= Html::a(Html::encode('click this link to verify your email address'),  $reset_url) ?></p>
    
    <div>
        <strong>Kind regards,<br />
        SVGCC</strong><br />
    </div>
    
    <p>
        * This e-mail is intended only for the address named above. As this e-mail may contain confidential or privileged information,
        if you are not the named address, you are not authorised to retain,  read, copy or disseminate this message or any part of it.
    </p>
</div>
