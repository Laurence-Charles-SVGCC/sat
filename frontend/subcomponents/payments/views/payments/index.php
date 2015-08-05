<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'SVGCC Administrative Terminal';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Payments Dashboard</h1>
        <h2>Select a Task</h2>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <?php if (Yii::$app->user->can('managePayments')): ?>
                    <?= Html::a('Manage Payments', ['payments/manage-payments'], ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('viewTransactionType')): ?>
                    <?= Html::a('Manage Transaction Types', ['payments/transaction-types'], ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('viewTransactionPurpose')): ?>
                    <?= Html::a('Manage Transaction Purposes', ['payments/transaction-purposes'], ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('viewPaymentMethod')): ?>
                    <?= Html::a('Manage Payment Methods', ['payments/payment-methods'], ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
            </div>
            
        </div>

    </div>
</div>
