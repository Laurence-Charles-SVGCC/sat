<?php

use dosamigos\datepicker\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <strong>Application Amendment Payment</strong>
        </h3>
    </div>
    <div class="panel-body">
        <?php
        $form =
            ActiveForm::begin([
                "id" => "bursary-application-amendment-payment-form",
                "action" =>
                Url::to(
                    [
                        "completed-applicant-payments/process-application-amendment-payment-form",
                        "username" => $username
                    ]
                )
            ]);
        ?>

        <?=
            $form->field($applicantAmendmentPaymentForm, "username")
                ->textInput(["class" => "form-control", "readonly" => true]);
        ?>

        <?=
            $form->field($applicantAmendmentPaymentForm, "fullName")
                ->textInput(["class" => "form-control", "readonly" => true]);
        ?>

        <?=
            $form->field($applicantAmendmentPaymentForm, "amount")
                ->textInput(["class" => "form-control", "readonly" => true]);
        ?>

        <?=
            $form->field($applicantAmendmentPaymentForm, 'datePaid')
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
            $form->field($applicantAmendmentPaymentForm, "paymentMethodId")
                ->inline()
                ->radioList(
                    $paymentMethods,
                    ["onClick" => "toggleChequeNumberField()"]
                );
        ?>

        <?php if ($applicantAmendmentPaymentForm->cheque_number == true) : ?>
            <div id="cheque-number-field" style="display:block">
                <?=
                    $form->field(
                        $applicantAmendmentPaymentForm,
                        "cheque_number"
                    )
                        ->textInput(["class" => "form-control"]);
                ?>
            </div>
        <?php else : ?>
            <div id="cheque-number-field" style="display:none">
                <?=
                    $form->field(
                        $applicantAmendmentPaymentForm,
                        "cheque_number"
                    )
                        ->textInput(["class" => "form-control"]);
                ?>
            </div>
        <?php endif; ?>


        <?=
            $form->field($applicantAmendmentPaymentForm, "autoPublish")
                ->inline()
                ->radioList([0 => "No", 1 => "Yes"]);
        ?>

        <?=
            Html::submitButton(
                "Add",
                [
                    "id" => "bursary-application-amendment-form-submit-button",
                    "class" => "btn btn-success pull-right"
                ]
            );
        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    function toggleChequeNumberField() {
        const paymentMethodField =
            document.getElementById("applicationamendmentpaymentform-paymentmethodid");

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
                    "applicationamendmentpaymentform-cheque_number"
                ).value = null;
            }
        }
    }
</script>