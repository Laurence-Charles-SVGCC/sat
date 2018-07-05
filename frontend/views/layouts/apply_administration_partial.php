<?php
    use yii\helpers\Url;
    use frontend\models\ApplicationPeriod;
?>

<li class="treeview">
    <a href="">
        <i class="fa fa-wrench" aria-hidden="true"></i> <span> Apply Administration</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    
    <ul class="treeview-menu">
        <li><a href="<?= Url::toRoute(['/subcomponents/applications/application-periods/view-periods'])?>"><i class="fa fa-circle-o"></i> View Periods</a></li>
        <li><a href="<?= Url::toRoute(['/subcomponents/applications/application-periods/view-period-statistics'])?>"><i class="fa fa-circle-o"></i> Period Statistics</a></li>

        <?php if (Yii::$app->user->can('Registrar')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/admissions/package'])?>"><i class="fa fa-circle-o"></i>Manage Packages</a></li>
        <?php endif; ?>

        <li class="treeview">
            <a href="">
                <i class="fa fa-circle-o" aria-hidden="true"></i> <span> Find Applicants</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            
            <ul class="treeview-menu">
                <li><a href="<?= Url::toRoute(['/subcomponents/applications/review-applications/find-applicant-by-email'])?>"><i class="fa fa-circle-o"></i> By Email</a></li>
                <li><a href="<?= Url::toRoute(['/subcomponents/applications/review-applications/find-applicant-by-applicantid'])?>"><i class="fa fa-circle-o"></i> By Applicant ID</a></li>
                <li><a href="<?= Url::toRoute(['/subcomponents/applications/review-applications/find-applicant-by-name'])?>"><i class="fa fa-circle-o"></i> By Name</a></li>
            </ul>
        </li>

        <li class="treeview">
            <a href=""><i class="fa fa-circle-o"></i><span>Utilities</span><i class="fa fa-angle-left pull-right"></i></a>
            
            <ul class="treeview-menu">
                <li><a href="<?= Url::toRoute(['/subcomponents/admissions/applicant-registration/index'])?>"><i class="fa fa-circle-o"></i>Deactivate Email</a></li>

                <?php if (Yii::$app->user->can('viewApplicationReports')): ?>    
                    <li><a href="<?= Url::toRoute(['/subcomponents/admissions/reports'])?>"><i class="fa fa-circle-o"></i>Applications Reports</a></li>
                <?php endif; ?>

                <?php if (Yii::$app->user->can('viewCurrentApplicantsSnapshot')): ?>    
                    <li><a href="<?= Url::toRoute(['/subcomponents/admissions/reports/snapshot'])?>"><i class="fa fa-circle-o"></i>Applications Snapshot</a></li>
                <?php endif; ?>
            </ul>
        </li>
    </ul>
</li>

