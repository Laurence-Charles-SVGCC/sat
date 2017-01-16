<?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
?>

<div>
    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'format' => 'html',
                    'label' => 'ID',
                    'value' => function($row, $status)
                     {
                                return Html::a($row['username'], 
                                            Url::to(['payments/view-user-transactions',
                                                     'id' => $row['personid'],
                                                    'status' => $row['status'],
                                              ]));

                      }
                ],
                [
                    'attribute' => 'firstname',
                    'format' => 'text',
                    'label' => 'First Name'
                ],
                [
                    'attribute' => 'middlename',
                    'format' => 'text',
                    'label' => 'Middle Name(s)'
                ],
                [
                    'attribute' => 'lastname',
                    'format' => 'text',
                    'label' => 'Last Name'
                ],
                [
                    'attribute' => 'gender',
                    'format' => 'text',
                    'label' => 'Gender'
                ],
                [
                    'attribute' => 'division',
                    'format' => 'text',
                    'label' => 'Division'
                ],
            ],
        ]); 
    ?>
</div>
