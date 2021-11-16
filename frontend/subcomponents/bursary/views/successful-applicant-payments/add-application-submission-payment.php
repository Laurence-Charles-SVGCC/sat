<?php

use dosamigos\datepicker\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = "Application Submission Payment";

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

<div class="panel panel-default">
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>

        <?=
            $form->field($applicantSubmissionPaymentForm, "username")
                ->textInput(["class" => "form-control", "readonly" => true]);
        ?>

        <?=
            $form->field($applicantSubmissionPaymentForm, "fullName")
                ->textInput(["class" => "form-control", "readonly" => true]);
        ?>

        <?=
            $form->field($applicantSubmissionPaymentForm, "amount")
                ->textInput(["class" => "form-control", "readonly" => true]);
        ?>

        <?=
            $form->field($applicantSubmissionPaymentForm, 'datePaid')
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
            $form->field($applicantSubmissionPaymentForm, "paymentMethodId")
                ->inline()
                ->radioList(
                    $paymentMethods,
                    ["onClick" => "toggleChequeNumberField()"]
                );
        ?>

        <?php if ($applicantSubmissionPaymentForm->cheque_number == true) : ?>
            <div id="cheque-number-field" style="display:block">
                <?=
                    $form->field($applicantSubmissionPaymentForm, "cheque_number")
                        ->textInput(["class" => "form-control"]);
                ?>
            </div>
        <?php else : ?>
            <div id="cheque-number-field" style="display:none">
                <?=
                    $form->field($applicantSubmissionPaymentForm, "cheque_number")
                        ->textInput(["class" => "form-control"]);
                ?>
            </div>
        <?php endif; ?>

        <?=
            $form->field($applicantSubmissionPaymentForm, "includeAmendmentFee")
                ->inline()
                ->radioList([0 => "No", 1 => "Yes"]);
        ?>

        <?=
            $form->field($applicantSubmissionPaymentForm, "autoPublish")
                ->inline()
                ->radioList([0 => "No", 1 => "Yes"]);
        ?>

        <?=
            Html::submitButton(
                "Add",
                [
                    "id" => "bursary-application-submission-form-submit-button",
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
            document.getElementById("applicationsubmissionpaymentform-paymentmethodid");

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
                    "applicationsubmissionpaymentform-cheque_number"
                ).value = null;
            }
        }
    }
</script>