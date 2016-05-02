<?php

/* 
 * Author: Laurence Charles
 * Date Created: 05/01/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\Event;
    use frontend\models\EventType;
    
    $this->title = "Create" . $event_type . "Record"
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
                <h1 class="custom_h1"><?=$this->title?></h1>
                
                </br>
                <?php
                    $form = ActiveForm::begin([
                                'id' => 'edit-event',
                                'options' => [
                                    'style' => 'width:90%; margin: 0 auto;',
                                ],
                            ]);
                ?>
                    <fieldset>
                        <legend class="custom_h2">Configure Record</legend>

                        <table class='table table-hover'>
                            <tr>
                                <th style='width:30%; vertical-align:middle'>Summary</th>
                                <td><?=$form->field($event_details, 'summary')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'style' => 'vertical-align:middle', 'rows' => 5])?></td>
                            </tr>

                            <tr>
                                <th style='width:30%; vertical-align:middle'>Description</th>
                                <td><?=$form->field($event_details, 'description')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'style' => 'vertical-align:middle', 'rows' => 15])?></td>
                            </tr>

                            <tr>
                                <th style='width:30%; vertical-align:middle'>Start Date</th>
                                <td><?=$form->field($event_details, 'startdate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])?></td>
                            </tr>

                            <tr>
                                <th style='width:30%; vertical-align:middle'>End Date</th>
                                <td><?=$form->field($event_details, 'enddate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])?></td>
                            </tr>
                        </table><br/>
                    </fieldset>

                   
                    <?= Html::a(' Cancel',['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);?>
                    <?= Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);?>

                <?php Activeform::end()?>
               
            </div>
        </div>
    </div>