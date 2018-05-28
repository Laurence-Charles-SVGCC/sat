<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use common\models\User;
    use frontend\models\CourseType;
    use frontend\models\PassCriteria;
    use frontend\models\PassFailType;
    use frontend\models\Semester;
    
    $this->title = 'Update Course';
    $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => 'Programme Overview', 'url' => Url::to(['programmes/programme-overview',
                                                            'programmecatalogid' => $programmecatalogid
                                                            ])];
    $this->params['breadcrumbs'][] = ['label' => 'Academic Offering Overview', 'url' => Url::to(['programmes/get-academic-offering',
                                                            'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid
                                                            ])];
    $this->params['breadcrumbs'][] = ['label' => 'Course Management', 'url' => Url::to(['programmes/course-management',
                                                            'iscape' => $iscape, 'programmecatalogid' => $programmecatalogid, 
                                                            'academicofferingid' => $academicofferingid, 'code' => $code
                                                            ])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body"> 
            <?php if($iscape):?>
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Course Code:</label>
                    <?=$form->field($course, 'coursecode')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
                </div>
             
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Course Name:</label>
                    <?=$form->field($course, 'name')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
                </div>
             
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Coursework Weight:</label>
                    <?=$form->field($course, 'courseworkweight')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
                </div>
             
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Exam Weight:</label>
                    <?=$form->field($course, 'examweight')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
                </div>
             
            <?php else:?>
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Course Type:</label>
                    <?=$form->field($course, 'coursetypeid')->label('')->dropDownList(ArrayHelper::map(CourseType::find()->all(), 'coursetypeid', 'name'), ['prompt'=>'Select Course Type', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
                </div>
            
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Pass Criteria:</label>
                    <?=$form->field($course, 'passcriteriaid')->label('')->dropDownList(ArrayHelper::map(PassCriteria::find()->all(), 'passcriteriaid', 'description'), ['prompt'=>'Select Pass Criteria', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
                </div>
            
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">GPA Consideration</label>
                    <?=$form->field($course, 'passfailtypeid')->label('')->dropDownList(ArrayHelper::map(PassFailType::find()->all(), 'passfailtypeid', 'description'), ['prompt'=>'Select GPA Consideration', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
                </div>
            
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Credits:</label>
                    <?=$form->field($course, 'credits')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Coursework Weight:</label>
                    <?=$form->field($course, 'courseworkweight')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
                </div>
             
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="title">Exam Weight:</label>
                    <?=$form->field($course, 'examweight')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
                </div>
             <?php endif;?>
        </div>
   
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel',['programmes/course-management','iscape' => $iscape,  'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid, 'code' => $code],
                                ['class' => 'btn btn-danger']);
                    ?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>