<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Breadcrumbs;
    use frontend\assets\AppAsset;
    use common\widgets\Alert;
    use frontend\models\EmployeeTitle;
    use frontend\models\ApplicationPeriod;
    use frontend\models\Employee;
    use frontend\models\CordinatorType;
    use frontend\models\LegacyYear;
    use frontend\models\ApplicationSettings;

    AppAsset::register($this);

    //Get User information
     $employee = Employee::find()->where(['personid' => Yii::$app->user->getId()])->one();
     if ($employee == true)
    {
        $employee_name = $employee->firstname . " " . $employee->lastname;
        $employee_username = Yii::$app->user->identity->username;
        $title = EmployeeTitle::findOne(['employeetitleid' => $employee->employeetitleid]);
        $employee_job_title = $title? $title->name: "Undefined Job Title";
    }
    else
    {
        $employee_name = "Error";
        $employee_username = "Unknown";
        $employee_job_title = "Error: Please login";
    }
?>


<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    
    <?php if (ApplicationSettings::getApplicationSettings()->is_online == true):?>
        <body class="skin-green-light sidebar-mini">
    <?php elseif (ApplicationSettings::getApplicationSettings()->is_online == false):?>
        <body class="skin-red-light sidebar-mini">
    <?php endif;?>
        <?php $this->beginBody() ?>
            <div class="wrapper">
                <div class="wrap">
                    <header class="main-header">
                        <!-- Logo -->
                        <a href="<?= Url::to(['/site/index']); ?>" class="logo">
                          <!-- mini logo for sidebar mini 50x50 pixels -->
                          <span class="logo-mini">SAT</span>
                          <!-- logo for regular state and mobile devices -->
                          <span class="logo-lg"><img src="css/dist/img/logo.png"/>SVGCC SAT</span>
                        </a>

                        <!-- Header Navbar: style can be found in header.less -->
                        <nav class="navbar navbar-static-top" role="navigation">
                          <!-- Sidebar toggle button-->
                          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                            <span class="sr-only">Toggle navigation</span>
                          </a>

                          <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav">
                              <!-- User Account: style can be found in dropdown.less -->
                              <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                  <img src="css/dist/img/logo.png" class="user-image" alt="Company Logo" />
                                  <span class="hidden-xs"><?= $employee_name ?></span>
                                </a>
                                  
                                <ul class="dropdown-menu">
                                  <!-- User image -->
                                  <li class="user-header">
                                    <img src="css/dist/img/logo.png" class="img-circle" alt="User Image" />
                                    <p>
                                      Username: <?= $employee_username ?>
                                      <small><?= $employee_job_title ?></small>
                                    </p>
                                  </li>
                                  <!-- Menu Body -->
                                  <!-- Menu Footer-->
                                  <li class="user-footer">
                                    <?php if (Yii::$app->user->can('System Administrator')): ?>
                                        <div class="pull-left" style="margin-left:2.5%">
                                            <?php if(strstr(Url::home(true), "localhost") == true) :?>
                                                <a href="./../../backend/web/" class="btn btn-default btn-flat glyphicon glyphicon-transfer"> Backend</a>
                                            <?php else:?>
                                                <a href="http://www.svgcc.vc/subdomains/sat/backend/web/index.php?r=site" class="btn btn-default btn-flat glyphicon glyphicon-transfer"> Backend</a>
                                            <?php endif;?>
                                        </div>
                                    <?php endif;?>
                                    <div class="pull-right">
                                      <a href="<?= Url::toRoute(['/site/logout']) ?>" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                  </li>
                                </ul>
                              </li>
                            </ul>
                          </div>
                        </nav>
                    </header>
                </div>

                <!--Sidebar -->
                <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                <!-- Sidebar user panel -->
                  <!-- sidebar menu: : style can be found in sidebar.less -->
                  <ul class="sidebar-menu">
                    <?php if (Yii::$app->user->can('System Administrator')): ?>
                      <li class="treeview">
                        <a href="">
                          <i class="fa fa-cogs" aria-hidden="true"></i> <span>Administrator Settings</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if (ApplicationSettings::getApplicationSettings()->is_online == true):?>
                                <li><a href="<?= Url::toRoute(['/site/toggle-maintenance-mode', 'status' => false])?>"><i class="fa fa-circle-o"></i>Set As Offline</a></li> 
                            <?php elseif (ApplicationSettings::getApplicationSettings()->is_online == false):?>
                                <li><a href="<?= Url::toRoute(['/site/toggle-maintenance-mode', 'status' => true])?>"><i class="fa fa-circle-o"></i>Set As Online</a></li>
                            <?php endif;?>
                        </ul>
                     </li>
                    <?php endif;?>
                     
                   
                   <?php if (Yii::$app->user->can('System Administrator')  || Yii::$app->user->can('Registrar')): ?>
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
                    <?php endif;?>
                    
                      
                   <?php if (Yii::$app->user->can('admissions')): ?>
                    <li class="treeview">
                      <a href="">
                        <i class="fa fa-institution"></i> <span>Admissions</span> <i class="fa fa-angle-left pull-right"></i>
                      </a>
                      <ul class="treeview-menu">
                        <?php if (Yii::$app->user->can('searchApplicant')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => 'pending'])?>"><i class="fa fa-circle-o"></i>Find Applicants (Current)</a></li>
                        <?php endif; ?>
                            
                        <?php if (Yii::$app->user->can('registerStudent') ): ?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => 'submitted-unlimited'])?>"><i class="fa fa-circle-o"></i>Verify Supporting Docs.</a></li>    
                        <?php endif; ?>     
                            
                        <?php if (Yii::$app->user->can('verifyApplicants')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/admissions/verify-applicants'])?>"><i class="fa fa-circle-o"></i>Verify Certificates</a></li>
                        <?php endif; ?>

                        <?php if (Yii::$app->user->can('reviewApplications')  /*&& Yii::$app->user->can('System Administrator')*/   && ApplicationPeriod::incompletePeriodExists()==true): ?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/admissions/process-applications'])?>"><i class="fa fa-circle-o"></i>Process Applications</a></li>
                        <?php endif; ?>
                            
                         <?php if (ApplicationPeriod::incompletePeriodExists() == true && (Yii::$app->user->can('viewOffer')  || Yii::$app->user->can('viewRejection'))): ?>
                            <li class="treeview">
                               <a href="">
                                 <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Applicant Results</span> <i class="fa fa-angle-left pull-right"></i>
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
                   <?php endif; ?>
                    
                    
                    <?php if (Yii::$app->user->can('registry')): ?>
                        <li class="treeview">
                            <a href="">
                                <i class="glyphicon glyphicon-book"></i> <span>Registry</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <?php if (Yii::$app->user->can('viewProgramme')): ?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/programmes/programmes/index'])?>"><i class="fa fa-circle-o"></i>Manage Programmes</a></li>
                                <?php endif; ?>

                                 <?php if (Yii::$app->user->can('Cordinator')
                                                && (in_array('Head of Department', CordinatorType::getCordinatorTypes(Yii::$app->user->identity->personid)) 
                                                            ||  in_array('Programme Head', CordinatorType::getCordinatorTypes(Yii::$app->user->identity->personid))) 
                                         ): ?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/programmes/programmes/programme-cordinator'])?>"><i class="fa fa-circle-o"></i>Audit Programme(s)</a></li>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/gradebook/gradebook/index'])?>"><i class="fa fa-circle-o"></i>View Student Grades</a></li>
                                <?php endif; ?>

                                <?php if (Yii::$app->user->can('manageAwards')): ?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/registry/awards/manage-awards'])?>"><i class="fa fa-circle-o"></i>Awards</a></li>
                                <?php endif; ?>

                                <?php if (Yii::$app->user->can('manageClubs')): ?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/registry/clubs/manage-clubs'])?>"><i class="fa fa-circle-o"></i>Clubs</a></li>
                                <?php endif; ?>

                                <?php if (Yii::$app->user->can('students')): ?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/admissions/reports/find-programme-intake'])?>"><i class="fa fa-circle-o"></i>Generate Intake Reports</a></li>
                                <?php endif; ?>

                                <?php if(Yii::$app->user->can('viewTransferData')):?>
                                     <li><a href="<?= Url::toRoute(['/subcomponents/students/student/view-transfers-and-deferrals'])?>"><i class="fa fa-circle-o"></i>Transfers & Deferrals</a></li>
                                <?php endif; ?>

                                <?php if (Yii::$app->user->can('students')): ?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/admissions/reports/find-unregistered-applicants'])?>"><i class="fa fa-circle-o"></i>View Unregistered Applicants</a></li>
                                <?php endif; ?>

                                <?php if (Yii::$app->user->can('viewAllStudentOptions') || Yii::$app->user->can('viewAcademicHolds')): ?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/students/student/view-active-academic-holds'])?>"><i class="fa fa-circle-o"></i>View Active Academic Holds</a></li>
                                <?php endif; ?>

                               <?php if (Yii::$app->user->can('students')): ?>
                                    <li class="treeview">
                                        <a href=""><i class="fa fa-circle-o"></i><span>Warnings and Promotions</span><i class="fa fa-angle-left pull-right"></i></a>
                                        <ul class="treeview-menu">
                                            <li><a href="<?= Url::toRoute(['/subcomponents/registry/warning/index'])?>"><i class="fa fa-circle-o"></i> Warnings</a></li>
                                            <li><a href="<?= Url::toRoute(['/subcomponents/registry/withdrawal/index'])?>"><i class="fa fa-circle-o"></i> Promotions</a></li>
                                        </ul>
                                    </li>
                                <?php endif; ?>

                                <!--<?php if (Yii::$app->user->can('manageTranscripts')): ?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/registry/transcripts/manage-transcripts'])?>"><i class="fa fa-circle-o"></i>Transcript Requests</a></li>
                                <?php endif; ?>-->
                            </ul>
                        </li>
                    <?php endif; ?>



                    <?php if (Yii::$app->user->can('students')): ?>
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
                                    
                                

                                <!--
                                <?php if (Yii::$app->user->can('searchApplicant')): ?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/admissions/view-applicant'])?>"><i class="fa fa-circle-o"></i>DASGS/DTVE (2015/2016) <br/> Applicants</a></li>
                                <?php endif; ?>
                                <?php if (Yii::$app->user->can('registerStudent')): ?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/admissions/view-applicant'])?>"><i class="fa fa-circle-o"></i>Late Registration</a></li>
                                <?php endif; ?>
                                -->
                                

                            </ul>
                        </li>
                    <?php endif; ?>
                        
                   <!--
                   <?php if (Yii::$app->user->can('Deputy Dean') || Yii::$app->user->can('Dean')  || Yii::$app->user->can('Registrar')): ?>
                        <li class="treeview">
                            <a href=""><span><i class="fa fa-graduation-cap"></i> Graduation</span> <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/programme-graduation-requirements'])?>"><i class="fa fa-circle-o"></i> Programme Requirements</a></li>
                                <li><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/generate-graduation-reports'])?>"><i class="fa fa-circle-o"></i> Review Students</a></li>
                                <li><a href=""><i class="fa fa-circle-o"></i> Graduation Listing</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>-->
                   


                    <?php if (Yii::$app->user->can('gradebook')): ?>
                        <li class="treeview">
                            <a href="">
                                <i class="glyphicon glyphicon-book"></i> <span>Grade Book</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>

                            <ul class="treeview-menu">
                                <li>
                                    <a href="<?= Url::toRoute(['/subcomponents/gradebook/gradebook/index'])?>">
                                        <i class="fa fa-circle-o"></i>View Student Grades
                                    </a>
                                </li>
                            </ul>                  
                        </li>
                    <?php endif; ?>


                   <?php if (Yii::$app->user->can('Bursar')  || Yii::$app->user->can('Bursary Staff')):?>
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
                    <?php endif; ?>

                    
                    <!--Legacy-->
                    <?php if (false/*Yii::$app->user->can('accessLegacy')*/): ?>
                        <li class="treeview">
                            <a href="">
                                <i class="glyphicon glyphicon-hourglass"></i> <span>Legacy</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>

                            <ul class="treeview-menu">
                                <?php if (Yii::$app->user->can('viewLegacyStudents')): ?>
                                    <?php if(LegacyYear::find()->where(['isactive' => 1, 'isdeleted' => 0])->count() > 0):?>
                                        <li><a href="<?= Url::toRoute(['/subcomponents/legacy/student/find-a-student'])?>"><i class="fa fa-circle-o"></i>Find/Create Student</a></li>
                                    <?php endif;?>
                                <?php endif;?>
                                
                                <?php if (Yii::$app->user->can('manageLegacyGrades')): ?>
                                    <?php if(LegacyYear::find()->where(['isactive' => 1, 'isdeleted' => 0])->count() > 0):?>
                                        <li><a href="<?= Url::toRoute(['/subcomponents/legacy/grades/find-batches'])?>"><i class="fa fa-circle-o"></i>Grades</a></li>
                                    <?php endif;?>
                                <?php endif;?>

                                <?php if (Yii::$app->user->can('manageLegacyYears')): ?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/legacy/year/index'])?>"><i class="fa fa-circle-o"></i>Manage Academic Year</a></li>
                                <?php endif;?>

                                <?php if (Yii::$app->user->can('manageLegacySubjects')): ?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/legacy/subjects/index'])?>"><i class="fa fa-circle-o"></i>Subject Catalog</a></li>
                                <?php endif;?>
                                    
                                <?php if (Yii::$app->user->can('manageLegacyBatches')): ?>
                                    <?php if(LegacyYear::find()->where(['isactive' => 1, 'isdeleted' => 0])->count() > 0):?>
                                        <li><a href="<?= Url::toRoute(['/subcomponents/legacy/batch/index'])?>"><i class="fa fa-circle-o"></i>Manage Batches</a></li>
                                    <?php endif;?>
                                <?php endif;?>
                            </ul>                  
                        </li>
                    <?php endif; ?>
                        
                        
                        
                        
                    <!--
                    <?php if (Yii::$app->user->can('programmes')): ?>
                    <li class="active treeview">
                      <a href="">
                        <i class="fa fa-book"></i> <span>Programmes</span> <i class="fa fa-angle-left pull-right"></i>
                      </a>
                      <ul class="treeview-menu">

                        <?php if (Yii::$app->user->can('viewProgramme')): ?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/programmes/programme-catalog/index'])?>"><i class="fa fa-circle-o"></i>Programme Catalog</a></li>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('viewCapeSubject')): ?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/programmes/cape-subject/index'])?>"><i class="fa fa-circle-o"></i>CAPE Subject Catalog</a></li>
                        <?php endif; ?>
                      </ul>
                    </li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('general')): ?>
                        <li class="active treeview">
                          <a href="#">
                            <i class="fa fa-cogs"></i>
                            <span>General</span>
                            <span class="label label-primary pull-right">4</span>
                          </a>
                          <ul class="treeview-menu">
                            <li><a href="<?= Url::toRoute(['/subcomponents/general/general/index'])?>"><i class="fa fa-circle-o"></i>Home</a></li>
                            <?php if (Yii::$app->user->can('viewDivision')): ?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/general/division'])?>"><i class="fa fa-circle-o"></i>Divisions</a></li>
                            <?php endif; ?>
                            <?php if (Yii::$app->user->can('viewCsecCentre')): ?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/general/csec-centre'])?>"><i class="fa fa-circle-o"></i>CSEC-Centres</a></li>
                            <?php endif; ?>
                            <?php if (Yii::$app->user->can('viewAcademicYear')): ?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/general/academic-year'])?>"><i class="fa fa-circle-o"></i>Academic Years</a></li>
                            <?php endif; ?>
                            <?php if (Yii::$app->user->can('viewSemester')): ?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/general/semester'])?>"><i class="fa fa-circle-o"></i>Academic Semesters</a></li>
                            <?php endif; ?>    
                          </ul>
                        </li>
                    <?php endif; ?>
                    -->
                  </ul>
                  <div style="position: fixed; z-index: 1000; width: 164px; height: 98px; bottom: 30px; left: 15px;">
                      <a href="#" onclick="window.open('https://www.sitelock.com/verify.php?site=svgcc.net','SiteLock','width=600,height=600,left=160,top=170');" >
                          <img class="img-responsive" alt="SiteLock" title="SiteLock" src="//shield.sitelock.com/shield/svgcc.net" />
                      </a>
                  </div>
                </section>
                <!-- /.sidebar -->
                </aside>


                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <div><p style="width:90%"><?= Alert::widget() ?></p></div>
                    
                    <!-- Main Content -->
                    <section class="content">
                        <?= $content ?>
                    </section>
                </div>
            </div>
        
            <footer class="footer">
                <div class="container">
                    <p class="pull-left">&copy; SVGCC <?= date('Y') ?></p>
                    <p class="pull-right"><?= Yii::powered() ?></p>
                </div>
            </footer>
        <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
