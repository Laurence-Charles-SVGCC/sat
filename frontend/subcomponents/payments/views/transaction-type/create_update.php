<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;

    $this->title = $action . " Transaction Type";
    $this->params['breadcrumbs'][] = ['label' => 'Transaction Types', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="transaction-type-create-update">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/payments/transaction-type/index']);?>" title="Transaction Type Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/bursary.png" alt="bursary-avatar">
                <span class="custom_module_label">Welcome to the Bursary Management System</span> 
                <img src ="css/dist/img/header_images/bursary.png" alt="bursary-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= $this->title?></h1>
            
            </br>                              
            <?php
                $form = ActiveForm::begin([
                    'id' => 'create-update-transaction-type-form',
                    'options' => [
                        'style' => 'width:80%; margin:0 auto;',
                    ],
                ]);
            ?>

                <table class='table table-hover'>
                    <tr>
                        <th style='width:30%; vertical-align:middle'>Name</th>
                        <td><?=$form->field($transaction_type, 'name')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'style' => 'vertical-align:middle'])?></td>
                    </tr>
                </table><br/>

                <?= Html::a(' Cancel', ['transaction-type/index'], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-right', 'style' => 'width:20%; margin-left:5%;']);?>
                <?= Html::submitButton(' Save', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:20%;']);?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>






