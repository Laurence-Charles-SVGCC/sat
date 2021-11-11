<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = "Enrollment Payments";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Find Account", "url" => ["profiles/search"]];

$this->params["breadcrumbs"][] =
    [
        "label" => $userFullname,
        "url" => ["profiles/redirect-to-customer-profile", "username" => $username]
    ];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<?php if ($outstandingFeesExist === true) : ?>
    <button id="toggle-button" class="btn btn-info btn-md pull-right" onClick="toggleEntryForm()">Display Payments Form</button><br /><br />
    <?=
        $this->render(
            "outstanding-enrollment-fees-form",
            [
                "batchStudentFeePaymentForm" => $batchStudentFeePaymentForm,

                "batchStudentFeePaymentBillingForms" =>
                $batchStudentFeePaymentBillingForms,

                "paymentMethods" => $paymentMethods,
            ]
        );
    ?>
<?php endif; ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title">
            <span>Enrollment Fee Report</span>

        </h2>
    </div>
    <div class="panel-body">
        <?=
            GridView::widget(
                [
                    "dataProvider" => $dataProvider,
                    "columns" => [
                        [
                            "attribute" => "fee",
                            "format" => "text",
                            "label" => "Fee"
                        ],
                        [
                            "attribute" => "cost",
                            "format" => "text",
                            "label" => "Cost"
                        ],
                        [
                            "attribute" => "totalPaid",
                            "format" => "text",
                            "label" => "Paid"
                        ],
                        [
                            "attribute" => "status",
                            "format" => "text",
                            "label" => "Status"
                        ],
                        [
                            "label" => "Action",
                            "format" => "raw",
                            "value" => function ($row) {
                                if ($row["status"] == "Paid In Full") {
                                    return "";
                                } else {
                                    return Html::a(
                                        "Pay",
                                        Url::toRoute([
                                            "make-fee-payment",
                                            "username" => $row["username"],
                                            "billingChargeId" => $row["billingChargeId"]
                                        ]),
                                        ["class" => "btn btn-success"]
                                    );
                                }
                            }
                        ],
                    ],
                ]
            );
        ?>

        <table class="table">
            <tr>
                <th style="text-align: center;">Total Cost: <?= $totalCost ?></th>
                <th style="text-align: center;">Total Paid: <?= $totalPaid ?></th>
                <th style="text-align: center;">Balance Due: <?= $balanceDue ?></th>
            </tr>
        </table>
    </div>
</div>

<script>
    function toggleEntryForm() {
        const toggleButtonExists = !!document.getElementById("toggle-button");
        const toggleButton = document.getElementById("toggle-button");
        const feeForm = document.getElementById("feeForm");

        const paymentFormExists = !!document.getElementById("outstanding-enrollment-fees-form");
        const paymentForm =
            document.getElementById("outstanding-enrollment-fees-form");

        if (toggleButtonExists == true) {
            let content = toggleButton.textContent;
            if (content === "Display Payments Form") {
                toggleButton.textContent = "Hide Form";
                toggleButton.removeAttribute("class");
                toggleButton.setAttribute(
                    "class",
                    "btn btn-danger btn-md pull-right"
                );
                if (paymentFormExists === true) {
                    paymentForm.style.display = "block";
                }

            } else if (content === "Hide Form") {
                toggleButton.textContent = "Display Payments Form";
                toggleButton.removeAttribute("class");
                toggleButton.setAttribute(
                    "class",
                    "btn btn-info btn-md pull-right"
                );
                if (paymentFormExists === true) {
                    paymentForm.style.display = "none";
                }
            }
        }
    }
</script>