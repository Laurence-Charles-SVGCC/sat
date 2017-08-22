<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use yii\helpers\ArrayHelper;
    use frontend\models\IntentType;
    use frontend\models\ExaminationBody;
    use frontend\models\QualificationType;
    use frontend\models\Department;
    
    $duration = [
        '' => 'Select Duration',
        1 => '1 Year',
        2 => '2 Years',
    ];
    
    $this->title = 'Add Programme to Institution Catalog';
    
    $this->params['breadcrumbs'][] = ['label' => 'Period Listing', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/manage-application-period'])];
    $this->params['breadcrumbs'][] = ['label' => 'Setup Dashboard', 'url' => Url::toRoute(['admissions/initiate-period', 'recordid' => $period->applicationperiodid])];
    $this->params['breadcrumbs'][] = ['label' => 'Assign Programmes', 'url' => Url::toRoute(['admissions/period-setup-step-three'])];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/programmes/programmes/index']);?>" title="Programme Management">
        <h1>Welcome to the Programme Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body"> 
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='name'>Name:</label>
                <?= $form->field($programme, 'name')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='specialisation'>Specialisation:</label>
                <?= $form->field($programme, 'specialisation')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='programmetypeid'>Programme Type:</label>
                <?= $form->field($programme, 'programmetypeid')->label('')->dropDownList(ArrayHelper::map(IntentType::find()->all(), 'intenttypeid', 'description'), ['prompt'=>'Select Programme Type', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='qualificationtypeid'>Qualification Type:</label>
                <?= $form->field($programme, 'qualificationtypeid')->label('')->dropDownList(ArrayHelper::map(QualificationType::find()->all(), 'qualificationtypeid', 'name'), ['prompt'=>'Select Qualification Type', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
             <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='examinationbodyid'>Examination Body:</label>
                <?= $form->field($programme, 'examinationbodyid')->label('')->dropDownList(ArrayHelper::map(ExaminationBody::find()->all(), 'examinationbodyid', 'name'), ['prompt'=>'Select Examination Body', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='departmentid'>Department:</label>
                <?= $form->field($programme, 'departmentid')->label('')->dropDownList(ArrayHelper::map(Department::find()
                                                                                                                                                                    ->where(['not', ['like', 'name', 'Administrative']])
                                                                                                                                                                    ->andWhere(['not', ['like', 'name', 'Library']])
                                                                                                                                                                    ->andWhere(['not', ['like', 'name', 'Senior']])
                                                                                                                                                                    ->andWhere(['not', ['like', 'name', 'CAPE']])
                                                                                                                                                                    ->all(), 'departmentid', 'name'),       
                                                                                                                                                                    ['prompt'=>'Select Department',
                                                                                                                                                                        'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9'
                                                                                                                                                                    ]);
                ?>
            </div>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for='duration'>Duration:</label>
                <?= $form->field($programme, 'duration')->label('', ['class'=> 'form-label'])->dropDownList($duration, ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
        </div>
   
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel',['admissions/period-setup-step-three'], ['class' => 'btn btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>