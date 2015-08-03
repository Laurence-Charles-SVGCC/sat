<?php

use yii\helpers\Html;
use yii\grid\GridView;

use frontend\models\Employee;
use frontend\models\TransactionSummary;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
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
        <?= Html::a('Add Transaction', ['transaction/create', 'transactionsummaryid' => $transactionsummaryid, 'payee_id' => $payee_id], 
            ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'receiptnumber',
            [
                'label' => 'Type',
                'value' => function($model)
                    {
                       return $model->getTransactiontype()->one()->name;
                    }
            ],
            'personid',
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
            
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
