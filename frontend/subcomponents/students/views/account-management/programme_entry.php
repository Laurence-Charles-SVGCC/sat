<?php
    use yii\widgets\Breadcrumbs;
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
    
    $this->title = 'Programme Selection';
    
    $this->params['breadcrumbs'][] = ['label' => 'Student Listing', 'url' => Url::toRoute(['/subcomponents/students/account-management'])];
    $this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['/subcomponents/students/account-management/account-dashboard', 'recordid' => $recordid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
             <?= Html::hiddenInput('cape-id', AcademicOffering::getCurrentCapeID()); ?>

            <!--Parent--> 
            <div class="form-group" id="choice-division">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="divisionid">Select Division:</label>
                <p><?= $form->field($application, 'divisionid')->label('')->dropDownList(Division::getDivisions(Applicant::getApplicantIntent($personid)), ['id' => 'division-id', 'onchange' => 'showCape();', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></p>   
            </div>

            <!--Child--> 
            <div id="cape-first-choice-programme" class="form-group"> 
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="divisionid">Select your programme of choice:</label>
                <?= $form->field($application, 'academicofferingid')->widget(DepDrop::classname(), [
                        'options'=>['id'=>'academicoffering-id', 'onchange' => 'showCape();'],
                        'pluginOptions'=>[
                            'depends'=>['division-id'],
                            'placeholder'=>'Select...',
                            'url'=> Url::toRoute(['/subcomponents/admissions/process-applications/academic-offering', 'personid' => $personid])
                            ]
                    ])->label('');?>
            </div>

            <div id="cape-choice" style="display:none;" class="form-group">
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
                        $subjects = CapeSubjectGroup::getActiveSubjects($groupid);               
                        $vals =  CapeSubject::processGroup($subjects);
                        echo $form->field($applicationcapesubject[$key], "[{$key}]capesubjectid")->label("")->radioList($vals, ['id' => 'choice1-group1', 'class' => 'radio1']);
                        echo "</fieldset>"; 
                        echo "</br>";
                    }
                ?>
            
         </div>

        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['account-management/account-dashboard', 'recordid' => $recordid], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>