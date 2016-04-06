<?php

/* 
 * 'edit_general' view.  Used for modifying information in the 'Extracurricular Activites' section of 'Profile' tab
 * Author: Laurence Charles
 * Date Created: 03/04/2016
 */

    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $this->title = 'Edit Extracurricular Activity';
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
                <h1 class="custom_h1">Edit Extracurricular Activities</h1>

                <?php
                    $form = ActiveForm::begin([
                                'id' => 'edit-excurricular-activities',
                                'options' => [
                                ],
                            ]);

                        echo "<br/>";
                            echo "<table class='table table-hover' style='width:95%; margin: 0 auto;'>";
                                echo "<tr>";
                                    echo "<th>National Sports</th>";
                                        echo "<td>{$form->field($applicant, 'nationalsports')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Recreational Sports</th>";
                                        echo "<td>{$form->field($applicant, 'othersports')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Club Memberships</th>";
                                        echo "<td>{$form->field($applicant, 'clubs')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Other Interests</th>";
                                        echo "<td>{$form->field($applicant, 'otherinterests')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "</tr>";
                            echo "</table>"; 

                            echo "<br/>";

                            echo Html::a(' Cancel',['view-applicant/applicant-profile', 'applicantusername' => $user->username], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                            echo Html::submitButton('Update', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);
                        ActiveForm::end();    
                    ?>
            </div>
        </div>
    </div>
        
                    



