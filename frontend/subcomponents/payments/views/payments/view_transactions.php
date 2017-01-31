<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;

    use frontend\models\Employee;
    use common\models\User;

    $this->title = 'Transactions';
    $this->params['breadcrumbs'][] = ['label' => 'Find'. ' ' .  ucwords($status), 'url' => ['find-applicant-or-student', 'status' => $status, 'new_search' => 1]];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/payments/payments/find-applicant-or-student', 'status' => $status, 'new_search' => 1]);?>" title="Find Applicant">
        <h1><?= $this->title?></h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
     <div class="box-header with-border">
         <span class="box-title"><?= $heading?></span>
         <?php if(Yii::$app->user->can('CreateTransaction')):?>
                <div class='dropdown pull-right' style="margin-left:2.5%">
                    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                      Create Multiple Transactions...
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                        <?php for($i=2 ; $i<=10 ; $i++) :?>
                            <li>
                                <a href="<?= Url::toRoute(['/subcomponents/payments/transaction/create-multiple-payments', 'personid' => $id, 'status' => $status, 'count' => $i]);?>">
                                   <?= $i ;?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
              </div>
         
                <div class='dropdown pull-right'>
                    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                       Create Single Transaction...
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/payments/transaction/create-full-payment', 'personid' => $id, 'status' => $status]);?>">
                                Full Payment
                            </a>
                        </li>
                         <li>
                            <a href="<?= Url::toRoute(['/subcomponents/payments/transaction/create-part-payment', 'personid' => $id, 'status' => $status]);?>">
                                Partial Payment
                            </a>
                        </li>
                    </ul>
              </div>
        <?php endif;?>
    </div>
    
    <div class =" box-body">
        <table class="table table-hover">
            <?= GridView::widget([
                    'dataProvider' => $finances_dataProvider,
                    'columns' => [
                        [
                            'format' => 'html',
                            'label' => 'Receipt#',
                            'value' => function($row)
                            {
                               return Html::a($row['receiptnumber'], 
                                       Url::to(['payments/get-transaction-receipt', 'receiptnumber' => $row['receiptnumber'], 'status' => $row['status']]));
                            }
                        ],
                        [
                            'attribute' => 'date_paid',
                            'format' => 'text',
                            'label' => 'Date'
                        ],
                        [
                            'attribute' => 'transaction_item',
                            'format' => 'text',
                            'label' => 'Item'
                        ],
                        [
                            'attribute' => 'purpose',
                            'format' => 'text',
                            'label' => 'Purpose'
                        ],
                        [
                            'attribute' => 'payment_method',
                            'format' => 'text',
                            'label' => 'Method'
                        ],
                        [
                            'attribute' => 'type',
                            'format' => 'text',
                            'label' => 'Full/Part'
                        ],
                        [
                            'attribute' => 'academic_year',
                            'format' => 'text',
                            'label' => 'Year'
                        ],
                        [
                            'attribute' => 'academic_semester',
                            'format' => 'text',
                            'label' => 'Sem.'
                        ],
                        [
                            'attribute' => 'comments',
                            'format' => 'text',
                            'label' => 'Notes'
                        ],
                        [
                            'attribute' => 'total_due',
                            'format' => 'text',
                            'label' => 'Due'
                        ],        
                        [
                            'attribute' => 'total_paid',
                            'format' => 'text',
                            'label' => 'Paid'
                        ],
                        [
                            'format' => 'html',
                            'label' => 'Balance',
                            'value' => function($row)
                            {
                                if ($row['type'] == "Partial Payment"  && $row['balance'] > 0)
                                {
                                    return Html::a($row['balance'], 
                                                            Url::toRoute(['/subcomponents/payments/transaction/pay-outstanding', 'personid' => $row['id'], 'status' => $row['status'], 'summaryid' => $row['summaryid']]));
                                }
                                else
                                {
                                    return $row['balance'];
                                }
                            }
                        ],
                        [
                            'format' => 'html',
                            'label' => 'Delete',
                            'value' => function($row)
                            {
                                if ($row['can_delete'] == true)
                                {
                                    return Html::a(' ', 
                                                            Url::toRoute(['/subcomponents/payments/transaction/delete-transaction', 'personid' => $row['id'], 'status' => $row['status'], 'transactionid' => $row['transactionid']]),
                                                             ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                'data' => [
                                                                    'confirm' => 'Are you sure you want to revoke this offer?',
                                                                    'method' => 'post',
                                                                ],
                                                            ]);
                                }
                                else
                                {
                                    return "N/A";
                                }
                            }
                        ],   
                    ],
                ]); 
            ?>
        </table>
    </div>
</div>
