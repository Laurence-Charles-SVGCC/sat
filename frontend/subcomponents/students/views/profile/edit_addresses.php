<?php

/* 
 * 'edit_addresses' view.  Used for modifying information in the 'General' section of 'Profile' tab
 * Author: Laurence Charles
 * Date Created: 29/12/2015
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\Address;
    
    $this->title = 'Edit Address';
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
                <h1 class="custom_h1">Edit Address</h1>

                <?php
                    $form = ActiveForm::begin([
                                //'action' => Url::to(['gradebook/index']),
                                'id' => 'edit-addresses-form',
                                'options' => [
//                                    'class' => 'form-layout form-inline'
//                                    'class' => 'form-inline',
                                ],
                            ]);

                        echo "<br/>";
                        echo "<table class='table table-hover' style='margin: 0 auto;'>";
                            echo "<tr>";
                                echo "<th rowspan='2' style='vertical-align:middle; text-align:center; font-size:1.2em;'>Permanent Address</th>";
                                echo "<th>Country</th>";
                                echo "<td>{$form->field($addresses[0], '[0]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'country', 'onchange'=>'checkCountry();'])}</td>"; 
                                echo "<th>Town</th>";
                                if(Address::checkTown($applicant->personid,1) == false)
                                    echo "<td>{$form->field($addresses[0], '[0]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showAddressLine();' , 'style'=>'display:none', 'id'=>'permLocalTown'])}</td>";
                                else
                                    echo "<td>{$form->field($addresses[0], '[0]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showAddressLine();' , 'style'=>'display:block', 'id'=>'permLocalTown'])}</td>";                         
                            echo "</tr>";
                            echo "<tr>";
                                echo "<th>Address Line</th>";
                                if(Address::checkAddressline($applicant->personid,1) == false)
                                    echo "<td>{$form->field($addresses[0], '[0]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:none", 'id'=>'permAddressLine'])}</td>";
                                else
                                    echo "<td>{$form->field($addresses[0], '[0]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:block", 'id'=>'permAddressLine'])}</td>";                                            
                            echo "</tr>";                                      

                            echo "<tr>";
                                echo "<th rowspan='2' style='vertical-align:middle; text-align:center; font-size:1.2em;'>Residential Address</th>";
                                echo "<th>Country</th>";
                                echo "<td>{$form->field($addresses[1], '[1]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'country2', 'onchange'=>'checkCountry2();'])}</td>";
                                echo "<th>Town</th>";
                                if(Address::checkTown($applicant->personid,2) == false)
                                    echo "<td>{$form->field($addresses[1], '[1]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showAddressLine2();' , 'style'=>'display:none', 'id'=>'resdLocalTown'])}</td>";
                                else
                                    echo "<td>{$form->field($addresses[1], '[1]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showAddressLine2();' , 'style'=>'display:block', 'id'=>'resdLocalTown'])}</td> ";                                          
                            echo "</tr>";
                            echo "<tr>";
                                echo "<td></td>";
                                echo "<th>Address Line</th>";
                                if(Address::checkAddressline($applicant->personid,2) == false)
                                    echo "<td>{$form->field($addresses[1], '[1]addressline')->label('')->textInput(['maxlength' => true, 'style'=>'display:none', 'id'=>'resdAddressLine'])}</td>";
                                else
                                    echo "<td>{$form->field($addresses[1], '[1]addressline')->label('')->textInput(['maxlength' => true, 'style'=>'display:block', 'id'=>'resdAddressLine'])}</td>";                                           
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th rowspan='2' style='vertical-align:middle; text-align:center; font-size:1.2em;'>Postal Address</th>";
                                echo "<th>Country</th>";
                                echo "<td>{$form->field($addresses[2], '[2]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'country3', 'onchange'=>'checkCountry3();'])}</td>"; 
                                echo "<th>Town</th>";
                                if(Address::checkTown($applicant->personid,3) == false)
                                    echo "<td>{$form->field($addresses[2], '[2]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showAddressLine3();' , 'style'=>'display:none', 'id'=>'postLocalTown'])}</td>";
                                else
                                    echo "<td>{$form->field($addresses[2], '[2]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showAddressLine3();' , 'style'=>'display:block', 'id'=>'postLocalTown'])}</td>";                                             
                            echo "</tr>";
                            echo "<tr>";
                                echo "<td></td>";
                                echo "<th>Address Line</th>";
                                if(Address::checkAddressline($applicant->personid,3) == false)
                                    echo "<td>{$form->field($addresses[2], '[2]addressline')->label('')->textInput(['maxlength' => true, 'style'=>'display:none', 'id'=>'postAddressLine'])}</td>";
                                else
                                    echo "<td>{$form->field($addresses[2], '[2]addressline')->label('')->textInput(['maxlength' => true, 'style'=>'display:none', 'id'=>'postAddressLine'])}</td>";                                   
                            echo "</tr>";                          
                        echo "</table>";

                        echo Html::a(' Cancel',['profile/student-profile', 'personid' => $applicant->personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton('Update', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);

                    ActiveForm::end();    
                ?>
            </div>
        </div>
    </div>
                    