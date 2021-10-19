<?php

use dosamigos\datepicker\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <strong>Application Submission Payment</strong>
        </h3>
    </div>
    <div class="panel-body">
        <?php
        $form =
            ActiveForm::begin([
                "id" => "bursary-application-submission-payment-form",
                "action" =>
                Url::to(
                    [
                        "completed-applicant-payments/process-application-submission-payment-form",
                        "username" => $username
                    ]
                )
            ]);
        ?>

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
            $form->field($applicantSubmissionPaymentForm, "receiptNumber")
                ->textInput(["class" => "form-control"]);
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
                ->radioList($paymentMethods);
        ?>

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