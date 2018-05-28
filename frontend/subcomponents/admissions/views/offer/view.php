<?php
    use yii\helpers\Html;
    use yii\widgets\DetailView;
    use yii\helpers\Url;


    $this->title = $model->offerid;
    $this->params['breadcrumbs'][] = ['label' => 'Offers', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="offer-view">
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

            <p>
              <?php if (Yii::$app->user->can('updateOffer')): ?>  
                <?= Html::a('Update', ['update', 'id' => $model->offerid], ['class' => 'btn btn-primary']) ?>
              <?php endif; ?>
              <?php if (Yii::$app->user->can('deleteOffer')): ?>  
                <?= Html::a('Revoke', ['delete', 'id' => $model->offerid], [
                    'class' => 'btn btn-danger']) ?>
               <?php endif; ?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'offerid',
                    'applicationid',
                    'issuedby',
                    'issuedate',
                    'revokedby',
                    'revokedate',
                    'ispublished:boolean',
                ],
            ]) ?>
        </div>
    </div>
</div>
