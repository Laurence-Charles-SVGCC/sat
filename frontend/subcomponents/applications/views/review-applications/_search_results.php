<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
?>

<div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'email',
                'format' => 'text',
                'label' => 'Email'
            ],
            [
                'attribute' => 'applicantname',
                'format' => 'text',
                'label' => 'ApplicantID'
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
                'attribute' => 'status',
                'format' => 'text',
                'label' => 'Status'
            ],
            [
                'attribute' => 'username',
                'format' => 'text',
                'label' => 'Username'
            ],
        ],
    ]); ?>     
</div>