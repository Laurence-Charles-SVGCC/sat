<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
    use frontend\models\Institution;
    use dosamigos\datepicker\DatePicker;

    $this->title = 'Applicant Details';
    $this->params['breadcrumbs'][] = ['label' => 'Applicant View', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-period-form">
    <div class = "custom_wrapper">
        
        <div class="custom_body">
            <h1><?= Html::encode($this->title) ?></h1>
            <h2>Details for: <?= $username ?> </h2>
            <h3>Personal Details</h3>
            <?php $form = ActiveForm::begin(); ?>
            <?= Html::hiddenInput('applicantid', $applicant->applicantid) ?>
            <?= Html::hiddenInput('username', $username) ?>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($applicant, 'title')->textInput(); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($applicant, 'firstname')->textInput(); ?>
                </div>
                <div class="col-lg-3">      
                    <?= $form->field($applicant, 'middlename')->textInput(); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($applicant, 'lastname')->textInput(); ?>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($applicant, 'gender')->dropDownList(
                                    ['male' => 'Male', 'female'=>'Female'] 
                            ); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($applicant, 'dateofbirth')->widget(
                                DatePicker::className(), [
                                    'inline' => false, 
                                     // modify template for custom rendering
                                    'template' => '{addon}{input}',
                                    'clientOptions' => [
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd'
                                    ]
                                ]); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($applicant, 'nationality')->textInput(); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($applicant, 'placeofbirth')->textInput(); ?>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($applicant, 'religion')->textInput(); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($applicant, 'sponsorname')->textInput(); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($applicant, 'clubs')->textInput(); ?>
                </div>
            </div>
            <br/>
            <div>
                <div class="col-lg-3">
                    <?= $form->field($applicant, 'maritalstatus')->textInput(); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($applicant, 'otherinterests')->textInput(); ?>
                </div>
            </div>
            <br/>
            <h3>Contact</h3>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($phone, 'homephone')->textInput(); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($phone, 'cellphone')->textInput(); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($phone, 'workphone')->textInput(); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($email, 'email')->textInput(); ?>
                </div>
            </div>
            <br/>
            <h3>Relation Contact</h3>
            <?php foreach($relations as $relation): ?>
                <?php if ($relation->firstname != ''): ?> 
                    <div class="row">
                        <div class="col-lg-2">
                            <?= $form->field($relation, '['. $relation->relationid . ']firstname')->textInput(['value' => $relation->firstname]) ; ?>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($relation, '['. $relation->relationid . ']lastname')->textInput(['value' => $relation->lastname]) ; ?>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($relation, '['. $relation->relationid . ']homephone')->textInput(['value' => $relation->homephone]); ?>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($relation, '['. $relation->relationid . ']cellphone')->textInput(['value' => $relation->cellphone]); ?>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($relation, '['. $relation->relationid . ']workphone')->textInput(['value' => $relation->workphone]); ?>
                        </div>     
                  </div>
            <br/>
                <?php endif; ?>
            <?php endforeach; ?>
            <br/>
            <h3>Institutional Attendance Details</h3>
            <?php foreach($institutions as $inst): ?>
            <?php $in = Institution::findone(['institutionid' => $inst->institutionid, 'isdeleted' => 0]); ?>
                <div class="row">
                    <div class="col-lg-4">
                        <?= $form->field($in, '['. $inst->personinstitutionid . ']institutionid')->dropDownList(
                                    ArrayHelper::map(Institution::find()->orderby('name')->all(), 'institutionid', 'name')
                            ) ?>
                    </div>
                    <div class="col-lg-2">

                        <?= $form->field($in, 'formername')->textInput(); ?>
                    </div>
                    <div class="col-lg-2">

                        <?= $form->field($inst, '['. $inst->personinstitutionid . ']startdate')->widget(
                                DatePicker::className(), [
                                    'inline' => false, 
                                     // modify template for custom rendering
                                    'template' => '{addon}{input}',
                                    'clientOptions' => [
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd'
                                    ]
                                ]); ?>
                    </div>
                    <div class="col-lg-2">

                        <?= $form->field($inst, '['. $inst->personinstitutionid . ']enddate')->widget(
                                DatePicker::className(), [
                                    'inline' => false, 
                                     // modify template for custom rendering
                                    'template' => '{addon}{input}',
                                    'clientOptions' => [
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd'
                                    ]
                                ]); ?>
                    </div>
                    <div class="col-lg-2">
                       <?= $form->field($inst, '['. $inst->personinstitutionid . ']hasgraduated')->checkbox(['label' => null])->label('Graduated'); ?>
                    </div>          
              </div>
            <?php endforeach; ?>

            <br/>
                <?php if (Yii::$app->user->can('editApplicantPersonal')): ?>
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']); ?>
                <?php endif; ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>