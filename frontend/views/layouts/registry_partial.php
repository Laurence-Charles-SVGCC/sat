<?php

use yii\helpers\Url;
use frontend\models\CordinatorType;
?>

<li class="treeview">
    <a href="">
        <i class="glyphicon glyphicon-book"></i> <span>Registry</span> <i class="fa fa-angle-left pull-right"></i>
    </a>

    <ul class="treeview-menu">
        <?php if (Yii::$app->user->can('viewProgramme')) : ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/programmes/programmes/index']) ?>"><i class="fa fa-circle-o"></i>Manage Programmes</a></li>
        <?php endif; ?>

        <?php if (
            in_array('Head of Department', CordinatorType::getCordinatorTypes(Yii::$app->user->identity->personid))
            ||  in_array('Programme Head', CordinatorType::getCordinatorTypes(Yii::$app->user->identity->personid))
        ) : ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/programmes/programmes/programme-cordinator']) ?>"><i class="fa fa-circle-o"></i>Audit Programme(s)</a></li>
            <li><a href="<?= Url::toRoute(['/subcomponents/gradebook/gradebook/index']) ?>"><i class="fa fa-circle-o"></i>View Student Grades</a></li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('manageAwards')) : ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/registry/awards/manage-awards']) ?>"><i class="fa fa-circle-o"></i>Awards</a></li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('manageClubs')) : ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/registry/clubs/manage-clubs']) ?>"><i class="fa fa-circle-o"></i>Clubs</a></li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('students')) : ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/admissions/reports/find-programme-intake']) ?>"><i class="fa fa-circle-o"></i>Generate Intake Reports</a></li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('viewTransferData')) : ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/students/student/view-transfers-and-deferrals']) ?>"><i class="fa fa-circle-o"></i>Transfers & Deferrals</a></li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('students')) : ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/admissions/reports/find-unregistered-applicants']) ?>"><i class="fa fa-circle-o"></i>View Unregistered Applicants</a></li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('viewAllStudentOptions') || Yii::$app->user->can('viewAcademicHolds')) : ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/students/student/view-active-academic-holds']) ?>"><i class="fa fa-circle-o"></i>View Active Academic Holds</a></li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('students')) : ?>
            <li class="treeview">
                <a href=""><i class="fa fa-circle-o"></i><span>Warnings and Promotions</span><i class="fa fa-angle-left pull-right"></i></a>

                <ul class="treeview-menu">
                    <li><a href="<?= Url::toRoute(['/subcomponents/registry/warning/index']) ?>"><i class="fa fa-circle-o"></i> Warnings</a></li>
                    <li><a href="<?= Url::toRoute(['/subcomponents/registry/withdrawal/index']) ?>"><i class="fa fa-circle-o"></i> Promotions</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <!--<?php if (Yii::$app->user->can('manageTranscripts')) : ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/registry/transcripts/manage-transcripts']) ?>"><i class="fa fa-circle-o"></i>Transcript Requests</a></li>
        <?php endif; ?>-->
    </ul>
</li>