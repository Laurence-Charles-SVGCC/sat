<?php

/* 
 * Author: Laurence Charles
 * Date Created: 27/04/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\grid\GridView;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    
    $this->title = 'Programme Control Panel';
?>


    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/registry/awards/manage-awards']);?>" title="Manage Programmes">     
                    <img class="custom_logo_students" src ="<?=Url::to('../images/programme.png');?>" alt="scroll avatar">
                    <span class="custom_module_label" > Welcome to the Programme Management System</span> 
                    <img src ="<?=Url::to('../images/programme.png');?>" alt="scroll avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$this->title?></h1>
                
                <br/>
                <div class="wide_center_content general_text">
                    <p>
                        Welcome. This application facilitates the management of the institution's programme
                        and course catalog.  
                    </p> 

                    <div>
                        There are two ways in which you can navigate this module.
                        <ol>
                            <li>You may begin your search based on division.</li>

                            <li>You may begin your search based on programme.</li>

                            <li>You may begin your search based on course.</li>
                        </ol>
                        
                        <?php $form = ActiveForm::begin(
                            [
                                'action' => Url::to(['programmes/index']),
                            ]);
                        ?>
                        
                            <p class="general_text">
                                Please select a method by which to begin your search.
                                <?= Html::radioList('overall_search_type', null, ['division' => 'By Division' , 'programme' => 'By Programme', 'course' => 'By Course'], ['class'=> 'form_field', 'onclick'=> 'overallSearchType();']);?>
                            </p>


                            <div id="div" style="display:none">
                                <?= Html::dropDownList('division_search', null, Division::getAllDivisions());?>
                                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>                               
                            </div>


                            <div id="prog" style="display:none">
                                <p class="general_text" style="margin-left:5%">
                                    Please select a method by which to begin your programme search.
                                    <?= Html::radioList('programme_search_type', null, ['all_programmes' => 'All Programmes' , 'programme_name' => 'By Programme Name'], ['class'=> 'form_field', 'style' => 'margin-left:5%', 'onclick'=> 'programmeSearchType();']);?>
                                </p>
                                <div id="all-programme" style="display:none">
                                    <a style="margin-left:5%" class="btn btn-success glyphicon glyphicon-eye-open" href=<?=Url::toRoute(['/subcomponents/programmes/programmes/view-all-programmes']);?> role="button"> View All Programmes</a>
                                </div>
                                <div id="by-programme-name" style="display:none">
                                    <?= Html::label( 'Programme Name',  'programme_label', ['style' => 'margin-left:5%;']); ?>
                                    <?= Html::input('text', 'programme_field', null, ['style' => 'width:50%;']); ?>
                                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:10%;']) ?>
                                </div>
                            </div>


                            <div id="course" style="display:none">
                                <p class="general_text"  style="margin-left:5%">
                                    Please select a method by which to begin your course search.
                                    <?= Html::radioList('course_search_type', null, ['all_courses' => 'All Courses', 'course_division' => 'By Division', 'course_department' => 'By Department', 'course_code' => 'By Course Code', 'course_name' => 'By Course Name'], ['class'=> 'form_field', 'style' => 'margin-left:5%', 'onclick'=> 'courseSearchType();']);?>
                                </p>
                                <div id="all-courses" style="display:none">
                                    <a style="margin-left:5%" class="btn btn-success glyphicon glyphicon-eye-open" href=<?=Url::toRoute(['/subcomponents/programmes/programmes/view-all-programmes']);?> role="button"> View All Programmes</a>
                                </div>
                                <div id="by-course-division"  style="display:none;margin-left:5%">
                                    <?= Html::dropDownList('course-division', null, Division::getDivisionsWithCourses());?>
                                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>                               
                                </div>
                                <div id="by-course-department"  style="display:none; margin-left:5%">
                                    <?= Html::dropDownList('course-department', null, Department::getDepartmentsWithCourses());?>
                                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>                               
                                </div>
                                <div id="by-course-code" style="display:none">
                                    <?= Html::label( 'Course Code',  'course_label', ['style' => 'margin-left:5%;']); ?>
                                    <?= Html::input('text', 'course_code_field', null, ['style' => 'width:50%;']); ?>
                                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:10%;']) ?>
                                </div>
                                <div id="by-course-name" style="display:none">
                                    <?= Html::label( 'Course Name',  'course_label', ['style' => 'margin-left:5%;']); ?>
                                    <?= Html::input('text', 'course_name_field', null, ['style' => 'width:50%;']); ?>
                                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:10%;']) ?>
                                </div>
                            </div> 
                        <?php ActiveForm::end(); ?>
                    </div>
                </div><br/>

                <div class="programme_result">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
//                                'attribute' => 'name',
//                                'format' => 'text',
//                                'label' => 'Programme Name',
                                'format' => 'html',
                                'value' => function($row)
                                    {
                                        return Html::a($row['name'], 
                                                        Url::to(['programmes/programme-overview', 'programmecatalogid' => $row['programmecatalogid']]));
                                    }
                            ],
                            [
                                'attribute' => 'qualificationtype',
                                'format' => 'text',
                                'label' => 'Qualification'
                            ],
                            [
                                'attribute' => 'specialisation',
                                'format' => 'text',
                                'label' => 'Specialisation'
                            ],
                            [
                                'attribute' => 'department',
                                'format' => 'text',
                                'label' => 'Department'
                            ],
                            [
                                'attribute' => 'exambody',
                                'format' => 'text',
                                'label' => 'Exam Body'
                            ],
                            [
                                'attribute' => 'programmetype',
                                'format' => 'text',
                                'label' => 'Type'
                            ],     
                            [
                                'attribute' => 'duration',
                                'format' => 'text',
                                'label' => 'Duration'
                            ],
                            [
                                'attribute' => 'creationdate',
                                'format' => 'text',
                                'label' => 'Created'
                            ],
                        ],
                    ]); ?>     
                </div>
            </div>
        </div>
    </div>

