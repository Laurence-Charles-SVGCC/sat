<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Find Account ';

$this->params["breadcrumbs"][] =
  ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<?php $form = ActiveForm::begin(['id' => 'bursary-account-search-by-name-form']) ?>
<?=
  $form->field($model, 'first_name')
    ->label(false)
    ->textInput(
      [
        "class" => "form-control",
        "placeholder" => "Enter firstname..."
      ]
    );
?>

<?=
  $form->field($model, 'last_name')
    ->label(false)
    ->textInput(
      [
        "class" => "form-control",
        "placeholder" => "...and or last name"
      ]
    );
?>

<?=
  Html::submitButton(
    "<i class='fa fa-search'></i>",
    [
      "id" => "bursary-account-search-by-name-form-submit-button",
      "class" => "btn btn-success pull-right"
    ]
  );
?>
<?php ActiveForm::end(); ?>