<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    use frontend\models\Division;
    use frontend\models\EmployeeDepartment;

    $this->title = 'Student Search';
?>
    

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/gradebook/gradebook/index']);?>" title="Grade Management Home">
        <h1>Welcome to the SVGCC Grade Management System</h1>
    </a>
</div>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:60%; margin: 0 auto;">
    <h2 class="text-center"><?= $this->title?></h2>
    
    <div class="box-header with-border">
        <span class="box-title">
            Welcome. This application facilitates the management of all student grades.  
        </span>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <p>
                Welcome. This application facilitates the management of all student grades.  
            </p> 

            <div>
                There are two ways in which you can navigate this application.
                <ol>
                    <?php  if (Yii::$app->user->can('Cordinator') == false):?>
                        <li>You may begin your search based on your Division of choice.</li>
                    <?php endif;?>

                    <li>You may begin your search based on your Student ID.</li>
                    <li>You may begin your search based on your Student Name.</li>
                </ol>
            </div> 
                    
            <p>
                Please select a method by which to begin your search.
                <?php  if (Yii::$app->user->can('Cordinator') == false):?>
                    <?= Html::radioList('search_method', null, ['division' => 'By Division' , 'studentid' => 'By StudentID', 'studentname' => 'By Student Name'], ['class'=> 'form_field', 'onclick'=> 'checkSearchMethod();']);?>
                <?php else:?>
                    <?= Html::radioList('search_method', null, ['studentid' => 'By StudentID', 'studentname' => 'By Student Name'], ['class'=> 'form_field', 'onclick'=> 'checkSearchMethod();']);?>
                <?php endif;?>
            </p>

            <div id="by_division" style="display:none">
                <?php if (EmployeeDepartment::getUserDivision() != 1):?>
                    <?= Html::dropDownList('division_choice', null, Division::getDivisionsAssignedTo(Yii::$app->user->identity->personid));?>
                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?> 
                <?php else:?>
                    <?= Html::dropDownList('division_choice', null, Division::getAllDivisions());?>
                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>                               
                <?php endif; ?>
            </div>

            <div id="by_studentid" style="display:none">
                <?= Html::label( 'Student ID',  'studentid_label'); ?>
                <?= Html::input('text', 'studentid_field'); ?>
                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right']) ?>
            </div>

            <div id="by_studentname" style="display:none">
                <?= Html::label( 'First Name',  'firstname_label'); ?>
                <?= Html::input('text', 'firstname_field'); ?> <br/><br/>

                <?= Html::label( 'Last Name',  'lastname_label'); ?>
                <?= Html::input('text', 'lastname_field'); ?> 

                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div><hr>

<?php if ($all_students_provider) : ?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
        <h3><?= "Search results for: " . $info_string ?></h3>
        <?= $this->render('_results', [
            'dataProvider' => $all_students_provider,
            'info_string' => $info_string,
        ]) ?>
    </div>
<?php endif; ?>