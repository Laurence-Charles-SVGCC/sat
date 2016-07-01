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

    $this->title = 'Profile Entry';
    
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    
    $relationType = [
        '' => 'Select...',
        1 => 'Mother',
        2 => 'Father',
        3 => 'Next of Kin',
        5 => 'Guardian',
    ];
    
    $spouseRelations = [
        "" => 'Select...',
        7 => 'Spouse',
    ];
    
    $compulsory_relations = [
        '' => 'Select...',
        'mother' => 'Mother',
        'father' => 'Father',
        'next of kin' => 'Next of Kin',
        'guardian' => 'Guardian',
        'spouse' => 'Spouse',
        'other' => 'Other'
    ];
?>

    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                    <img class="custom_logo_students" src ="css/dist/img/create_male.png" alt="Find A Student">
                    <span class="custom_module_label">Welcome to the Student Management System</span> 
                    <img src ="css/dist/img/create_female.png" alt="student avatar" class="pull-right">
                </a>   
            </div>
        
            <div class="custom_body"> 
                <h1 class="custom_h1"><?= $this->title?></h1>
                <br/>

                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'profile-information-form',
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
                        <legend>Profile</legend>

                        <?= $form->field($model, 'title')->label('Title *', ['class'=> 'form-label'])->dropDownList(Yii::$app->params['titles']);?>  

                        <?= $form->field($model, 'firstname')->label('First Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'middlename')->label('Middle Name', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'lastname')->label('Last Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'dateofbirth')->label('Date Of Birth *', ['class'=> 'form-label'])->widget(
                                DatePicker::className(), [
                                    'inline' => false,
        //                             modify template for custom rendering
                                    'template' => '{addon}{input}',
                                    'clientOptions' => [
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd'
                                    ]
                                ]);
                        ?>

                        <?= $form->field($model, 'gender')->label('Gender *', ['class'=> 'form-label'])->inline()->radioList(Yii::$app->params['gender'], ['class'=> 'form-field']) ?>                                      

                        <?= $form->field($model, 'nationality')->label('Nationality *', ['class'=> 'form-label'])->dropDownList(Yii::$app->params['nationality']);?>

                        <?= $form->field($model, 'placeofbirth')->label('Place Of Birth *', ['class'=> 'form-label'])->dropDownList(Yii::$app->params['placeofbirth']);?>

                        <?= $form->field($model, 'religion')->label('Religion', ['class'=> 'form-label'])->dropDownList(Yii::$app->params['religion']);?>

                        <?= $form->field($model, 'maritalstatus')->label("What is your marital Status *", ['class'=> 'form-label'])->inline()->radioList(Yii::$app->params['maritalstatus'], ['class'=> 'form-field', 'onclick' => 'showSpouse();']);?>

                        <?= $form->field($model, 'sponsorname')->label("If sponsored please name the organisation(s).", ['class' => 'form-label'])->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'isexternal')->label("Were you awarded any academic certificates outside of St. Vincent and the Grenadines? *", ['class'=> 'form-label2'])->inline()->radioList(Yii::$app->params['external'], ['class'=> 'form-field'] );?>

                        <?= $form->field($model, 'otheracademics')->label("Techincal and Vocation Academic Experience", ['class'=> 'form-label'])->textArea(['rows' => '5']) ?>

                        <?= $form->field($model, 'nationalsports')->label("National Sports", ['class'=> 'form-label'])->textArea(['rows' => '5']) ?>

                        <?= $form->field($model, 'othersports')->label("Recreational Sports", ['class'=> 'form-label'])->textArea(['rows' => '5']) ?>

                        <?= $form->field($model, 'clubs')->label("Club Participation", ['class'=> 'form-label'])->textArea(['rows' => '5']) ?>

                        <?= $form->field($model, 'otherinterests')->label("Other Interests", ['class'=> 'form-label'])->textArea(['rows' => '5']) ?>
                    </fieldset></br>

                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']);?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>    
        </div>   
    </div>
           


