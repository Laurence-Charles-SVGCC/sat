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

    $this->title = 'Address Information Entry';
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
            <div class="alert in alert-block fade alert-warning mainButtons">
                <h4 style="text-align:center"><strong>Important Note</strong></h4> 
                <p>You are required to list all three (3) addresses.</p> 
            </div>
            
            <fieldset id="permanent-addresses">
                <legend>Permanent Addresses</legend>
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="homephone">Country:</label>
                    <?= $form->field($addresses[0], '[0]country')->label('')->dropDownList(Yii::$app->params['country'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="town">Town:</label>
                    <?= $form->field($addresses[0], '[0]town')->label('')->dropDownList(Yii::$app->params['towns'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="addressline">Additional Address Details(Street|Town|PO Box):</label>
                    <?= $form->field($addresses[0], '[0]addressline')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                </div>
            </fieldset></br>

            <fieldset id="residential-addresses">
                <legend>Residential Addresses</legend>
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="country">Country:</label>
                    <?= $form->field($addresses[1], '[1]country')->label('')->dropDownList(Yii::$app->params['country'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                </div>
                    
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="town">Town:</label> 
                    <?= $form->field($addresses[1], '[1]town')->label('')->dropDownList(Yii::$app->params['towns'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                </div>
                
                   <div class="form-group">
                        <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="addressline">Additional Address Details (Street|Town|PO Box):</label> 
                        <?= $form->field($addresses[1], '[1]addressline')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                </div>
            </fieldset></br>

            <fieldset id="postal-addresses">
                <legend>Postal Addresses</legend>
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="country">Country:</label>
                    <?= $form->field($addresses[2], '[2]country')->label('')->dropDownList(Yii::$app->params['country'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="town">own:</label>
                    <?= $form->field($addresses[2], '[2]town')->label('')->dropDownList(Yii::$app->params['towns'], ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="addressline">Additional Address Details (Street|Town|PO Box):</label>
                    <?= $form->field($addresses[2], '[2]addressline')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
                </div>
            </fieldset></br>
        </div>

        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['account-management/account-dashboard', 'recordid' => $recordid], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>