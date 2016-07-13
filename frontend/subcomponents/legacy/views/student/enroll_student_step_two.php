<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\LegacyYear;
    use frontend\models\LegacyFaculty;


     $this->title = 'Complete Enrollment Process';
     $this->params['breadcrumbs'][] = ['label' => 'Student Listing', 'url' => ['index']];
     $this->params['breadcrumbs'][] = ['label' => 'Student Enrollment Configuration', 'url' => ['enroll-students-step-one']];
     $this->params['breadcrumbs'][] = $this->title;
     
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
            
            <br/>
            <div style="width:98%; margin: 0 auto; font-size: 20px;">
                <?php 
                    $form = ActiveForm::begin([
                        'action' =>  Url::to(['student/enroll-students-step-two', 'record_count' => count($marksheets), 'batchid' => $legacybatchid]),
                        'id' => 'enroll-student-step-two',
                        'options' => [
                             'class' => 'form-layout',
                             'style' => 'width:70%;',
                        ]
                    ]) 
                ?>
               
                    <?= Html::hiddenInput('legacy_student_count', count($marksheets)); ?>
                
                    <br/>
                    <table class='table table-condensed' style='width:100%; margin: 0 auto;'>
                    <?php for ($i=0 ; $i<count($marksheets) ; $i++): ?>
                        <tr style='border-top:solid 5px'>
                            <th style='vertical-align:middle;'><?=($i+1);?> .Select student you wish to enroll in batch: </th>
                            <td><?=$form->field($marksheets[$i], "[$i]legacystudentid")->label('')->dropDownList($student_listing);?></td>
                        </tr>
                    <?php endfor;?>
                    </table> 
                     
                    
                    <?= Html::a(' Back', ['student/enroll-students-step-one'],
                                ['class' => 'btn btn-danger glyphicon glyphicon-remove-circle', 'style' => 'width:20%; margin-left:55%;margin-right:2.5%']);
                    ?>
                    <?= Html::submitButton(' Save', ['class' => 'btn btn-success glyphicon glyphicon-ok', 'style' => 'width:20%;', 'onclick' => '']);?>
                    
                <?php ActiveForm::end() ?>
             </div>
            
        </div>
    </div>
</div>

