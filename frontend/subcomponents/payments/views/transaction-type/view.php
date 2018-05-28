<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;

    use frontend\models\Employee;

    $this->title = "Transaction Type Details";
    $this->params['breadcrumbs'][] = ['label' => 'Transaction Types', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary"  style="font-size:1.1em">
     <div class="box-header with-border">
         <span class="box-title"><?= $this->title?></span>
     </div>
    
    <div class="container">
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Name</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= $transaction_type->name; ?></div>
        </div>
        
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Creator</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= Employee::getEmployeeName($transaction_type->createdby); ?></div>
        </div>
        
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Last Modified By</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= (Employee::getEmployeeName($transaction_type->lastmodifiedby))? Employee::getEmployeeName($transaction_type->lastmodifiedby) : "N/A"; ?></div>
        </div>
    </div>
</div>