<?php

/* 
 * Author: Laurence Charles
 * Date Created: 29/05/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\web\UrlManager;
    use yii\bootstrap\ActiveForm;
    use yii\bootstrap\ActiveField;
    use kartik\depdrop\DepDrop;
    use yii\bootstrap\Modal;
    
    use frontend\models\Application;
    use frontend\models\Applicant;
    use frontend\models\Division;
    use frontend\models\CapeGroup;
    use frontend\models\CapeSubjectGroup;
    use frontend\models\CapeSubject;
    use frontend\models\ApplicationCapesubject;
    use frontend\models\AcademicOffering;
    
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    
    $this->title = 'Programme Selection';
?>


<div class="verify-applicants-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/create_male.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/create_female.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
           
            <h2 class="custom_h1"><?= Html::encode($this->title) ?></h2><br/>
    
            <div>           
                <?php 
                    $form = yii\bootstrap\ActiveForm::begin([
                       'id' => 'programme-entry',
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
                        'validateOnBlur' => true,
                        'successCssClass' => 'alert in alert-block fade alert-success',
                        'errorCssClass' => 'alert in alert-block fade alert-error',
                        'options' => [
                            'class' => 'form-layout'
                        ],
                    ])
                ?>

                    <?= Html::hiddenInput('cape-id', AcademicOffering::getCurrentCapeID()); ?>

                    <!--Parent--> 
                    <div id="choice-division" style="font-size:17px;">              
                        <p><?= $form->field($application, 'divisionid')->label("Select your division of first choice")->dropDownList(Division::getDivisions(Applicant::getApplicantIntent($personid)), ['id' => 'division-id', 'onchange' => 'showCape();']);?></p>   
                    </div>
                    </br>

                    <!--Child--> 
                    <div id="cape-first-choice-programme" style="font-size:17px;">       
                        <p> <?= $form->field($application, 'academicofferingid')->widget(DepDrop::classname(), [
                                'options'=>['id'=>'academicoffering-id', 'onchange' => 'showCape();'],
                                'pluginOptions'=>[
                                    'depends'=>['division-id'],
                                    'placeholder'=>'Select...',
                                    'url'=> Url::toRoute(['/subcomponents/admissions/process-applications/academic-offering', 'personid' => $personid])
//                                    'url'=> Url::to(['process-applications/academic-offering', 'personid' => $personid])
                                    ]
                            ])->label('Select your programme of choice:');?>
                    </div></br> 

                    <div id="cape-choice" style="font-size:14px; border: thin black solid; padding:10px; display:none;">
                        <h3 style='text-align:center'>CAPE Subject Selection</h3>
                        <p><strong>
                                The options below represents the CAPE subjects from which you can select.
                                You are allowed to select 3 or 4 subjects. You can not select two
                                subjects from the same group.
                        </strong></p>
                        
                        <?php
                            foreach($capegroups as $key=>$group)
                            {                           
                                echo "<fieldset>";
                                echo "<legend>".$group->name;echo"</legend>";                         
                                $groupid = $group->capegroupid;
                                $subjects = CapeSubjectGroup::getSubjects($groupid);                         
                                $vals =  CapeSubject::processGroup($subjects);
                                echo $form->field($applicationcapesubject[$key], "[{$key}]capesubjectid")->label("")->radioList($vals, ['id' => 'choice1-group1', 'class' => 'radio1']);
                                echo "</fieldset>"; 
                                echo "</br>";
                            }
                        ?>
                    </div></br></br>

                   <div class="form-group">
                       <?= Html::submitButton('Save', ['class' => 'btn btn-success']);?>
                   </div>
                <?php yii\bootstrap\ActiveForm::end(); ?>       
            </div>    
        </div>    
    </div>  
</div>




