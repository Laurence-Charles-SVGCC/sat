<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

     $this->title = 'Legacy Students';
     $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/legacy/student/find-a-student']);?>" title="Legacy Student Home">
        <h1>Welcome to the Legacy Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:99%; margin: 0 auto;">
    <h2 class="text-center">
        <?= $this->title?>
        
        <?php if (true/*Yii::$app->user->can('createLegacyStudent')*/): ?>
           <?= Html::a(' Create Student(s)', ['student/choose-create'], ['class' => 'btn btn-info pull-right', 'style' => 'margin-right: 1%;']) ?>
       <?php endif; ?>
    </h2>
    
    <div class="box-header with-border">
        <span class="box-title">
            Welcome. This application facilitates the management of all student grades.  
             Please enter the student first name and / or last name.
        </span><br/>
    </div>
    
    <div class="box-body"> 
        <?php $form = ActiveForm::begin([]);?>
            <?= Html::label( 'First Name: ',  'fname_label'); ?>
            <?= Html::input('text', 'fname_field', null, ['style' => 'width:40%']); ?> <br/><br/>

            <?= Html::label( 'Last Name: ',  'lname_label'); ?>
            <?= Html::input('text', 'lname_field', null, ['style' => 'width:40%']); ?> 

            <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:40%;']) ?>
        <?php ActiveForm::end(); ?>
    </div><br/>
</div><br/><br/>

<?php if ($dataProvider) : ?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
        <div class="box-header with-border">
            <span class="box-title"><?= "Search results for: " . $info_string ?></span>
        </div>
        
        <div class="box-body">
            <?= $this->render('student_listing', ['dataProvider' => $dataProvider, 'info_string' => $info_string]) ?>
        </div>
    </div>
<?php endif; ?>