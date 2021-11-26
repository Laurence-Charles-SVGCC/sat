<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Void Receipt";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Find Account", "url" => ["profiles/search"]];

$this->params["breadcrumbs"][] =
    [
        "label" => $userFullname,
        "url" => [
            "profiles/redirect-to-customer-profile",
            "username" => $username
        ]
    ];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="box box-primary" style="background-image: url('img/bursary/void-watermark2.jpeg'); background-repeat: no-repeat; background-position: center; background-size: cover;">
    <div class="box-body" style="opacity:0.9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Receipt Number - <?= $receipt->receipt_number ?>
                </h3>
            </div>
            <div class="panel-body">
                <table class="table " style="margin: 0 auto;">
                    <tr>
                        <th>User</th>
                        <td><?= $receipt->username ?></td>

                        <th>FullName</th>
                        <td><?= $receipt->full_name ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Payment Method</th>
                        <td><?= $paymentMethod ?></td>

                        <th>Application Period</th>
                        <td><?= $applicationPeriod ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Registration</th>
                        <td><?= $registration ?></td>
                        <th>Total</th>
                        <td>$<?= $receiptTotal ?>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td><?= $receipt->email ?></td>

                        <th>Publish Count</th>
                        <td><?= $receipt->publish_count ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Date Paid</th>
                        <td>
                            <?= date_format(new \DateTime($receipt->date_paid), "F j, Y") ?>
                        </td>

                        <th>Notes</th>
                        <td><?= $receipt->notes ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="box-body" style="opacity:0.8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"> Billings</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped" style="margin: 0 auto;">
                    <tr>
                        <th>Type</th>
                        <th>Cost</th>
                        <th>Amount Paid</th>
                    </tr>
                    <?php foreach ($billings as $billing) : ?>
                        <tr>
                            <td>
                                <?= $billing->getBillingCharge()->one()
                                    ->getBillingType()->one()->name;
                                ?>
                            </td>
                            <td><?= $billing->cost ?></td>
                            <td><?= $billing->amount_paid ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>