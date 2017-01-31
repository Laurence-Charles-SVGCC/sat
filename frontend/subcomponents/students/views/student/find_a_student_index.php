<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    use frontend\models\Division;
    use frontend\models\EmployeeDepartment;

    $this->title = 'Find A Student';
    
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find  A Student">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:60%; margin: 0 auto;">
    <h2 class="text-center"><?= $this->title?></h2>
    
    <div class="box-header with-border">
        <span class="box-title">Welcome. This application facilitates the management of all student grades. </span>
    </div>
    
    <div class="box-body">
        <div>
            There are three ways in which you can navigate this application.
            <ol>
                <li>You may begin your search based on your Division of choice.</li>

                <li>You may begin your search based on your Student ID.</li>

                <li>You may begin your search based on your Student Name.</li>
            </ol>
        </div> 

        <?php $form = ActiveForm::begin();?>

            <p class="general_text">
                Please select a method by which to begin your search.
                <?= Html::radioList('search_type', null, ['division' => 'By Division' , 'studentid' => 'By StudentID', 'studentname' => 'By Student Name'], ['class'=> 'form_field', 'onclick'=> 'checkSearchType();']);?>
            </p>

            <div id="by_div" style="display:none">
                <?php /*if ((Yii::$app->user->can('Deputy Dean') || Yii::$app->user->can('Dean')  || Yii::$app->user->can('Divisional Staff'))  && !Yii::$app->user->can('System Administrator')):*/?>
                <?php if (EmployeeDepartment::getUserDivision() != 1):?>
                    <?= Html::dropDownList('division', null, Division::getDivisionsAssignedTo(Yii::$app->user->identity->personid));?>
                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?> 
                <?php else:?>
                    <?= Html::dropDownList('division', null, Division::getAllDivisions());?>
                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>                               
                <?php endif; ?>
            </div>

            <div id="by_id" style="display:none">
                <?= Html::label( 'Student ID',  'id_label'); ?>
                <?= Html::input('text', 'id_field'); ?>
                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>
            </div>

            <div id="by_name" style="display:none">
                <?= Html::label( 'First Name',  'fname_label'); ?>
                <?= Html::input('text', 'fname_field'); ?> <br/><br/>

                <?= Html::label( 'Last Name',  'lname_label'); ?>
                <?= Html::input('text', 'lname_field'); ?> 

                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>
            </div>            
        <?php ActiveForm::end(); ?>
    </div>
</div><br/><br/>

<?php if ($all_students_provider) : ?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
        <div class="box-header with-border">
            <span class="box-title"><?= "Search results for: " . $info_string ?></span>
        </div>
        
        <div class="box-body">
            <?= $this->render('_find_a_student_result', [
                'dataProvider' => $all_students_provider,
                'info_string' => $info_string,
            ]) ?>
        </div>
    </div>
<?php endif; ?>