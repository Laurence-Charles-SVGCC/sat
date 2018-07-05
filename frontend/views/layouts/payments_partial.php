<?php
    use yii\helpers\Url;
?>

<li class="treeview">
    <a href=""><i class="glyphicon glyphicon-usd"></i> <span>Payments</span> <i class="fa fa-angle-left pull-right"></i></a>

    <ul class="treeview-menu">
        <?php if (Yii::$app->user->can('System Administrator')): ?>
            <li class="treeview">
                <a href="">
                    <i class="fa fa-circle-o"></i><span>CRUD Controls</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                
                <ul class="treeview-menu">
                    <?php if (Yii::$app->user->can('viewPaymentMethod')): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/payments/payment-method/index'])?>"><i class="fa fa-circle-o"></i>Payment Methods</a></li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('viewTransactionPurpose')): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/payments/transaction-purpose/index'])?>"><i class="fa fa-circle-o"></i>Transaction Purposes</a></li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('viewTransactionType')): ?>   
                        <li><a href="<?= Url::toRoute(['/subcomponents/payments/transaction-type/index'])?>"><i class="fa fa-circle-o"></i>Transaction Types</a></li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('viewTransactionItem')): ?>   
                        <li><a href="<?= Url::toRoute(['/subcomponents/payments/transaction-item/index'])?>"><i class="fa fa-circle-o"></i>Transaction Items</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('managePayments')): ?>
            <li class="treeview">
                <a href="">
                    <i class="fa fa-circle-o"></i><span>Manage Payments</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                
                <ul class="treeview-menu">
                    <li><a href="<?= Url::toRoute(['/subcomponents/payments/payments/find-applicant-or-student', 'status' => 'applicant',  'new_search' => 1])?>"><i class="fa fa-circle-o"></i>Find Applicant</a></li>
                    <li><a href="<?= Url::toRoute(['/subcomponents/payments/payments/find-applicant-or-student', 'status' => 'student' ,  'new_search' => 1])?>"><i class="fa fa-circle-o"></i>Find Student</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('generateInsuranceListing')): ?>    
            <li><a href="<?= Url::toRoute(['/subcomponents/payments/reports/find-beneficieries'])?>"><i class="fa fa-circle-o"></i>Generate Beneficiery Listing</a></li>
        <?php endif; ?>
    </ul>
</li>

