<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Url;
    use frontend\assets\LoginAsset;

    $this->title = 'Login';
    $this->params['breadcrumbs'][] = $this->title;

    LoginAsset::register($this);
?>

<div class="container" style="background-image: url('css/dist/img/college_home.png');">
    <div class="login-box">
        <div class="login-logo">
            <span class="logo-lg"><img src="<?= Url::to('css/dist/img/logo.png')?>"/></span>
        </div>
        
        <div class="login-box-body">
            <p class="login-box-msg"><strong style="font-size:1.1em">Welcome to SVGCC Administrative Terminal</strong></p>
                <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'username', ['options' => [
                        'tag'=>'div',
                        'class' => 'form-group field-loginform-username has-feedback required'
                        ],
                        'template' => '{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>
                        {error}{hint}'
                    ])->textInput(['placeholder' => 'Username'])?>
            
                    <?= $form->field($model, 'password', ['options' => [
                        'tag'=>'div',
                        'class' => 'form-group field-loginform-password has-feedback required'
                        ],
                        'template' => '{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        {error}{hint}'
                    ])->passwordInput(['placeholder' => 'Password']) ?>
                    
                    <div style="color:#999;margin:1em 0">
                        If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                    </div>
                   
                    <div class="form-group">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
    </div>
</div>