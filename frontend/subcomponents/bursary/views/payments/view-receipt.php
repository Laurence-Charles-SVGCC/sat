<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "View Receipt";

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

<div class="box box-primary">
    <div class="dropdown pull-right">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="text-align: left;width:100%;">
            Select action...
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <li>
                <?=
                    Html::a(
                        "View PDF",
                        ["view-receipt-pdf", "receiptId" => $receipt->id],
                        ["target" => "blank"]
                    );
                ?>
            </li>
            <li>
                <?=
                    Html::a(
                        "Publish",
                        ["publish-receipt", "receiptId" => $receipt->id]
                    );
                ?>
            </li>
            <li>
                <?=
                    Html::a(
                        "Delete",
                        ["delete-receipt", "receiptId" => $receipt->id],
                        [
                            "title" => "Delete Receipt",
                            "data" => [
                                "method" => "post",
                                "confirm" => "Are you sure? This will delete item.",
                            ]
                        ]
                    ); ?>
            </li>
        </ul>
    </div><br /><br />

    <div class="box-body">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Receipt Number - <?= $receipt->receipt_number ?>
                </h3>
            </div>
            <div class="panel-body">
                <table class="table table-hover" style="margin: 0 auto;">
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
    </div><br />

    <div class="box-body">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"> Billings</h3>
            </div>
            <div class="panel-body">
                <table class="table table-hover" style="margin: 0 auto;">
                    <tr>
                        <th>Type</th>
                        <th>Cost</th>
                        <th>Amount Paid</th>
                        <th>Action</th>
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
                            <td>
                                <?php
                                $form =
                                    ActiveForm::begin([
                                        "id" => "delete-billing-{$billing->id}-form",
                                        "action" =>
                                        Url::to([
                                            "delete-billing",
                                            "billingId" => $billing->id
                                        ])
                                    ]);
                                ?>
                                <?=
                                    Html::submitButton(
                                        "Delete",
                                        [
                                            "id" =>
                                            "delete-billing-{$billing->id}-form-submit-button",
                                            "class" => "btn btn-danger"
                                        ]
                                    );
                                ?>
                                <?php ActiveForm::end(); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>