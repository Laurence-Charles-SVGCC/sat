<?php

    use yii\widgets\Breadcrumbs;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;

    $this->title = "Edit {$applicantname} Contact Details";

    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary">
  <div class="box-header with-border">
    <span class="box-title"><?= $this->title?></span>
  </div>

  <?php $form = ActiveForm::begin();?>
    <div class="box-body">
      <?=
        $form->field($phone, 'homephone')
        ->label('Home Phone*')
        ->textInput()
      ?>

      <?=
        $form->field($phone, 'cellphone')
        ->label('Cell Phone:')
        ->textInput() ?>

      <?=
        $form->field($phone, 'workphone')
        ->label('Work Phone*')
        ->textInput() ?>

      <?=
        $form->field($email, 'email')
        ->label('Email*')
        ->textInput();
      ?>

      <?=
        Html::submitButton(
            'Update',
            [
            'class' => 'btn btn-success pull-right',
            'style' => 'margin-right:20px'
          ]
        );
      ?>
    </div>

<?php ActiveForm::end(); ?>
</div>
