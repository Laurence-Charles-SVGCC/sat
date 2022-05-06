<?php

use dosamigos\datepicker\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = "Modify Payment";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Find Account", "url" => ["profiles/search"]];

$this->params["breadcrumbs"][] =
    [
        "label" => $customerFullName,
        "url" => [
            "profiles/redirect-to-customer-profile",
            "username" => $customerUsername
        ]
    ];

$this->params["breadcrumbs"][] =
    [
        "label" => "View Receipt",
        "url" => [
            "profiles/redirect-to-customer-profile",
            "id" => $receiptId,
            "username" => $customerUsername
        ]
    ];

$this->params["breadcrumbs"][] = $this->title;
?>

<div id="modify-payment-form" class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span>Modify Receipt# <?= $receiptNumber ?></span>
        </h3>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>

        <?=
        $form->field($paymentReceiptForm, "username")
            ->textInput(["class" => "form-control", "readonly" => true]);
        ?>

        <?=
        $form->field($paymentReceiptForm, "fullName")
            ->textInput(["class" => "form-control", "readonly" => true]);
        ?>

        <?=
        $form->field($paymentReceiptForm, 'datePaid')
            ->widget(
                DatePicker::class,
                [
                    'inline' => false,
                    'template' => '{addon}{input}',
                    'clientOptions' =>
                    ['autoclose' => true, 'format' => 'yyyy-mm-dd']
                ]
            );
        ?>

        <?=
        $form->field($paymentReceiptForm, "paymentMethodId")
            ->inline()
            ->radioList(
                $paymentMethods,
                ["onClick" => "toggleChequeNumberField()"]
            );
        ?>

        <?php if ($paymentReceiptForm->chequeNumber == true) : ?>
            <div id="cheque-number-field" style="display:block">
                <?=
                $form->field($batchStudentFeePaymentForm, "chequeNumber")
                    ->textInput(["class" => "form-control"]);
                ?>
            </div>
        <?php else : ?>
            <div id="cheque-number-field" style="display:none">
                <?=
                $form->field($paymentReceiptForm, "chequeNumber")
                    ->textInput(["class" => "form-control"]);
                ?>
            </div>
        <?php endif; ?>

        <br />
        <table class="table table-striped">
            <tr>
                <th></th>
                <th>Fee</th>
                <th>Cost</th>
                <th>Amt. Received</th>
            </tr>


            <?php foreach ($billingForms as $key => $value) : ?>
                <tr>
                    <td width="5%">
                        <?=
                        $form->field(
                            $billingForms[$key],
                            "[{$key}]isActive"
                        )
                            ->checkbox(['label' => false]);
                        ?>
                    </td>

                    <td width="45%">
                        <?=
                        $form->field(
                            $billingForms[$key],
                            "[{$key}]fee"
                        )
                            ->label(false)
                            ->textInput(["readonly" => true]);
                        ?>
                    </td>

                    <td width="25%">
                        <?=
                        $form->field(
                            $billingForms[$key],
                            "[{$key}]balance"
                        )
                            ->label(false)
                            ->textInput(["readonly" => true]);
                        ?>
                    </td>

                    <td width="25%">
                        <?=
                        $form->field(
                            $billingForms[$key],
                            "[{$key}]amountPaid"
                        )
                            ->label(false)
                            ->textInput();
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>


        <?=
        Html::submitButton(
            "Add",
            ["class" => "btn btn-success pull-right"]
        );
        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    function toggleChequeNumberField() {
        const paymentMethodField =
            document.getElementById("paymentreceiptform-paymentmethodid");

        if (paymentMethodField === null) {
            //do nothing
        } else {
            let chequeNumberField =
                document.getElementById("cheque-number-field");
            let selectedIndex = paymentMethodField.selectedIndex;
            var radio = paymentMethodField.getElementsByTagName("input");
            var label = paymentMethodField.getElementsByTagName("label");
            let selectedValue = null;
            for (var i = 0; i < radio.length; i++) {
                if (radio[i].checked) {
                    selectedValue = label[i].innerHTML;
                }
            }

            if (selectedValue.includes("Cheque")) {
                chequeNumberField.style.display = "block";
            } else {
                chequeNumberField.style.display = "none";
                document.getElementById(
                    "paymentreceiptform-chequeNumber"
                ).value = null;
            }
        }
    }
</script>