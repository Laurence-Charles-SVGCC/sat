<?php
    use yii\helpers\Url;
    use frontend\models\ApplicationPeriod;
?>

<li class="treeview">
    <a href="">
        <i class="fa fa-institution"></i> <span>Admissions</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    
    <ul class="treeview-menu">
        <?php if (Yii::$app->user->can('searchApplicant')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => 'pending'])?>"><i class="fa fa-circle-o"></i>Find Applicants (Current)</a></li>
        <?php endif; ?>
            
        <li class="treeview">
            <a href="">
                <i class="fa fa-circle-o"></i> <span>Verification</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            
            <ul class="treeview-menu">
                <?php if (Yii::$app->user->can('verifyApplicants')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                    <li><a href="<?= Url::toRoute(['/subcomponents/admissions/verify-applicants'])?>"><i class="fa fa-circle-o"></i>Verify Certificates</a></li>
                <?php endif; ?>

                <?php if (Yii::$app->user->can('registerStudent') ): ?>
                    <li><a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => 'submitted-unlimited'])?>"><i class="fa fa-circle-o"></i>Verify Supporting Docs.</a></li>    
                <?php endif; ?>   
            </ul>
        </li>   

        <?php if (Yii::$app->user->can('reviewApplications')  /*&& Yii::$app->user->can('System Administrator')*/   && ApplicationPeriod::incompletePeriodExists()==true): ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/admissions/process-applications'])?>"><i class="fa fa-circle-o"></i>Process Applications</a></li>
        <?php endif; ?>

        <?php if (ApplicationPeriod::incompletePeriodExists() == true && (Yii::$app->user->can('viewOffer')  || Yii::$app->user->can('viewRejection'))): ?>
        <li class="treeview">
            <a href="">
                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Application Responses</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            
            <ul class="treeview-menu">
                <?php if (Yii::$app->user->can('viewOffer')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                    <li><a href="<?= Url::toRoute(['/subcomponents/admissions/offer', 'offertype' => 2])?>"><i class="fa fa-circle-o"></i>Interviews</a></li>
                <?php endif; ?>

                <?php if (Yii::$app->user->can('viewOffer')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                    <li><a href="<?= Url::toRoute(['/subcomponents/admissions/offer', 'offertype' => 1])?>"><i class="fa fa-circle-o"></i>Offers</a></li>
                <?php endif; ?>

                <?php if (Yii::$app->user->can('viewRejection')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                    <li><a href="<?= Url::toRoute(['/subcomponents/admissions/rejection', 'rejectiontype' => 1])?>"><i class="fa fa-circle-o"></i>Rejections</a></li>
                <?php endif; ?> 

                <?php if (Yii::$app->user->can('viewRejection')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                    <li><a href="<?= Url::toRoute(['/subcomponents/admissions/rejection', 'rejectiontype' => 2])?>"><i class="fa fa-circle-o"></i>Post-Interview Rejections</a></li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>
        
        <?php if (Yii::$app->user->can('scheduleInterview') == true): ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/admissions/interview-appointments'])?>"><i class="fa fa-circle-o"></i>Interview Appointments</a></li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('registerStudent') ): ?>
            <li><a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => 'successful'])?>"><i class="fa fa-circle-o"></i>Enroll Applicants</a></li>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('studentCard')): ?> 
            <li class="treeview">
                <a href=""><i class="fa fa-circle-o"></i><span>Library Utilities</span><i class="fa fa-angle-left pull-right"></i></a>
                
                <ul class="treeview-menu">
                    <li><a href="<?= Url::toRoute(['/subcomponents/admissions/card'])?>"><i class="fa fa-circle-o"></i>Student Cards</a></li>
                </ul>
            </li>
    <?php endif; ?>
    </ul>
</li>
