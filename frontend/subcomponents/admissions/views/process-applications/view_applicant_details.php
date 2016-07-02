<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yii\bootstrap\ActiveField;

    use frontend\models\Relation;
    use frontend\models\CompulsoryRelation;
    use frontend\models\Applicant;
    use frontend\models\Application;
    use frontend\models\CapeSubject;
    use frontend\models\NursingAdditionalInfo;
    use frontend\models\TeachingAdditionalInfo;
    use frontend\models\PostSecondaryQualification;

    $this->title = 'Applicant Details';
    $this->params['breadcrumbs'][] = $this->title;
    
    $financing_options = [
        'Father' => 'Father',
        'Mother' => 'Mother',
        'Self' => 'Self',
        'Other' => 'Other'
    ];
            
    $student_loan = [
        1 => 'Yes', 
        0 => 'No'
    ];
    
    $sponsorship_request = [
        'Grant' => 'Grant',
        'Sponsohip' => 'Sponsoship',
        'Both' => 'Both',
        'Neither' => 'Neither'
    ];
    
    $is_repeat_applicant = [
                    1 => 'Yes',
                    0 => 'No'
                ];
    
    $is_organisational_member = [
                    1 => 'Yes',
                    0 => 'No'
                ];
?>

<div class="process-applicants-index">
    <div class = "custom_wrapper" style="min-height:11800px;">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body" style="min-height:11000px;">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
 
            <?php
                $form = yii\bootstrap\ActiveForm::begin([
                    'id' => 'review-form',
                    'options' => [
                       'class' => 'well',
                       'style' => 'width: 80%; margin: 0 auto;'
                    ],
                ])
            ?>
                       
                <fieldset>
                    <legend style="color:green"><strong>Profile Summary</strong></legend>
                    
                    <?= $form->field($applicant, 'title')->label('Title', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                                           
                    <?= $form->field($applicant, 'firstname')->label('First Name', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <?= $form->field($applicant, 'middlename')->label('Middle Name', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <?= $form->field($applicant, 'lastname')->label('Last Name', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <?= $form->field($applicant, 'dateofbirth')->label('Date of Birth', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                   
                    <?= $form->field($applicant, 'gender')->label('Gender', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                    <?= $form->field($applicant, 'nationality')->label('Nationality', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <?= $form->field($applicant, 'placeofbirth')->label('Place of Birth', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <?= $form->field($applicant, 'religion')->label('Religion', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <span>
                        <?= $form->field($applicant, 'maritalstatus')->label("Marital Status", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    </span>
                        
                    <?php if ($applicantDetails[0] == 1):?>
                        <?= $form->field($applicant, 'sponsorname')->label('Sponsor', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    
                    <h4 style="text-align:center; font-size:20px; color:darkslategrey">Permanent Address</h4>
                    <?= $form->field($addresses[0], '[0]country')->label("Country", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                    <?php if (is_null($addresses[0]->town)==false && strcmp($addresses[0]->town,"")!=0):?>
                        <?= $form->field($addresses[0], '[0]town')->label("Town", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    <?php if (is_null($addresses[0]->addressline) == false && strcmp($addresses[0]->addressline,"")!=0):?>
                        <?= $form->field($addresses[0], '[0]addressline')->label("Addressline", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    
                    <h4 style="text-align:center; font-size:20px; color:darkslategrey">Residential Address</h4>
                    <?= $form->field($addresses[1], '[1]country')->label("Country", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    
                    <?php if (is_null($addresses[1]->town) == false || strcmp($addresses[1]->town,"")!=0):?>
                        <?= $form->field($addresses[1], '[1]town')->label("Town", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    <?php if (is_null($addresses[1]->addressline) == false && strcmp($addresses[1]->addressline,"")!=0):?>
                        <?= $form->field($addresses[1], '[1]addressline')->label("Addressline", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>

                    
                    </br>
                    <h4 style="text-align:center; font-size:20px; color:darkslategrey">Postal Address</h4>
                    <?= $form->field($addresses[2], '[2]country')->label("Country", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true,]) ?>
                    
                    <?php if (is_null($addresses[2]->town) == false && strcmp($addresses[2]->town,"")!=0):?>
                        <?= $form->field($addresses[2], '[2]town')->label("Town", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    <?php if (is_null($addresses[2]->addressline) == false && strcmp($addresses[2]->addressline,"")!=0):?>
                        <?= $form->field($addresses[2], '[2]addressline')->label("AddressLine", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    
                    <h4 style="text-align:center; font-size:20px; color:darkslategrey">Phone Detail</h4>
                    <?php if (is_null($phone->homephone) == false && strcmp($phone->homephone,"")!=0):?>
                        <?= $form->field($phone, 'homephone')->label("Home Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    <?php if (is_null($phone->cellphone) == false && strcmp($phone->cellphone,"")!=0):?>
                        <?= $form->field($phone, 'cellphone')->label("Cell Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>
                    
                    <?php if (is_null($phone->workphone) == false && strcmp($phone->workphone,"")!=0):?>
                        <?= $form->field($phone, 'workphone')->label("Work Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?php endif; ?>

                    
                    <div id="medical-condition">
                        </br>
                        <fieldset>
                            <legend style="color:green"><strong>Medical Conditions<strong></legend>                       
                            <?php
                                if ($medicalConditions!=false){                               
                                    for($i=0 ; $i<count($medicalConditions) ; $i++){
                                        $j = $i+1;
                                        echo "<h4>Medical Condition {$j}</h4>";
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
                         </fieldset>
                    </div>
                    
                    
                    <fieldset>
                        <legend style="color:green"><strong>Contacts</strong></legend>
                        <div id="beneficiary">
                            <h4 style="text-align:center; font-size:20px; color:darkslategrey">Beneficiary</h4>
                            <?= $form->field($beneficiary, 'relationdetail')->label("Relation Type", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            
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

                            <?= $form->field($beneficiary, 'address')->label("Address", ['class'=> 'form-label'])->textArea(['rows' => '4', 'readonly' => true]) ?>
                        </div>
                    

                        <div id="compulsory">
                            <h4 style="text-align:center; font-size:20px; color:darkslategrey">Emergency Contact</h4>
                            <?= $form->field($emergencycontact, 'relationdetail')->label("Relation Type", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            
                            <?= $form->field($emergencycontact, 'title')->label("Title", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($emergencycontact, 'firstname')->label("First Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($emergencycontact, 'lastname')->label("Last Name", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($emergencycontact, 'occupation')->label("Occupation", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($emergencycontact, 'homephone')->label("Home Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($emergencycontact, 'cellphone')->label("Cell Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?= $form->field($emergencycontact, 'workphone')->label("Work Phone", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>

                            <?php if (is_null($emergencycontact->email) == false && strcmp($emergencycontact->email,"")!=0):?>
                                <?= $form->field($emergencycontact, 'email')->label("Email", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?php endif; ?>

                            <?= $form->field($emergencycontact, 'address')->label("Address", ['class'=> 'form-label'])->textArea(['rows' => '4', 'readonly' => true]) ?>
                        </div>

                        <?php if ($spouse!= false):?>
                            <div id="spouse">
                                </br>
                                <h4 style="text-align:center; font-size:20px; color:darkslategrey">Spouse</h4>
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

                                <?= $form->field($spouse, 'address')->label("Address", ['class'=> 'form-label'])->textArea(['rows' => '4', 'readonly' => true]) ?>      
                            </div> 
                        <?php endif; ?>

                        <?php if ($mother!= false):?>
                            <div id="mother">
                                <h4 style="text-align:center; font-size:20px; color:darkslategrey">Mother</h4>
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
                                
                                <?= $form->field($mother, 'address')->label("Address", ['class'=> 'form-label'])->textArea(['rows' => '4', 'readonly' => true]) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($father != false):?>
                            <div id="father">
                                <h4 style="text-align:center; font-size:20px; color:darkslategrey">Father</h4>
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

                                <?= $form->field($father, 'address')->label("Address", ['class'=> 'form-label'])->textArea(['rows' => '4', 'readonly' => true]) ?> ?>      
                            </div>
                        <?php endif; ?>
                            
                        
                        <?php if ($nextofkin != false):?>
                            <div id="next-of-kin">
                                </br>
                                <h4 style="text-align:center; font-size:20px; color:darkslategrey">Next Of Kin</h4>
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

                                <?= $form->field($nextofkin, 'address')->label("Address", ['class'=> 'form-label'])->textArea(['rows' => '4', 'readonly' => true]) ?>
                            </div>
                        <?php endif; ?>
                        
                            
                        <?php if ($guardian != false):?>
                            <div id="guaridan">
                                <h4 style="text-align:center; font-size:20px; color:darkslategrey">Guardian</h4>
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

                               <?= $form->field($guardian, 'address')->label("Address", ['class'=> 'form-label'])->textArea(['rows' => '4', 'readonly' => true]) ?>
                            </div> 
                        <?php endif; ?>        
                    </fieldset>    

                    
                    <!--If applicant intends to submit application to DTE programme-->
                    <?php if (Applicant::getApplicantIntent($applicant->personid) == 4):?> 
                        </br>
                        <fieldset>
                            <legend  style="color:green"><strong>Additional Information<strong></legend>
                            <h4 style="text-align:center">Family Information</h4>
                            <?= $form->field($teaching_info, 'childcount')->label("How many children do you have?", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>                                                                  
                            <?php if($teaching_info->childcount > 0):?>
                                <?= $form->field($teaching_info, 'childages')->label("Ages of children", ['class'=> 'form-label'])->textArea(['rows' => '1']) ?>                 
                            <?php endif;?>
                            <?= $form->field($teaching_info, 'brothercount')->label("How many brothers do you have?", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>    
                            <?= $form->field($teaching_info, 'sistercount')->label("How many sisters do you have?", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?> 
                            <?= $form->field($teaching_info, 'applicationmotivation')->label("Motivation for application", ['class'=> 'form-label'])->textArea(['rows' => '3', 'readonly' => true]) ?>
                            <?= $form->field($teaching_info, 'additionalcomments')->label("Other Comments ", ['class'=> 'form-label'])->textArea(['rows' => '5', 'readonly' => true]) ?>                                        
                            
                            </br>
                            <fieldset>
                                <legend style="color:green"><strong>General Work Experience</strong></legend>
                                <?php
                                    if ($generalExperiences != false)
                                    {      
                                        $j = 1;
                                        for($i=0 ; $i<count($generalExperiences) ; $i++)
                                        { 
                                            echo "<p style='font-size:20px; color:lightblue'><strong>Role " . $j ."</strong></p>";
                                            echo "<table class='table table-hover' style='margin: 0 auto; font-size:18px;'>";
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>Role</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->role . "</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>Employer</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->employer . "</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>Employer Address</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->employeraddress . "</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>Nature Of Duties</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->natureofduties . "</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>Salary</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->salary . "</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>Start Date</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->startdate . "</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>End Date</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->enddate . "</td>";
                                                echo "</tr>";
                                            echo "</table><br>"; 
                                            $j++;
                                        }
                                    }
                                    else
                                    {
                                        echo "</br><p><strong>No past work roles have been entered.</strong></p></br>";
                                    }          
                                    ?> <br> 
                             </fieldset>
                            
                             </fieldset> 
                                <legend style="color:green"><strong>References</strong></legend>  
                                <h4 style="font-size:20px; color:lightblue"><strong>Reference 1</strong></h4>
                                    <?= $form->field($references[0], '[0]title')->label("Title *", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]); ?>           
                                    <?= $form->field($references[0], '[0]firstname')->label('First Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                                    <?= $form->field($references[0], '[0]lastname')->label('Last Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                                    <?= $form->field($references[0], '[0]address')->label("Address *", ['class'=> 'form-label'])->textArea(['rows' => '3', 'readonly' => true]) ?>
                                    <?= $form->field($references[0], '[0]occupation')->label('Occupation *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                                    <?= $form->field($references[0], '[0]contactnumber')->label('Contact Number *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>   

                                <br/><h4 style="font-size:20px; color:lightblue"><strong>Reference 2</strong></h4>
                                    <?= $form->field($references[1], '[1]title')->label("Title *", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>          
                                    <?= $form->field($references[1], '[1]firstname')->label('First Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>                   
                                    <?= $form->field($references[1], '[1]lastname')->label('Last Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>                
                                    <?= $form->field($references[1], '[1]address')->label("Address *", ['class'=> 'form-label'])->textArea(['rows' => '3', 'readonly' => true]) ?>                  
                                    <?= $form->field($references[1], '[1]occupation')->label('Occupation *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>                   
                                    <?= $form->field($references[1], '[1]contactnumber')->label('Contact Number *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            </fieldset>
                            
                             <br/><fieldset>
                                <legend style="color:green"><strong>Financial Information</strong></legend><fieldset>
                                    <?= $form->field($teaching_info, 'benefactor')->label("How will your studies be financed? *", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);?>

                                    <?php if ($teaching_info->benefactordetails != NULL && strcmp($teaching_info->benefactordetails,"") != 0): ?>
                                        <?= $form->field($teaching_info, 'benefactordetails')->label('Specify Financer', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                                    <?php endif;?>

                                    <?= $form->field($teaching_info, 'appliedforloan')->label("Have you applied for Student Loan? *", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);?>

                                    <?= $form->field($teaching_info, 'sponsorship')->label("Have you requested? *", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]);?>               

                                    <?php if ($teaching_info->sponsorname != NULL && strcmp($teaching_info->sponsorname,"") != 0): ?>
                                        <?= $form->field($teaching_info, 'sponsorname')->label("If you are sponsored please state the organization(s).", ['class'=> 'form-label'])->textArea(['rows' => '2', 'readonly' => true]) ?>
                                    <?php endif;?>
                            </fieldset>
                        </fieldset>
                    <?php endif; ?>
                    
                    
                    <!--If applicant intends to submit application to DNE's Nursing or Nursing Assistant programme-->
                    <?php if (Applicant::getApplicantIntent($applicant->personid) == 6):?> 
                        </br>
                        <fieldset>
                            <legend style="color:green"><strong>Additional Information</strong></legend>
                            <h4 style="text-align:center">Family Information</h4>
                            <?= $form->field($nursinginfo, 'childcount')->label("How many children do you have?", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>                                                                  
                            <?= $form->field($nursinginfo, 'childages')->label("Ages of children", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>                 
                            <?= $form->field($nursinginfo, 'brothercount')->label("How many brothers do you have?", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>    
                            <?= $form->field($nursinginfo, 'sistercount')->label("How many sisters do you have?", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?> 
                                                     
                            </br
                            <fieldset>
                                <legend style="color:green"><strong>General Work Experience</strong></legend>
                                <?php
                                    if ($generalExperiences != false)
                                    {      
                                        $j = 1;
                                        for($i=0 ; $i<count($generalExperiences) ; $i++)
                                        {   
                                            echo "<p style='font-size:20px; color:lightblue'><strong>Role " . $j ."</strong></p>";
                                            echo "<table class='table table-hover' style='margin: 0 auto; font-size:18px;'>";
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>Role</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->role . "</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>Employer</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->employer . "</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>Employer Address</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->employeraddress . "</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>Nature Of Duties</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->natureofduties . "</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>Salary</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->salary . "</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>Start Date</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->startdate . "</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                    echo "<th style='width:50%'>End Date</th>";
                                                    echo "<td style='width:50%'>" . $generalExperiences[$i]->enddate . "</td>";
                                                echo "</tr>";
                                            echo "</table><br>"; 
                                            $j++;
                                        }
                                    }
                                    else
                                    {
                                        echo "</br><p><strong>No past work roles have been entered.</strong></p></br>";
                                    }          
                                    ?> 
                             </fieldset>
                            
                            <?php if (NursingAdditionalInfo::hasOtherApplications($applicant->personid) == true):?>            
                                <?= $form->field($nursinginfo, 'otherapplicationsinfo')->label("Where have you applied for a job? (Apart from this application? *", ['class'=> 'form-label'])->textArea(['rows' => '1', 'readonly' => true]) ?>
                            <?php endif; ?>
                            
                            <fieldset>
                                <legend style="color:green"><strong>References</strong></legend>
                                <h4 style="font-size:20px; color:lightblue"><strong>Reference 1</strong></h4>
                                    <?= $form->field($references[0], '[0]title')->label("Title *", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]); ?>           
                                    <?= $form->field($references[0], '[0]firstname')->label('First Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                                    <?= $form->field($references[0], '[0]lastname')->label('Last Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                                    <?= $form->field($references[0], '[0]address')->label("Address *", ['class'=> 'form-label'])->textArea(['rows' => '3', 'readonly' => true]) ?>
                                    <?= $form->field($references[0], '[0]occupation')->label('Occupation *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                                    <?= $form->field($references[0], '[0]contactnumber')->label('Contact Number *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>   

                                <h4 style="font-size:20px; color:lightblue"><strong>Reference 2</strong></h4>
                                    <?= $form->field($references[1], '[1]title')->label("Title *", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>          
                                    <?= $form->field($references[1], '[1]firstname')->label('First Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>                   
                                    <?= $form->field($references[1], '[1]lastname')->label('Last Name *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>                
                                    <?= $form->field($references[1], '[1]address')->label("Address *", ['class'=> 'form-label'])->textArea(['rows' => '3', 'readonly' => true]) ?>                  
                                    <?= $form->field($references[1], '[1]occupation')->label('Occupation *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>                   
                                    <?= $form->field($references[1], '[1]contactnumber')->label('Contact Number *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            </fieldset>
                                
                            <h3 style="text-align:center">Other</h3>
                                <?= $form->field($nursinginfo, 'applicationmotivation1')->label("Reason #1 *", ['class'=> 'form-label'])->textArea(['rows' => '3', 'readonly' => true]) ?>
                                <?= $form->field($nursinginfo, 'applicationmotivation2')->label("Reason #2 *", ['class'=> 'form-label'])->textArea(['rows' => '3', 'readonly' => true]) ?>
                                <?= $form->field($nursinginfo, 'additionalcomments')->label("Other Comments ", ['class'=> 'form-label'])->textArea(['rows' => '5', 'readonly' => true]) ?>  
                                
                                <?php if (Application::hasMidwiferyApplication($applicant->personid) == true):?>
                                    <?= $form->field($nursinginfo, 'ismember')->label("Are you a member of a professional organisation? *", ['class'=> 'form-label'])->inline()->radioList($is_organisational_member, ['class'=> 'form-field', 'onclick' => 'toggleOrganisationDetails();']);?>
                                <?php endif;?>

                                <!-- Organization details -->
                                <?php if (Application::hasMidwiferyApplication($applicant->personid) == true  && NursingAdditionalInfo::isMember($applicant->personid) == true):?>
                                    <div id="member-organisations" style="display:block">  
                                        <?= $form->field($nursinginfo, 'memberorganisations')->label('If yes, state which?', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                                    </div>
                                <?php else:?>
                                    <div id="member-organisations" style="display:none">
                                         <?= $form->field($nursinginfo, 'memberorganisations')->label('If yes, state which?', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>   
                                    </div>
                                <?php endif; ?>

                                <!--Reason for not joining organization-->
                                <?php if (Application::hasMidwiferyApplication($applicant->personid) == true  && NursingAdditionalInfo::isMember($applicant->personid) == false):?>
                                    <div id="exclusion-reason" style="display:block">  
                                        <?= $form->field($nursinginfo, 'exclusionreason')->label('If no, give reason(s)?', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                                    </div>
                                <?php else:?>
                                    <div id="exclusion-reason" style="display:none">
                                         <?= $form->field($nursinginfo, 'exclusionreason')->label('If no, give reason(s)?', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>   
                                    </div>
                                <?php endif; ?>

                                <!-- Is repeat applicant radiolist -->
                                <?php if (Application::hasMidwiferyApplication($applicant->personid) == true):?>
                                    <?= $form->field($nursinginfo, 'repeatapplicant')->label("Have you applied for entry into this course previously? *", ['class'=> 'form-label'])->inline()->radioList($is_repeat_applicant, ['class'=> 'form-field', 'onclick' => 'togglePreviousYears();']);?>
                                <?php endif;?>

                                <!-- Previous years -->
                                <?php if (Application::hasMidwiferyApplication($applicant->personid) == true  && NursingAdditionalInfo::hasPreviousApplication($applicant->personid) == true):?>
                                    <div id="previous-years" style="display:block">                                
                                <?php else:?>
                                    <div id="previous-years" style="display:none">
                                <?php endif; ?>
                                         <?= $form->field($nursinginfo, 'previousyears')->label('If yes, state when?', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>   
                                    </div>
                            
                            
                                <?php if ($applicantDetails[1] == 1):?>
                                    <div id="national-sports">
                                        <?= $form->field($applicant, 'nationalsports')->label("National Sports", ['class'=> 'form-label'])->textArea(['rows' => '5', 'id'=>'national-sport-details', 'readonly' => true]) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($applicantDetails[2] == 1):?>
                                    <div id="recreational-sports">
                                        <?= $form->field($applicant, 'othersports')->label("Other Sports", ['class'=> 'form-label'])->textArea(['rows' => '5', 'id'=>'other-sport-details', 'readonly' => true]) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($applicantDetails[3] == 1):?>
                                    <div id="club-participation">
                                        <?= $form->field($applicant, 'clubs')->label("Clubs", ['class'=> 'form-label'])->textArea(['rows' => '5', 'id'=>'clubDetails', 'readonly' => true]) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($applicantDetails[4] == 1):?>
                                    <div id="extracurricular-activities">
                                        <?= $form->field($applicant, 'otherinterests')->label("Other Interests", ['class'=> 'form-label'])->textArea(['rows' => '5', 'id'=>'interestDetails', 'readonly' => true]) ?>
                                    </div>
                                <?php endif; ?>
                        </fieldset>
                    <?php endif; ?>
                       
                        
                    <?php if ((Applicant::getApplicantIntent($applicant->personid) == 6 && NursingAdditionalInfo::hasCriminalRecord($applicant->personid)== true)
                                ||  (Applicant::getApplicantIntent($applicant->personid) == 4 && TeachingAdditionalInfo::hasCriminalRecord($applicant->personid)== true)):?>
                        </br>
                        <fieldset>
                            <legend style="color:green"><strong>Criminal Record</strong></legend>
                            <?= $form->field($criminalrecord, 'natureofcharge')->label("Nature Of Charge *", ['class'=> 'form-label'])->textArea(['rows' => '3', 'readonly' => true]) ?>
                            <?= $form->field($criminalrecord, 'outcome')->label("Outcome *", ['class'=> 'form-label'])->textArea(['rows' => '3', 'readonly' => true]) ?>
                            <?= $form->field($criminalrecord, 'dateofconviction')->label('Date Of Conviction *', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>                           
                        </fieldset>
                    <?php endif; ?>
                        
                    
                    <?php if (Applicant::getApplicantIntent($applicant->personid) == 6 && NursingAdditionalInfo::hasPreviousNurseExperience($applicant->personid)== true):?>
                        </br>
                        <fieldset>
                            <legend style="color:green"><strong>Nurse Experience</strong></legend>
                            <?= $form->field($nurseExperience, "natureoftraining")->label("Nature of Training *", ['class'=> 'form-label'])->textArea(['rows' => '3', 'readonly' => true]) ?>
                            <?= $form->field($nurseExperience, "location")->label("Address *", ['class'=> 'form-label'])->textArea(['rows' => '3', 'readonly' => true]) ?>
                            <?= $form->field($nurseExperience, "tenureperiod")->label("Dates (From-To) *", ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            <?= $form->field($nurseExperience, "departreason")->label("Reason for leaving (if applicable) *", ['class'=> 'form-label'])->textArea(['rows' => '3', 'readonly' => true]) ?>                      
                        </fieldset>
                    <?php endif; ?>
                        
                    
                    <?php if (Applicant::getApplicantIntent($applicant->personid) == 4 ):?>
                        </br>
                        <fieldset>
                            <legend style="color:green"><strong>Teaching Experience</strong></legend>
                            <?php
                                if (count($teachingExperiences) > 0)
                                {                               
                                    for($i=0 ; $i<count($teachingExperiences) ; $i++) 
                                    {
                                        $j = $i+1;
                                        echo "<h4 style='font-size:20px; color:lightblue'><strong>Teaching Role {$j}</strong></h4>";
                                        echo "<table class='table table-hover' style='margin: 0 auto; font-size:17px;'>";
                                            echo "<tr>";
                                                echo "<th style='width:50%'><strong>Institution Name</strong></th>";
                                                echo "<td style='width:50%'>" . $teachingExperiences[$i]->institutionname . "</td>";
                                            echo "</tr>";

                                            echo "<tr>";
                                                echo "<th style='width:50%'><strong>Address<strong></th>";
                                                echo "<td style='width:50%'>" . $teachingExperiences[$i]->address . "</td>";
                                            echo "</tr>";
                                            
                                            if($teachingExperiences[$i]->dateofappointment != NULL)
                                            {
                                                echo "<tr>";
                                                    echo "<th style='width:50%'><strong>Date of Appointment</strong></th>";
                                                    echo "<td style='width:50%'>" . $teachingExperiences[$i]->dateofappointment . "</td>";
                                                echo "</tr>";
                                            }

                                            echo "<tr>";
                                                echo "<th style='width:50%'><strong>Start Date</strong></th>";
                                                echo "<td style='width:50%'>" . $teachingExperiences[$i]->startdate . "</td>";
                                            echo "</tr>";
                                            
                                            if($teachingExperiences[$i]->enddate != NULL)
                                            {
                                                echo "<tr>";
                                                    echo "<th style='width:50%'><strong>End Date</strong></th>";
                                                    echo "<td style='width:50%'>" . $teachingExperiences[$i]->enddate . "</td>";
                                                echo "</tr>";
                                            
                                            }

                                            echo "<tr>";
                                                echo "<th style='width:50%'><strong>Class Taught</strong></th>";
                                                echo "<td style='width:50%'>" . $teachingExperiences[$i]->classtaught . "</td>";
                                            echo "</tr>";

                                            echo "<tr>";
                                                echo "<th style='width:50%'><strong>Subject</strong></th>";
                                                echo "<td style='width:50%'>" . $teachingExperiences[$i]->subject . "</td>";
                                            echo "</tr>";
                                        echo "</table>";                   
                                    }
                                }
                                else
                                {
                                    echo "</br><p><strong>No past teaching roles have been entered.</strong></p></br>";
                                }          
                            ?>   
                        </fieldset>
                    <?php endif; ?>
                </fieldset>
            
            
                </br>
                <fieldset>
                    <legend style="color:green"><strong>Institutional Attendance</strong></legend>
                    
                    <?php 
                    
                        if ($preschools != false)
                        {
                            echo "<h4 style='font-size:20px; color:lightblue'><strong>Pre-Schools</strong></h4>";
                            for ($i=0 ; $i<count($preschools) ; $i++)
                            {
                                echo "<table class='table table-hover' style='margin: 0 auto; font-size:18px;'>";
                                    echo "<tr>";
                                        echo "<th style='width:50%'><strong>Name</strong></th>";
                                        echo "<td style='width:50%'>" . $preschoolNames[$i] . "</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th style='width:50%'><strong>Start Date</strong></th>";
                                        echo "<td style='width:50%'>" . $preschools[$i]->startdate . "</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th style='width:50%'><strong>End Date</strong></th>";
                                        echo "<td style='width:50%'>" . $preschools[$i]->enddate . "</td>";
                                    echo "</tr>";
                                echo "</table><br>";
                            } 
                        }
                        else
                        {
                            echo "</br><p><strong>No pre-school records have been entered.</strong></p>";
                        }   
                    ?>
                    
                    <?php 
                        if ($primaryschools != false)
                        {
                            echo "<h4 style='font-size:20px; color:lightblue'><strong>Primary Schools</strong></h4>";
                            for ($i=0 ; $i<count($primaryschools) ; $i++)
                            {
                               echo "<table class='table table-hover' style='margin: 0 auto; font-size:18px;'>";
                                    echo "<tr>";
                                        echo "<th style='width:50%'><strong>Name</strong></th>";
                                        echo "<td style='width:50%'>" . $primaryschoolNames[$i] . "</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th style='width:50%'><strong>Start Date</strong></th>";
                                        echo "<td style='width:50%'>" . $primaryschools[$i]->startdate . "</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th style='width:50%'><strong>End Date</strong></th>";
                                        echo "<td style='width:50%'>" . $primaryschools[$i]->enddate . "</td>";
                                    echo "</tr>";
                                echo "</table><br>";
                            }    
                        }
                        else
                        {
                            echo "</br><p><strong>No primary school records have been entered.</strong></p>";
                        }
                    ?>
                                    
                    <?php 
                        if ($secondaryschools != false)
                        {
                            echo "<h4 style='font-size:20px; color:lightblue'><strong>Secondary Schools</strong></h4>";
                            for ($i=0 ; $i<count($secondaryschools) ; $i++)
                            {
                                echo "<table class='table table-hover' style='margin: 0 auto; font-size:18px;'>";
                                    echo "<tr>";
                                        echo "<th style='width:50%'><strong>Name</strong></th>";
                                        echo "<td style='width:50%'>" . $secondaryschoolNames[$i] . "</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th style='width:50%'><strong>Start Date</strong></th>";
                                        echo "<td style='width:50%'>" . $secondaryschools[$i]->startdate . "</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th style='width:50%'><strong>End Date</strong></th>";
                                        echo "<td style='width:50%'>" . $secondaryschools[$i]->enddate . "</td>";
                                    echo "</tr>";
                                echo "</table><br>";
                            }
                        }
                        else
                        {
                            echo "</br><p><strong>No secondary school records have been entered.</strong></p>";
                        }
                    ?>
                    
                    <?php 
                        if ($tertieryschools != false)
                        {
                            echo "<h4 style='font-size:20px; color:lightblue'><strong>Post Secondary Schools</strong></h4>";
                            for ($i=0 ; $i<count($tertieryschools) ; $i++)
                            {
                                echo "<table class='table table-hover' style='margin: 0 auto; font-size:18px''>";
                                    echo "<tr>";
                                        echo "<th style='width:50%'><strong>Name</strong></th>";
                                        echo "<td style='width:50%'>" . $tertieryschoolNames[$i] . "</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th style='width:50%'><strong>Start Date</strong></th>";
                                        echo "<td style='width:50%'>" . $tertieryschools[$i]->startdate . "</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th style='width:50%'><strong>End Date</strong></th>";
                                        echo "<td style='width:50%'>" . $tertieryschools[$i]->enddate . "</td>";
                                    echo "</tr>";
                                echo "</table><br>";
                            }
                        }
                        else
                        {
                            echo "</br><p><strong>No post-secondary school records have been entered.</strong></p></br>";
                        }
                    ?>         
                </fieldset>
            
                
                <?php if (Applicant::getApplicantIntent($applicant->personid) == 6 && Application::hasMidwiferyApplication($applicant->personid) == true):?>
                    </br>
                    <fieldset>
                        <legend style="color:green"><strong>Nursing Certifications</strong></legend>
                        <?php
                            if (count($certificates) ==0)
                            {
                                echo "</br><p><strong>No nursing certification have been entered.</strong></p></br>";
                            }
                            else
                            {
                                $k = 1;
                                foreach ($certificates as $certificate) 
                                {
                                    echo "<p style='font-size:20px; color:lightblue'><strong>Certification " . $k ."</strong></p>";
                                    echo "<table class='table table-hover' style='margin: 0 auto; font-size:18px''>";
                                        echo "<tr>";
                                            echo "<th style='width:50%'><strong>Name</strong></th>";
                                            echo "<td style='width:50%'>" . $certificate->certification . "</td>";
                                        echo "</tr>";

                                        echo "<tr>";
                                            echo "<th style='width:50%'><strong>Dates of Training</strong></th>";
                                            echo "<td style='width:50%'>" . $certificate->datesoftraining . "</td>";
                                        echo "</tr>";

                                        echo "<tr>";
                                            echo "<th style='width:50%'><strong>Length of Training</strong></th>";
                                            echo "<td style='width:50%'>" . $certificate->lengthoftraining . "</td>";
                                        echo "</tr>";

                                        echo "<tr>";
                                            echo "<th style='width:50%'><strong>Name of Institution</strong></th>";
                                            echo "<td style='width:50%'>" . $certificate->institutionname . "</td>";
                                        echo "</tr>";
                                    echo "</table>";

                                    echo "</br></br>";
                                    $k++;
                                }
                            }
                        ?>   
                    </fieldset>
                <?php endif;?>
                    
                
                <?php if (PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid) == true):?>
                    <br/><fieldset>
                        <p style="color:green; font-size:21px;"><strong>Post Secondary Qualification</strong></p>
                        <table class='table table-hover' style='margin: 0 auto; font-size:18px;'>
                            <tr>
                                <th style='width:50%'><strong>Name of Degree</strong></th>
                                <td style='width:50%'><?= $qualification->name; ?></td>
                            </tr>

                            <tr>
                               <th style='width:50%'><strong>Awarding Institution</strong></th>
                               <td style='width:50%'><?= $qualification->awardinginstitution;?></td>
                            </tr>

                            <tr>
                                <th style='width:50%'><strong>Year Degree Awarded</strong></th>
                                <td style='width:50%'><?= $qualification->yearawarded;?></td>
                            </tr>
                        </table>
                    </fieldset>
                <?php else:?>
                    <br/><fieldset>
                        <p style="color:green; font-size:21px;"><strong>Post Secondary Qualification</strong></p>
                        <p><strong>Applicant did not indicate they have a Post-Secondary Degree.</strong></p></br>
                    </fieldset>
                <?php endif;?>
                
                
                <p id="edit-application">
                    <?= Html::a('Return to Application Review Dashboard',
                        ['process-applications/view-applicant-certificates', 'personid' => $applicant->personid, 'programme' => $programme, 'application_status' => $application_status], 
                        ['class' => 'btn btn-block btn-primary btn-danger']) 
                    ?>
                </p>
    
            <?php yii\bootstrap\ActiveForm::end(); ?>
        </div>
    </div>
</div>
    

