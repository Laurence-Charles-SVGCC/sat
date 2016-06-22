<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
     use yii\helpers\Url;

    $this->title = 'Co-ordinator Dashboard';
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/programmes/cordinators/index']);?>" title="Manage Co-ordinators">     
                    <img class="custom_logo_students" src ="<?=Url::to('../images/programme.png');?>" alt="scroll avatar">
                    <span class="custom_module_label" > Welcome to the Co-ordinator Management System</span> 
                    <img src ="<?=Url::to('../images/programme.png');?>" alt="scroll avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$this->title?></h1>
                
                 <p>
                    <?php if (Yii::$app->user->can('powerCordinator')): ?>
                        <?= Html::a(' Assign Co-ordinator', ['create'], ['class' => 'btn btn-info pull-right glyphicon glyphicon-plus', 'style' => 'margin-right:5%;']) ?>
                    <?php endif; ?>
                </p>
                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'title',
                                'format' => 'text',
                                'label' => 'Title'
                            ],
                            [
                                'attribute' => 'firstname',
                                'format' => 'text',
                                'label' => 'First Name'
                            ],
                            [
                                'attribute' => 'lastname',
                                'format' => 'text',
                                'label' => 'Last Name'
                            ],
                             [
                                'attribute' => 'cordinatortype',
                                'format' => 'text',
                                'label' => 'Cordinator Type'
                            ],
                            [
                                'attribute' => 'details',
                                'format' => 'text',
                                'label' => 'Details'
                            ],
                            [
                                'attribute' => 'academicyear',
                                'format' => 'text',
                                'label' => 'Academic Year'
                            ],
                            [
                                'attribute' => 'isserving',
                                'format' => 'boolean',
                                'label' => 'Serving'
                            ],
                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); 
               ?>
            </div>
        </div>
</div>