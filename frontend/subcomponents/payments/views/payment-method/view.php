<?php

        use yii\helpers\Html;
        use yii\helpers\Url;

        use frontend\models\Employee;

        $this->title = "Payment Method Details";
        $this->params['breadcrumbs'][] = ['label' => 'Payment Methods', 'url' => ['index']];
        $this->params['breadcrumbs'][] = $this->title;
?>

<div class="payment-method-view">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/payments/payment-method/index']);?>" title="Payment Method Home">     
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
                    <td style="vertical-align:middle"><?= $payment_method->name; ?></td>
                </tr>
                
                <tr>    
                    <th style="width:30%; vertical-align:middle">Creator</th>
                    <td style="vertical-align:middle"><?= Employee::getEmployeeName($payment_method->createdby); ?></td>
                </tr>
                
                <tr>  
                    <th style="width:30%; vertical-align:middle">Last Modified By</th>
                    <td style="vertical-align:middle"><?= (Employee::getEmployeeName($payment_method->lastmodifiedby))? Employee::getEmployeeName($payment_method->lastmodifiedby) : "N/A"; ?></td>
                </tr>
            </table><br/>
        </div>
    </div>
</div>