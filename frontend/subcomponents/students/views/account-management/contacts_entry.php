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

    $this->title = 'Contact Information Entry';
    
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
            <div class="alert in alert-block fade alert-info mainButtons">
                <h4 style="text-align:center"><strong>Important Note</strong></h4> 
                <p style="text-align:center">You are required to enter at least one contact number.</p>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="homephone">Home Phone:</label>
                <?= $form->field($model, 'homephone')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
                
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="cellphone">Cell Phone:</label>
                <?= $form->field($model, 'cellphone')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
            </div>
                
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="'workphone">Work Phone:</label>
                <?= $form->field($model, 'workphone')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?> 
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