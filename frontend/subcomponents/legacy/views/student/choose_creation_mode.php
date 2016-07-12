<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use yii\widgets\ActiveForm;
    
     $this->title = 'Student Creation Mode';
     $this->params['breadcrumbs'][] = $this->title;
     
     $no_of_students = [
         0 => 0,
         1 => 1,
         2 => 2,
         3 => 3,
         4 => 4,
         5 => 5,
         6 => 6,
         7 => 7,
         8 => 8,
         9 => 9,
         10 => 10,
//         11 => 11,
//         12 => 12,
//         13 => 13,
//         14 => 14,
//         15 => 15,
//         16 => 16,
//         17 => 17,
//         18 => 18,
//         19 => 19,
//         20 => 20,
     ];
?>


<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/legacy/legacy/index']);?>" title="Manage Legacy Records">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/legacy.png" alt="legacy avatar">
                <span class="custom_module_label" > Welcome to the Legacy Management System</span> 
                <img src ="css/dist/img/header_images/legacy.png" alt="legacy avatar" class="pull-right">
            </a>  
        </div>
        
        
        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title;?></h1>
            
            <div style="width:96%; margin:0 auto;"
                
                    <p class="general_text">
                        Please select student creation mode.
                        <?= Html::radioList('student_creation_mode', null, ['single' => 'Create Single Student', 'batch' => 'Create Multiple Students'], ['class'=> 'form_field', 'onclick'=> 'showCreationMode();']);?>
                    </p>

                    <div id="single-mode" style="display:none">
                        <?= Html::a(' Create Single Student', ['student/create-single-student'], ['class' => 'btn btn-success glyphicon glyphicon-plus', 'style' => '']) ?>
                    </div>
                    
                <?php $form = ActiveForm::begin(['action' => Url::to(['student/generate-batch-form']),]); ?>
                    <div id="batch-mode" style="display:none">
                        <br/><?= Html::label( 'Number of students',  'student-count-label'); ?>
                        <?= Html::dropDownList('student-count-field', null, $no_of_students, ['id' => 'student-count-field', 'onchange' => 'showBatchCreationButton();']) ; ?>
                    </div>

                    <div id="batch-button" style="display:none">
                            <?= Html::submitButton('Generate Batch Student Entry Form', ['class' => 'btn btn-success', 'style' => ';']) ?>
                    </div>
                <?php ActiveForm::end(); ?>   
            </div>
             
             
        </div>
    </div>
</div>
