<?php

/* 
 * 'add_school' view.  Used for modifying information in the 'General' section of 'Profile' tab
 * Author: Laurence Charles
 * Date Created: 28/02/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\Institution;
    
    $this->title = 'Add School';
    
    $graduated = [
                '' => 'Select..',
                1 => 'Yes',
                0 => 'No'
    ];
    
    $level = NULL;
    if($levelid == 1)
        $level = "Pre School";
    elseif($levelid == 2)
        $level = "Primary School";
    elseif($levelid == 3)
        $level = "Secondary School";
    elseif($levelid == 4)
        $level = "Tertiary School";
?>

    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                    <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                    <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
                </a>   
            </div>
            
            <div class="custom_body">
                <h1 class="custom_h1">Add New <?=$level?></h1>

                <?php
                    $form = ActiveForm::begin([
                                //'action' => Url::to(['gradebook/index']),
                                'id' => 'add-new-school-form',
                                'options' => [
                                ],
                            ]);

                        echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Name</th>";
                                echo "<td>{$form->field($school, 'institutionid')->label('')->dropDownList(Institution::initializeSchoolList($levelid), ['style'=> 'font-size:14px;'])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Start Date</th>";
                                echo "<td>{$form->field($school, 'startdate')->label('')->widget(
                                                        DatePicker::className(), [
                                                        // inline too, not bad
                                                            'inline' => false,
    //                                                      modify template for custom rendering
                                                            'template' => '{addon}{input}',
                                                            'clientOptions' => [
                                                                'autoclose' => true,
                                                                'format' => 'yyyy-mm-dd',                    
                                                            ]
                                                        ]
                                                    )}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>End Date</th>";
                                echo "<td>{$form->field($school, 'enddate')->label('')->widget(
                                                        DatePicker::className(), [
                                                        // inline too, not bad
                                                            'inline' => false,
    //                                                      modify template for custom rendering
                                                            'template' => '{addon}{input}',
                                                            'clientOptions' => [
                                                                'autoclose' => true,
                                                                'format' => 'yyyy-mm-dd',                    
                                                            ]
                                                        ]
                                                    )}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle; min-width:400px;'>Has student graduated from this institution?</th>";
                                echo "<td>{$form->field($school, 'hasgraduated')->label('')->dropDownList($graduated)}</td>";
                            echo "</tr>";                     
                        echo "</table>";

                        echo Html::a(' Cancel',['view-applicant/applicant-profile', 'applicantusername' => $user->username], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);

                    ActiveForm::end();    
                ?>
            </div>
        </div>
    </div>
