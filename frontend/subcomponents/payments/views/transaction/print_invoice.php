<?php

    use yii\helpers\Url;
    use frontend\models\Employee;

    $invoice_total = 0.0;
?>

<body onload="window.print();">
    <p><i class="fa"><img src="<?= Url::to('css/dist/img/logo.png')?>"/></i></p>
    
     <p><strong>Date:</strong> <?= date('Y-m-d');?></p>
    
     <div>
        <strong>From:</strong>
        <address>
            St. Vincent and the Grenadines Community College<br/>
            Villa Flat <br>
            St. Vincent W.I<br>
            Phone: (784) 457-4503<br/>
            Email: bursary@svgcc.vc
        </address>
    </div><br/>

    <div>
        <strong>ID: </strong><?= $user->username ?><br>
        <strong>Name: </strong><?= $applicant->firstname . " " . $applicant->lastname ?><br>
    </div><br/>

    <table border="1">
        <thead>
            <tr>
                <th>Receipt Number</th>
                <th>Type</th>
                <th>Purpose</th>
                <th>Payment Method</th>
                <th>Date</th>
                <th>Total Due</th>
                <th>Amount</th>
                <th>Balance</th>
                <th>Recepient</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($models as $model): ?>
                <?php $recepient = Employee::find()->where(['personid' => $model->getRecepient()->one()->personid])->one();
                   $rname = $recepient ? $recepient->firstname . " " . $recepient->lastname : 'Recepient Undefined'; ?>
                <tr>
                    <td><?= $model->receiptnumber ?></td>
                    <td><?= $model->getTransactiontype()->one()->name ?></td>
                    <td><?= $model->getTransactionpurpose()->one()->name ?></td>
                    <td><?= $model->getPaymentmethod()->one()->name; ?></td>
                    <td><?= $model->paydate; ?></td>
                    <td>$<?= $model->totaldue; ?></td>
                    <td>$<?= $model->paymentamount; ?></td>
                    <td>$<?= $model->totaldue - $model->paymentamount; ?></td>
                    <td><?= $rname; ?></td>
                    <?php $invoice_total += $model->paymentamount; ?>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table><br/>
    
    
    <div>
        <strong>Total: </strong>$<?= $invoice_total; ?><br>
        <strong>Signature: </strong>
    </div><br/>
</body>
