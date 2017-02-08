<?php
    use yii\widgets\Breadcrumbs;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
     use yii\bootstrap\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    
    use frontend\models\CsecQualification;
    
    $this->title = 'Edit General';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find Applicant', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => $search_status])];
    $this->params['breadcrumbs'][] = ['label' => 'Applicant Profile', 'url' => Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'search_status' => $search_status, 'applicantusername' => $user->username])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => $search_status]);?>" title="Find Applicant">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Title:</label>
               <?= $form->field($applicant, 'title')->label('')->dropDownList(Yii::$app->params['titles'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?> 
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="firstname">First Name*:</label>
               <?= $form->field($applicant, 'firstname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="middlename">Middle Name:</label>
               <?= $form->field($applicant, 'middlename')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="lastname">Last Name*:</label>
               <?= $form->field($applicant, 'lastname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="dateofbirth">Date Of Birth:</label>
               <?= $form->field($applicant, 'dateofbirth')->label(false)->widget(
                        DatePicker::className(), [
                            'inline' => false,
                            'template' => '{addon}{input}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                                "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"
                            ]
                        ]); 
               ?>
            </div>
            
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="gender">Gender*:</label>
               <?= $form->field($applicant, 'gender')->label(false)->inline()->radioList(Yii::$app->params['gender'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>                                      
           </div><br/><br/>
           
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="nationality">Nationality*:</label>
                <?= $form->field($applicant, 'nationality')->label('')->dropDownList(Yii::$app->params['nationality'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
           </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="placeofbirth">Place Of Birth*:</label>
                <?= $form->field($applicant, 'placeofbirth')->label('')->dropDownList(Yii::$app->params['placeofbirth'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="religion">Religion*:</label>
                <?= $form->field($applicant, 'religion')->label('')->dropDownList(Yii::$app->params['religion'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="maritalstatus">What is your marital Status*:</label>
                <?= $form->field($applicant, 'maritalstatus')->label(false)->inline()->radioList(Yii::$app->params['maritalstatus'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'onclick' => 'showSpouse();']);?>
            </div><br/><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="sponsorname">If sponsored please name the organisation(s):</label>
                <?= $form->field($applicant, 'sponsorname')->label(false)->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div><br/><br/>
            
            <?php if (CsecQualification::find()->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0])->one() == true):?>
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="sponsorname">Has no GCE/CSEC/CAPE but has other qualifications:</label>
                     <?= $form->field($applicant, 'isexternal')->label(false)->inline()->radioList(Yii::$app->params['external'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9'] );?>
                </div><br/><br/>
            <?php endif;?>
        </div>

         <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['view-applicant/applicant-profile',  'search_status' => $search_status,  'applicantusername' => $user->username], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>