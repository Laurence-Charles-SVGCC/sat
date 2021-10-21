<?php

use dosamigos\datepicker\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = "Application Amendment Payment";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Find Account", "url" => ["profiles/search"]];

$this->params["breadcrumbs"][] =
    [
        "label" => $userFullname,
        "url" => ["profiles/successful-applicant-profile", "username" => $username]
    ];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="panel panel-default">
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>

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