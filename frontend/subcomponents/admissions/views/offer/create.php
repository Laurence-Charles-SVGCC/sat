<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

    $this->title = 'Create Offer';
    $this->params['breadcrumbs'][] = ['label' => 'Offers', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="offer-create">
    <div class = "custom_wrapper">
        
        <div class="custom_body">
            <h1><?= Html::encode($this->title) ?></h1>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
