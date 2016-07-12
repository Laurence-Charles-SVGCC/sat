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
    
    use frontend\models\LegacySubjectType;

     $this->title = 'Create New Subject';
     $this->params['breadcrumbs'][] = ['label' => 'Subject Listing', 'url' => ['index']];
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
            <div style="width:80%; margin: 0 auto; font-size: 20px;">
                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'create-subject',
                        'options' => [
                             'class' => 'form-layout',
                        ]
                    ]) 
                ?>
                    
                    <br/>
                    <table class='table table-hover' style='width:100%; margin: 0 auto;'>
                       <tr>
                            <th style='width:30%; vertical-align:middle'>Name</th>
                            <td><?=$form->field($subject, 'name')->label('')->textInput(['maxlength' => true]);?></td>
                        </tr>

                        <tr>
                            <th style='width:30%; vertical-align:middle'>Examination Body</th>
                            <td><?=$form->field($subject, 'legacysubjecttypeid')->label('')->dropDownList(ArrayHelper::map(LegacySubjectType::find()->all(), 'legacysubjecttypeid', 'name'), ['prompt'=>'Select Examination Body..']);?></td>
                        </tr>
                    </table> 
                
                     <br/>
                    <?= Html::a(' Cancel',
                                ['subjects/index'],
                                ['class' => 'btn btn-danger glyphicon glyphicon-remove-circle', 'style' => 'width:20%; margin-left:55%;margin-right:2.5%']
                                );
                    ?>
                    <?= Html::submitButton(' Save', ['class' => 'btn btn-success glyphicon glyphicon-ok', 'style' => 'width:20%;']);?>
                    
                <?php ActiveForm::end() ?>
             </div>
            
        </div>
    </div>
