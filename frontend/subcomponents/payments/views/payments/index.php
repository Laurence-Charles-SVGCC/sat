<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'SVGCC Administrative Terminal';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Admissions Home</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Registrar</h2>
                <?= Html::a('Manage Payments', ['payments/manage-payments'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Manage Transaction Types', ['payments/transaction-types'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Manage Transaction Purposes', ['payments/transaction-purposes'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Manage Payment Methods', ['payments/payment-methods'], ['class' => 'btn btn-success']) ?>
            </div>
            
            <div class="col-lg-4">
                <h2>Registrar's Office</h2>
                
            </div>
            
            <div class="col-lg-4">
                <h2>Deans/Deputy Deans</h2>
            </div>
        </div>

    </div>
</div>
