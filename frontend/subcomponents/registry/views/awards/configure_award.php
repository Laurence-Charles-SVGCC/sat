<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\AwardCategory;
    use frontend\models\AwardType;
    use frontend\models\AwardScope;
    use frontend\models\Award;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Division;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    
    $this->title = $action . " Award";
    $this->params['breadcrumbs'][] = ['label' => 'Award Listing', 'url' => Url::toRoute(['/subcomponents/registry/awards/manage-awards'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/registry/awards/manage-awards']);?>" title="Manage Awards">
        <h1>Welcome To The Club Management System</h1>
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
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">Name:</label>
               <?=$form->field($award, 'name')->label('', ['class'=> 'form-label'])->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="description">Description:</label>
               <?=$form->field($award, 'description')->label('', ['class'=> 'form-label'])->textArea(['style' => 'vertical-align:middle', 'rows' => 5, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="awardcategoryid">Award Category:</label>
               <?=$form->field($award, 'awardcategoryid')->label('')->dropDownList(ArrayHelper::map(AwardCategory::find()->all(), 'awardcategoryid', 'name'), ['prompt'=>'Select Category', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="awardtypeid">Award Type:</label>
               <?=$form->field($award, 'awardtypeid')->label('')->dropDownList(ArrayHelper::map(AwardType::find()->all(), 'awardtypeid', 'name'), ['prompt'=>'Select Type', 'onchange' => 'toggleAwardType();', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="academicyearid">Academic Year:</label>
               <?=$form->field($award, 'academicyearid')->label('')->dropDownList($academicyears, ["style" => "display:none", "id" => "award-year", "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="semesterid">Semester:</label>
               <?=$form->field($award, 'semesterid')->label('')->dropDownList(ArrayHelper::map(Semester::associativeSemesterListing(), 'semesterid', 'title'), ['prompt'=>'Select Semester', "id" => "award-semester", "style" => "display:none", "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="awardscopeid">Scope:</label>
               <?=$form->field($award, 'awardscopeid')->label('')->dropDownList(ArrayHelper::map(AwardScope::find()->all(), 'awardscopeid', 'name'), ['prompt'=>'Select Scope', 'onchange' => 'toggleAwardScope();', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="divisionid">Division:</label>
               <?=$form->field($award, 'divisionid')->label('')->dropDownList(ArrayHelper::map(Division::find()->where(['divisionid' => [4,5,6,7]])->all(), 'divisionid', 'name'), ['prompt'=>'Select Division', "id" => "award-division", "style" => "display:none", "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="departmentid">Department:</label>
               <?=$form->field($award, 'departmentid')->label('')->dropDownList(ArrayHelper::map(Department::find()->where(['departmentid' => [1,2,3,4,5,6,7,8,9,10,11]])->all(), 'departmentid', 'name'), ['prompt'=>'Select Department', "id" => "award-department", "style" => "display:none", "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="programmecatalogid">Programme:</label>
               <?=$form->field($award, 'programmecatalogid')->label('')->dropDownList(ArrayHelper::map(ProgrammeCatalog::find()->all(), 'programmecatalogid', 'name'), ['prompt'=>'Select Programme', "id" => "award-programme", "style" => "display:none", "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="subject">Subject:</label>
               <?=$form->field($award, 'subject')->label('', ['class'=> 'form-label'])->textInput(["id" => "award-subject", "style" => "display:none", "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
        </div>
        
    
         <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['awards/manage-awards'], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>