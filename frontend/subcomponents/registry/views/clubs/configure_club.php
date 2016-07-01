<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\Club;
    use frontend\models\Division;
    
    $this->title = $action . " Club";

?>


    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/registry/awards/manage-awards']);?>" title="Manage Awards">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/club.png" alt="club avatar">
                    <span class="custom_module_label" style="margin-left:5%;"> Welcome to the Award Management System</span> 
                    <img src ="css/dist/img/header_images/club.png" alt="club avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$this->title?></h1>
                
                </br>                              
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'configure-club-form',
                        'options' => [
                            'style' => 'width:80%; margin:0 auto;',
                        ],
                    ]);
                ?>
                
                    <table class='table table-hover' style='width:100%; margin: 0 auto;'>
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Name</th>
                            <td><?=$form->field($club, 'name')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'style' => 'vertical-align:middle'])?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Description</th>
                            <td><?=$form->field($club, 'description')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'style' => 'vertical-align:middle', 'rows' => 10])?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Motto</th>
                            <td><?=$form->field($club, 'motto')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'style' => 'vertical-align:middle', 'rows' => 10])?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Year Founded</th>
                            <td><?=$form->field($club, 'yearfounded')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'style' => 'vertical-align:middle'])?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Division</th>
                            <td><?=$form->field($clubdivision, 'divisionid')->label('')->dropDownList(ArrayHelper::map(Division::find()->where(['divisionid' => [1,4,5,6,7]])->all(), 'divisionid', 'name'), ['prompt'=>'Select Division', 'onchange' => 'toggleAwardType();']) ?></td>
                        </tr> 
                    </table><br/>
                    
                    <?= Html::a(' Cancel',['clubs/manage-clubs'], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-right', 'style' => 'width:20%; margin-left:5%;']);?>
                    <?= Html::submitButton(' Save', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:20%;']);?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

