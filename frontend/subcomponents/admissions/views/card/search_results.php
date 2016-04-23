<?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
?>

<div class="card_search_results">
    <?php 
        ActiveForm::begin(
            [
                'action' => Url::to(['card/update-applicants'])
            ]); 
    ?>
    
        <?= 
            GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['style' => 'width:98%; margin: 0 auto;'],
                'columns' => [
                    [
                       'attribute' => 'studentno',
                        'label' => 'Student No.',
                    ],
                    'firstname',
                    'lastname',
                    'programme',
                    'published:boolean',
                    [
                        'label' => 'Picture Taken',
                        'format' => 'raw',
                        'value' => function($row)
                         {
                            return  $row['studentreg'] ? Html::Checkbox('receivedpicture[' . $row['studentreg']->studentregistrationid . ']',
                                    $row['studentreg']->receivedpicture ,['label' => NULL]) : 'N/A';
                         }
                    ],
                    [
                        'label' => 'Card Ready',
                        'format' => 'raw',
                        'value' => function($row)
                         {
                            return  Html::Checkbox('cardready[' . $row['studentreg']->studentregistrationid . ']',
                                    $row['studentreg']->cardready , ['label' => NULL]);
                         }
                    ],
                    [
                        'label' => 'Card Collected',
                        'format' => 'raw',
                        'value' => function($row)
                         {
                            return  Html::Checkbox('cardcollected[' . $row['studentreg']->studentregistrationid . ']',
                                    $row['studentreg']->cardcollected ,['label' => NULL]);
                         }
                    ],
                    [

                        'format' => 'raw',
                        'value' => function($row)
                         {
                            return  Html::HiddenInput('studentreg[]',  $row['studentreg']->studentregistrationid);
                         }
                    ],

                ],
            ]); 
        ?>
    
        <?php if (Yii::$app->user->can('updateStudentCard')): ?>
            <?= Html::submitButton('Update Card Data', ['class' => 'btn btn-success pull-right', 'name' => 'register', 'style' => 'margin-right: 5%']); ?>
        <?php endif; ?>    
   <?php ActiveForm::end(); ?>
</div>

