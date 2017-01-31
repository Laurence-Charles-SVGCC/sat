<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\Club;
    use frontend\models\ClubRole;
    
    $this->title = ucwords($action) .' Club Membership';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find An Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = ['label' => 'Student Profile', 'url' => Url::toRoute(['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find  A Student">
        <h1>Welcome to the Student Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?=$this->title?></span>
    </div>
    
     <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="clubid">Club Name:</label>
               <?php if ($action == "create"):?>
                    <?=$form->field($club_assignment, 'clubid')->label('')->dropDownList(ArrayHelper::map(Club::find()->all(), 'clubid', 'name'), ['prompt'=>'Select Club', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
               <?php else:?>
                    <?=$form->field($club_assignment, 'clubid')->label('')->dropDownList(ArrayHelper::map(Club::find()->all(), 'clubid', 'name'), ['prompt'=>'Select Club', 'readonly' => true, 'disabled' => true, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
               <?php endif;?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="clubroleid">Club Role:</label>
               <?php if ($action == "create"):?>
                    <?=$form->field($club_assignment, 'clubroleid')->label('')->dropDownList(ArrayHelper::map(ClubRole::find()->all(), 'clubroleid', 'name'), ['prompt'=>'Select Member Role', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
              <?php else:?>
                    <?=$form->field($club_assignment, 'clubroleid')->label('')->dropDownList(ArrayHelper::map(ClubRole::find()->all(), 'clubroleid', 'name'), ['prompt'=>'Select Member Role', 'readonly' => true, 'disabled' => true, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
              <?php endif;?>
           </div>
            
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="comments">Comments:</label>
               <?=$form->field($club_assignment, 'comments')->label('')->textArea(['maxlength' => true, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9", 'rows' => 5])?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="appointmentdate">Date Joined:</label>
               <?=$form->field($club_assignment, 'appointmentdate')->label(false)->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]])?>
           </div>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn  btn-danger']);?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>