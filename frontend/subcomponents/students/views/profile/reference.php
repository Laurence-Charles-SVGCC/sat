<?php

    /* 
     * 'edit_reference' view. 
     * Author: Laurence Charles
     * Date Created: 07/03/2016
     */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use dosamigos\datepicker\DatePicker;
    use yii\widgets\ActiveForm;
    
    $titles = [
            '' => 'Title', 
            'Mr' => 'Mr',
            'Ms' => 'Ms', 
            'Mrs' => 'Mrs'
        ];
    
    $this->title = 'Update Reference';
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
                            'id' => 'update-reference',
                            'options' => [
                            ],
                        ]);

                        echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Title</th>";
                                echo "<td>{$form->field($reference, 'title')->label("Title *", ['class'=> 'form-label'])->dropDownList($titles)}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>First Name</th>";
                                echo "<td>{$form->field($reference, 'firstname')->label('First Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Last Name</th>";
                                echo "<td>{$form->field($reference, 'lastname')->label('Last Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Address</th>";
                                echo "<td>{$form->field($reference, 'address')->label("Address *", ['class'=> 'form-label'])->textArea(['rows' => '3'])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Occupation</th>";
                                echo "<td>{$form->field($reference, 'occupation')->label('Occupation *', ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Contact Number</th>";
                                echo "<td>{$form->field($reference, 'contactnumber')->label('Contact Number *', ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";
                        echo "</table>";
                            
                        echo Html::a(' Cancel',['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);

                    ActiveForm::end();    
                ?>
            </div>
        </div>
    </div>
