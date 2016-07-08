<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

     $this->title = 'Student Search';
     $this->params['breadcrumbs'][] = ['label' => 'Student Listing', 'url' => ['index']];
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
            
            <div class="center_content general_text" style="min-height: 200px;">
                <p>
                    Welcome. This application facilitates the management of all student grades.  
                    Please enter the student first name and / or last name.
                </p> 
                
                <?php $form = ActiveForm::begin([]);?>
                    <?= Html::label( 'First Name',  'fname_label'); ?>
                    <?= Html::input('text', 'fname_field'); ?> <br/><br/>

                    <?= Html::label( 'Last Name',  'lname_label'); ?>
                    <?= Html::input('text', 'lname_field'); ?> 

                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>
                <?php ActiveForm::end(); ?>
            </div><hr>


            <?php if ($dataProvider) : ?>
                <h2 class="custom_h2"><?= "Search results for: " . $info_string ?></h2>
                <?= $this->render('student_listing', [
                    'dataProvider' => $dataProvider,
                    'info_string' => $info_string,
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>