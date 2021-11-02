<?php

use yii\helpers\Url;
?>

<div class="box-body">
    <div class="panel panel-default">
        <div class="panel-heading">
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
                <strong>Date:</strong> <?= date_format(new \DateTime($receipt->date_paid), "F j, Y") ?><br />
                <strong>ApplicantID:</strong> <?= $applicantId ?><br />
                <strong>Name:</strong> <?= $applicantName ?><br />
            </div>
            <hr>

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
                    <td style="text-align: right;"><?= $total ?></td>
                </tr>
            </table>
        </div><br />

        <div class="panel-footer">
            <?php if (stripos(Url::home(true), "localhost") == false) : ?>
                <img src="https://sat.svgcc.vc/images/college_stamp.png" alt="college_stamp" class="img-rounded" style="width:189px; height:74px" />
            <?php else : ?>
                <img src="http://localhost:8888/sat_dev/frontend/web/img/college_stamp.png" alt="college_stamp" class="img-rounded" style="width:189px; height:74px" />
            <?php endif; ?>
        </div>
    </div>
</div>