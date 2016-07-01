<?php

/* 
 * Author: Laurence Charles
 * Date Created 17/06/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use common\models\User;
    use frontend\models\CourseType;
    use frontend\models\PassCriteria;
    use frontend\models\PassFailType;
    use frontend\models\Semester;
    
    $this->title = 'Update Course Information';
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

<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/programmes/programmes/index']);?>" title="Manage Programmes">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/programme.png" alt="scroll avatar">
                <span class="custom_module_label" > Welcome to the Programme Management System</span> 
                <img src ="css/dist/img/header_images/programme.png" alt="scroll avatar" class="pull-right">
            </a>    
        </div>
        
        
        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title?></h1>
            
            <br/>
            <div style="width:80%; margin: 0 auto; font-size: 20px;">
                
                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'edit-course',
                        'options' => [
                             'class' => 'form-layout',
                        ]
                    ]) 
                ?>
                    
                    <?php if($iscape):?>
                        <br/>
                        <table class='table table-hover' style='width:100%; margin: 0 auto;'>
                           <tr>
                                <th style='width:30%; vertical-align:middle'>Course Code</th>
                                    <td><?=$form->field($course, 'coursecode')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true]);?></td>
                            </tr>
                           
                            <tr>
                                <th style='width:30%; vertical-align:middle'>Course Name</th>
                                    <td><?=$form->field($course, 'name')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true]);?></td>
                            </tr>
                            
                             <tr>
                                <th style='width:30%; vertical-align:middle'>Coursework Weight</th>
                                    <td><?=$form->field($course, 'courseworkweight')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true]);?></td>
                            </tr>
                            
                             <tr>
                                <th style='width:30%; vertical-align:middle'>Exam Weight</th>
                                    <td><?=$form->field($course, 'examweight')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true]);?></td>
                            </tr>
                        </table> 
                    <?php else:?>
                        <br/>
                        <table class='table table-hover' style='width:100%; margin: 0 auto;'>
                            <tr>
                                <th style='width:30%; vertical-align:middle'>Course Type</th>
                                <td><?=$form->field($course, 'coursetypeid')->label('')->dropDownList(ArrayHelper::map(CourseType::find()->all(), 'coursetypeid', 'name'), ['prompt'=>'Select Course Type']);?></td>
                            </tr>
                            
                            <tr>
                                <th style='width:30%; vertical-align:middle'>Pass Criteria</th>
                                <td><?=$form->field($course, 'passcriteriaid')->label('')->dropDownList(ArrayHelper::map(PassCriteria::find()->all(), 'passcriteriaid', 'description'), ['prompt'=>'Select Pass Criteria']);?></td>
                            </tr>
                            
                            <tr>
                                <th style='width:30%; vertical-align:middle'>GPA Consideration</th>
                                <td><?=$form->field($course, 'passfailtypeid')->label('')->dropDownList(ArrayHelper::map(PassFailType::find()->all(), 'passfailtypeid', 'description'), ['prompt'=>'Select GPA Consideration']);?></td>
                            </tr>
                           
                            <tr>
                                <th style='width:30%; vertical-align:middle'>Credits</th>
                                    <td><?=$form->field($course, 'credits')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true]);?></td>
                            </tr>
                            
                             <tr>
                                <th style='width:30%; vertical-align:middle'>Coursework Weight</th>
                                    <td><?=$form->field($course, 'courseworkweight')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true]);?></td>
                            </tr>
                            
                             <tr>
                                <th style='width:30%; vertical-align:middle'>Exam Weight</th>
                                    <td><?=$form->field($course, 'examweight')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true]);?></td>
                            </tr>
                        </table> 
                    <?php endif;?>
                
                     <br/>
                    <?= Html::a(' Cancel',
                                ['programmes/course-management','iscape' => $iscape,  'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid, 'code' => $code],
                                ['class' => 'btn btn-danger glyphicon glyphicon-remove-circle', 'style' => 'width:20%; margin-left:55%;margin-right:2.5%']
                                );
                    ?>
                    <?= Html::submitButton(' Save', ['class' => 'btn btn-success glyphicon glyphicon-ok', 'style' => 'width:20%;']);?>
                    
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

