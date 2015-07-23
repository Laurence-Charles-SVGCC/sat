<?php

use yii\helpers\Html;

//$payee = Applicant::
$invoice_total = 0.0;
?>

<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Invoice
          </h1>
        </section>

        <!-- Main content -->
        <section class="invoice">
          <!-- title row -->
          <div class="row">
            <div class="col-xs-12">
              <h2 class="page-header">
                <i class="fa fa-globe"></i>SVGCC
                <small class="pull-right">Date: 2/10/2014</small>
              </h2>
            </div><!-- /.col -->
          </div>
          <!-- info row -->
          <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
              From
              <address>
                <strong>St. Vincent and the Grenadines Community College</strong><br>
                VillaFlat <br>
                St. Vincent W.I<br>
                Phone: (784) 457-4503<br/>
                Email: bursary@svgcc.vc
              </address>
            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col">
              To
              <address>
                <strong>John Doe</strong><br>
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
                    <th>Amount</th>
                    <th>Balance</th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach ($models as $model): ?>
                  <tr>
                    <td><?= $model->receiptnumber ?></td>
                    <td><?= $model->getTransactiontype()->one()->name ?></td>
                    <td><?= $model->getTransactionpurpose()->one()->name ?></td>
                    <td><?= $model->getPaymentmethod()->one()->name; ?></td>
                    <td><?= $model->paydate; ?></td>
                    <td>$<?= $model->paymentamount; ?></td>
                    <td>$<?= $model->totaldue; ?></td>
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
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->

          <!-- TODO: Implement Printing Invoice. this row will not appear when printing -->
          <!--<div class="row no-print">
            <div class="col-xs-12">
              <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
            </div>
          </div>-->
        </section><!-- /.content -->
        <div class="clearfix"></div>
      </div><!-- /.content-wrapper -->
