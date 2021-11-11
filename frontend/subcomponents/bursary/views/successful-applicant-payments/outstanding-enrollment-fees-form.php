<?php

use dosamigos\datepicker\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div id="outstanding-enrollment-fees-form" class="panel panel-default" style="display:none">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span>Process Outstanding Fees</span>
        </h3>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>

        <?=
            $form->field($batchStudentFeePaymentForm, "username")
                ->textInput(["class" => "form-control", "readonly" => true]);
        ?>

        <?=
            $form->field($batchStudentFeePaymentForm, "fullName")
                ->textInput(["class" => "form-control", "readonly" => true]);
        ?>

        <?=
            $form->field($batchStudentFeePaymentForm, 'datePaid')
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
            $form->field($batchStudentFeePaymentForm, "paymentMethodId")
                ->inline()
                ->radioList($paymentMethods);
        ?>

        <br />
        <table class="table table-striped">
            <tr>
                <th></th>
                <th>Fee</th>
                <th>Balance</th>
                <th>Amt. Received</th>
            </tr>


            <?php foreach ($batchStudentFeePaymentBillingForms as $key => $value) : ?>
                <tr>
                    <td width="5%">
                        <?=
                            $form->field(
                                $batchStudentFeePaymentBillingForms[$key],
                                "[{$key}]isActive"
                            )
                                ->checkbox(['label' => false]);
                        ?>
                    </td>

                    <td width="45%">
                        <?=
                            $form->field(
                                $batchStudentFeePaymentBillingForms[$key],
                                "[{$key}]fee"
                            )
                                ->label(false)
                                ->textInput(["readonly" => true]);
                        ?>
                    </td>

                    <td width="25%">
                        <?=
                            $form->field(
                                $batchStudentFeePaymentBillingForms[$key],
                                "[{$key}]balance"
                            )
                                ->label(false)
                                ->textInput(["readonly" => true]);
                        ?>
                    </td>

                    <td width="2f%">
                        <?=
                            $form->field(
                                $batchStudentFeePaymentBillingForms[$key],
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