<?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
?>


 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => [],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'applicantname',
                'format' => 'text',
                'label' => 'Applicant ID'
            ],
            [
                'attribute' => 'title',
                'format' => 'text',
                'label' => 'Title'
            ],
            [
                'attribute' => 'firstname',
                'format' => 'text',
                'label' => 'First Name'
            ],
            
            [
                'attribute' => 'lastname',
                'format' => 'text',
                'label' => 'Last Name'
            ],
            [
                'attribute' => 'email',
                'format' => 'text',
                'label' => 'Email'
            ],
            [
                'format' => 'html',
                'label' => 'Toggle',
                'value' => function($row)
                {
                    if ($row['status'] == 'Active')
                    {
                        return Html::a('Deactivate', Url::to(['applicant-registration/toggle', 'id' => $row['id'], 'action' => 'deactivate']));
                    }
                    else
                    {
                        return Html::a('Activate', Url::to(['applicant-registration/toggle', 'id' => $row['id'], 'action' => 'activate']));
                    }
                }
            ],
        ],
    ]); 
?>

