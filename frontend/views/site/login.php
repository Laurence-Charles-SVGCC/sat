<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Url;
    use frontend\assets\LoginAsset;

    $this->title = 'Login';
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
        <h2>Log In</h2>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'username', ['options' => ['id' => 'username' ],])->textInput(['placeholder' => 'Username'])->label(false) ?>
        
         <?= $form->field($model, 'password', ['options' => ['id' => 'username'] ])->passwordInput(['placeholder' => 'Password'])->label(false) ?>
        
        <?= Html::submitButton('Log In ', []) ?><br/><br/>
    <?php ActiveForm::end(); ?>
    
    <?= Html::a('Forgot your password?', ['site/request-password-reset']) ?>
</div>
