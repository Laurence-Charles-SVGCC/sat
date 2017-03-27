<?php
     use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\LegacyYear;
    use frontend\models\LegacyFaculty;
    use frontend\models\LegacyStudent;
    
    $this->title = 'Update Student Grades';
    $this->params['breadcrumbs'][] = ['label' => 'Batch Listing', 'url' => ['grades/find-batches']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/legacy/grades/find-batches']);?>" title="Legacy Batches Home">
        <h1>Welcome to the Legacy Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <h2 class="text-center">
        <?= $this->title?>
        <div class='dropdown pull-right' style="margin-right:2%">
               <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                    Add additional students...
                    <span class='caret'></span>
               </button>
               <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                   <?php for ($i = 1 ; $i <= 5 ; $i++): ?>
                    <li>
                        <a href="<?= Url::toRoute(['/subcomponents/legacy/grades/add-marksheets/', 'batchid' => $batchid, 'count' => $i]);?>"><?= $i ;?></a>
                    </li>
                   <?php endfor;?>
               </ul>
        </div>  
    </h2>
    
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
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Term Mark</th>
                        <th>Exam Mark</th>
                        <th>Final Mark</th>
                    </tr>
                </thead>

                <tbody>
                    <?php for ($i=0 ; $i<count($marksheets) ; $i++): ?>
                        <tr>
                            <td><?=$form->field($marksheets[$i], "[$i]legacystudentid")->label('')->dropDownList(ArrayHelper::map(LegacyStudent::find()->all(), 'legacystudentid', 'fullname'), ['prompt'=>'Select Student..', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9", "disabled" => true]);?></td>
                            <td><?=$form->field($marksheets[$i], "[$i]term")->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></td>
                            <td><?=$form->field($marksheets[$i], "[$i]exam")->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></td>
                            <td><?=$form->field($marksheets[$i], "[$i]final")->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></td>
                        </tr>
                    <?php endfor;?>
                </tbody>
            </table>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['grades/find-batches'],  ['class' => 'btn btn-danger'] );?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>