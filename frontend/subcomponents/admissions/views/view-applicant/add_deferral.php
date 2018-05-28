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
   
    $this->title = "Applicant Deferral Resumption";
    
    $this->params['breadcrumbs'][] = ['label' => 'Find Applicant', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => $search_status])];
    $this->params['breadcrumbs'][] = ['label' => 'Applicant Profile', 'url' => Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'search_status' => $search_status, 'applicantusername' => $user->username, 'unrestricted' => $unrestricted])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title;?></span>
        <span class="pull-right"><strong>Current Programme:</strong> <?= $current_programme;?></span>
     </div><br/>
    
     <div class="alert alert-info" role="alert" style="width: 85%; margin: 0 auto; font-size:16px;">
        The feature should only be used to re-enroll a student that received prior approval to defer their enrollment.  
    </div><br/>
    
    <?php $form = ActiveForm::begin([
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
                        'validateOnBlur' => true,
                        'successCssClass' => 'alert in alert-block fade alert-success',
                        'errorCssClass' => 'alert in alert-block fade alert-error',
                        'options' => ['class' => 'form-layout'],
                    ]);
     ?>
        <div class="box-body">
            <?= Html::hiddenInput('cape-id', AcademicOffering::getMostRecentCapeAcademicOfferingID()); ?>

            <!-- Parent -->
           <div id='division-choice' style='font-size:20px;'>
                <p><?= $form->field($new_application, 'divisionid')->label("Select division")->dropDownList(Division::getDivisions(Applicant::getApplicantIntent(NULL)), ['id' => 'division-id', 'onchange' => 'showCape();']);?></p>   
            </div><br/>

            <!-- Child --> 
            <div id='programme-choice' style='font-size:20px;'>       
                <p> <?= $form->field($new_application, 'academicofferingid')->widget(DepDrop::classname(), [
                                'options'=>['id'=>'academicoffering-id', 'onchange' => 'showCape()'],
                                'pluginOptions'=>[
                                    'depends'=>['division-id'],
                                    'placeholder'=>'Select...',
                                    'url'=>Url::to(['view-applicant/academicoffering', 'personid' => $personid])
                                ]
                            ])->label('Select the programme you wish to issue new offer for...')?>
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
        </div>
    
        <div class="box-footer" style="background-color: #CCCCCF;">
            <span class = "pull-right">
                <?= Html::submitButton(' Save', ['class' => 'btn btn-success', 'style' => 'margin-right:20px;']);?>
                <?= Html::a(' Cancel', ['view-applicant/applicant-profile','search_status' => $search_status, 'applicantusername' => $user->username, 'unrestricted' => $unrestricted], ['class' => 'btn btn-danger']);?>
          </span>
        </div><br/>  
    <?php ActiveForm::end();?><br/> 
</div>
    
