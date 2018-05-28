<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use yii\widgets\ActiveForm;
    
     $this->title = 'Student Creation Mode';
     $this->params['breadcrumbs'][] = ['label' => 'Legacy Students', 'url' => ['find-a-student']];
     $this->params['breadcrumbs'][] = $this->title;
     
     $no_of_students = [
         0 => 0,
         1 => 1,
         2 => 2,
         3 => 3,
         4 => 4,
         5 => 5,
         6 => 6,
         7 => 7,
         8 => 8,
         9 => 9,
         10 => 10,
         11 => 11,
         12 => 12,
         13 => 13,
         14 => 14,
         15 => 15,
         16 => 16,
         17 => 17,
         18 => 18,
         19 => 19,
         20 => 20,
         21 => 21,
         22 => 22,
         23 => 23,
         24 => 24,
         25 => 25
     ];
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/legacy/student/find-a-student']);?>" title="LEgacy Student Home">
        <h1>Welcome to the Legacy Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:99%; margin: 0 auto;">
    <div class="box-header with-border">
        <span class="box-title"><?=$this->title;?></span>
    </div>
    
    <div class="box-body">
        <p>
            Please select student creation mode.
            <?= Html::radioList('student_creation_mode', null, ['single' => 'Create Single Student', 'batch' => 'Create Multiple Students'], ['class'=> 'form_field', 'onclick'=> 'showCreationMode(); showBatchCreationButton()']);?>
        </p>

        <div id="single-mode" style="display:none">
            <?= Html::a(' Create Student', ['student/create-single-student'], ['class' => 'btn btn-success']) ?>
        </div>

        <?php $form = ActiveForm::begin(['action' => Url::to(['student/generate-batch-form']),]); ?>
            <div id="batch-mode" style="display:none; margin-left: 175px">
                <?= Html::label( 'No. of new students:',  'student-count-label'); ?>
                <?= Html::dropDownList('student-count-field', null, $no_of_students, ['id' => 'student-count-field', 'onchange' => 'showBatchCreationButton();']) ; ?>
            
                <span id="batch-button" style="display:none; margin-left: 200px">
                    <?= Html::submitButton('Create students', ['class' => 'btn btn-success', 'style' => ';']) ?>
                </span>
            </div>
        <?php ActiveForm::end(); ?>   
    </div>
</div>