<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;

    use frontend\models\Employee;
    use frontend\models\TransactionPurpose;

    $this->title = "Transaction Item Details";
    $this->params['breadcrumbs'][] = ['label' => 'Transaction Items', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary"  style="font-size:1.1em">
     <div class="box-header with-border">
         <span class="box-title"><?= $this->title?></span>
     </div>
    
    <div class="container">
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Name</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= $transaction_item->name; ?></div>
        </div>
        
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Transaction Purpose</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= TransactionPurpose::find()->where(['transactionpurposeid' => $transaction_item->transactionpurposeid, 'isdeleted' => 0])->one()->name; ?></div>
        </div>
        
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Creator</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= Employee::getEmployeeName($transaction_item->createdby); ?></div>
        </div>
        
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Last Modified By</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= (Employee::getEmployeeName($transaction_item->lastmodifiedby))? Employee::getEmployeeName($transaction_item->lastmodifiedby) : "N/A"; ?></div>
        </div>
    </div>
</div>