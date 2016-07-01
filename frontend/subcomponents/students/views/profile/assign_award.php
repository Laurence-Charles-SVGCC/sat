<?php

/* 
 * Date Created: 28/04/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\Award;
    use frontend\models\PersonAward;
    
    $this->title = $action .' Award';
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
                <h1 class="custom_h1"><?=$this->title?></h1>

                <?php
                    $form = ActiveForm::begin([
                                'id' => 'assign_award',
                                'options' => [
                                    'style' => 'width:90%; margin: 0 auto;',
                                ],
                            ]);
                ?>
                    <table class='table table-hover' style='margin: 0 auto;'>
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Award</th>
                            <td><?=$form->field($award_assignment, 'awardid')->label('')->dropDownList(ArrayHelper::map(Award::find()->all(), 'awardid', 'name'), ['prompt'=>'Select Award']) ?></td>
                        </tr>

                        <tr>
                            <th style='width:30%; vertical-align:middle'>Comments</th>
                            <td><?=$form->field($award_assignment, 'comments')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'style' => 'vertical-align:middle', 'rows' => 5])?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Date Awarded</th>
                            <td><?=$form->field($award_assignment, 'dateawarded')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])?></td>
                        </tr>
                    </table><br/>

                    <?= Html::a(' Cancel',['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);?>
                    <?= Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);?>

                <?php ActiveForm::end(); ?>   
            </div>
        </div>
    </div>


