<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Url;
    use frontend\assets\LoginAsset;

    $this->title = 'Request Password Reset';
    $this->params['breadcrumbs'][] = $this->title;

    LoginAsset::register($this);
?>
	
<div class="top">
     <h1 id="title" class="hidden">
         <span id="logo" style="margin: 0 auto; display: block"><img src="<?= Url::to('css/login/img/logo.png')?>"/></span>
     </h1>
</div>

<div class="login-box animated fadeInUp">
    <div class="box-header">
        <h2>Reset Password</h2>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'password')->passwordInput()->label(false) ?>
        
        <?= Html::submitButton('Save New Password ', []) ?><br/>
    <?php ActiveForm::end(); ?>
</div>