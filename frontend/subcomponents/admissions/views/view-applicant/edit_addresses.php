<?php
    use yii\widgets\Breadcrumbs;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
     use yii\bootstrap\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;

    use frontend\models\Address;
    
    $this->title = 'Edit Addresses';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find Applicant', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => $search_status])];
    $this->params['breadcrumbs'][] = ['label' => 'Applicant Profile', 'url' => Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'search_status' => $search_status, 'applicantusername' => $user->username])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <h4 class="text-center">Permanent Address</h4>
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="country">Country:</label>
               <?= $form->field($addresses[0], '[0]country')->label('')->dropDownList(Yii::$app->params['country'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>

            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="town">Town:</label>
               <?= $form->field($addresses[0], '[0]town')->label('')->dropDownList(Yii::$app->params['towns'],["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>

            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="addressline">Address-line:</label>
               <?= $form->field($addresses[0], '[0]addressline')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div><hr>
            
            
            <h4 class="text-center">Residential Address</h4>
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="country">Country:</label>
               <?= $form->field($addresses[1], '[1]country')->label('')->dropDownList(Yii::$app->params['country'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>

            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="town">Town:</label>
               <?= $form->field($addresses[1], '[1]town')->label('')->dropDownList(Yii::$app->params['towns'],["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>

            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="addressline">Address-line:</label>
               <?= $form->field($addresses[1], '[1]addressline')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div><hr>
            
            
            <h4 class="text-center">Postal Address</h4>
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="country">Country:</label>
               <?= $form->field($addresses[2], '[2]country')->label('')->dropDownList(Yii::$app->params['country'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>

            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="town">Town:</label>
               <?= $form->field($addresses[2], '[2]town')->label('')->dropDownList(Yii::$app->params['towns'],["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>

            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="addressline">Address-line:</label>
               <?= $form->field($addresses[2], '[2]addressline')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
        </div>

         <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['view-applicant/applicant-profile',  'search_status' => $search_status,  'applicantusername' => $user->username], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>