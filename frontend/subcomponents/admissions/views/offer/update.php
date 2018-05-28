<?php
    use yii\helpers\Html;
    use yii\helpers\Url;

    $this->title = 'Update Offer: ' . ' ' . $model->offerid;
    $this->params['breadcrumbs'][] = ['label' => 'Offers', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->offerid, 'url' => ['view', 'id' => $model->offerid]];
    $this->params['breadcrumbs'][] = 'Update';
?>
<div class="offer-update">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1><?= Html::encode($this->title) ?></h1>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
