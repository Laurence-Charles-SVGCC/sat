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
    
    $this->title = 'Transfer Student';
?>

    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                    <img class="custom_logo_students" src ="<?=Url::to('../images/sms_4.png');?>" alt="Find A Student">
                    <span class="custom_module_label">Welcome to the Student Management System</span> 
                    <img src ="<?=Url::to('../images/sms_4.png');?>" alt="student avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">                
                <div class="module_body">
                    <h1 class="custom_h1">Transfer Student</h1>
                    
                    <?php
                        $form = ActiveForm::begin([
                                    //'action' => Url::to(['gradebook/index']),
                                    'id' => 'add-transfer-form',
                                    'options' => [
                                        'class' => 'form-layout'
                                    ],
                                ]);
                            
                                echo "<p>{$form->field($transfer, 'details')->label('Do you wish to add any comments')->textArea(['rows' => '4'])}</p>";
                        
                            /*** Parent ***/ 
                            echo "<div id='division-choice' style='font-size:20px;'>";
                                $function_name = 'showCape(' . $cape_id . ');';
                                if (Applicant::getApplicantIntent($personid) == 1)
                                    echo "<p>{$form->field($application, 'divisionid')->label('Select division')->dropDownList(Division::getDivisions(Applicant::getApplicantIntent($personid)), ['onchange' => $function_name])}</p>";
                                else
                                    echo "<p>{$form->field($application, 'divisionid')->label('Select division')->dropDownList(Division::getDivisions(Applicant::getApplicantIntent($personid)))}</p>";
                            echo "</div>";
                            echo "</br>";

                            /*** Child ***/ 
                            echo "<div id='programme-choice' style='font-size:20px;'>";       
                                echo "<p>{$form->field($application, 'academicofferingid')->widget(DepDrop::classname(), [
                                                'options'=>['id'=>'academicoffering-id', 'onchange' => $function_name],
                                                'pluginOptions'=>[
                                                    'depends'=>['application-divisionid'],
                                                    'placeholder'=>'Select...',
                                                    'url'=>Url::to(['profile/academicoffering', 'personid' => $personid])
                                                ]
                                            ])->label('Select your programme of first choice:')}"
                                . "</p>";
                            echo "</div></br>";

                            echo "<div id='cape-choice' style='font-size:18px; border: thin black solid; padding:10px; display:none;'>";
                                echo "<h3 style='text-align:center'>CAPE Subject Selection</h3>";
                                echo "<p>";
                                    echo "<strong>";
                                        echo "The options below represents the CAPE subjects from which you can select.
                                              You are allowed to select 3 or 4 subjects. You can not select two
                                              subjects from the same group.";
                                    echo "</strong>";
                                echo "</p>";
        
                                $i = 0;
                                foreach($capegroups as $group)
                                {                           
                                    echo "<fieldset>";
                                        echo "<legend>$group->name</legend>";                         
                                        $groupid = $group->capegroupid;
                                        $subjects = CapeSubjectGroup::getSubjects($groupid);                         
                                        $vals =  CapeSubject::processGroup($subjects);
                                        echo $form->field($applicationcapesubject[$i], "[{$i}]capesubjectid")->label('')->radioList($vals, ['id' => 'choice1-group1', 'class' => 'radio1']);
                                    echo "</fieldset>"; 
                                    echo "</br>";
                                    $i++;
                                }
                            echo "</div>";   
                            
                            
                            echo Html::a(' Cancel',['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                            echo Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);
                              echo "</br></br></br>"; 
                       ActiveForm::end();    
                    ?>
                
                </div>
            </div>
        </div>
    </div>
