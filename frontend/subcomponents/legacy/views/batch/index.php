<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;

     $this->title = 'Batch Listing';
     $this->params['breadcrumbs'][] = $this->title;
     
?>


<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/legacy/legacy/index']);?>" title="Manage Legacy Records">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/legacy.png" alt="legacy avatar">
                <span class="custom_module_label" > Welcome to the Legacy Management System</span> 
                <img src ="css/dist/img/header_images/legacy.png" alt="legacy avatar" class="pull-right">
            </a>  
        </div>
        
        
        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title;?></h1>
            
            <p>
                <?php if (true/*Yii::$app->user->can('createLegacySubject')*/): ?>
                    <?= Html::a(' Create Batch', ['batch/create'], ['class' => 'btn btn-info pull-right glyphicon glyphicon-plus', 'style' => 'margin-right:5%;']) ?>
                <?php endif; ?>
            </p>
            
             <br/>
            <div style="width:98%; margin: 0 auto;">
                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'name',
                                'format' => 'text',
                                'label' => 'Subject Name'
                            ],
                            [
                                'attribute' => 'type',
                                'format' => 'text',
                                'label' => 'Subject Type'
                            ],
                            [
                                'attribute' => 'count',
                                'format' => 'text',
                                'label' => 'No. of Batches'
                            ],
                        ],
                    ]); 
               ?>
            </div>
        </div>
    </div>
</div>