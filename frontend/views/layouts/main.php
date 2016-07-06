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

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
//Get User information
$employee = Employee::find()->where(['personid' => Yii::$app->user->getId()])->one();
if ($employee)
{
    $emp_firstname = $employee->firstname; 
    $emp_lastname = $employee->lastname;
    $emp_username = Yii::$app->user->identity->username;
    $emp_title = EmployeeTitle::findOne(['employeetitleid' => $employee->employeetitleid]);
    $job_title = $emp_title ? $emp_title->name : 'Undefined Job Title';
}
else
{
    $emp_firstname = $emp_lastname = $emp_username = 'Undefined'; 
    $job_title = 'Undefined Job Title';
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

<body class="skin-green-light sidebar-mini">
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
                              <img src="css/dist/img/logo.png" class="user-image" alt="User Image" />
                              <span class="hidden-xs"><?= $emp_firstname . " " . $emp_lastname ?></span>
                            </a>
                            <ul class="dropdown-menu">
                              <!-- User image -->
                              <li class="user-header">
                                <img src="css/dist/img/logo.png" class="img-circle" alt="User Image" />
                                <p>
                                  Username: <?= $emp_username ?>
                                  <small><?= $job_title ?></small>
                                </p>
                              </li>
                              <!-- Menu Body -->
                              <!-- Menu Footer-->
                              <li class="user-footer">
                                <div class="pull-left">
                                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                                </div>
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
               <?php if (Yii::$app->user->can('admissions')): ?>
                <li class="active treeview">
                  <a href="">
                    <i class="fa fa-institution"></i> <span>Admissions</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                    
                    <?php if (Yii::$app->user->can('Registrar')): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/manage-application-period'])?>"><i class="fa fa-circle-o"></i>Manage Application Periods</a></li> 
                    <?php endif; ?>
                    
                    <?php if (Yii::$app->user->can('searchApplicant')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => 'pending'])?>"><i class="fa fa-circle-o"></i>Find Current Applicant</a></li>
                    <?php endif; ?>
                    
                    <?php if (Yii::$app->user->can('verifyApplicants')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/admissions/verify-applicants'])?>"><i class="fa fa-circle-o"></i>Verify Applicants</a></li>
                    <?php endif; ?>
                    
                    <?php if (Yii::$app->user->can('reviewApplications')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/admissions/process-applications'])?>"><i class="fa fa-circle-o"></i>Process Applications</a></li>
                    <?php endif; ?>
                    
                    <?php if (Yii::$app->user->can('Registrar')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/admissions/package'])?>"><i class="fa fa-circle-o"></i>Manage Packages</a></li>
                    <?php endif; ?>
                   
                    <?php if (Yii::$app->user->can('viewOffer')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/admissions/offer', 'offertype' => 2])?>"><i class="fa fa-circle-o"></i>Interviewees</a></li>
                    <?php endif; ?>
                    
                    <?php if (Yii::$app->user->can('viewOffer')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/admissions/offer', 'offertype' => 1])?>"><i class="fa fa-circle-o"></i>Unconditional Offers</a></li>
                    <?php endif; ?>
                        
                    <?php if (Yii::$app->user->can('viewRejection')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/admissions/rejection', 'rejectiontype' => 1])?>"><i class="fa fa-circle-o"></i>Pre-Interview Rejections</a></li>
                    <?php endif; ?> 
                        
                    <?php if (Yii::$app->user->can('viewRejection')  && ApplicationPeriod::incompletePeriodExists()==true): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/admissions/rejection', 'rejectiontype' => 2])?>"><i class="fa fa-circle-o"></i>Post-Interview Rejections</a></li>
                    <?php endif; ?>
                    
                    <?php if (Yii::$app->user->can('registerStudent') /*&& ApplicationPeriod::incompletePeriodExists()==true*/): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => 'successful'])?>"><i class="fa fa-circle-o"></i>Enroll Applicants</a></li>
                    <?php endif; ?>
                   
                    <?php if (Yii::$app->user->can('studentCard')): ?>    
                        <li><a href="<?= Url::toRoute(['/subcomponents/admissions/card'])?>"><i class="fa fa-circle-o"></i>Student Cards</a></li>
                    <?php endif; ?>
                        
                    <?php if (Yii::$app->user->can('viewApplicationReports')): ?>    
                        <li><a href="<?= Url::toRoute(['/subcomponents/admissions/reports'])?>"><i class="fa fa-circle-o"></i>Applications Reports</a></li>
                    <?php endif; ?>
                  </ul>
                </li>
               <?php endif; ?>
                

                
                <?php if (Yii::$app->user->can('registry')): ?>
                    <li class="active treeview">
                        <a href="">
                            <i class="fa fa-graduation-cap"></i> <span>Registry</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if (Yii::$app->user->can('viewProgramme')): ?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/programmes/programmes/index'])?>"><i class="fa fa-circle-o"></i>Manage Programmes</a></li>
                            <?php endif; ?>
                                
                             <?php if (Yii::$app->user->can('Cordinator')
                                            && (in_array('Head of Department', CordinatorType::getCordinatorTypes(Yii::$app->user->identity->personid)) 
                                                        ||  in_array('Programme Head', CordinatorType::getCordinatorTypes(Yii::$app->user->identity->personid))) 
                                     ): ?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/programmes/programmes/programme-cordinator'])?>"><i class="fa fa-circle-o"></i>View Programme Performance</a></li>
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
                                
                            <?php if (Yii::$app->user->can('students')): ?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/admissions/reports/find-unregistered-applicants'])?>"><i class="fa fa-circle-o"></i>View Unregistered Applicants</a></li>
                            <?php endif; ?>
                            
                            <?php if (Yii::$app->user->can('viewAllStudentOptions') || Yii::$app->user->can('viewAcademicHolds')): ?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/students/student/view-active-academic-holds'])?>"><i class="fa fa-circle-o"></i>View Active Academic Holds</a></li>
                            <?php endif; ?>
                                
                            <!--<?php if (Yii::$app->user->can('manageTranscripts')): ?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/registry/transcripts/manage-transcripts'])?>"><i class="fa fa-circle-o"></i>Transcript Requests</a></li>
                            <?php endif; ?>-->
                        </ul>
                    </li>
                <?php endif; ?>
                
                
                
                <?php if (Yii::$app->user->can('students')): ?>
                    <li class="active treeview">
                        <a href="">
                            <i class="fa fa-user"></i> <span>Students</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if (Yii::$app->user->can('students') || Yii::$app->user->can('viewAllStudentOptions')): ?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student'])?>"><i class="fa fa-circle-o"></i>Find A Student</a></li>
                            <?php endif; ?>
                            
                            <?php if (Yii::$app->user->can('searchApplicant')): ?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => 'pending-unlimited'])?>"><i class="fa fa-circle-o"></i>Find Past Applicant</a></li>
                            <?php endif; ?>
                                
                            <?php if (Yii::$app->user->can('students')): ?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/students/account-management'])?>"><i class="fa fa-circle-o"></i>Create Student Account</a></li>
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


                
                <?php if (Yii::$app->user->can('gradebook')): ?>
                    <li class="active treeview">
                        <a href="">
                            <i class="glyphicon glyphicon-book"></i> <span>Grade Book</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>

                        <ul class="treeview-menu">
                            <li>
                                <a href="<?= Url::toRoute(['/subcomponents/gradebook/gradebook/index'])?>">
                                    <i class="fa fa-circle-o"></i>Find A Student
                                </a>
                            </li>


                        </ul>                  
                    </li>
                <?php endif; ?>
                    
                    
                <!--Legacy-->
                <?php if (false/*Yii::$app->user->can('legacy')*/): ?>
                    <li class="active treeview">
                        <a href="">
                            <i class="glyphicon glyphicon-leaf"></i> <span>Legacy</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>

                        <ul class="treeview-menu">
                            <li>
                                <a href="<?= Url::toRoute(['/subcomponents/legacy/student/find-a-student'])?>">
                                    <i class="fa fa-circle-o"></i>Find Student Transcript
                                </a>
                            </li>
                            
                            <?php if (true/*Yii::$app->user->can('manageLegacyYears')*/): ?>
                                <li>
                                    <a href="<?= Url::toRoute(['/subcomponents/legacy/year/index'])?>">
                                        <i class="fa fa-circle-o"></i>Manage Academic Year
                                    </a>
                                </li>
                            <?php endif;?>
                                
                            <?php if (true/*Yii::$app->user->can('manageLegacyStudents')*/): ?>
                                <li>
                                    <a href="<?= Url::toRoute(['/subcomponents/legacy/student/manage-students'])?>">
                                        <i class="fa fa-circle-o"></i>Manage Student Profiles
                                    </a>
                                </li>
                            <?php endif;?>
                                
                            <?php if (true/*Yii::$app->user->can('enrollLegacyStudents')*/): ?>
                                <li>
                                    <a href="<?= Url::toRoute(['/subcomponents/legacy/student/enroll-students'])?>">
                                        <i class="fa fa-circle-o"></i>Enroll Students
                                    </a>
                                </li>
                            <?php endif;?>
                            
                            <?php if (true/*Yii::$app->user->can('manageLegacyGrades')*/): ?>
                                <li>
                                    <a href="<?= Url::toRoute(['/subcomponents/legacy/grades/enter-grades'])?>">
                                        <i class="fa fa-circle-o"></i>Enter Grades
                                    </a>
                                </li>
                            <?php endif;?>
                                
                            <?php if (true/*Yii::$app->user->can('manageLegacySubjects')*/): ?>
                                <li>
                                    <a href="<?= Url::toRoute(['/subcomponents/legacy/subjects/manage-subjects'])?>">
                                        <i class="fa fa-circle-o"></i>Manage Subjects
                                    </a>
                                </li>
                            <?php endif;?>
                                
                            <?php if (true/*Yii::$app->user->can('manageLegacyBatches')*/): ?>
                                <li>
                                    <a href="<?= Url::toRoute(['/subcomponents/legacy/batch/manage-batches'])?>">
                                        <i class="fa fa-circle-o"></i>Manage Batches
                                    </a>
                                </li>
                            <?php endif;?>
                        </ul>                  
                    </li>
                <?php endif; ?>
                    
                    
                    
                    
                    

                
                <?php if (Yii::$app->user->can('payments')): ?>
                <li class="active treeview">
                  <a href="">
                    <i class="fa fa-money"></i> <span>Payments</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                    <li class="active"><a href="<?= Url::toRoute(['/subcomponents/payments/payments/index'])?>"><i class="fa fa-circle-o"></i>Home</a></li>
                    <?php if (Yii::$app->user->can('managePayments')): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/payments/payments/manage-payments'])?>"><i class="fa fa-circle-o"></i>Manage Payments</a></li>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->can('viewTransactionType')): ?>    
                        <li><a href="<?= Url::toRoute(['/subcomponents/payments/payments/transaction-types'])?>"><i class="fa fa-circle-o"></i>Transaction Types</a></li>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->can('viewTransactionPurpose')): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/payments/payments/transaction-purposes'])?>"><i class="fa fa-circle-o"></i>Transaction Purposes</a></li>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->can('viewPaymentMethod')): ?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/payments/payments/payment-methods'])?>"><i class="fa fa-circle-o"></i>Payment Methods</a></li>
                    <?php endif; ?>
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
            </section>
            <!-- /.sidebar -->
            </aside>
            
            
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="container">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </div>
            </div>

            <footer class="footer">
                <div class="container">
                <p class="pull-left">&copy; SVGCC <?= date('Y') ?></p>
                <p class="pull-right"><?= Yii::powered() ?></p>
                </div>
            </footer>
        </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
