<?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $this->title = 'Update Student Card Information';
?>

<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/sms_4.png');?>" alt="Find A Student">
                <span class="custom_module_label">Welcome to the Student Management System</span> 
                <img src ="<?=Url::to('../images/sms_4.png');?>" alt="student avatar" class="pull-right">
            </a>    
        </div>

        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title?></h1>
            <br/>
            <?php 
                ActiveForm::begin(
                    [
                        'options' => [
                            'style' => 'margin: 0 auto; width: 80%'
                        ],
                    ]); 
            ?>
                <table class='table table-hover' style='margin: 0 auto;'>
                    <tr>
                        <th>Institution ID Criteria</th>
                        <th>Status</th>
                    </tr>

                    <tr>
                        <td>Picture Received</td>
                        <td>
                            <?=Html::Checkbox('receivedpicture',
                                            $reg->receivedpicture,
                                            ['label' => NULL]);
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Card Ready</td>
                        <td>
                            <?=Html::Checkbox('cardready',
                                            $reg->cardready,
                                            ['label' => NULL]);
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Card Delivered</td>
                        <td>
                            <?=Html::Checkbox('cardcollected',
                                            $reg->cardcollected,
                                            ['label' => NULL]);
                            ?>
                        </td>
                    </tr>
                </table><br/>
                
                <?= Html::a(' Cancel',['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $reg->studentregistrationid], ['class' => 'btn btn-block btn-danger glyphicon glyphicon-remove-circle pull-right', 'style' => 'width:15%; margin-left:5%;'])?>
                <?= Html::submitButton(' Update', ['class' => 'btn btn-success glyphicon glyphicon-ok pull-right', 'style' => 'margin-right: 5%; width:15%; ']); ?>
           <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

