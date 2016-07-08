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

    $this->title = 'Create New Student';
    $this->params['breadcrumbs'][] = ['label' => 'Student Listing', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => 'Student Creation Mode', 'url' => ['choose-create']];
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
                        'id' => 'create-single-student',
                        'options' => [
                             'class' => 'form-layout',
                        ]
                    ]) 
                ?>
                    
                    <br/>
                    <table class='table table-hover' style='width:100%; margin: 0 auto;'>
                       <tr>
                            <th style='width:30%; vertical-align:middle'>Title</th>
                            <td><?=$form->field($student, 'title')->label('')->dropDownList(['' => 'Select Title', 'Mr' => 'Mr', 'Ms' => 'Ms', 'Mrs' => 'Mrs']);?></td>
                        </tr>

                        <tr>
                            <th style='width:30%; vertical-align:middle'>First Name</th>
                            <td><?=$form->field($student, 'firstname')->label('')->textInput(['maxlength' => true]);?></td>
                        </tr>

                         <tr>
                            <th style='width:30%; vertical-align:middle'>Middle Name</th>
                            <td><?=$form->field($student, 'middlename')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true]);?></td>
                        </tr>

                         <tr>
                            <th style='width:30%; vertical-align:middle'>Last Name</th>
                            <td><?=$form->field($student, 'lastname')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true]);?></td>
                        </tr>
                        
                        <tr>
                              <th style='vertical-align:middle;'>Date of Birth</th>
                              <td><?=$form->field($student, 'dateofbirth')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Address</th>
                            <td><?=$form->field($student, 'address')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'rows' =>3]);?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Gender</th>
                            <td><?=$form->field($student, 'gender')->label('')->dropDownList(['' => 'Select Gender', 'Male' => 'Male', 'Female' => 'Female']);?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Admission Year</th>
                            <td><?=$form->field($student, 'legacyyearid')->label('')->dropDownList(ArrayHelper::map(LegacyYear::find()->all(), 'legacyyearid', 'name'), ['prompt'=>'Select the admission year of student..']);?></td>
                        </tr>
                        
                        <tr>
                            <th style='width:30%; vertical-align:middle'>Faculty</th>
                            <td><?=$form->field($student, 'legacyfacultyid')->label('')->dropDownList(ArrayHelper::map(LegacyFaculty::find()->all(), 'legacyfacultyid', 'name'), ['prompt'=>'Select Faculty..']);?></td>
                        </tr>
                    </table> 
                
                     <br/>
                    <?= Html::a(' Cancel',
                                ['student/choose-create'],
                                ['class' => 'btn btn-danger glyphicon glyphicon-remove-circle', 'style' => 'width:20%; margin-left:55%;margin-right:2.5%']
                                );
                    ?>
                    <?= Html::submitButton(' Save', ['class' => 'btn btn-success glyphicon glyphicon-ok', 'style' => 'width:20%;']);?>
                    
                <?php ActiveForm::end() ?>
             </div>
        </div>
    </div>
</div>