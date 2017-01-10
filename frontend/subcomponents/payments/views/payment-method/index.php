<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    
    use frontend\models\PaymentMethod;

    $this->title = 'Payment Methods';
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/payments/payment-method/index']);?>" title="Payment Method Home">
        <h1>Welcome to the Payment Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
     <div class="box-header with-border">
         <span class="box-title"><?= $this->title?></span>
         <?php if(Yii::$app->user->can('createPaymentMethod')):?>
            <a class="btn btn-info pull-right" href=<?=Url::to(['configure-payment-method', 'action' => 'create']);?> role="button"> Create</a>
        <?php endif;?>
    </div>
    
    <table class="table table-hover">
        <?php if($dataProvider == false):?>
            <tr>
                <td>No payment methods have been created</td>
            </tr>
        <?php else:?>
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
    </table>
</div>
