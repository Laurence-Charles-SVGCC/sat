<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use frontend\models\Employee;

    $this->title = 'Receipt';
    $this->params['breadcrumbs'][] = ['label' => 'Find Applicant/Student', 'url' => ['find-applicant-or-student', 'status' => $status, 'new_search' => 1]];
    $this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['view-user-transactions', 'id' => $personid, 'status' => $status]];
    $this->params['breadcrumbs'][] = $this->title;

    $invoice_total = 0.0;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/payments/payments/print-transaction-receipt', 'receiptnumber' => $models[0]->receiptnumber]);?>">
        <h1>Print Receipt</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/>

<div class="content-wrapper" style="margin: 0 auto">
        <!-- Main content -->
        <section class="invoice">
          <!-- title row -->
          <div class="row">
            <div class="col-xs-12">
              <h2 class="page-header">
                  <i class="fa"><img src="<?= Url::to('css/dist/img/logo.png')?>"/></i>
                <small class="pull-right">Date: <?= date('Y-m-d');?></small>
              </h2>
            </div><!-- /.col -->
          </div>
          <!-- info row -->
          <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
              From
              <address>
                <strong>St. Vincent and the Grenadines Community College</strong><br>
                Villa Flat <br>
                St. Vincent W.I<br>
                Phone: (784) 457-4503<br/>
                Email: bursary@svgcc.vc
              </address>
            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col">
              To
              <address>
                <strong><?= $applicant->firstname . " " . $applicant->lastname ?></strong><br>
              </address>
            </div><!-- /.col -->
          </div><!-- /.row -->

          <!-- Table row -->
          <div class="row">
            <div class="col-xs-12 table-responsive">
              <table class="table table-striped">
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
                      <td><a href="#"><?= $model->receiptnumber ?></a></td>
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
              </table>
            </div><!-- /.col -->
          </div><!-- /.row -->

          <div class="row">
            <!-- TODO: Add accepted payments column from Payment Methods table-->
            
            <div class="col-xs-6">
              <p class="lead">Summary</p>
              <div class="table-responsive">
                <table class="table">
                  <tr>
                    <th>Total:</th>
                    <td>$<?= $invoice_total; ?></td>
                  </tr>
                </table>
                  
                Signed:
                <hr/>
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
        <div class="clearfix"></div>
</div>
