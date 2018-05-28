<?php
    use yii\web\UrlManager;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    
    $this->title = 'Programme Control Panel';
?>


<?php if(Yii::$app->user->can('powerCordinator')):?>
    <div id="cordinaor-access">
        <a class="btn btn-info pull-right"
            href=<?=Url::toRoute(['/subcomponents/programmes/cordinator/index']);?> role="button"> Manage Co-ordinators
        </a>
    </div><br/><br/>
<?php endif;?>
        
<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  margin: 0 auto;">
    <h2 class="text-center"><?= $this->title?></h2>
    
     <div class="box-header with-border">
        <span class="box-title">
            Welcome. This application facilitates the management of the institution's programme catalog.
        </span>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div>
                There are two ways in which you can navigate this module.
                <ol>
                    <li>You may begin your search based on division.</li>
                    <li>You may begin your search based on programme.</li>
                    <!--<li>You may begin your search based on course.</li>-->
                </ol>
            </div>
            
            <p>
                Please select a method by which to begin your search.
                <?= Html::radioList('overall_search_type', null, ['division' => 'By Division' , 'programme' => 'By Programme'/*, 'course' => 'By Course'*/], ['class'=> 'form_field', 'onclick'=> 'overallSearchType();']);?>
            </p>


            <div id="div" style="display:none">
                <?= Html::dropDownList('division_search', null, Division::getDivisionsInScope());?>
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
                    <a style="margin-left:5%" class="btn btn-success glyphicon glyphicon-eye-open" href=<?=Url::toRoute(['/subcomponents/programmes/programmes/view-all-courses']);?> role="button"> View All Courses</a>
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
                    <?= Html::input('text', 'course-code-field', null, ['style' => 'width:50%;']); ?>
                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:10%;']) ?>
                </div>
                <div id="by-course-name" style="display:none">
                    <?= Html::label( 'Course Name',  'course_label', ['style' => 'margin-left:5%;']); ?>
                    <?= Html::input('text', 'course-name-field', null, ['style' => 'width:50%;']); ?>
                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:10%;']) ?>
                </div>
            </div> 
        </div>
    <?php ActiveForm::end(); ?>
</div><hr>


<?php if ($programme_dataprovider) : ?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;" id="programme_results">
        <div id="programme-header">
            <h2><?= "Search results for: " . $info_string ?></h2>
            <a class="btn btn-info pull-right" href=<?=Url::toRoute(['/subcomponents/programmes/programmes/create-programme', 'divisionid' => $divisionid]);?> role="button"> Create Programme</a>
        </div><br/>
        <?= $this->render('programme_results', [
            'dataProvider' => $programme_dataprovider,
            'info_string' => $info_string,
        ]) ?>
    </div>
<?php endif; ?>


<?php if ($course_dataprovider) : ?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;"  id="course_results">
        <h2><?= "Search results for: " . $info_string ?></h2>
        <?= $this->render('course_results', [
            'dataProvider' => $course_dataprovider,
            'info_string' => $info_string,
        ]) ?>
    </div>
<?php endif; ?>