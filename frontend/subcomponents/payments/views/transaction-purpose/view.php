<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;

    use frontend\models\Employee;

    $this->title = "Transaction Purpose Details";
    $this->params['breadcrumbs'][] = ['label' => 'Transaction Purposes', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/payments/transaction-purpose/index']);?>" title="Transaction Purpose Home">
        <h1>Welcome to the Payment Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>


<div class="box box-primary"  style="font-size:1.1em">
     <div class="box-header with-border">
         <span class="box-title"><?= $this->title?></span>
     </div>
    
    <div class="container">
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Name</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= $transaction_purpose->name; ?></div>
        </div>
        
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Description</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= $transaction_purpose->description; ?></div>
        </div>
        
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Creator</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= Employee::getEmployeeName($transaction_purpose->createdby); ?></div>
        </div>
        
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Last Modified By</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= (Employee::getEmployeeName($transaction_purpose->lastmodifiedby))? Employee::getEmployeeName($transaction_purpose->lastmodifiedby) : "N/A"; ?></div>
        </div>
    </div>
</div>