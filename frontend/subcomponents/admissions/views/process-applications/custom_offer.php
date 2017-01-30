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
    
    
    $this->title = 'Customized Offer';
   
    $this->params['breadcrumbs'][] = ['label' => 'Review Applicants', 'url' => Url::toRoute(['/subcomponents/admissions/process-applications'])];
    $this->params['breadcrumbs'][] = $this->title;
?>



<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/process-applications']);?>" title="Process Applications">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/>

<h2 class="text-center"><?= $this->title;?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
    <br/>
    <?php 
        $form = yii\bootstrap\ActiveForm::begin([
           'id' => 'custom-offer',
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
        <div id="cape-first-choice-division" style="font-size:17px;">              
            <p><?= $form->field($application, 'divisionid')->label("Select division")->dropDownList(Division::getDivisions(Applicant::getApplicantIntent($personid)), ['id' => 'division-id', 'onchange' => 'showCape();']);?></p>   
        </div>
        </br>

        <!--Child--> 
        <div id="cape-first-choice-programme" style="font-size:17px;">       
            <p> <?= $form->field($application, 'academicofferingid')->widget(DepDrop::classname(), [
                    'options'=>['id'=>'academicoffering-id', 'onchange' => 'showCape();'],
                    'pluginOptions'=>[
                        'depends'=>['division-id'],
                        'placeholder'=>'Select...',
                        'url'=> Url::to(['process-applications/academic-offering', 'personid' => $personid])
                        ]
                ])->label('Select your programme of choice:');?>
        </div></br> 

        <div id="cape-choice" style="font-size:14px; border: thin black solid; padding:10px; display:none;">
            <h3 style='text-align:center'>CAPE Subject Selection</h3>
            <p><strong>
                    The options below represents the CAPE subjects from which you can select.
                    You are allowed to select 2 - 4 subjects. You can not select two
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
        </div><br/><br/>

       <div class="form-group">
           <?= Html::submitButton('Save', ['class' => 'btn btn-success']);?>
       </div>
    <?php yii\bootstrap\ActiveForm::end(); ?><br/>
</div>




