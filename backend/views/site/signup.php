<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use frontend\assets\LoginAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;

LoginAsset::register($this);
?>
<div class="site-signup">
    <div class="register-box">
        <div class="login-logo">
            <span class="logo-lg"><img src="<?= Url::to('css/dist/img/logo.png')?>"/></span>
        </div>
    <div class="register-box-body">
        <p class="register-box-msg">Welcome to SAT Administrators Console. Create an account to begin</p>
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($employee_model, 'firstname', ['options' => [
                    'tag'=>'div',
                    'class' => 'form-group field-signupform-firstname has-feedback required'
                    ],
                    'template' => '{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>
                    {error}{hint}'
                ])->textInput(['placeholder' => 'Firstname']) ?>
                <?= $form->field($employee_model, 'lastname', ['options' => [
                    'tag'=>'div',
                    'class' => 'form-group field-signupform-lastname has-feedback required'
                    ],
                    'template' => '{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>
                    {error}{hint}'
                ])->textInput(['placeholder' => 'Lastname']) ?>
                <?= $form->field($model, 'email', ['options' => [
                    'tag'=>'div',
                    'class' => 'form-group field-signupform-emailname has-feedback required'
                    ],
                    'template' => '{input}<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    {error}{hint}'
                ])->textInput(['placeholder' => 'Email']) ?>
                <?= $form->field($model, 'password', ['options' => [
                    'tag'=>'div',
                    'class' => 'form-group field-signupform-password has-feedback required'
                    ],
                    'template' => '{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    {error}{hint}'
                ])->passwordInput(['placeholder' => 'Password']) ?>
                <?= $form->field($model, 'confirm_password', ['options' => [
                    'tag'=>'div',
                    'class' => 'form-group field-signupform-confirm_password has-feedback required'
                    ],
                    'template' => '{input}<span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    {error}{hint}'
                ])->passwordInput(['placeholder' => 'Confirm Password']) ?>
                <div class="form-group">
                    <?= Html::submitButton('Register', ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
