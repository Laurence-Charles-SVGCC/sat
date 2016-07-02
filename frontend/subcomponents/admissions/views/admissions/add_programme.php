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
    use yii\helpers\ArrayHelper;
    use frontend\models\IntentType;
    use frontend\models\ExaminationBody;
    use frontend\models\QualificationType;
    use frontend\models\Department;
    
    $duration = [
        '' => 'Select Duration',
        1 => '1 Year',
        2 => '2 Years',
    ];
    
    $divisions = [
        '' => 'Select Division',
        4 => 'DASGS',
        5 => 'DTVE',
        6 => 'DTE',
        7 => 'DNE',
    ];

    $this->title = 'Add Programme to Insitution Catalog';
    $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
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
            <h1 class="custom_h1">Add New Programme</h1>
            
            <br/>
            <div style="width:80%; margin: 0 auto; font-size: 20px;">
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'add-programme-catalog-form',
                        'options' => [
                                            'class' => 'form-layout'
                        ],
                    ]);

                        echo "<br/>";
                        echo "<table class='table table-hover' style='width:100%; margin: 0 auto;'>";
                            echo "<tr>";
                                echo "<th style='width:30%; vertical-align:middle'>Name</th>";
                                    echo "<td>{$form->field($programme, 'name')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='width:30%; vertical-align:middle'>Specialisation</th>";
                                    echo "<td>{$form->field($programme, 'specialisation')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>Programme Type</th>";
                                echo "<td>{$form->field($programme, 'programmetypeid')->label('')->dropDownList(ArrayHelper::map(IntentType::find()->all(), 'intenttypeid', 'description'), ['prompt'=>'Select Programme Type'])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>Qualification Type</th>";
                                echo "<td>{$form->field($programme, 'qualificationtypeid')->label('')->dropDownList(ArrayHelper::map(QualificationType::find()->all(), 'qualificationtypeid', 'name'), ['prompt'=>'Select Qualification Type'])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>Examination Body</th>";
                                echo "<td>{$form->field($programme, 'examinationbodyid')->label('')->dropDownList(ArrayHelper::map(ExaminationBody::find()->all(), 'examinationbodyid', 'abbreviation'), ['prompt'=>'Select Examination Body'])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>Department</th>";
                                echo "<td>{$form->field($programme, 'departmentid')->label('')->dropDownList(ArrayHelper::map(Department::find()->where(['divisionid' => $period->divisionid])->andWhere(['not', ['like', 'name', 'Administrative']])->andWhere(['not', ['like', 'name', 'Library']])->andWhere(['not', ['like', 'name', 'Senior']])->andWhere(['not', ['like', 'name', 'CAPE']])->all(), 'departmentid', 'name'), ['prompt'=>'Select Department'])}</td>";
                            echo "</tr>";
                                
                            echo "<tr>";
                                echo "<th style='width:30%; vertical-align:middle'>Duration</th>";
                                    echo "<td>{$form->field($programme, 'duration')->label('', ['class'=> 'form-label'])->dropDownList($duration)}</td>";
                            echo "</tr>"; 
                        echo "</table>"; 

                        echo "<br/>";
                        echo Html::a(' Cancel',['admissions/period-setup-step-three'], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton('Save', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);        
                    ActiveForm::end();    
                ?>
                
            </div>
            
            
        </div>
    </div>
</div>

