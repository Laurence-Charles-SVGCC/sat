<?php

/* 
 * 'add_optional_relatives' view.  Used for modifying information in the 'General' section of 'Profile' tab
 * Author: Laurence Charles
 * Date Created: 28/02/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap\ActiveForm;
    use yii\bootstrap\ActiveField;

    $this->title = 'Add Relative';
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
                <h1 class="custom_h1">Add New Relative</h1>
                <?php
                    $form = ActiveForm::begin([
                                //'action' => Url::to(['gradebook/index']),
                                'id' => 'add-optional-relative',
                                'options' => [
                                ],
                            ]);

                        echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                            echo "<tr>";
                                echo "<th>Title</th>";
                                echo "<td>{$form->field($relative, 'title')->label('')->dropDownList(Yii::$app->params['titles'])}</td>";
                                echo "<th>First Name</th>";
                                echo "<td>{$form->field($relative, 'firstname')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "<th>Last Name</th>";
                                echo "<td>{$form->field($relative, 'lastname')->label('')->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th>Home Phone</th>";
                                echo "<td>{$form->field($relative, 'homephone')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "<th>Cell Phone</th>";
                                echo "<td>{$form->field($relative, 'cellphone')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "<th>Work Phone</th>";
                                echo "<td>{$form->field($relative, 'workphone')->label('')->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th>Address</th>";
                                echo "<td>{$form->field($relative, 'address')->label('')->textArea(['rows' => '3'])}</td>";
                                echo "<th>Occupation</th>";
                                echo "<td>{$form->field($relative, 'occupation')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "<th>Relation Type</th>";
                                echo "<td>{$form->field($relative, 'relationtypeid')->label('')->dropDownList($optional_relations)}</td>";
                                echo "</tr>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<td colspan='4'>{$form->field($relative, 'receivemail')->label("Would you like the institution to forward a copy of your emails to your beneficiary?", ['class'=> 'form-label'])->inline()->radioList([1 => 'Yes', 0 => 'No'], ['class'=> 'form-field', 'onclick' => 'showNewRelativeEmailField();'])}</td>";
                                echo "<td id='email_field' style='display:none'>{$form->field($relative, 'email')->label('')->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";

                        echo "</table>"; 

                        echo Html::a(' Cancel',['view-applicant/applicant-profile', 'applicantusername' => $user->username], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);

                    ActiveForm::end();    
                ?>
            </div>
        </div>
    </div>
