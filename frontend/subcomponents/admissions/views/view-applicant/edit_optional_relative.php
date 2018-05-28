<?php
    use yii\widgets\Breadcrumbs;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
     use yii\bootstrap\ActiveForm;
    use yii\bootstrap\ActiveField;

    
    $this->title = 'Edit Relative';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find Applicant', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => $search_status])];
    $this->params['breadcrumbs'][] = ['label' => 'Applicant Profile', 'url' => Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'search_status' => $search_status, 'applicantusername' => $user->username])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title">Edit <?=$relation_name?> Details</span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Title:</label>
               <?= $form->field($relative, 'title')->label('')->dropDownList(Yii::$app->params['titles'], [ 'prompt'=>'Select Title', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?> 
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="firstname">First Name*:</label>
               <?= $form->field($relative, 'firstname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="lastname">Last Name*:</label>
               <?= $form->field($relative, 'lastname')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="homephone">Home Phone:</label>
               <?= $form->field($relative, 'homephone')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
           
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="cellphone">Cell Phone:</label>
               <?= $form->field($relative, 'cellphone')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="workphone">Work Phone:</label>
               <?= $form->field($relative, 'workphone')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <?php if ($relative->address != NULL && strcmp($relative->address,"") != 0):?>
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="address">Address:</label>
                    <?= $form->field($relative, 'address')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
                </div>
            <?php else:?>
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="country">Country:</label>
                    <?= $form->field($relative, 'country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'FatherCountry', 'onchange'=>'checkFatherCountry();', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="country">Town:</label>
                    <?php if ($relative->checkTown() == false):?>
                        <?= $form->field($relative, 'town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showFatherAddressLine();' , 'style'=>'display:none', 'id'=>'FatherTown', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
                    <?php else:?>
                         <?= $form->field($relative, 'town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showFatherAddressLine();' , 'style'=>'display:block', 'id'=>'FatherTown', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
                    <?php endif;?>
                </div>
            
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="country">Additional Address Details:</label>
                    <?php if ($relative->checkAddressline() == false):?>
                        <?= $form->field($relative, 'addressline')->label('')->textInput(['style'=>'display:none', 'id'=>'FatherAddressLine', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
                    <?php else:?>
                         <?= $form->field($relative, 'addressline')->label('')->textInput(['style'=>'display:block', 'id'=>'FatherAddressLine', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
                    <?php endif;?>
                </div><br/>
            <?php endif;?>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="occupation">Occupation:</label>
               <?= $form->field($relative, 'occupation')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <?php if ($relative->receivemail==1 && ($relative->email!=NULL || strcmp($relative->email,'')!= 0)):?>
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="email">Email:</label>
                    <?= $form->field($relative, 'email')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
                </div>
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