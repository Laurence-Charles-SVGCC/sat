<?php

/* 
 * Author: Laurence Charles
 * Date Created 12/04/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    
    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\PackageType;
    use frontend\models\PackageProgress;
    use frontend\models\Package;
    
    $this->title = 'Package Testing';
?>

<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        
        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title?></h1>
            
            <br/>
            <div style="width:80%; margin: 0 auto; font-size: 20px;">
                <?=Html::beginForm();?>
                    <p class="form_label">
                        Select the number of recepients you wish to receive this test package:
                        <?= Html::dropDownList ( 'email_count',
                                                '0',
                                                [
                                                    0 => '0',
                                                    1 => '1',
                                                    2 => '2',
                                                    3 => '3'
                                                ], 
                                                [
                                                    'id' => 'email_count',
                                                    'class' => 'form_field',
                                                    'onchange' => 'toggleEmailFields();'
                                                ] );
                        ?>
                    </p><br/>

                    <div id="email_1" style="display:none">
                        <label>Email Address #1: </label>
                        <?=Html::textInput('email-1', 
                                            null, 
                                            [
                                                'id' => 'email-1',
                                                'class' => 'form_field',
                                                'style' => 'width:50%',
                                            ] )?>
                    </div><br/>

                    <div id="email_2" style="display:none">
                        <label>Email Address #2: </label>
                         <?=Html::textInput('email-2', 
                                            null, 
                                            [
                                                'id' => 'email-2',
                                                'class' => 'form_field',
                                                'style' => 'width:50%',
                                            ] )?>
                    </div><br/>

                    <div id="email_3" style="display:none">
                        <label>Email Address #3: </label>
                         <?=Html::textInput('email-3', 
                                            null, 
                                            [
                                                'id' => 'email-3',
                                                'class' => 'form_field',
                                                'style' => 'width:50%',
                                            ] )?>
                    </div><br/>
                    
                    <?php if ($recordid == true):?>
                        <?=Html::a(' Cancel',['package/initiate-package', 'recordid' => $recordid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-right', 'style' => 'width:20%; margin-left:5%;']);?>
                    <?php else:?>      
                        <?=Html::a(' Cancel',['package/initiate-package'], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);?>
                    <?php endif;?>
                    
                    <?=Html::submitButton('Perform Test', ['id'=>'submit-button',  'class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:5%;display:none']);?>
                    
                <?=Html::endForm();?>
            </div>
        </div>
    </div>
</div>


