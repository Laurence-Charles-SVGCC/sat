<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;

     $this->title = 'Student Listing';
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
                <?php if (true/*Yii::$app->user->can('powerCordinator')*/): ?>
                    <?= Html::a(' Create Student', ['student/choose-create'], ['class' => 'btn btn-info pull-right glyphicon glyphicon-plus', 'style' => 'margin-right:5%;']) ?>
                <?php endif; ?>
            </p>
            
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'format' => 'html',
                            'label' => 'Full Name',
                            'value' => function($row)
                            {
                                return Html::a($row['fullname'], 
                                                        Url::to(['student/view', 
                                                                  'id' => $row['studentid']
                                                              ])
                                                        );
                            }
                        ],
                        [
                            'attribute' => 'dateofbirth',
                            'format' => 'text',
                            'label' => 'Date of Birth'
                        ],
                        [
                            'attribute' => 'gender',
                            'format' => 'text',
                            'label' => 'Gender'
                        ],
                         [
                            'attribute' => 'address',
                            'format' => 'text',
                            'label' => 'Address'
                        ],
                        [
                            'attribute' => 'admissionyear',
                            'format' => 'text',
                                    'label' => 'Year of Admission'
                        ],
                        [
                            'attribute' => 'faculty',
                            'format' => 'text',
                            'label' => 'Faculty'
                        ],
                    ],
                ]); 
           ?>
        </div>
    </div>
</div>