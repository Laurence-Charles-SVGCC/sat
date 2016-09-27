<?php

/* 
 * Author: Laurence Charles
 * Date Created 12/04/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\file\FileInput;
    
    $this->title = 'Upload File';
?>

<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/sms_4.png" alt="student avatar">
                <span class="custom_module_label">Welcome to the Student Management System</span> 
                <img src ="css/dist/img/header_images/sms_4.png" alt="student avatar" class="pull-right">
            </a>   
        </div>
        
        
        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title?></h1>
            
            <br/>
            <div style="width:95%; margin: 0 auto; font-size: 18px;">
                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'upload-email-address-files',
                        'options' => [
                            'enctype' => 'multipart/form-data'
                        ]
                    ]) 
                ?>

                    <?= $form->field($model, 'files[]')
                            ->label('Select file you would like to upload:', ['class'=> 'form-label'])
                            ->fileInput(['multiple' => true,
                                                'style' => 'text-align: center; font: bold 18px Arial, Helvetica, Geneva, sans-serif; color: #4B4B55;text-shadow: #fffeff 0 1px 0; padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #e4e4e4;'
                                    ]); 
                    ?>

                    <?= Html::a(' Cancel',['email-upload/index'], ['class' => 'btn btn-danger glyphicon glyphicon-arrow-left pull-left', 'style' => '']);?>
                    <?= Html::submitButton(' Upload', ['class' => 'glyphicon glyphicon-upload btn btn-success pull-left', 'style' => 'margin-left:5%;']);?>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

