<?php
    use yii\widgets\Breadcrumbs;
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
    
    $this->title = 'Transfer Student';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find An Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = ['label' => 'Student Profile', 'url' => Url::toRoute(['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<h2 class="text-center"><?= $this->title;?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title">Current Programme: <?= $current_programme;?></span>
    </div>
    
    <div class="box-body">
        <?php $form = ActiveForm::begin([
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

                <?= Html::hiddenInput('cape-id', AcademicOffering::getCurrentCapeID()); ?>

               <p><?= $form->field($transfer, 'details')->label('Do you wish to add any comments')->textArea(['rows' => '4'])?></p>

                <!-- Parent -->
               <div id='division-choice' style='font-size:20px;'>
                    <p><?= $form->field($application, 'divisionid')->label("Select division")->dropDownList(Division::getDivisions(Applicant::getApplicantIntent($personid)), ['id' => 'division-id', 'onchange' => 'showCape();']);?></p>   
                </div>
                <br/>

                <!-- Child --> 
                <div id='programme-choice' style='font-size:20px;'>       
                    <p> <?= $form->field($application, 'academicofferingid')->widget(DepDrop::classname(), [
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
                            You are allowed to select 3 or 4 subjects. You can not select two
                             subjects from the same group.
                        </strong>
                    </p>

                   <?php
                    foreach($capegroups as $key=>$group)
                    {                           
                        echo "<fieldset>";
                        echo "<legend>".$group->name;echo"</legend>";                         
                        $groupid = $group->capegroupid;
                        $subjects = CapeSubjectGroup::getActiveSubjects($groupid);                         
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
    
    



<!--    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/sms_4.png" alt="student avatar">
                    <span class="custom_module_label">Welcome to the Student Management System</span> 
                    <img src ="css/dist/img/header_images/sms_4.png" alt="student avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">
                <h1 class="custom_h1">Transfer Student</h1>

                <br/>
                <h2 class="custom_h2" style="margin-left:7.5%">Current Programme: <?= $current_programme;?></h2><br/>
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
                    
                        <?= Html::hiddenInput('cape-id', AcademicOffering::getCurrentCapeID()); ?>
                    
                       <p><?= $form->field($transfer, 'details')->label('Do you wish to add any comments')->textArea(['rows' => '4'])?></p>

                         Parent 
                       <div id='division-choice' style='font-size:20px;'>
                            <p><?= $form->field($application, 'divisionid')->label("Select division")->dropDownList(Division::getDivisions(Applicant::getApplicantIntent($personid)), ['id' => 'division-id', 'onchange' => 'showCape();']);?></p>   
                        </div>
                        <br/>

                         Child  
                        <div id='programme-choice' style='font-size:20px;'>       
                            <p> <?= $form->field($application, 'academicofferingid')->widget(DepDrop::classname(), [
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
                                    You are allowed to select 3 or 4 subjects. You can not select two
                                     subjects from the same group.
                                </strong>
                            </p>

                           <?php
                            foreach($capegroups as $key=>$group)
                            {                           
                                echo "<fieldset>";
                                echo "<legend>".$group->name;echo"</legend>";                         
                                $groupid = $group->capegroupid;
                                $subjects = CapeSubjectGroup::getActiveSubjects($groupid);                         
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
    </div>-->
