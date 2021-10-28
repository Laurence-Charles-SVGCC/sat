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
                ->radioList($paymentMethods);
        ?>

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