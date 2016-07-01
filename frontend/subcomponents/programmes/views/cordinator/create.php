<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\CordinatorType;
    use frontend\models\AcademicYear;
    use frontend\models\Department;
    

    $this->title = 'Assign Co-ordinator';
    $this->params['breadcrumbs'][] = ['label' => 'Co-ordinator Dashboard', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/programmes/cordinator/index']);?>" title="Manage Co-ordinators">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/cordinator.png" alt="scroll avatar">
                    <span class="custom_module_label" > Welcome to the Co-ordinator Management System</span> 
                    <img src ="css/dist/img/header_images/cordinator.png" alt="scroll avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$this->title?></h1>
                
                <br/>
                <div style="width:100%; margin: 0 auto; font-size: 18px;">
                    <?= Html::hiddenInput('cordinator_assignment_baseUrl', Url::home(true)); ?>
                    
                    <?php 
                        $form = ActiveForm::begin([
                            'id' => 'assign-cordinator',
                            'options' => [
                                 'class' => 'form-layout',
                            ]
                        ]) 
                    ?>
                        
                        <br/>
                        <table class='table table-hover' style='width:100%; margin: 0 auto;'>
                            <tr>
                                <th style='width:30%; vertical-align:middle'>Employee</th>
                                <td><?=$form->field($cordinator, 'personid')->label('')->dropDownList($employees);?></td>
                            </tr>
                            
                            <tr>
                                <th style='width:30%; vertical-align:middle'>Academic Year</th>
                                <td><?= $form->field($cordinator, 'academicyearid')->label('') ->dropDownList($academicyears, ['onchange' => 'toggleCordinatorType();']);?></td>
                            </tr>
                            
                            
                             <tr>
                                <th style='width:30%; vertical-align:middle'>Type</th>
                                <td  id="cordinator-cordinatortype" style="display:none">
                                    <?=$form->field($cordinator, 'cordinatortypeid')
                                            ->label('')
                                            ->dropDownList(ArrayHelper::map(CordinatorType::find()->where(['cordinatortypeid' => [1,2]])->all(), 'cordinatortypeid', 'name'), 
                                                                            ['prompt'=>'Select Co-ordinator Type',
                                                                                'onchange' => 'toggleDetails();respondToAcademicYearSelection(event);'
                                                                            ]
                                                                    )
                                        ;?>
                                </td>
                            </tr>
                            
                            <tr>
                                <th style='width:30%; vertical-align:middle'>Department</th>
                                <td id="cordinator-department" style="display:none">
                                    <?= Html::dropDownList('departmentid',  "Select...", $departments, ['id' => 'department_field']) ; ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <th style='width:30%; vertical-align:middle'>Programme</th>
                                <td id="cordinator-programme" style="display:none">
                                    <?= Html::dropDownList('academicofferingid',  "Select...", ['' => 'Select...'], ['id' => 'academic_offering_field']) ; ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <th style='width:30%; vertical-align:middle'>Course</th>
                                <td id="cordinator-course" style="display:none">
                                    <?= Html::dropDownList('courseofferingid',  "Select...", ['' => 'Select...'], ['id' => 'course_offering_field']) ; ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <th style='width:30%; vertical-align:middle'>CAPE Subject</th>
                                <td id="cordinator-subject" style="display:none">
                                    <?= Html::dropDownList('capesubjectid',  "Select...", ['' => 'Select...'], ['id' => 'cape_subject_field']) ; ?>
                                </td>
                            </tr>
                        </table>
                    
                        <br/>
                        <?= Html::a(' Cancel',
                                    ['cordinator/index'],
                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove-circle', 'style' => 'width:20%; margin-left:55%;margin-right:2.5%']
                                    );
                        ?>
                        <?= Html::submitButton(' Save', ['class' => 'btn btn-success glyphicon glyphicon-ok', 'style' => 'width:20%;']);?>
                <?php ActiveForm::end() ?>
            </div>
        </div>
</div>

