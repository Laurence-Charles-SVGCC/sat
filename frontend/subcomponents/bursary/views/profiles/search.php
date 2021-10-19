<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Find Account';

$this->params["breadcrumbs"][] =
  ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<?php $form = ActiveForm::begin(['id' => 'bursary-account-search-form']) ?>
<?=
  $form->field($model, 'id')
    ->label(false)
    ->textInput(
      [
        "class" => "form-control",
        "placeholder" => "Enter ApplicantID or StudentID"
      ]
    );
?>

<?=
  Html::submitButton(
    "<i class='fa fa-search'></i>",
    [
      "id" => "bursary-account-search-form-submit-button",
      "class" => "btn btn-success pull-right"
    ]
  );
?>
<?php ActiveForm::end(); ?>