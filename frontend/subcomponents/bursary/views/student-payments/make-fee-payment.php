<?php

use dosamigos\datepicker\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = "Pay {$fee}";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Find Account", "url" => ["profiles/search"]];

$this->params["breadcrumbs"][] =
    [
        "label" => $userFullname,
        "url" => ["profiles/redirect-to-customer-profile", "username" => $username]
    ];

$this->params["breadcrumbs"][] =
    [
        "label" => "Enrollment Payments",
        "url" => [
            "student-payments/scheduled-fee-report",
            "username" => $username,
            "studentRegistrationId" => $studentRegistrationId
        ]
    ];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="panel panel-default">
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
        <?=
            $form->field($model, "username")
                ->textInput(["class" => "form-control", "readonly" => true]);
        ?>

        <?=
            $form->field($model, "fullName")
                ->textInput(["class" => "form-control", "readonly" => true]);
        ?>

        <?=
            $form->field($model, "balance")
                ->label("Amount Due")
                ->textInput(["class" => "form-control", "readonly" => true]);
        ?>

        <?=
            $form->field($model, "amountPaid")
                ->label("Payment")
                ->textInput(["class" => "form-control"]);
        ?>

        <?=
            $form->field($model, 'datePaid')
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
            $form->field($model, "paymentMethodId")
                ->inline()
                ->radioList($paymentMethods);
        ?>

        <?= Html::submitButton("Add", ["class" => "btn btn-success pull-right"]); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>