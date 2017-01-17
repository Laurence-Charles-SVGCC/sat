<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;

    use frontend\models\Employee;
    use common\models\User;

    $this->title = 'Transactions';
    $this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['find-current-applicant', 'status' => $status]];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/payments/payments/find-applicant-or-student', 'status' => $status, 'new_search' => 1]);?>" title="Find Applicant">
        <h1><?= $this->title?></h1>
    </a>
</div>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
     <div class="box-header with-border">
         <span class="box-title"><?= $heading?></span>
         <?php if(Yii::$app->user->can('CreateTransaction')):?>
            <!--<a class="btn btn-info pull-right" href="#" role="button"> Create</a>-->
                <div class='dropdown pull-right'>
                    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                       Enter New Payment...
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/payments/transactions/create-full-payment', 'personid' => $id]);?>">
                                Enter New Full Payment
                            </a>
                        </li>
                         <li>
                            <a href="<?= Url::toRoute(['/subcomponents/payments/transactions/create-part-payment', 'personid' => $id]);?>">
                                Enter New Partial Payment
                            </a>
                        </li>
                         <li>
                            <a href="<?= Url::toRoute(['/subcomponents/payments/transactions/pay-outstanding', 'personid' => $id]);?>">
                               Pay Outstanding Charge
                            </a>
                        </li>
                    </ul>
              </div>
        <?php endif;?>
    </div>
    
    <div class =" box-body">
        <table class="table table-hover">
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'format' => 'html',
                            'label' => 'Receipt#',
                            'value' => function($row, $status)
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
                            'label' => 'Semester'
                        ],
//                        [
//                            'format' => 'html',
//                            'label' => 'Linked Payments',
//                            'value' => function($row)
//                                {
//                                   return Html::a($row['transaction_group_id'], 
//                                           Url::to(['payments/view-transactions', 'transactionsummaryid' => $row['transaction_group_id']]));
//                                }
//                        ],
                        [
                            'attribute' => 'comments',
                            'format' => 'text',
                            'label' => 'Notes'
                        ],
                        [
                            'attribute' => 'total_paid',
                            'format' => 'text',
                            'label' => 'Total Paid'
                        ],
                        [
                            'attribute' => 'balance',
                            'format' => 'text',
                            'label' => 'Balance'
                        ],
                    ],
                ]); 
            ?>
        </table>
    </div>
</div>
