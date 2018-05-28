<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $this->title = 'Update IDCard Status';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find An Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = ['label' => 'Student Profile', 'url' => Url::toRoute(['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $reg->studentregistrationid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?=$this->title?></span>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <table class='table table-hover' style='margin: 0 auto;'>
                <tr>
                    <th>Institution ID Criteria</th>
                    <th>Status</th>
                </tr>

                <tr>
                    <td>Picture Received</td>
                    <td><?=Html::Checkbox('receivedpicture', $reg->receivedpicture,  ['label' => NULL]);?></td>
                </tr>

                <tr>
                    <td>Card Ready</td>
                    <td><?=Html::Checkbox('cardready', $reg->cardready, ['label' => NULL]);?></td>
                </tr>

                <tr>
                    <td>Card Delivered</td>
                    <td><?=Html::Checkbox('cardcollected',$reg->cardcollected, ['label' => NULL]);?></td>
                </tr>
            </table>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $reg->studentregistrationid], ['class' => 'btn  btn-danger']);?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>