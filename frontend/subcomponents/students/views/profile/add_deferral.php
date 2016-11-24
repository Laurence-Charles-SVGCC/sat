<?php

/* 
 * 'add_transfer' view.  Used for modifying information in the 'General' section of 'Profile' tab
 * Author: Laurence Charles
 * Date Created: 08/01/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use kartik\depdrop\DepDrop;
    
    use frontend\models\Application;
    use frontend\models\Applicant;
    use frontend\models\Division;
    use frontend\models\CapeGroup;
    use frontend\models\CapeSubjectGroup;
    use frontend\models\CapeSubject;
    use frontend\models\ApplicationCapesubject;
    use frontend\models\AcademicOffering;
   
    $this->title = $title;
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
                <h1 class="custom_h1"><?= $title;?></h1>

                <br/>
                <h2 class="custom_h2" style="margin-left:7.5%">Current Programme: <?= $current_programme;?></h2>
                
                <?php if ($title == "Deferral Resumption"):?>
                   <div class="alert alert-info" role="alert" style="width: 85%; margin: 0 auto; font-size:16px;">
                        The feature should only be used to re-enroll a student that received prior approval to defer their enrollment
                        (after registration was already completed).  
                    </div>
                <?php elseif ($title == "Registration Renewal"):?>
                    <div class="alert alert-info" role="alert" style="width: 85%; margin: 0 auto; font-size:16px;">
                        The feature should only be used in the event a current student received approval to re-enroll in a 
                        new programme as a Year One student.
                    </div>
                <?php endif;?>
                    
                <br/>
                <?php
                    $form = ActiveForm::begin([
                                'id' => 'add-transfer-form',
                                'enableAjaxValidation' => false,
                                'enableClientValidation' => true,
                                'validateOnSubmit' => true,
                                'validateOnBlur' => true,
                                'successCssClass' => 'alert in alert-block fade alert-success',
                                'errorCssClass' => 'alert in alert-block fade alert-error',
                                'options' => [
                                    'class' => 'form-layout'
                                ],
                            ]);
                    ?>
                        <?= Html::hiddenInput('cape-id', AcademicOffering::getMostRecentCapeAcademicOfferingID()); ?>
                        
                       <?php if ($title != "Applicant Deferral Resumption"):?>
                            <p><?= $form->field($deferral, 'details')->label('Do you wish to add any comments')->textArea(['rows' => '4'])?></p>
                       <?php endif;?>
                            
                        <!-- Parent -->
                       <div id='division-choice' style='font-size:20px;'>
                            <p><?= $form->field($new_application, 'divisionid')->label("Select division")->dropDownList(Division::getDivisions(Applicant::getApplicantIntent(NULL)), ['id' => 'division-id', 'onchange' => 'showCape();']);?></p>   
                        </div>
                        <br/>

                        <!-- Child --> 
                        <div id='programme-choice' style='font-size:20px;'>       
                            <p> <?= $form->field($new_application, 'academicofferingid')->widget(DepDrop::classname(), [
                                            'options'=>['id'=>'academicoffering-id', 'onchange' => 'showCape()'],
                                            'pluginOptions'=>[
                                                'depends'=>['division-id'],
                                                'placeholder'=>'Select...',
                                                'url'=>Url::to(['profile/academicoffering', 'personid' => $personid])
                                            ]
                                        ])->label('Select your programme of first choice:')?>
                            </p>
                        </div><br/>

                        <div id='cape-choice' style='font-size:18px; border: thin black solid; padding:10px; display:none;'>
                            <h3 style='text-align:center'>CAPE Subject Selection</h3>
                            <p>
                                <strong>
                                    The options below represents the CAPE subjects from which you can select.
                                    You are allowed to select 2 - 4 subjects. You can not select two
                                     subjects from the same group.
                                </strong>
                            </p>

                           <?php
                            foreach($capegroups as $key=>$group)
                            {                           
                                echo "<fieldset>";
                                echo "<legend>".$group->name;echo"</legend>";                         
                                $groupid = $group->capegroupid;
                                $subjects = CapeSubjectGroup::getMostRecentCapeSubjects($groupid);                         
                                $vals =  CapeSubject::processGroup($subjects);
                                echo $form->field($applicationcapesubject[$key], "[{$key}]capesubjectid")->label("")->radioList($vals, ['id' => 'choice1-group1', 'class' => 'radio1']);
                                echo "</fieldset>"; 
                                echo "</br>";
                            }
                        ?>
                    </div><br/><br/>  
                    
                    <div class="form-group">
                        <?= Html::a(' Cancel',['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);?>
                        <?= Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);?>
                    </div><br/><br/>  
                     
                    <?php ActiveForm::end();?>
            </div>
        </div>
    </div>
