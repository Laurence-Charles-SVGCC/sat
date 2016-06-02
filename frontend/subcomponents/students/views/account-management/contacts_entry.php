<?php

/* 
 * Author: Laurence Charles
 * Date Created: 29/05/2016
 */

    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Url;
    
    use common\models\User;
    use frontend\models\Relation;
    use frontend\models\CompulsoryRelation;  
    use frontend\models\Applicant;
    use frontend\models\Address;
    use frontend\models\MedicalCondition;

    $this->title = 'Contact Information Entry';
    
?>

    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                    <img class="custom_logo_students" src ="<?=Url::to('../images/sms_4.png');?>" alt="Find A Student">
                    <span class="custom_module_label">Welcome to the Student Management System</span> 
                    <img src ="<?=Url::to('../images/sms_4.png');?>" alt="student avatar" class="pull-right">
                </a>   
            </div>
        
            <div class="custom_body"> 
                <h1 class="custom_h1"><?= $this->title?></h1>
                <br/>

                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'phone-information-form',
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
                        'validateOnBlur' => true,
                        'successCssClass' => 'alert in alert-block fade alert-success',
                        'errorCssClass' => 'alert in alert-block fade alert-error',
                        'options' => [
                            'class' => 'form-layout'
                        ],
                    ])
                ?>

                    <fieldset>
                        <legend>Phone Details</legend>
                        <div class="alert in alert-block fade alert-warning mainButtons">
                            <h4 style="text-align:center"><strong>Important Note</strong></h4> 
                            <p>
                                You are required to enter at least one contact number.
                            </p>
                        </div>
                        <?= $form->field($model, 'homephone')->label("Home Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'cellphone')->label("Cell Phone *", ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'workphone')->label("Work Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?> 
                    </fieldset></br>

                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']);?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>    
        </div>   
    </div>
           


