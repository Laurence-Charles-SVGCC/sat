<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    
    use frontend\models\PaymentMethod;

    $this->title = 'Payment Methods';
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="payment-method-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/payments/payment-method/index']);?>" title="Payment Method Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/bursary.png" alt="bursary-avatar">
                <span class="custom_module_label">Welcome to the Bursary Management System</span> 
                <img src ="css/dist/img/header_images/bursary.png" alt="bursary-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
            
            </br>                              
            <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em"> Payment Methods Listing
                    <?php if(Yii::$app->user->can('createPaymentMethod')):?>
                        <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::to(['configure-payment-method', 'action' => 'create']);?> role="button"> Create Payment Method</a>
                    <?php endif;?>
                </div>

                <?php if($dataProvider == false):?>
                    <h3>No payment methods have been created</h3>
                <?php else:?>
                    <br/>
                    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                [
                                    'attribute' => 'name',
                                    'format' => 'text',
                                    'label' => 'Name'
                                ],
                                [
                                    'attribute' => 'createdby',
                                    'format' => 'text',
                                    'label' => 'Creator'
                                ],
                                [
                                    'attribute' => 'lastmodifiedby',
                                    'format' => 'text',
                                    'label' => 'Last Modified By'
                                ],
                                [
                                    'attribute' => 'active',
                                    'format' => 'boolean',
                                    'label' => 'Active'
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header'=>'Action',
                                    'headerOptions' => ['width' => '80'],
                                    'template' => '{view} {update} {toggle} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $row) {
                                            return Html::a(
                                                '<span class="glyphicon glyphicon-eye-open"></span>',
                                                Url::to(['payment-method/view', 'id' => $row['id']]),
                                                ['title' => 'View']
                                               );
                                        },
                                        'update' => function ($url, $row) {
                                            return Html::a(
                                                '<span class="glyphicon glyphicon-pencil"></span>',
                                                Url::to(['payment-method/configure-payment-method', 'action' => 'update', 'id' => $row['id']]),
                                                ['title' => 'Update']
                                               );
                                        },
                                        'toggle' => function ($url, $row) {
                                            if ( $row['active'] == 1)
                                            {
                                                return Html::a(
                                                '<span class="glyphicon glyphicon-remove"></span>',
                                                Url::to(['payment-method/toggle', 'id' => $row['id']]),
                                                ['title' => 'De-activate']
                                               );
                                            }
                                            else
                                            {
                                                return Html::a(
                                                    '<span class="glyphicon glyphicon-ok"></span>',
                                                    Url::to(['payment-method/toggle', 'id' => $row['id']]),
                                                    ['title' => 'Re-activate']
                                                   );
                                            }
                                        },
                                        'delete' => function ($url, $row) {
                                            return Html::a(
                                                '<span class="glyphicon glyphicon-trash"></span>',
                                                Url::to(['payment-method/delete', 'id' => $row['id']]),
                                                ['title' => 'Delete']
                                               );
                                        },
                                    ],
                                ],

                            ],
                            'tableOptions' =>['class' => 'table table-condensed table-hover'],
                            'options'=>['class'=>'grid-view gridview-newclass', 'style' => 'font-size:16px'],
                        ]); 
                    ?>
                <?php endif;?>
        </div>
    </div>
</div>
