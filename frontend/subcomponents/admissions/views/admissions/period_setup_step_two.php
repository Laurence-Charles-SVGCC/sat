<?php

/* 
 * Author: Laurence Charles
 * Date Created 11/02/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    
    $type = [
        '' => 'Select Type',
        1 => 'Full-time Enrollment',
        2 => 'Part-time Enrollment',
    ];
    
    $divisions = [
        '' => 'Select Division',
        4 => 'DASGS',
        5 => 'DTVE',
        6 => 'DTE',
        7 => 'DNE',
    ];

    $this->title = 'Application Period Setup Step-2';
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
            <h1 class="custom_h1">Configure Application Period</h1>
            
            <br/>
            <div style="width:70%; margin: 0 auto; font-size: 20px;">
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'create-application-period-form',
                        'options' => [
//                                            'class' => 'form-layout'
                        ],
                    ]);

                        echo "<br/>";
                        echo "<table class='table table-hover' style='width:100%; margin: 0 auto;'>";
                            echo "<tr>";
                                echo "<th style='width:30%; vertical-align:middle'>Name</th>";
                                    echo "<td>{$form->field($template_period, 'name')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>Division</th>";
                                    echo "<td>{$form->field($template_period, 'divisionid')->label('')->dropDownList($divisions)}</td>";
                                echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>Academic Year</th>";
                                    echo "<td>{$form->field($template_period, 'academicyearid')->label('')->dropDownList(AcademicYear::getCurrentAcademicYearPrepared())}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>On-Campus Start Date</th>";
                                    echo "<td>{$form->field($template_period, 'onsitestartdate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>On-Campus End Date</th>";
                                    echo "<td>{$form->field($template_period, 'onsiteenddate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>Off-Campus Start Date</th>";
                                    echo "<td>{$form->field($template_period, 'offsitestartdate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>Off-Campus End Date</th>";
                                    echo "<td>{$form->field($template_period, 'offsiteenddate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th>Type</th>";
                                echo "<td>{$form->field($template_period, 'applicationperiodtypeid')->label('', ['class'=> 'form-label'])->dropDownList($type)}</td>";
                            echo "</tr>";
                        echo "</table>"; 

                        echo "<br/>";
                        echo Html::a(' Cancel',['admissions/initiate-period', 'recordid' => $period->applicationperiodid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton('Save', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);        
                    ActiveForm::end();    
                ?>
                
            </div>
            
            
        </div>
    </div>
</div>
