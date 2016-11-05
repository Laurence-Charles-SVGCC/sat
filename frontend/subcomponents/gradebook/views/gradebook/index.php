<?php

/* 
 * Author: Laurence Charles
 * Date Created: 04/12/2015
 * Date Last Modified: 04/12/2015
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    use frontend\models\Division;

    /* @var $this yii\web\View */
    $this->title = 'Grade Book';
?>
    
    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/gradebook/gradebook/index']);?>" title="Gradebook Home">     
                    <img class="custom_logo" src ="css/dist/img/header_images/grade_a+.png" alt="A+">
                    <span class="custom_module_label">Welcome to the SVGCC Grade Management System</span> 
                    <img src ="css/dist/img/header_images/grade_a+.png" alt="A+" class="pull-right">
                </a>        
            </div>
            
            

            <div class="custom_body"> 
                <h1 class="custom_h1">Instructions</h1>

                <div class="center_content general_text">
                    <p>
                        Welcome. This application facilitates the management of all student grades.  
                    </p> 

                    <div>
                        There are two ways in which you can navigate this application.
                        <ol>
                            <?php  if (Yii::$app->user->can('Cordinator') == false):?>
                                <li>You may begin your search based on your Division of choice.</li>
                            <?php endif;?>

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
                            <?php  if (Yii::$app->user->can('Cordinator') == false):?>
                                <?= Html::radioList('search_method', null, ['division' => 'By Division' , 'studentid' => 'By StudentID', 'studentname' => 'By Student Name'], ['class'=> 'form_field', 'onclick'=> 'checkSearchMethod();']);?>
                            <?php else:?>
                                <?= Html::radioList('search_method', null, ['studentid' => 'By StudentID', 'studentname' => 'By Student Name'], ['class'=> 'form_field', 'onclick'=> 'checkSearchMethod();']);?>
                            <?php endif;?>
                        </p>
                        
                        <div id="by_division" style="display:none">
                            <?php if ((Yii::$app->user->can('Deputy Dean') || Yii::$app->user->can('Dean')  || Yii::$app->user->can('Divisional Staff'))  && !Yii::$app->user->can('System Administrator')):?>
                                <?= Html::dropDownList('division_choice', null, Division::getDivisionsAssignedTo(Yii::$app->user->identity->personid));?>
                                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?> 
                            <?php else:?>
                                <?= Html::dropDownList('division_choice', null, Division::getAllDivisions());?>
                                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>                               
                            <?php endif; ?>
                        </div>

                        <div id="by_studentid" style="display:none">
                            <?= Html::label( 'Student ID',  'studentid_label'); ?>
                            <?= Html::input('text', 'studentid_field'); ?>
                            <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right']) ?>
                        </div>

                        <div id="by_studentname" style="display:none">
                            <?= Html::label( 'First Name',  'firstname_label'); ?>
                            <?= Html::input('text', 'firstname_field'); ?> <br/><br/>

                            <?= Html::label( 'Last Name',  'lastname_label'); ?>
                            <?= Html::input('text', 'lastname_field'); ?> 

                            <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right']) ?>
                        </div>            
                    <?php ActiveForm::end(); ?>
                </div><hr>


                <?php if ($all_students_provider) : ?>
                    <h2 class="custom_h2"><?= "Search results for: " . $info_string ?></h2>
                    <?= $this->render('_results', [
                        'dataProvider' => $all_students_provider,
                        'info_string' => $info_string,
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>