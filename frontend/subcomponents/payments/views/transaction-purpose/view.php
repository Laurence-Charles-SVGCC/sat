<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

    use frontend\models\Employee;

    $this->title = "Transaction Purpose Details";
    $this->params['breadcrumbs'][] = ['label' => 'Transaction Purposes', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="transaction-type-view">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/payments/transaction-purpose/index']);?>" title="Transaction Purpose Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/bursary.png" alt="bursary-avatar">
                <span class="custom_module_label">Welcome to the Bursary Management System</span> 
                <img src ="css/dist/img/header_images/bursary.png" alt="bursary-avatar" class="pull-right">
            </a>    
        </div>
        
         <div class="custom_body">
            <h1 class="custom_h1"><?= $this->title?></h1>
            
            <br/>
            <table class='table table-hover' style='width:70%; margin: 0 auto;'>
                <tr>
                    <th style="width:30%; vertical-align:middle">Name</th>
                    <td style="vertical-align:middle"><?= $transaction_purpose->name; ?></td>
                </tr>
                
                <tr>
                    <th style="width:30%; vertical-align:middle">Description</th>
                    <td style="vertical-align:middle"><?= $transaction_purpose->description; ?></td>
                </tr>
                
                <tr>    
                    <th style="width:30%; vertical-align:middle">Creator</th>
                    <td style="vertical-align:middle"><?= Employee::getEmployeeName($transaction_purpose->createdby); ?></td>
                </tr>
                
                <tr>  
                    <th style="width:30%; vertical-align:middle">Last Modified By</th>
                    <td style="vertical-align:middle"><?= (Employee::getEmployeeName($transaction_purpose->lastmodifiedby))? Employee::getEmployeeName($transaction_type->lastmodifiedby) : "N/A"; ?></td>
                </tr>
            </table><br/>
        </div>
    </div>
</div>