<?php

/* 
 * Author: Laurence Charles
 * Date Created: 19/12/2015
 * Date Last Modified: 19/12/2015
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    use frontend\models\Division;

    /* @var $this yii\web\View */
    $this->title = 'Find A Student';
?>
    
    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/sms_4.png" alt="student avatar">
                    <span class="custom_module_label">Welcome to the Student Management System</span> 
                    <img src ="css/dist/img/header_images/sms_4.png" alt="student avatar" class="pull-right">
                </a>        
            </div>
            
            

            <div class="custom_body"> 
                <h1 class="custom_h1">Instructions</h1>

                <div class="center_content general_text">
                    <p>
                        Welcome. This application facilitates the management of all student grades.  
                    </p> 

                    <div>
                        There are three ways in which you can navigate this application.
                        <ol>
                            <li>You may begin your search based on your Division of choice.</li>

                            <li>You may begin your search based on your Student ID.</li>

                            <li>You may begin your search based on your Student Name.</li>
                        </ol>
                    </div> 

                    <?php $form = ActiveForm::begin(
                        [
                        //'action' => Url::to(['gradebook/index']),
                        ]);
                    ?>

                        <p class="general_text">
                            Please select a method by which to begin your search.
                            <?= Html::radioList('search_type', null, ['division' => 'By Division' , 'studentid' => 'By StudentID', 'studentname' => 'By Student Name'], ['class'=> 'form_field', 'onclick'=> 'checkSearchType();']);?>
                        </p>

                        <div id="by_div" style="display:none">
                            <?php if ((Yii::$app->user->can('Deputy Dean') || Yii::$app->user->can('Dean'))  && !Yii::$app->user->can('System Administrator')):?>
                                <?= Html::dropDownList('division', null, Division::getDivisionsAssignedTo(Yii::$app->user->identity->personid));?>
                                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?> 
                            <?php else:?>
                                <?= Html::dropDownList('division', null, Division::getAllDivisions());?>
                                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>                               
                            <?php endif; ?>
                        </div>

                        <div id="by_id" style="display:none">
                            <?= Html::label( 'Student ID',  'id_label'); ?>
                            <?= Html::input('text', 'id_field'); ?>
                            <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>
                        </div>

                        <div id="by_name" style="display:none">
                            <?= Html::label( 'First Name',  'fname_label'); ?>
                            <?= Html::input('text', 'fname_field'); ?> <br/><br/>

                            <?= Html::label( 'Last Name',  'lname_label'); ?>
                            <?= Html::input('text', 'lname_field'); ?> 

                            <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>
                        </div>            
                    <?php ActiveForm::end(); ?>
                </div><hr>


                <?php if ($all_students_provider) : ?>
                    <h2 class="custom_h2"><?= "Search results for: " . $info_string ?></h2>
                    <?= $this->render('_find_a_student_result', [
                        'dataProvider' => $all_students_provider,
                        'info_string' => $info_string,
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>