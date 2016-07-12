<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

     $this->title = 'Create Academic Year';
     $this->params['breadcrumbs'][] = ['label' => 'Academic Year Listing', 'url' => ['index']];
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
            
            <div style="width:80%; margin: 0 auto; font-size: 20px;">
                <?php if($saved_years):?>
                    <p>
                        Please ensure that you do not create a duplicate academic year record.  Please find below a list of all the academic years that 
                        have been created thus far.
                    </p>
                    
                    <ol>
                        <?php foreach($saved_years as $record):?>
                            <li><?=$record;?></li>
                        <?php endforeach;?>
                    </ol>
                <?php else:?>
                    <p>No academic years currently exist.
                <?php endif;?>
            
                <br/>
                <div>
                    <?php 
                        $form = ActiveForm::begin([
                            'id' => 'create-year',
                            'options' => [
                                 'class' => 'form-layout',
                            ]
                        ]) 
                    ?>
                        <table class='table table-hover' style='width:100%; margin: 0 auto;'>
                           <tr>
                                <th style='width:30%; vertical-align:middle'>Year Title</th>
                                <td><?=$form->field($year, 'name')->label('')->textInput(['maxlength' => true]);?></td>
                            </tr>
                        </table> 

                         <br/>
                        <?= Html::a(' Cancel',
                                    ['year/index'],
                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove-circle', 'style' => 'width:20%; margin-left:55%;margin-right:2.5%']
                                    );
                        ?>
                        <?= Html::submitButton(' Save', ['class' => 'btn btn-success glyphicon glyphicon-ok', 'style' => 'width:20%;']);?>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
