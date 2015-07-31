<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

use frontend\models\Employee;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

//Get User information
$person = Employee::find()->where(['personid' => Yii::$app->user->identity->username])->one();
if ($person)
{
    $emp_firstname = $person->firstname; 
    $emp_lastname = $person->lastname;
    $emp_username = $person->personid;
    //TODO: Get Job description from Database
    $job_title = 'Job Title';
}
else
{
    $emp_firstname = $emp_lastname = $emp_username = 'Undefined'; 
    $job_title = 'Job Title';
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
          <span class="logo-lg"><img src="<?= Url::to('css/dist/img/logo.png')?>"/>SVGCC SAT</span>
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
                  <img src="css/dist/img/user2-160x160.jpg" class="user-image" alt="User Image" />
                  <span class="hidden-xs"><?= $emp_firstname . " " . $emp_lastname ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="css/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
                    <p>
                      Username: <?= $emp_username ?>
                      <small><?= $job_title ?></small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                    <div class="col-xs-4 text-center">
                      <a href="#">Friends</a>
                    </div>
                  </li>
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
            <li class="active treeview">
              <a href="">
                <i class="fa fa-institution"></i> <span>Admissions</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
             <?php if (Yii::$app->user->can('viewAdmissions')): ?>
              <ul class="treeview-menu">
                <li class="active"><a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index'])?>"><i class="fa fa-circle-o"></i>Home</a></li>
                <li><a href="<?= Url::toRoute(['/subcomponents/admissions/application-period'])?>"><i class="fa fa-circle-o"></i>Application Periods</a></li>
                <li><a href="<?= Url::toRoute(['/subcomponents/admissions/academic-offering'])?>"><i class="fa fa-circle-o"></i>Academic Offerings</a></li>
                <li><a href="<?= Url::toRoute(['/subcomponents/admissions/verify-applicants'])?>"><i class="fa fa-circle-o"></i>Verify Applicants</a></li>
                <li><a href="<?= Url::toRoute(['/subcomponents/admissions/review-applications'])?>"><i class="fa fa-circle-o"></i>Review Applications</a></li>
                <li><a href="<?= Url::toRoute(['/subcomponents/admissions/offer'])?>"><i class="fa fa-circle-o"></i>Manage Offers</a></li>
              </ul>
              <?php endif; ?>
            </li>
            <li class="active treeview">
              <a href="">
                <i class="fa fa-money"></i> <span>Payments</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="active"><a href="<?= Url::toRoute(['/subcomponents/payments/payments/index'])?>"><i class="fa fa-circle-o"></i>Home</a></li>
                <li><a href="<?= Url::toRoute(['/subcomponents/payments/payments/manage-payments'])?>"><i class="fa fa-circle-o"></i>Manage Payments</a></li>
                <!--<li><a href="<?= Url::toRoute(['/subcomponents/payments/payments/manage-transaction-types'])?>"><i class="fa fa-circle-o"></i>Manage Transaction Types</a></li>
                <!--<li><a href="<?= Url::toRoute(['/subcomponents/admissions/verify-applicants'])?>"><i class="fa fa-circle-o"></i>Verify Applicants</a></li>-->
              </ul>
            </li>
            <li class="active treeview">
              <a href="">
                <i class="fa fa-mortar-board"></i> <span>Programmes</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="active"><a href="<?= Url::toRoute(['/subcomponents/programmes/programmes/index'])?>"><i class="fa fa-circle-o"></i>Home</a></li>
                <li><a href="<?= Url::toRoute(['/subcomponents/programmes/programme-catalog/index'])?>"><i class="fa fa-circle-o"></i>Programme Catalog</a></li>
              </ul>
            </li>
            <li class="active treeview">
              <a href="#">
                <i class="fa fa-cogs"></i>
                <span>General</span>
                <span class="label label-primary pull-right">4</span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= Url::toRoute(['/subcomponents/general/general/index'])?>"><i class="fa fa-circle-o"></i>Home</a></li>
                <li><a href="<?= Url::toRoute(['/subcomponents/general/division'])?>"><i class="fa fa-circle-o"></i>Divisions</a></li>
                <li><a href="<?= Url::toRoute(['/subcomponents/general/csec-centre'])?>"><i class="fa fa-circle-o"></i>CSEC-Centres</a></li>
              </ul>
            </li>
            <!--<li>
              <a href="pages/widgets.html">
                <i class="fa fa-th"></i> <span>Widgets</span> <small class="label pull-right bg-green">new</small>
              </a>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-share"></i> <span>Multilevel</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                <li>
                  <a href="#"><i class="fa fa-circle-o"></i> Level One <i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                    <li>
                      <a href="#"><i class="fa fa-circle-o"></i> Level Two <i class="fa fa-angle-left pull-right"></i></a>
                      <ul class="treeview-menu">
                        <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                        <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                      </ul>
                    </li>
                  </ul>
                </li>
                <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
              </ul>
            </li>
            <li><a href="documentation/index.html"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
            <li class="header">LABELS</li>
            <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>-->
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

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
