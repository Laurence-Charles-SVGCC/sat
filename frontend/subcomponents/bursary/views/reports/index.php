<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Reports Panel';

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="row">
    <div class="col-sm-4 col-md-4">
        <div class="thumbnail" style="min-height:100px">
            <div class="caption text-center">
                <?=
                    Html::a(
                        '<h3>Receipts Report</h3>',
                        Url::toRoute(['receipts-by-date'])
                    );
                ?>
                <p>
                    Generate date constrained receipts report.
                </p>
            </div>
        </div>
    </div>

    <div class="col-sm-4 col-md-4">
        <div class="thumbnail" style="min-height:100px">
            <div class="caption text-center">
                <?=
                    Html::a(
                        '<h3>Billings Report</h3>',
                        Url::toRoute(['billings-by-date'])
                    );
                ?>
                <p>
                    Generate date constrained billings report.
                </p>
            </div>
        </div>
    </div>

    <div class="col-sm-4 col-md-4">
        <div class="thumbnail" style="min-height:100px">
            <div class="caption text-center">
                <?=
                    Html::a(
                        '<h3>Enrollment Payments</h3>',
                        Url::toRoute(['enrolment-payments-by-programme'])
                    );
                ?>
                <p>
                    Generate listing of students by programme and their
                    enrollment payments record.
                </p>
            </div>
        </div>
    </div>
</div>