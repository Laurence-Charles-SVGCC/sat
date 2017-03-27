<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\LegacyYear;
    use frontend\models\LegacyFaculty;

     $this->title = 'Create Multiple Students';
     $this->params['breadcrumbs'][] = ['label' => 'Legacy Students', 'url' => ['find-a-student']];
     $this->params['breadcrumbs'][] = ['label' => 'Student Creation Mode', 'url' => ['choose-create']];
     $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/legacy/student/find-a-student']);?>" title="LEgacy Student Home">
        <h1>Welcome to the Legacy Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <h2 class="text-center"><?= $this->title?></h2>
    
    <?php $form = ActiveForm::begin([ 'action' =>  Url::to(['student/create-multiple-students', 'record_count' => count($students)]) ]) ?>
        <div class="box-body">
            <?= Html::hiddenInput('legacy_record_count', count($students)); ?>
            
            <br/>
            <table class='table table-condensed' style='width:100%; margin: 0 auto;'>
            <?php for ($i=0 ; $i<count($students) ; $i++): ?>
                <tr style='border-top:solid 5px'>
                    <th style='vertical-align:middle;'><?=($i+1);?> .Title</th>
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
                    <td colspan='3'><?=$form->field($students[$i], "[$i]address")->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'rows' =>3]);?></td>
                 </tr>
            <?php endfor;?>
            </table> 
        </div>
            
        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['student/choose-create'],  ['class' => 'btn btn-danger'] );?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>