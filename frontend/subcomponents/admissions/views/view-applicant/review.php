<?php
    use yii\helpers\Html;
    
    use frontend\models\CapeSubject;

    $this->title = 'Application Review';
    $this->params['breadcrumbs'][] = ['label' => 'Search Applicant', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="review-form">
    <div id="contentContainer">
        <div class="rgtergtet">
            
            <h1><?= Html::encode($this->title) ?></h1>
 
            <?php
            $form = yii\bootstrap\ActiveForm::begin([
                        'id' => 'review-form',
                        'options' => [
                            'class' => 'well'
                        ],
                    ])
            ?>
            
            
            <fieldset>
                    <legend>Personal Information</legend>
                    
                    <h4>Profile</h4>
                    <?= $form->field($applicant, 'title')->label('Title', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                                           
                    <?= $form->field($applicant, 'firstname')->label('First Name', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <?= $form->field($applicant, 'middlename')->label('Middle Name', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <?= $form->field($applicant, 'lastname')->label('Last Name', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <?= $form->field($applicant, 'dateofbirth')->label('Date of Birth', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                   
                    <?= $form->field($applicant, 'gender')->label('Gender', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                    <?= $form->field($applicant, 'nationality')->label('Nationality', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <?= $form->field($applicant, 'placeofbirth')->label('Place of Birth', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <?= $form->field($applicant, 'religion')->label('Religion', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <?php if ($applicant && $applicant->sponsorname != ''):?>
                        <?= $form->field($applicant, 'sponsorname')->label('Sponsor', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    
                    </br>
                    <h4 style="text-align:center">Permanent Address</h4>
                    <?= $form->field($addresses[0], '[0]country')->label("Country", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                    <?php if (is_null($addresses[0]->town)==false && strcmp($addresses[0]->town,"")!=0):?>
                        <?= $form->field($addresses[0], '[0]town')->label("Town", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    <?php if (is_null($addresses[0]->addressline) == false && strcmp($addresses[0]->addressline,"")!=0):?>
                        <?= $form->field($addresses[0], '[0]addressline')->label("Addressline", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    
                    </br>
                    <h4 style="text-align:center">Residential Address</h4>
                    <?= $form->field($addresses[1], '[1]country')->label("Country", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <?php if (is_null($addresses[1]->town) == false || strcmp($addresses[1]->town,"")!=0):?>
                        <?= $form->field($addresses[1], '[1]town')->label("Town", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    <?php if (is_null($addresses[1]->addressline) == false && strcmp($addresses[1]->addressline,"")!=0):?>
                        <?= $form->field($addresses[1], '[1]addressline')->label("Addressline", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>

                    
                    </br>
                    <h4 style="text-align:center">Postal Address</h4>
                    <?= $form->field($addresses[2], '[2]country')->label("Country", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true,]) ?>
                    
                    <?php if (is_null($addresses[2]->town) == false && strcmp($addresses[2]->town,"")!=0):?>
                        <?= $form->field($addresses[2], '[2]town')->label("Town", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    <?php if (is_null($addresses[2]->addressline) == false && strcmp($addresses[2]->addressline,"")!=0):?>
                        <?= $form->field($addresses[2], '[2]addressline')->label("AddressLine", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    
                    </br>
                    <h4 style="text-align:center">Phone Details</h4>
                    <?php if (is_null($phone->homephone) == false && strcmp($phone->homephone,"")!=0):?>
                        <?= $form->field($phone, 'homephone')->label("Home Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    <?php if (is_null($phone->cellphone) == false && strcmp($phone->cellphone,"")!=0):?>
                        <?= $form->field($phone, 'cellphone')->label("Cell Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    <?php if (is_null($phone->workphone) == false && strcmp($phone->workphone,"")!=0):?>
                        <?= $form->field($phone, 'workphone')->label("Work Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    
                    <?php if ($mother!= false):?>
                        </br>
                        <div id="mother">
                            <h4 style="text-align:center">Mother</h4>
                            <?= $form->field($mother, 'title')->label("Title", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($mother, 'firstname')->label("First Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($mother, 'lastname')->label("Last Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($mother, 'occupation')->label("Occupation", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($mother, 'homephone')->label("Home Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($mother, 'cellphone')->label("Cell Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($mother, 'workphone')->label("Work Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($mother->email) == false && strcmp($mother->email,"")!=0):?>
                                <?= $form->field($mother, 'email')->label("Email", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>                   
                            <?php endif; ?>

                            <?= $form->field($mother, 'country')->label("Country", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($mother->town) == false && strcmp($mother->town,"")!=0):?>
                                <?= $form->field($mother, 'town')->label("Town", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>

                            <?php if (is_null($mother->addressline) == false && strcmp($mother->addressline,"")!=0):?>
                                <?= $form->field($mother, 'addressline')->label("Addressline", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    
                    <?php if ($father != false):?>
                        </br>
                        <div id="father">
                            <h4 style="text-align:center">Father</h4>
                            <?= $form->field($father, 'title')->label("Title", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($father, 'firstname')->label("First Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($father, 'lastname')->label("Last Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($father, 'occupation')->label("Occupation", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($father, 'homephone')->label("Home Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($father, 'cellphone')->label("Cell Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($father, 'workphone')->label("Work Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($father->email) == false && strcmp($father->email,"")!=0):?>                      
                                <?= $form->field($father, 'email')->label("Email", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>

                            <?= $form->field($father, 'country')->label("Country", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($father->town) == false && strcmp($father->town,"")!=0):?>
                                <?= $form->field($father, 'town')->label("Town", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>

                            <?php if (is_null($father->addressline) == false && strcmp($father->addressline,"")!=0):?>
                                <?= $form->field($father, 'addressline')->label("AddressLine", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                             <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <span>
                        <?= $form->field($applicant, 'maritalstatus')->label("Marital Status", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    </span>
                    
                    
                    <?php if ($spouse!= false):?>
                        </br>
                        <div id="spouse">
                            </br>
                            <h4 style="text-align:center">Spouse</h4>
                            <?= $form->field($spouse, 'title')->label("Title", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($spouse, 'firstname')->label("First Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($spouse, 'lastname')->label("Last Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($spouse, 'occupation')->label("Occupation", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($spouse, 'homephone')->label("Home Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($spouse, 'cellphone')->label("Cell Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($spouse, 'workphone')->label("Work Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($spouse->email) == false && strcmp($spouse->email,"")!=0):?>
                                <?= $form->field($spouse, 'email')->label("Email", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>

                            <?= $form->field($spouse, 'country')->label("Country", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($spouse->town) == false && strcmp($spouse->town,"")!=0):?>
                                <?= $form->field($spouse, 'town')->label("Town", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>

                            <?php if (is_null($spouse->addressline) == false && strcmp($spouse->addressline,"")!=0):?>
                                <?= $form->field($spouse, 'addressline')->label("Addressline", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>
                        </div> 
                    <?php endif; ?>
                    
                    
                    <?php if ($nextofkin != false):?>
                        </br>
                        <div id="next-of-kin">
                            </br>
                            <h4 style="text-align:center">Next Of Kin</h4>
                            <?= $form->field($nextofkin, 'title')->label("Title", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($nextofkin, 'firstname')->label("First Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($nextofkin, 'lastname')->label("Last Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($nextofkin, 'occupation')->label("Occupation", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($nextofkin, 'homephone')->label("Home Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($nextofkin, 'cellphone')->label("Cell Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($nextofkin, 'workphone')->label("Work Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($nextofkin->email) == false && strcmp($nextofkin->email,"")!=0):?>
                                <?= $form->field($nextofkin, 'email')->label("Email", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                             <?php endif; ?>

                            <?= $form->field($nextofkin, 'country')->label("Country", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($nextofkin->town) == false && strcmp($nextofkin->town,"")!=0):?>
                                <?= $form->field($nextofkin, 'town')->label("Town", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>

                            <?php if (is_null($nextofkin->addressline) == false && strcmp($nextofkin->addressline,"")!=0):?>
                                <?= $form->field($nextofkin, 'addressline')->label("Addressline", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    
                    <?php if ($emergencycontact!= false):?>
                        </br>
                        <div id="emergency_contact">
                            <h4 style="text-align:center">Emergency Contact</h4>
                            <?= $form->field($emergencycontact, 'title')->label("Title", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($emergencycontact, 'firstname')->label("First Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($emergencycontact, 'lastname')->label("Last Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($emergencycontact, 'occupation')->label("Occupation", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($emergencycontact, 'homephone')->label("Home Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($emergencycontact, 'cellphone')->label("Cell Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($emergencycontact, 'workphone')->label("Work Pnone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($emergencycontact->email) == false && strcmp($emergencycontact->email,"")!=0):?>
                                <?= $form->field($emergencycontact, 'email')->label("Email", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                             <?php endif; ?>

                            <?= $form->field($emergencycontact, 'country')->label("Country", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($emergencycontact->town) == false && strcmp($emergencycontact->town,"")!=0):?>
                                <?= $form->field($emergencycontact, 'town')->label("Town", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>

                            <?php if (is_null($emergencycontact->addressline) == false && strcmp($emergencycontact->addressline,"")!=0):?>
                                <?= $form->field($emergencycontact, 'addressline')->label("Addressline", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    
                    <?php if ($guardian != false):?>
                        </br>
                        <div id="guaridan">
                            <h4 style="text-align:center">Guardian</h4>
                            <?= $form->field($guardian, 'title')->label("Title", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($guardian, 'firstname')->label("First Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($guardian, 'lastname')->label("Last Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($guardian, 'occupation')->label("Occuptation", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($guardian, 'homephone')->label("Home Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($guardian, 'cellphone')->label("Cell Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($guardian, 'workphone')->label("Work Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($guardian->email) == false && strcmp($guardian->email,"")!=0):?>
                                <?= $form->field($guardian, 'email')->label("Email", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                             <?php endif; ?>

                            <?= $form->field($guardian, 'country')->label("Country", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($guardian->town) == false && strcmp($guardian->town,"")!=0):?>
                                <?= $form->field($guardian, 'town')->label("Town", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>

                            <?php if (is_null($guardian->addressline) == false && strcmp($guardian->addressline,"")!=0):?>
                                <?= $form->field($guardian, 'addressline')->label("Address Line", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>
                        </div> 
                    <?php endif; ?>
                    
                    
                    <?php if ($beneficiary!= false):?>
                        </br>
                        <div id="beneficiary">
                            <h4 style="text-align:center">Beneficiary</h4>
                            <?= $form->field($beneficiary, 'title')->label("Title", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($beneficiary, 'firstname')->label("First Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($beneficiary, 'lastname')->label("Last Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($beneficiary, 'occupation')->label("Occupation", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($beneficiary, 'homephone')->label("Home Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($beneficiary, 'cellphone')->label("Cell Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($beneficiary, 'workphone')->label("Work Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($beneficiary->email) == false && strcmp($beneficiary->email,"")!=0):?>
                                <?= $form->field($beneficiary, 'email')->label("Email", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                             <?php endif; ?>

                            <?= $form->field($beneficiary, 'country')->label("Country", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($beneficiary->town) == false && strcmp($beneficiary->town,"")!=0):?>
                                <?= $form->field($beneficiary, 'town')->label("Town", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>

                            <?php if (is_null($beneficiary->addressline) == false && strcmp($beneficiary->addressline,"")!=0):?>
                                <?= $form->field($beneficiary, 'addressline')->label("Addressline", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                        
                    
                    <div id="medical-condition">
                        </br>
                        <h3>Medical Conditions</h3>                       
                        <?php
                            if ($medicalConditions!=false){                               
                                for($i=0 ; $i<count($medicalConditions) ; $i++){
                                    $j = $i+1;
                                    echo "<h4>Medical Condition {$j}:</h4>";
                                    echo $form->field($medicalConditions[$i], "[{$i}]medicalcondition")->label("Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                    echo $form->field($medicalConditions[$i], "[{$i}]description")->label("Description", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                    echo $form->field($medicalConditions[$i], "[{$i}]emergencyaction")->label("Emergency Action", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                    echo "</br>";                             
                                }
                            }
                            else{
                                echo "</br><p><strong>No medical conditions have been entered.</strong></p></br>";
                            }          
                        ?>     
                    </div>
                    
                    
                    <?php if ($applicant && $applicant->nationalsports != ''):?>
                        <div id="national-sports">
                            <!--<h4>National Sporting Activities</h4>-->
                            <?= $form->field($applicant, 'nationalsports')->label("National Sports", ['class'=> 'form-label'])->textArea(['rows' => '5', 'id'=>'national-sport-details']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($applicant && $applicant->othersports != ''):?>
                        <div id="recreational-sports">
                             <!--<h4>Recreational Sports</h4>-->
                            <?= $form->field($applicant, 'othersports')->label("Other Sports", ['class'=> 'form-label'])->textArea(['rows' => '5', 'id'=>'other-sport-details']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($applicant && $applicant->clubs != ''):?>
                        <div id="club-participation">
                             <!--<h4>Club Participation</h4>-->
                            <?= $form->field($applicant, 'clubs')->label("Clubs", ['class'=> 'form-label'])->textArea(['rows' => '5', 'id'=>'clubDetails']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($applicant && $applicant->otherinterests != ''):?>
                        <div id="extracurricular-activities">
                             <!--<h4>Extracurricular Activities</h4>-->
                            <?= $form->field($applicant, 'otherinterests')->label("Other Interests", ['class'=> 'form-label'])->textArea(['rows' => '5', 'id'=>'interestDetails']) ?>
                        </div>
                    <?php endif; ?>
            </fieldset>
            
            
            </br></br>
            <fieldset>
                    <legend>Institutional Attendance</legend>
                    
                    <?php 
                        if ($preschools != false){
                            echo "<h4 style='text-align:center'>Pre-Schools</h4>";
                            for ($i=0 ; $i<count($preschools) ; $i++){
                                echo "<p style='font-size:17px'><strong>" . $preschoolNames[$i]; echo "</strong></p></br>";
                                echo $form->field($preschools[$i], "[{$i}]startdate")->label("Start Date", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                echo $form->field($preschools[$i], "[{$i}]enddate")->label("End Date", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                //echo $form->field($preschools[$i], "[{$i}]hasgraduated")->label("Has Graduated?", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                echo "</br></br></br>";
                            } 
                        }
                    ?>

                    
                    <?php 
                        if ($primaryschools != false){
                            echo "<h4 style='text-align:center'>Primary Schools</h4>";
                            for ($i=0 ; $i<count($primaryschools) ; $i++){
                                echo "<p style='font-size:17px'><strong>" . $primaryschoolNames[$i]; echo "</strong></p></br>";                          
                                echo $form->field($primaryschools[$i], "[{$i}]startdate")->label("Start Date", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                echo $form->field($primaryschools[$i], "[{$i}]enddate")->label("End Date", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                
                                //echo $form->field($primaryschools[$i], "[{$i}]hasgraduated")->label("Has Graduated", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                echo "</br></br></br>";
                            }    
                        }
                    ?>
                    
                    
                    <?php 
                        if ($secondaryschools != false){
                            echo "<h4 style='text-align:center'>Secondary Schools</h4>";
                            for ($i=0 ; $i<count($secondaryschools) ; $i++){
                                echo "<p style='font-size:17px'><strong>" . $secondaryschoolNames[$i]; echo "</strong></p></br>"; 
                                echo $form->field($secondaryschools[$i], "[{$i}]startdate")->label("Start Date", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                echo $form->field($secondaryschools[$i], "[{$i}]enddate")->label("End Date", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                //echo $form->field($secondaryschools[$i], "[{$i}]hasgraduated")->label("Has Graduated", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                echo "</br></br>";
                            }
                        }
                    ?>
                    
                    
                    <?php 
                        if ($tertieryschools != false){
                            echo "<h4 style='text-align:center'>Tertiary Schools</h4>";
                            for ($i=0 ; $i<count($tertieryschools) ; $i++){
                                echo "<p style='font-size:17px'><strong>" . $tertieryschoolNames[$i]; echo "</strong></p></br>"; 
                                echo $form->field($tertieryschools[$i], "[{$i}]startdate")->label("Start Date", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                echo $form->field($tertieryschools[$i], "[{$i}]enddate")->label("End Date", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                //echo $form->field($tertieryschools[$i], "[{$i}]hasgraduated")->label("Has Graduated", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);
                                echo "</br></br>";
                            }
                        }
                    ?>         
            </fieldset>
            
            
            </br></br>
            <fieldset>
                    <legend>Academic Qualification</legend>
                    <?php
                        if ($qualifications==false){
                            echo "</br><p><strong>No qualifications have been entered. Please ensure that you make contact with the Registrar to present you appropriate external certificates</strong></p></br>";
                        }
                        else{
                            echo "<div>";

                            for ($i = 0; $i < count($qualifications); $i++) {
                                $j = $i+1;
                                echo " <div class='alert in alert-block fade alert-success mainButtons'>";
                                echo "<table border = '1' style='width:60%; margin-left:20%; margin-right:20%;'>";
                                    echo "<tr>";
                                        echo "<td colspan='2'><h3 style='text-align:center'>Qualification #" . $j ; echo "</h3></td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th><strong style = 'font-size:14px'>Candidate Number</strong></th>";
                                        echo "<td>" . $qualifications[$i]->candidatenumber; echo"</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th><strong style = 'font-size:14px'>Examination Body</strong></th>";
                                        echo "<td>" . $qualificationDetails[$i]['examinationbody']; echo "</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th><strong style = 'font-size:14px'>Subject</strong></th>";
                                        echo "<td>" . $qualificationDetails[$i]['subject']; echo "</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th><strong style = 'font-size:14px'>Proficiency</strong></th>";
                                        echo "<td>" . $qualificationDetails[$i]['proficiency']; echo "</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th><strong style = 'font-size:14px'>Grade</strong></th>";
                                        echo "<td>" . $qualificationDetails[$i]['grade']; echo "</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th><strong style = 'font-size:14px'>Year</strong></th>";
                                        echo "<td>" . $qualifications[$i]->year; echo "</td>";
                                    echo "</tr>";
                                echo "</table>";
                                echo "</div>";
                                echo "</br></br>";
                            }
                            echo "</div>";
                            
                        }
                    ?>   
            </fieldset>
            
            
            </br></br>
            <fieldset>
                    <legend>Programme Choices</legend>
               
                    <?php if (count($first) > 0):?>
                        <h4>Programme of First Choice</h4> 
      
                        <div class="alert in alert-block fade alert-success mainButtons">      
                                <table class="table">
                                    <tr>
                                        <th><strong style="font-size:14px">Division</strong></th>
                                        <td style="font-size:12px"> <?= $firstDetails[0] ?></td>
                                    </tr>
                                    <tr>
                                        <th><strong style="font-size:14px">Programme</strong></th>
                                        <td style="font-size:12px"> <?= $firstDetails[1] ?></td>
                                    </tr>
                                    <?php if (count($first) == 2) : ?>
                                        <tr>
                                            <th><strong style="font-size:14px">CAPE Subjects</strong></th>
                                            <td  style="font-size:12px"> 
                                                <?php
                                                for ($j = 0; $j < count($first[1]) - 1; $j++) {
                                                    $temp = CapeSubject::find()
                                                            ->where(['capesubjectid' => $first[1][$j]->capesubjectid])
                                                            ->one();
                                                    echo $temp->subjectname . ", ";
                                                }
                                                $temp = CapeSubject::find()
                                                        ->where(['capesubjectid' => $first[1][$j]->capesubjectid])
                                                        ->one();
                                                echo $temp->subjectname;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </table>    
                            </div>
                    <?php endif; ?>
                        
                        
                        
                        
                    <?php if (count($second) > 0):?>
                        <h4>Programme of Second Choice</h4>                  
     
                        <div class="alert in alert-block fade alert-success mainButtons">      
                        <table class="table">
                            <tr>
                                <th><strong style="font-size:14px">Division</strong></th>
                                <td style="font-size:12px"> <?= $secondDetails[0]?></td>
                            </tr>
                            <tr>
                                <th><strong style="font-size:14px">Programme</strong></th>
                                <td style="font-size:12px"> <?= $secondDetails[1]?></td>
                            </tr>
                            <?php if(count($second)==2) :?>
                                <tr>
                                    <th><strong style="font-size:14px">CAPE Subjects</strong></th>
                                    <td  style="font-size:12px"> 
                                        <?php
                                            for($k=0 ; $k<count($second[1])-1 ; $k++){ 
                                                $temp2 = CapeSubject::find()
                                                        ->where(['capesubjectid' => $second[1][$k]->capesubjectid])
                                                        ->one();
                                                echo $temp2->subjectname . ", ";
                                            }
                                            $temp2 = CapeSubject::find()
                                                        ->where(['capesubjectid' => $second[1][$k]->capesubjectid])
                                                        ->one();
                                            echo $temp2->subjectname;
                                        ?>
                                    </td>
                                </tr>
                            <?php endif ;?>  
                        </table>    
                    </div>
                        
                        
                        
                    <?php endif; ?>
                        
                    <?php if (count($third)>0):?>
                        <h4>Programme of Third Choice</h4>                                         
                        
                        <div class="alert in alert-block fade alert-success mainButtons">
                                <table class="table">
                                    <tr>
                                        <th><strong style="font-size:14px">Division</strong></th>
                                        <td style="font-size:12px"> <?= $thirdDetails[0] ?></td>
                                    </tr>
                                    <tr>
                                        <th><strong style="font-size:14px">Programme</strong></th>
                                        <td style="font-size:12px"> <?= $thirdDetails[1] ?></td>
                                    </tr>
                                    <?php if (count($third) == 2) : ?>
                                        <tr>
                                            <th><strong style="font-size:14px">CAPE Subjects</strong></th>
                                            <td  style="font-size:12px"> 
                                                <?php
                                                for ($l = 0; $l < count($third[1]) - 1; $l++) {
                                                    $temp3 = CapeSubject::find()
                                                            ->where(['capesubjectid' => $third[1][$l]->capesubjectid])
                                                            ->one();
                                                    echo $temp3->subjectname . ", ";
                                                }
                                                $temp3 = CapeSubject::find()
                                                        ->where(['capesubjectid' => $third[1][$l]->capesubjectid])
                                                        ->one();
                                                echo $temp3->subjectname;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>  
                                </table>  
                            </div>    
                        
                    <?php endif; ?>
            </fieldset>
                          
            <?php yii\bootstrap\ActiveForm::end(); ?>
            
            
            

        </div>
    </div>
</div>
    