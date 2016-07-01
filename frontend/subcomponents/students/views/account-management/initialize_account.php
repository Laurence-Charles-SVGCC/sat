<?php

/* 
 * Author: Laurence Charles
 * Date Created: 27/05/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap\ActiveForm;
    use yii\bootstrap\Modal;
    
    $this->title = 'Initialize Account';
    $this->title;
?>

    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/create_male.png" alt="Find A Student">
                    <span class="custom_module_label">Welcome to the Student Management System</span> 
                    <img src ="css/dist/img/header_images/create_female.png" alt="student avatar" class="pull-right">
                </a>   
            </div>
        
            <div class="custom_body"> 
                <h1 class="custom_h1"><?= $this->title?></h1>
                <br/>
                
                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'initialize-student',
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
                        'validateOnBlur' => true,
                        'successCssClass' => 'alert in alert-block fade alert-success',
                        'errorCssClass' => 'alert in alert-block fade alert-error',
                        'options' => [
                            'class' => 'form-layout',
                        ],
                    ]);
                ?>
                
                    <?= $form->field($model, 'title')->label('Title *', ['class'=> 'form-label'])->dropDownList(Yii::$app->params['titles']);?> 
                                         
                    <?= $form->field($model, 'firstname')->label('First Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'middlename')->label('Middle Name', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'lastname')->label('Last Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($model, 'email')->label('Student\'s Personal Email: ', ['class'=> 'form-label'])->textInput()?>

                    <?= $form->field($model, 'pword')->label('Password: ', ['class'=> 'form-label'])->passwordInput([]) ?>

                    <?= $form->field($model, 'confirm')->label('Confirm Password', ['class'=> 'form-label'])->passwordInput([]) ?>
                
                    <div class="form-group">
                       <?= Html::submitButton('Save' , ['class' => 'btn btn-success', 'style' => 'margin-left:90%; width:10%;']) ?>
                    </div>       
                <?php ActiveForm::end(); ?>
             </div>
        </div>
    </div>