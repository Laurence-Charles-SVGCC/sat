<?php
    use yii\helpers\Url;
?>

<li class="treeview">
    <a href="">
        <i class="fa fa-user"></i> <span>Students</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    
    <ul class="treeview-menu">
        <?php if (Yii::$app->user->can('students') || Yii::$app->user->can('viewAllStudentOptions')): ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student'])?>"><i class="fa fa-circle-o"></i>Find A Student</a></li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('searchApplicant')): ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => 'pending-unlimited'])?>"><i class="fa fa-circle-o"></i>Find Applicants (All)</a></li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('students')  && (Yii::$app->user->can('Assistant Registrar')  || Yii::$app->user->can('Registry Staff'))): ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/students/account-management'])?>"><i class="fa fa-circle-o"></i>Create Student Account</a></li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('manageStudentEmails')): ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/students/email-upload'])?>"><i class="fa fa-circle-o"></i>Email Management</a></li>
        <?php endif; ?>
    </ul>
</li>