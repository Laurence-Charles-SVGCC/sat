<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\ApplicationPeriod;
    use frontend\models\Division;

    /* @var $this yii\web\View */
    /* @var $dataProvider yii\data\ActiveDataProvider */


    $this->title = "Package Management Dashboard";
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
            
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>

            <img style="display: block; margin: auto;" src ="<?=Url::to('../images/under_construction.jpg');?>" alt="Under Construction">
        </div>
    </div>
</div>

