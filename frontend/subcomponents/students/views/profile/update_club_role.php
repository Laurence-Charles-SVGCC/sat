<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\Club;
    use frontend\models\ClubRole;
    
    $this->title = 'Update Club Member Role';
?>

<div class="site-index">
    <div class = "custom_wrapper">

        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title?></h1>

            <?php
                $form = ActiveForm::begin([
                            'id' => 'assign_club',
                            'options' => [
                                'style' => 'width:90%; margin: 0 auto;',
                            ],
                        ]);
            ?>
                <table class='table table-hover' style='margin: 0 auto;'>
                    <tr>
                        <th style='width:30%; vertical-align:middle'>Club Name</th>
                        <td><?=$form->field($club_assignment, 'clubid')->label('')->dropDownList(ArrayHelper::map(Club::find()->all(), 'clubid', 'name'), ['prompt'=>'Select Club', 'readonly' => true, 'disabled' => true]) ?></td>
                    </tr>

                    <tr>
                        <th style='width:30%; vertical-align:middle'>Club Role</th>
                        <td><?=$form->field($club_assignment, 'clubroleid')->label('')->dropDownList(ArrayHelper::map(ClubRole::find()->all(), 'clubroleid', 'name'), ['prompt'=>'Select Member Role']) ?></td>
                    </tr>

                    <tr>
                        <th style='width:30%; vertical-align:middle'>End Date of Previous Role</th>
                        <td><?=$form->field($member_history, 'enddate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])?></td>
                    </tr>

                    <tr>
                        <th style='width:30%; vertical-align:middle'>Start Date of New Role</th>
                        <td><?=$form->field($member_history, 'startdate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])?></td>
                    </tr>

                    <tr>
                        <th style='width:30%; vertical-align:middle'>Comments</th>
                        <td><?=$form->field($club_assignment, 'comments')->label('', ['class'=> 'form-label'])->textArea(['maxlength' => true, 'style' => 'vertical-align:middle', 'rows' => 5])?></td>
                    </tr>
                </table><br/>

                <?= Html::a(' Cancel',['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);?>
                <?= Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);?>

            <?php ActiveForm::end(); ?>   
        </div>
    </div>
</div>




