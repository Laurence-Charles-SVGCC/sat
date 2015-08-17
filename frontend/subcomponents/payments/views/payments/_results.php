<?php

use yii\widgets\ActiveForm;

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

?>
<div class="verify-applicants-index">
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'username',
                'format' => 'text',
                'label' => $type . ' ID'
            ],
            [
                'format' => 'html',
                'label' => 'Transaction Group ID',
                'value' => function($row)
                    {
                       return Html::a($row['transaction_group_id'], 
                               Url::to(['payments/view-transactions', 'transactionsummaryid' => $row['transaction_group_id']]));
                    }
            ],
            [
                'attribute' => 'academic_year',
                'format' => 'text',
                'label' => 'Academic Year'
            ],
            [
                'attribute' => 'academic_semester',
                'format' => 'text',
                'label' => 'Academic Semester'
            ],
            [
                'attribute' => 'fee_purpose',
                'format' => 'text',
                'label' => 'Purpose'
            ],
            [
                'attribute' => 'total_paid',
                'format' => 'text',
                'label' => 'Total Paid'
            ],
            [
                'attribute' => 'balance',
                'format' => 'text',
                'label' => 'Balance'
            ],
        ],
    ]); ?>
    
    <p>
        <?php if (Yii::$app->user->can('createTransaction')): ?>
            <?php $form = ActiveForm::begin(
                    ['action' => Url::to(['payments/new-payment']),]
                    ); ?>
            <?= Html::submitButton('New Payment Group', ['class' => 'btn btn-success']) ?>
            <?= Html::label('Select User', 'select_user') ?>
            <?= Html::dropDownList('select_user', 0, $result_users) ?>
            <?php ActiveForm::end(); ?>
        <?php endif; ?>
    </p>

</div>