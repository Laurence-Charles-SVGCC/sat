<?php

use dosamigos\datepicker\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = "Amendment Payment";

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
                "id" => "add-subsequent-amendment-payment-to-applicant-profile-form",
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
            $form->field($applicantAmendmentPaymentForm, "receiptNumber")
                ->textInput(["class" => "form-control"]);
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
                    "id" => "add-subsequent-amendment-payment-to-applicant-profile-form-submit-button",
                    "class" => "btn btn-success pull-right"
                ]
            );
        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>