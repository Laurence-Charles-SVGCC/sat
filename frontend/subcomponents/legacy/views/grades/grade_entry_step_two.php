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
     $this->params['breadcrumbs'][] = ['label' => 'Batch Selection', 'url' => ['find-batch-marksheet']];
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
                        'action' =>  Url::to(['grades/update-grades', 'record_count' => count($marksheets), 'batchid' => $marksheets[0]->legacybatchid]),
                        'id' => 'grade-entry-step-two',
                        'options' => [
                             'class' => 'form-layout',
                             'style' => 'width:70%;',
                        ]
                    ]) 
                ?>
               
                    <br/>
                    <ul>
                        <li><strong>Year > </strong> <?=$year;?></li>
                        <ul>
                            <li><strong>Term > </strong><?=$term;?></li>
                            <ul>
                                <li><strong>Subject > </strong><?=$subject;?></li>
                                <ul>
                                    <li><strong>Level > </strong><?=$level;?></li>
                                    <ul>
                                        <li><strong>Type > </strong><?=$type;?> batch</li>
                                    </ul>
                                </ul>
                            </ul>
                        </ul>
                    </ul>
                    
                    <br/>
                    <table class='table table-condensed' style='width:100%; margin: 0 auto;'>
                        <tr>
                            <th>Name</th>
                            <th>Mark</th>
                        </tr>
                    <?php for ($i=0 ; $i<count($marksheets) ; $i++): ?>
                        <!--<tr style='border-top:solid 5px'>-->
                        <tr>
                           <td style='vertical-align:middle;'><?=$students[$i];?></td>
                           <td style='vertical-align:middle;'><?=$form->field($marksheets[$i], "[$i]mark")->label('')->textInput(['maxlength' => true]);?></td>
                        </tr>
                    <?php endfor;?>
                    </table> 
                     
                    
                    <?= Html::a(' Back', ['grades/find-batch-marksheet'],
                                ['class' => 'btn btn-danger glyphicon glyphicon-remove-circle', 'style' => 'width:20%; margin-left:55%;margin-right:2.5%']);
                    ?>
                    <?= Html::submitButton(' Save', ['class' => 'btn btn-success glyphicon glyphicon-ok', 'style' => 'width:20%;']);?>
                    
                <?php ActiveForm::end() ?>
             </div>
            
        </div>
    </div>
</div>

