<?php

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
?>


    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/registry/awards/manage-awards']);?>" title="Manage Awards">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/award.png" alt="award avatar">
                    <span class="custom_module_label" style="margin-left:5%;"> Welcome to the Award Management System</span> 
                    <img class="custom_logo_students" src ="css/dist/img/header_images/award.png" alt="award avatar">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$this->title?></h1>
                
                </br>                              
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'configure-award-form',
                        'options' => [
                            'style' => 'width:80%; margin:0 auto;',
                        ],
                    ]);
                ?>
                
                    <table class='table table-hover' style='width:100%; margin: 0 auto;'>
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Name</th>
                            <td><?=$form->field($award, 'name')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'style' => 'vertical-align:middle'])?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Description</th>
                            <td><?=$form->field($award, 'description')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'style' => 'vertical-align:middle', 'rows' => 10])?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Category</th>
                            <td><?=$form->field($award, 'awardcategoryid')->label('')->dropDownList(ArrayHelper::map(AwardCategory::find()->all(), 'awardcategoryid', 'name'), ['prompt'=>'Select Category']) ?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Type</th>
                            <td><?=$form->field($award, 'awardtypeid')->label('')->dropDownList(ArrayHelper::map(AwardType::find()->all(), 'awardtypeid', 'name'), ['prompt'=>'Select Type', 'onchange' => 'toggleAwardType();']) ?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Year</th>
                            <td  style="display:none;" id="award-year" ><?=$form->field($award, 'academicyearid')->label('')->dropDownList(ArrayHelper::map(AcademicYear::find()->all(), 'academicyearid', 'title'), ['prompt'=>'Select Year']) ?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Semester</th>
                            <td id="award-semester" style="display:none;"><?=$form->field($award, 'semesterid')->label('')->dropDownList(ArrayHelper::map(Semester::find()->all(), 'semesterid', 'title'), ['prompt'=>'Select Semester']) ?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Scope</th>
                            <td><?=$form->field($award, 'awardscopeid')->label('')->dropDownList(ArrayHelper::map(AwardScope::find()->all(), 'awardscopeid', 'name'), ['prompt'=>'Select Scope', 'onchange' => 'toggleAwardScope();']) ?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Division</th>
                            <td id="award-division" style="display:none"><?=$form->field($award, 'divisionid')->label('')->dropDownList(ArrayHelper::map(Division::find()->where(['divisionid' => [4,5,6,7]])->all(), 'divisionid', 'name'), ['prompt'=>'Select Division']) ?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Department</th>
                            <td id="award-department" style="display:none"><?=$form->field($award, 'departmentid')->label('')->dropDownList(ArrayHelper::map(Department::find()->where(['departmentid' => [1,2,3,4,5,6,7,8,9,10,11]])->all(), 'departmentid', 'name'), ['prompt'=>'Select Department']) ?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Programme</th>
                            <td id="award-programme" style="display:none"><?=$form->field($award, 'programmecatalogid')->label('')->dropDownList(ArrayHelper::map(ProgrammeCatalog::find()->all(), 'programmecatalogid', 'name'), ['prompt'=>'Select Programme']) ?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Subject</th>
                            <td id="award-subject" style="display:none"><?=$form->field($award, 'subject')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'style' => 'vertical-align:middle'])?></td>
                        </tr>
                    </table><br/>
                    
                    <?= Html::a(' Cancel',['awards/manage-awards'], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-right', 'style' => 'width:20%; margin-left:5%;']);?>
                    <?= Html::submitButton(' Save', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:20%;']);?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

