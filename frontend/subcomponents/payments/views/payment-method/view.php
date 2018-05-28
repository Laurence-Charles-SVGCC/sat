<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;

    use frontend\models\Employee;

    $this->title = "Payment Method Details";
    $this->params['breadcrumbs'][] = ['label' => 'Payment Methods', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary"  style="font-size:1.1em">
     <div class="box-header with-border">
         <span class="box-title"><?= $this->title?></span>
     </div>
    
    <div class="container">
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Name</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= $payment_method->name; ?></div>
        </div>
        
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Creator</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= Employee::getEmployeeName($payment_method->createdby); ?></div>
        </div>
        
        <div class="row" style="height:3em;">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><strong>Last Modified By</strong></div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><?= (Employee::getEmployeeName($payment_method->lastmodifiedby))? Employee::getEmployeeName($payment_method->lastmodifiedby) : "N/A"; ?></div>
        </div>
    </div>
</div>
