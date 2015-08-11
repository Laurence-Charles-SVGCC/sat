<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

use frontend\models\Employee;
//use frontend\models\TransactionSummary;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//Get payee ID
$payee_id = '';
if (count($dataProvider->getModels()) > 0)
{
    $payee_id = $dataProvider->getModels()[0]->personid;
}
?>
<div class="transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php if (Yii::$app->user->can('CreateTransaction')): ?>
            <?= Html::a('Add Transaction', ['transaction/create', 'transactionsummaryid' => $transactionsummaryid, 'payee_id' => $payee_id], 
                ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'receiptnumber',
                'format' => 'html',
                'label' => 'Receipt #',
                'value' => function($model)
                    {
                       return Html::a($model->receiptnumber, 
                               Url::to(['payments/update-transaction', 'receiptnumber' => $model->receiptnumber]));
                    }
            ],
            [
                'label' => 'Type',
                'value' => function($model)
                    {
                       return $model->getTransactiontype()->one()->name;
                    }
            ],
            [
                'attribute' => 'personid',
                'format' => 'html',
                'label' => 'Applicant ID',
                'value' => function($model)
                    {
                       $user = User::findOne(['personid' => $model->personid]);
                       $username = $user ? $user->username : $model->personid;
                       return $username;
                    }
            ],
            [
                'label' => 'Purpose',
                'value' => function($model)
                    {
                       return $model->getTransactionpurpose()->one()->name;
                    }
            ],
             [
                'label' => 'Recepient',
                'value' => function($model)
                    {
                        $recepient = Employee::find()->where(['personid' => $model->getRecepient()->one()->personid])->one();
                       return $recepient ? $recepient->firstname . " " . $recepient->lastname : 'Recepient Undefined';
                    }
            ],
             [
                'label' => 'Payment Method',
                'value' => function($model)
                    {
                       return $model->getPaymentmethod()->one()->name;
                    }
            ],
            'paydate',
            'paymentamount',
            'totaldue',
            'comments:ntext',
            [
                'format' => 'html',
                'label' => 'Receipt',
                'value' => function($model)
                    {
                       return Html::a('View', 
                               Url::to(['payments/get-transaction-receipt', 'receiptnumber' => $model->receiptnumber]));
                    }
            ],
            [
                'format' => 'html',
                'label' => 'Receipt',
                'value' => function($model)
                    {
                       return Html::a('Print', 
                               Url::to(['payments/print-transaction-receipt', 'receiptnumber' => $model->receiptnumber]));
                    }
            ],            
        ],
    ]); ?>

</div>
