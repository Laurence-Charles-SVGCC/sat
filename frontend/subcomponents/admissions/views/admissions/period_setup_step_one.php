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
    
    $intent = [
        '1' => 'DASGS/DTVE',
        '2' => 'DASGS (P)',
        '3' => 'DTVE (P)',
        '4' => 'DTE',
        '5' => 'DTE (P)',
        '6' => 'DNE',
        '7' => 'DNE (P)'
    ];
    
   $options = [
       'yes' => 'Yes',
       'no' =>'No'
   ];

    $this->title = 'Application Period Setup Step-1';
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
            <h1 class="custom_h1">Confirm Academic Year Availability </h1>
            
            <br/>
            <div style="width:70%; margin: 0 auto; font-size: 20px;">
                <p>The current academic year is <strong><?=$current_year->title?></strong>.</p>
                
                <p>
                    Please select the type of application period you intend to create.
                    <?= Html::radioList('intent', NULL, $intent, ['class'=> 'form-field', 'onclick'=> 'toggleAcademicYearMessage()']);?>
                </p>
                
                <p id="dasgs-dtve" style="display:none">
                    As you are intending to create a new application period for DASGS/DTVE full-time intake, you will not be required to create a new academic year record.</br>
                </p> 
                
                <p id="dasgs-part" style="display:none">
                    As you are intending to create a new application period for DASGS part-time intake, you will not be required to create a new academic year record.
                </p>
                
                <p id="dtve-part" style="display:none">
                    As you are intending to create a new application period for DTVE part-time intake, you will not be required to create a new academic year record.
                </p>
                
                <p id="dte" style="display:none">
                    As you are intending to create a new application period for DTE full-time intake, you may require the creation of a new academic year record.
                </p>
                
                <p id="dte-part" style="display:none">
                    As you are intending to create a new application period for DTE part-time intake, you will not be required to create a new academic year record.
                </p>
                
                <p id="dne" style="display:none">
                    As you are intending to create a new application period for DNE full-time intake, you may require the creation of a new academic year record.
                </p>
                
                <p id="dne-part" style="display:none">
                    As you are intending to create a new application period for DNE part-time intake, you will not be required to create a new academic year record.
                </p>
                
                
                <p id="new-year-question" style="display:none">
                    Do you wish to create a new academic year?
                    <?= Html::radioList('new-year', NULL, $options, ['class'=> 'form-field', 'onclick'=> 'toggleAcademicYearForm()', 'style' => 'display:none' ,'id' => 'new-year-needed']);?>               
                </p>
                    
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'create-academic-year-form',
                        'options' => [
                            'style' => 'display:none',
                        ],
                    ]);

                        echo "<br/>";
                        echo "<table class='table table-hover' style='width:100%; margin: 0 auto;'>";
                            echo "<tr>";
                                echo "<th style='width:30%; vertical-align:middle'>Title</th>";
                                    echo "<td>{$form->field($new_year, 'title')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'style' => 'vertical-align:middle'])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>Start Date</th>";
                                    echo "<td>{$form->field($new_year, 'startdate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>End Date</th>";
                                    echo "<td>{$form->field($new_year, 'enddate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                            echo "</tr>";
                        echo "</table>";

                        echo "<br/>";
                        echo Html::a(' Cancel',['admissions/initiate-period', 'recordid' => $period->applicationperiodid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton('Update', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);        
                    ActiveForm::end();    
                ?>
                
                
                
                <div id="buttons" style="display:none">
                    <br/><hr>
                    <?= Html::a(' Back',['admissions/initiate-period', 'recordid' => $period->applicationperiodid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);?>
                    <?= Html::a(' Next',['admissions/period-setup-step-one', 'reuse_year' => 1], ['class' => 'btn btn-block btn-lg btn-success glyphicon glyphicon-tick pull-right', 'style' => 'width:25%; margin-right:15%;']);?>
                </div>
            </div>
        </div>
    </div>
</div>



