<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

use frontend\models\Employee;
use frontend\models\EmployeeTitle;

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
<body class="skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">
    <div class="wrap">
        <header class="main-header">
        <!-- Logo -->
        <a href="<?= Url::to('site/index'); ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini">SAT</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img src="<?= Url::to('css/dist/img/logo.png')?>"/>SAT Admin</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success">4</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 4 messages</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- start message -->
                        <a href="#">
                          <div class="pull-left">
                            <img src="css/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
                          </div>
                          <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li><!-- end message -->
                    </ul>
                  </li>
                  <li class="footer"><a href="#">See All Messages</a></li>
                </ul>
              </li>
              
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
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
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
            <li class="fa fa-home">
              <a href="<?= Url::toRoute(['/site/index'])?>">
                <i class="fa fa-dashboard"></i> <span>Home</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
            </li>
            <li class="treeview">
              <a href="">
                <i class="fa fa-dashboard"></i> <span>Roles & Permissions</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="active"><a href="<?= Url::toRoute(['/rbac/index'])?>"><i class="fa fa-circle-o"></i>Home</a></li>
                <li><a href="<?= Url::toRoute(['/auth-item/index'])?>"><i class="fa fa-circle-o"></i>Manage Roles and Permissions</a></li>
                <li><a href="<?= Url::toRoute(['/auth-rule/index'])?>"><i class="fa fa-circle-o"></i>Manage Authorization Rules</a></li>
                <li><a href="<?= Url::toRoute(['/auth-item-child/index'])?>"><i class="fa fa-circle-o"></i>Assign Children</a></li>
                <li><a href="<?= Url::toRoute(['/auth-assignment/index'])?>"><i class="fa fa-circle-o"></i>Assign Roles and Permissions</a></li>
              </ul>
            </li>
            <li class="active treeview">
              <a href="">
                <i class="fa fa-dashboard"></i> <span>Users</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="active"><a href="<?= Url::toRoute(['/user/index'])?>"><i class="fa fa-circle-o"></i>Home</a></li>
                <li><a href="<?= Url::toRoute(['/user/create'])?>"><i class="fa fa-circle-o"></i>Create User</a></li>
                <!--<li><a href="<?= Url::toRoute(['/auth-rule/index'])?>"><i class="fa fa-circle-o"></i>Manage Authorization Rules</a></li>
                <li><a href="<?= Url::toRoute(['/auth-item-child/index'])?>"><i class="fa fa-circle-o"></i>Assign Children</a></li>
                <li><a href="<?= Url::toRoute(['/auth-assignment/index'])?>"><i class="fa fa-circle-o"></i>Manage Authorization Rules</a></li>-->
              </ul>
            </li>
            <!--<li class="treeview">
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Miscellaneous</span>
                <span class="label label-primary pull-right">4</span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= Url::toRoute(['/subcomponents/general/index'])?>"><i class="fa fa-circle-o"></i>Home</a></li>
                <li><a href="<?= Url::toRoute(['/subcomponents/general/division'])?>"><i class="fa fa-circle-o"></i>Divisions</a></li>
              </ul>
            </li>
            <li>
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
