<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = "Receipt Preview";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Find Account", "url" => ["profiles/search"]];

$this->params["breadcrumbs"][] =
    [
        "label" => $applicantName,
        "url" => [
            "profiles/redirect-to-customer-profile",
            "username" => $applicantId
        ]
    ];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="dropdown pull-right">
    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="text-align: left;width:100%;">
        Select action...
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li>
            <?=
                Html::a(
                    "Approve and Publish",
                    ["approve-and-publish-receipt", "receiptId" => $receipt->id]
                );
            ?>
        </li>
        <li>
            <?=
                Html::a(
                    "Redo",
                    [
                        "redo-receipt",
                        "receiptId" => $receipt->id,
                        "studentRegistrationId" => $studentRegistrationId
                    ],
                    [
                        "title" => "Redo Receipt",
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
        <div>
            <?php if (stripos(Url::home(true), "localhost") == false) : ?>
                <img src="https://sat.svgcc.vc/images/email_header.png" alt="email_header" class="img-rounded" style="max-width:700px; max-height:150px" />
            <?php else : ?>
                <img src="http://localhost:8888/sat_dev/frontend/web/img/email_header.png" alt="email_header" class="img-rounded" style="max-width:700px; max-height:150px" />
            <?php endif; ?>
        </div><br />

        <div class="panel-body">
            <div>
                SVGCC Payment Receipt<br />
                Receipt # <?= $receipt->receipt_number; ?><br />
                Tel:1-784-457-4503<br /><br />
                St. Vincent and the Grenadines Community College<br />
                PO Box 829<br />
                Villa<br />
                St. Vincent and the Grenadines<br />
            </div><br />

            <div>
                <strong>Operator:</strong> <?= $operator ?><br />
                <strong>Date:</strong> <?= date_format(new \DateTime($receipt->date_paid), "F j, Y") ?><br />
                <strong>ApplicantID:</strong> <?= $applicantId ?><br />
                <strong>Name:</strong> <?= $applicantName ?><br />
            </div><br />

            <table class="table table-hover" style="text-align:left">
                <tr>
                    <td><strong>Billing</strong></td>
                    <td><strong>Cost</strong></td>
                    <td><strong>Paid</strong></th>
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

                <tr>
                    <td><strong>Total</strong></td>
                    <td></td>
                    <td><?= $total ?></td>
                </tr>
            </table>

            <?php if (stripos(Url::home(true), "localhost") == false) : ?>
                <img src="https://sat.svgcc.vc/images/college_stamp.png" alt="college_stamp" class="img-rounded" style="width:189px; height:74px" />
            <?php else : ?>
                <img src="http://localhost:8888/sat_dev/frontend/web/img/college_stamp.png" alt="college_stamp" class="img-rounded" style="width:189px; height:74px" />
            <?php endif; ?>
        </div><br />
    </div>
</div>