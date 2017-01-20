<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\TransactionItem;
    use frontend\models\TransactionType;
    use frontend\models\PaymentMethod;
    use frontend\models\Semester;

    $this->title = "Enter Multiple Payments";
    $this->params['breadcrumbs'][] = ['label' => 'Find'. ' ' .  ucwords($status), 'url' => ['payments/find-applicant-or-student', 'status' => $status, 'new_search' => 1]];
    $this->params['breadcrumbs'][] = ['label' => 'View Transactions', 'url' => ['payments/view-user-transactions', 'id' => $id, 'status' => $status]];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/payments/payments/find-applicant-or-student', 'status' => $status, 'new_search' => 1]);?>" title="Find Applicant">
        <h1><?= $this->title ?></h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
     <div class="box-header with-border">
         <span class="box-title">New Payment(s): <?= $name . "(" . $username . ")"?></span>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Payment Type</th>
                        <th>Payment Method</th>
                        <th>Semester</th>
                        <th>Date</th>
                        <th>Amt. Due</th>
                        <th>Payment</th>
                        <th>Comments</th>
                    </tr>
                </thead>

                <tbody>
                    <?php for ($j=0 ; $j<$count ; $j++): ?>
                        <tr>
                            <td>
                                <?= $form->field($transactions[$j], "[$j]transactionitemid")
                                        ->label('')
                                        ->dropDownList( ArrayHelper::map(TransactionItem::find()->all(), 'transactionitemid', 'name'), [ 'prompt'=>'Select...',]) 
                                ?>
                            </td>

                            <td>
                                <?= $form->field($transactions[$j], "[$j]transactiontypeid")
                                        ->label('')
                                        ->dropDownList( ArrayHelper::map(TransactionType::find()->all(), 'transactiontypeid', 'name'), [ 'prompt'=>'Select...',]) 
                                ?>
                            </td>

                            <td>
                                <?= $form->field($transactions[$j], "[$j]paymentmethodid")
                                        ->label('')
                                        ->dropDownList( ArrayHelper::map(PaymentMethod::find()->all(), 'paymentmethodid', 'name'), [ 'prompt'=>'Select...',]) 
                                ?>
                            </td>

                            <td>
                                <?= $form->field($transactions[$j], "[$j]semesterid")
                                        ->label('')
                                        ->dropDownList( ArrayHelper::map(Semester::associativeSemesterListing(), 'semesterid', 'title'), [ 'prompt'=>'Select...']) 
                                ?>
                            </td>

                            <td>
                                <?= $form->field($transactions[$j], "[$j]paydate")
                                        ->label('')
                                        ->widget(
                                            DatePicker::className(), [
                                                'inline' => false,
                                                'template' => '{addon}{input}',
                                                'clientOptions' => [
                                                    'autoclose' => true,
                                                    'format' => 'yyyy-mm-dd',
                                                    "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"
                                                ]
                                        ]); 
                                ?>
                            </td>

                            <td><?=$form->field($transactions[$j], "[$j]totaldue")->label('')->textInput()?></td>

                            <td><?=$form->field($transactions[$j], "[$j]paymentamount")->label('')->textInput()?></td>

                            <td><?=$form->field($transactions[$j], "[$j]comments")->label('')->textArea(["rows" => 1])?></td>
                     <?php endfor; ?>
                </tbody>
            </table>
        </div>

        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['payments/view-user-transactions', 'id' => $id, 'status' => $status], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>