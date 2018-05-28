<?php
use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\Address;
    
    $this->title = 'Edit Addresses';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find An Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = ['label' => 'Student Profile', 'url' => Url::toRoute(['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
    </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <fieldset>
                <legend class="text-center">Permanent Address</legend>
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="country">Country:</label>
                    <?= $form->field($addresses[0], '[0]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'country', 'onchange'=>'checkCountry();', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="town">Town:</label>
                    <?php if(Address::checkTown($applicant->personid,1) == false):?>
                        <?= $form->field($addresses[0], '[0]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showAddressLine();' , 'style'=>'display:none', 'id'=>'permLocalTown', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                    <?php else:?>
                        <?= $form->field($addresses[0], '[0]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showAddressLine();' , 'style'=>'display:block', 'id'=>'permLocalTown', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                    <?php endif;?>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="addressline">Address-line:</label>
                    <?php  if(Address::checkAddressline($applicant->personid,1) == false):?>
                        <?= $form->field($addresses[0], '[0]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:none", 'id'=>'permAddressLine', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                    <?php else:?>
                        <?= $form->field($addresses[0], '[0]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:block", 'id'=>'permAddressLine', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                    <?php endif;?>
                </div>
            </fieldset><br/>
    
            <fieldset>
                <legend class="text-center">Residential Address</legend>
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="country">Country:</label>
                    <?= $form->field($addresses[1], '[1]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'country2', 'onchange'=>'checkCountry2();', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="town">Town:</label>
                    <?php if(Address::checkTown($applicant->personid,2) == false):?>
                        <?= $form->field($addresses[1], '[1]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showAddressLine()2;' , 'style'=>'display:none', 'id'=>'resdLocalTown', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                    <?php else:?>
                        <?= $form->field($addresses[1], '[1]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showAddressLine()2;' , 'style'=>'display:block', 'id'=>'resdLocalTown', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                    <?php endif;?>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="addressline">Address-line:</label>
                    <?php  if(Address::checkAddressline($applicant->personid,2) == false):?>
                        <?= $form->field($addresses[1], '[1]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:none", 'id'=>'resdAddressLine', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                    <?php else:?>
                        <?= $form->field($addresses[1], '[1]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:block", 'id'=>'resdAddressLine', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                    <?php endif;?>
                </div>
            </fieldset><br/>
            
            <fieldset>
                <legend class="text-center">Postal Address</legend>
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="country">Country:</label>
                    <?= $form->field($addresses[2], '[2]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'country3', 'onchange'=>'checkCountry3();', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="town">Town:</label>
                    <?php if(Address::checkTown($applicant->personid,3) == false):?>
                        <?= $form->field($addresses[2], '[2]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showAddressLine()3;' , 'style'=>'display:none', 'id'=>'postLocalTown', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                    <?php else:?>
                        <?= $form->field($addresses[2], '[2]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showAddressLine()3;' , 'style'=>'display:block', 'id'=>'postLocalTown', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                    <?php endif;?>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="addressline">Address-line:</label>
                    <?php  if(Address::checkAddressline($applicant->personid,3) == false):?>
                        <?= $form->field($addresses[2], '[2]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:none", 'id'=>'postAddressLine', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                    <?php else:?>
                        <?= $form->field($addresses[2], '[2]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:block", 'id'=>'postAddressLine', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                    <?php endif;?>
                </div>
            </fieldset>
        </div>
    
        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn  btn-danger']);?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>