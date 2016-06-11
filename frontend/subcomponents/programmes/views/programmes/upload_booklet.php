<?php

/* 
 * Author: Laurence Charles
 * Date Created 02/05/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\file\FileInput;
    
    use common\models\User;
    
    $this->title = 'Upload Programme Booklet';
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
            <div style="width:80%; margin: 0 auto; font-size: 20px;">
                
                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'upload-booklet',
                        'options' => [
                            'enctype' => 'multipart/form-data'
                        ]
                    ]) 
                ?>
                    <?= $form->field($model, 'files[]')
                            ->label('Select programme booklet file:', 
                                    [
                                        'class'=> 'form-label',
                                    ])
                            ->fileInput(
                                    [
                                        'multiple' => true,
                                        'style' => 'text-align: center; font: bold 18px Arial, Helvetica, Geneva, sans-serif; color: #4B4B55;text-shadow: #fffeff 0 1px 0; padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #e4e4e4;'
                                    ]); ?>

                    <br/>

                    <?= Html::a(' Cancel',
                                ['programmes/get-academic-offering', 'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid],
                                ['class' => 'btn btn-danger glyphicon glyphicon-remove-circle pull-right', 'style' => 'width:20%; margin-left:5%;']
                                );
                    ?>
                    <?= Html::submitButton(' Upload', ['class' => 'btn btn-success glyphicon glyphicon-upload pull-right', 'style' => 'width:20%;']);?>

                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

