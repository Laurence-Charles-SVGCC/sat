<?php

/* 
 * 'add_hold' view.  Used for modifying information in the 'General' section of 'Profile' tab
 * Author: Laurence Charles
 * Date Created: 04/01/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\Hold;
    
    $hold_type = "";
    if ($categoryid == 1)
        $hold_type = "Financial";
    elseif ($categoryid == 2)
        $hold_type = "Academic";
    elseif ($categoryid == 3)
        $hold_type = "Library";
    
    $this->title = 'Add Hold';
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
                <h1 class="custom_h1">Add New <?=$hold_type?> Hold</h1>

                <?php
                    $form = ActiveForm::begin([
                                //'action' => Url::to(['gradebook/index']),
                                'id' => 'add-hold',
                                'options' => [
//                                    'class' => 'form-layout'
//                                    'class' => 'form-inline',
                                ],
                            ]);

                        echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Hold Type</th>";
                                echo "<td>{$form->field($hold, 'holdtypeid')->label('')->dropDownList(Hold::initializeHoldList($categoryid), ['style'=> 'font-size:14px;'])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Details</th>";
                                echo "<td>{$form->field($hold, 'details')->label('')->textInput(['maxlength' => true, 'style'=> 'font-size:14px;'])}</td>";
                            echo "</tr>";
                        echo "</table>";

                        echo Html::a(' Cancel',['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);

                    ActiveForm::end();    
                ?>
            </div>
        </div>
    </div>

