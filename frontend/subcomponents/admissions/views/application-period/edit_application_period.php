<?php

/* 
 * Author: Laurence Charles
 * Date Created 09/02/2016
 */
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    
    $status = [
        '' => 'Select Status',
        4 => 'active',
        5 => 'close'
    ];
    
    $type = [
        '' => 'Select Type',
        1 => 'Full-time Enrollment',
        2 => 'Part-time Enrollment'
    ];
    
    $this->title = 'Edit Application Period';
?>

<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">  
            <h1 class="custom_h1">Configure Application Period </h1>
            <?php
                $form = ActiveForm::begin([
                        'id' => 'edit-contact-details-form',
                        'options' => [
//                                    'class' => 'form-layout form-inline'
//                                    'class' => 'form-inline',
                        ],
                    ]);

                    echo "<br/>";
                    echo "<table class='table table-hover' style='width:80%; margin: 0 auto;'>";
                        echo "<tr>";
                            echo "<th style='width:30%; vertical-align:middle'>Name</th>";
                                echo "<td>{$form->field($period, 'name')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='vertical-align:middle;'>Division</th>";
                                echo "<td>{$form->field($period, 'divisionid')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";

                        echo "<tr>";
                            echo "<th style='vertical-align:middle;'>Academic Year</th>";
                                echo "<td>{$form->field($period, 'academicyearid')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='vertical-align:middle;'>On-site Start Date</th>";
                                echo "<td>{$form->field($period, 'onsitestartdate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='vertical-align:middle;'>On-site End Date</th>";
                                echo "<td>{$form->field($period, 'onsiteenddate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='vertical-align:middle;'>Off-site Start Date</th>";
                                echo "<td>{$form->field($period, 'offsitestartdate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='vertical-align:middle;'>Off-site End Date</th>";
                                echo "<td>{$form->field($period, 'offsiteenddate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th>Type</th>";
                            echo "<td>{$form->field($period, 'applicationperiodtypeid')->label('', ['class'=> 'form-label'])->dropDownList($type)}</td>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='vertical-align:middle;'>Status</th>";
                            echo "<td>{$form->field($period, 'applicationperiodstatusid')->label('', ['class'=> 'form-label'])->dropDownList($status)}</td>";
                        echo "</tr>";
                    echo "</table>"; 

                    echo "<br/>";

                    echo Html::a(' Cancel',['admissions/manage-application-period'], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                    echo Html::submitButton('Update', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);

                                
                ActiveForm::end();    
            ?>
        </div>
        
        
    </div>
</div>
            



