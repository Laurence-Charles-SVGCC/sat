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


     $this->title = 'Create Multiple Students';
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
            <div style="width:99%; margin: 0 auto; font-size: 20px;">
                <?php 
                    $form = ActiveForm::begin([
                        'action' =>  Url::to(['student/create-multiple-students', 'record_count' => count($students)]),
                        'id' => 'create-multiple-students',
                        'options' => [
                             'class' => 'form-layout2',
                        ]
                    ]) 
                ?>
                     <?= Html::hiddenInput('legacy_record_count', count($students)); ?>
                
                    <br/>
                    <table class='table table-condensed' style='width:100%; margin: 0 auto;'>
                    <?php for ($i=0 ; $i<count($students) ; $i++): ?>
                        <tr style='border-top:solid 5px'>
                            <th style='vertical-align:middle;'>Title</th>
                            <td><?=$form->field($students[$i], "[$i]title")->label('')->dropDownList(['' => 'Select..', 'Mr' => 'Mr', 'Ms' => 'Ms', 'Mrs' => 'Mrs']);?></td>
                            
                            <th style='vertical-align:middle;'>Firstname</th>
                            <td><?=$form->field($students[$i], "[$i]firstname")->label('')->textInput(['maxlength' => true]);?></td>
                            
                            <th style='vertical-align:middle;'>Middle</th>
                            <td><?=$form->field($students[$i], "[$i]middlename")->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true]);?></td>
                            
                            <th style='vertical-align:middle;'>Lastname</th>
                            <td><?=$form->field($students[$i], "[$i]lastname")->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true]);?></td>
                        </tr>
                        
                        <tr>
                            <th style='vertical-align:middle'>Gender</th>
                            <td><?=$form->field($students[$i], "[$i]gender")->label('')->dropDownList(['' => 'Select..', 'Male' => 'Male', 'Female' => 'Female']);?></td>
                            
                            <th style='vertical-align:middle'>Admission Year</th>
                            <td><?=$form->field($students[$i], "[$i]legacyyearid")->label('')->dropDownList(ArrayHelper::map(LegacyYear::find()->all(), 'legacyyearid', 'name'), ['prompt'=>'Select year..']);?></td>
                            
                            <th style='vertical-align:middle'>Faculty</th>
                            <td><?=$form->field($students[$i], "[$i]legacyfacultyid")->label('')->dropDownList(ArrayHelper::map(LegacyFaculty::find()->all(), 'legacyfacultyid', 'name'), ['prompt'=>'Select Faculty..']);?></td>
                            
                            <th style='vertical-align:middle;'>Date of Birth</th>
                            <td colspan='2'><?=$form->field($students[$i], "[$i]dateofbirth")->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]);?></td>
                        </tr>
                        
                        <tr>
                            <th style='vertical-align:middle'>Address</th>
                            <td colspan='3'><?=$form->field($students[$i], "[$i]address")->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'rows' =>2]);?></td>
                         </tr>
                    <?php endfor;?>
                    </table> 
                     
                    
                    <?= Html::a(' Cancel', ['student/choose-create'],
                                ['class' => 'btn btn-danger glyphicon glyphicon-remove-circle', 'style' => 'width:20%; margin-left:55%;margin-right:2.5%']);
                    ?>
                    <?= Html::submitButton(' Save', ['class' => 'btn btn-success glyphicon glyphicon-ok', 'style' => 'width:20%;', 'onclick' => 'generateStudentBlanks();']);?>
                    
                <?php ActiveForm::end() ?>
             </div>
            
        </div>
    </div>
</div>