<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Url;
    
    use common\models\User;
    use frontend\models\Relation;
    use frontend\models\CompulsoryRelation;  
    use frontend\models\Applicant;
    use frontend\models\Address;
    use frontend\models\MedicalCondition;

    $this->title = 'Profile Entry';
    $this->params['breadcrumbs'][] = ['label' => 'Student Listing', 'url' => Url::toRoute(['/subcomponents/students/account-management'])];
    $this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['/subcomponents/students/account-management/account-dashboard', 'recordid' => $recordid])];
    $this->params['breadcrumbs'][] = $this->title;
    
    $relationType = [
        '' => 'Select...',
        1 => 'Mother',
        2 => 'Father',
        3 => 'Next of Kin',
        5 => 'Guardian',
    ];
    
    $spouseRelations = [
        "" => 'Select...',
        7 => 'Spouse',
    ];
    
    $compulsory_relations = [
        '' => 'Select...',
        'mother' => 'Mother',
        'father' => 'Father',
        'next of kin' => 'Next of Kin',
        'guardian' => 'Guardian',
        'spouse' => 'Spouse',
        'other' => 'Other'
    ];
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/students/account-management'])?>" title="Student Creation Management">
        <h1>Welcome to the Student Management System</h1>
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
               <?= $form->field($model, 'title')->label('')->dropDownList(Yii::$app->params['titles'], [ 'prompt'=>'Select Title', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?> 
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="firstname">First Name*:</label>
               <?= $form->field($model, 'firstname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="middlename">Middle Name:</label>
               <?= $form->field($model, 'middlename')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="lastname">Last Name*:</label>
               <?= $form->field($model, 'lastname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="dateofbirth">Date Of Birth:</label>
               <?= $form->field($model, 'dateofbirth')->label(false)->widget(
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
               <?= $form->field($model, 'gender')->label(false)->inline()->radioList(Yii::$app->params['gender'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>                                      
           </div><br/><br/>
           
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="nationality">Nationality*:</label>
                <?= $form->field($model, 'nationality')->label('')->dropDownList(Yii::$app->params['nationality'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
           </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="placeofbirth">Place Of Birth*:</label>
                <?= $form->field($model, 'placeofbirth')->label('')->dropDownList(Yii::$app->params['placeofbirth'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="religion">Religion*:</label>
                <?= $form->field($model, 'religion')->label('')->dropDownList(Yii::$app->params['religion'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="maritalstatus">What is your marital Status*:</label>
                <?= $form->field($model, 'maritalstatus')->label(false)->inline()->radioList(Yii::$app->params['maritalstatus'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9', 'onclick' => 'showSpouse();']);?>
            </div><br/><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="sponsorname">If sponsored please name the organisation(s):</label>
                <?= $form->field($model, 'sponsorname')->label(false)->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div><br/><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="'isexternal">Were you awarded any academic certificates outside of St. Vincent and the Grenadines? *:</label>
                <?= $form->field($model, 'isexternal')->label(false)->inline()->radioList(Yii::$app->params['external'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9'] );?>
            </div><br/><br/><br/><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="otheracademics">Technical and Vocation Academic Experience:</label>
                <?= $form->field($model, 'otheracademics')->label('')->textArea(['rows' => '5'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div><br/><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="nationalsports">National Sports:</label>
                <?= $form->field($model, 'nationalsports')->label('')->textArea(['rows' => '5'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div><br/><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="othersports">Recreational Sports:</label>
                <?= $form->field($model, 'othersports')->label('')->textArea(['rows' => '5'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div><br/><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="clubs">Club Participation:</label>
                <?= $form->field($model, 'clubs')->label('')->textArea(['rows' => '5'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div><br/><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="otherinterests">Other Interests:</label>
                <?= $form->field($model, 'otherinterests')->label('')->textArea(['rows' => '5'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div>
        </div>

        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['account-management/account-dashboard', 'recordid' => $recordid], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>