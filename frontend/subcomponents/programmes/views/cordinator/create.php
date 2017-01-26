<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\CordinatorType;
    use frontend\models\AcademicYear;
    use frontend\models\Department;
    
    $this->title = 'Assign Co-ordinator';
    $this->params['breadcrumbs'][] = ['label' => 'Co-ordinator Dashboard', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/programmes/cordinator/index']);?>" title="Manage Coordinators">
        <h1>Welcome to the Co-ordinator Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?= Html::hiddenInput('cordinator_assignment_baseUrl', Url::home(true)); ?>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="personid">Employee:</label>
               <?=$form->field($cordinator, 'personid')->label('')->dropDownList($employees, ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="academicyearid">Academic Year:</label>
               <?= $form->field($cordinator, 'academicyearid')->label('') ->dropDownList($academicyears, ['onchange' => 'toggleCordinatorType();', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="cordinatortypeid">Type:</label>
               <?=$form->field($cordinator, 'cordinatortypeid')
                    ->label('')
                    ->dropDownList(ArrayHelper::map(CordinatorType::find()->where(['cordinatortypeid' => [1,2]])->all(), 'cordinatortypeid', 'name'), 
                                                    ['prompt'=>'Select Co-ordinator Type',
                                                        'onchange' => 'toggleDetails();respondToAcademicYearSelection(event);',
//                                                         "id" => "cordinator-cordinatortype",
                                                        "style" => "display:none",
                                                        'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9'
                                                    ]
                                            )
                ;?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="departmentid">Department:</label>
               <?= Html::dropDownList('departmentid',  "Select...", $departments, ['id' => 'department_field', "style" => "display:none",'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ; ?><br/>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="academicofferingid">Programme:</label>
               <?= Html::dropDownList('academicofferingid',  "Select...", ['' => 'Select...'], ['id' => 'academic_offering_field', "style" => "display:none", 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ; ?><br/>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="courseofferingid">Course:</label>
               <?= Html::dropDownList('courseofferingid',  "Select...", ['' => 'Select...'], ['id' => 'course_offering_field', "style" => "display:none", 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ; ?><br/>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="capesubjectid">CAPE Subject:</label>
               <?= Html::dropDownList('capesubjectid',  "Select...", ['' => 'Select...'], ['id' => 'cape_subject_field', "style" => "display:none", 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ; ?><br/>
            </div>
        </div>
        
         <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['cordinator/index'], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>

