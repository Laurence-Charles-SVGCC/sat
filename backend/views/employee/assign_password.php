<?php

    /* 
     * Author: Laurence Charles
     * Date Created: 02/11/2016
     */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use yii\widgets\ActiveForm;
    
    $this->title = 'Password Assignment';
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/create_male.png" alt="Find A Student">
                <span class="custom_module_label">Welcome to the Employee Management System</span> 
                <img src ="css/dist/img/header_images/create_female.png" alt="student avatar" class="pull-right">
            </a>   
        </div>
        
        <div class="custom_body"> 
            <!--<h1 class="custom_h1"><?= $this->title?></h1>-->
            
            </br>
            <?php
                $form = ActiveForm::begin([
                            'id' => 'assign-password',
                            'options' => [
                                'style' => 'width:90%; margin: 0 auto;',
                            ],
                        ]);
            ?>
                <fieldset>
                    <legend class="custom_h2"><?= $this->title?></legend>

                    <table class='table table-hover'>
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Summary</th>
                            <td><?=$form->field($model, 'userid')->label('')->dropDownList($employees, ['style'=> 'font-size:14px;']); ?></td>
                        </tr>

                        <tr>
                            <th style='width:30%; vertical-align:middle'>Password</th>
                            <td><?= $form->field($model, 'password')->passwordInput()->label('')?></td>
                        </tr>

                        <tr>
                            <th style='width:30%; vertical-align:middle'>Confirm Password</th>
                            <td><?= $form->field($model, 'confirm_password')->passwordInput()->label('')?></td>
                        </tr>
                    </table><br/>
                </fieldset>

                <?= Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-lg btn-success pull-right', 'style' => 'width:25%; ']);?>
            <?php Activeform::end()?>
         </div>
    </div>
</div>