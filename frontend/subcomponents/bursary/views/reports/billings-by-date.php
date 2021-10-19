<?php

use dosamigos\datepicker\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Billings By Date';

$this->params["breadcrumbs"][] =
  ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
  ["label" => "Report", "url" => ["reports/index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<?php $form = ActiveForm::begin(['id' => 'billings-by-date-search-form']) ?>
<?=
  $form->field($model, 'startDate')
    ->widget(
      DatePicker::class,
      [
        'inline' => false,
        'template' => '{addon}{input}',
        'clientOptions' =>
        ['autoclose' => true, 'format' => 'yyyy-mm-dd']
      ]
    );
?><br />

<?=
  $form->field($model, 'endDate')
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
  Html::submitButton(
    "<i class='fa fa-search'></i>",
    [
      "id" => "billings-by-date-search-form-submit-button",
      "class" => "btn btn-success pull-right"
    ]
  );
?>
<?php ActiveForm::end(); ?>