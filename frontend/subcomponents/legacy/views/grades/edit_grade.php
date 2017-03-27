<?php
     use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\LegacyYear;
    use frontend\models\LegacyFaculty;

    $this->title = 'Update Student Record';
    $this->params['breadcrumbs'][] = ['label' => 'Legacy Students', 'url' => ['student/find-a-student']];
    $this->params['breadcrumbs'][] = ['label' => 'Student Profile', 'url' => ['student/view', 'id' => $marksheet->legacystudentid]];
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

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <h2 class="text-center"><?= $this->title?></h2>
    
    <div class="box-header with-border">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Academic Level</th>
                    <th>Academic Year</th>
                    <th>Academic Term</th>
                    <th>Academic Subject</th>
                    <th>Subject Type</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><?= $level;?></td>
                    <td><?= $year;?></td>
                    <td><?= $term;?></td>
                    <td><?= $subject;?></td>
                    <td><?= $subject_type;?></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="term">Term:</label>
               <span><?=$form->field($marksheet, 'term')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></span>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="exam">Final:</label>
               <span><?=$form->field($marksheet, 'exam')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></span>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="final">Exam:</label>
               <span><?=$form->field($marksheet, 'final')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></span>
           </div>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['student/view', 'id' => $marksheet->legacystudentid],  ['class' => 'btn btn-danger'] );?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>