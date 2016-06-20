<?php

/* 
 * Author: Laurence Charles
 * Date Created 20/06/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use common\models\User;
    use frontend\models\CourseOutline;
    
    $this->title = $action . ' Course Outline';
    $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => 'Programme Overview', 'url' => Url::to(['programmes/programme-overview',
                                                            'programmecatalogid' => $programmecatalogid
                                                            ])];
    $this->params['breadcrumbs'][] = $this->title;
    
    $levels = [
        "1" => "1",
        "2" => "2",
        "3" => "3",
    ];
?>

<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/programmes/programmes/index']);?>" title="Manage Awards">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/programme.png');?>" alt="award avatar">
                <span class="custom_module_label" > Welcome to the Programme Management System</span> 
                <img src ="<?=Url::to('../images/programme.png');?>" alt="award avatar" class="pull-right">
            </a>    
        </div>
        
        
        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title?></h1>
            
            <br/>
            <div style="width:90%; margin: 0 auto; font-size: 20px;">
                
                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'add-course-outline',
                        'options' => [
                             'class' => 'form-layout',
                        ]
                    ]) 
                ?>
             
                    <br/>
                    <table class='table table-hover' style='width:100%; margin: 0 auto;'>
                       <tr>
                            <th style='width:30%; vertical-align:middle'>Course Code</th>
                            <td><?=$form->field($outline, 'code')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'disabled' => true]);?></td>
                       </tr>

                       <tr>
                            <th style='width:30%; vertical-align:middle'>Course Name</th>
                            <td><?=$form->field($outline, 'name')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'disabled' => true]);?></td>
                       </tr>

                       <tr>
                            <th style='width:30%; vertical-align:middle'>Credits</th>
                            <td><?=$form->field($outline, 'credits')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'disabled' => true]);?></td>
                       </tr>

                       <tr>
                            <th style='width:30%; vertical-align:middle'>Level</th>
                            <td><?=$form->field($outline, 'level')->label('')->dropDownList(($levels), ['prompt'=>'Select Course Level', 'disabled' => true]);?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Pre-requisites</th>
                            <td><?=$form->field($outline, 'prerequisites')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'disabled' => true]);?></td>
                       </tr>

                       <tr>
                            <th style='width:30%; vertical-align:middle'>Co-requisities</th>
                            <td><?=$form->field($outline, 'corequisites')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'disabled' => true]);?></td>
                       </tr>
                       
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Semesters Delivered</th>
                            <td><?=$form->field($outline, 'deliveryperiod')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'disabled' => true]);?></td>
                       </tr>

                       <tr>
                            <th style='width:30%; vertical-align:middle'>Course Provider</th>
                            <td><?=$form->field($outline, 'courseprovider')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'disabled' => true]);?></td>
                       </tr>

                       <tr>
                            <th style='width:30%; vertical-align:middle'>Total Study Hours</th>
                            <td><?=$form->field($outline, 'totalstudyhours')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'rows' => 5, 'disabled' => true]);?></td>
                       </tr>

                       <tr>
                            <th style='width:30%; vertical-align:middle'>Course Description</th>
                            <td><?=$form->field($outline, 'description')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'rows' => 7, 'disabled' => true]);?></td>
                       </tr>

                       <tr>
                            <th style='width:30%; vertical-align:middle'>Course Rationale</th>
                            <td><?=$form->field($outline, 'rational')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'rows' => 7, 'disabled' => true]);?></td>
                       </tr>

                       <tr>
                            <th style='width:30%; vertical-align:middle'>Learning Outcomes</th>
                            <td><?=$form->field($outline, 'outcomes')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'rows' => 10, 'disabled' => true]);?></td>
                       </tr>

                       <tr>
                            <th style='width:30%; vertical-align:middle'>Content</th>
                            <td><?=$form->field($outline, 'content')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'rows' => 10, 'disabled' => true]);?></td>
                       </tr>

                       <tr>
                            <th style='width:30%; vertical-align:middle'>Teaching Methodology</th>
                            <td><?=$form->field($outline, 'teachingmethod')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'rows' => 3, 'disabled' => true]);?></td>
                       </tr>

                       <tr>
                            <th style='width:30%; vertical-align:middle'>Assessment Method</th>
                            <td><?=$form->field($outline, 'assessmentmethod')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'rows' => 12, 'disabled' => true]);?></td>
                       </tr>

                       <tr>
                            <th style='width:30%; vertical-align:middle'>Learning Resources</th>
                            <td><?=$form->field($outline, 'resources')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'rows' => 12, 'disabled' => true]);?></td>
                       </tr>
                    </table>
                     
                     <br/>
                    <?= Html::a(' Back',
                                ['programmes/programme-overview', 'programmecatalogid' => $programmecatalogid],
                                ['class' => 'btn btn-danger glyphicon glyphicon-remove-circle', 'style' => 'width:20%; margin-left:80%']
                                );
                    ?>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>



