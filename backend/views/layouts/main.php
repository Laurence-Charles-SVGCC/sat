<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Breadcrumbs;
    use frontend\assets\AppAsset;
    use common\widgets\Alert;

    use frontend\models\Employee;
    use frontend\models\EmployeeTitle;
    use frontend\models\ApplicationSettings;

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
        
        <?php if (ApplicationSettings::getApplicationSettings()->is_online == true):?>
            <body class="skin-blue-light sidebar-mini">
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
                              <!-- User Account: style can be found in dropdown.less -->
                              <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                  <img src="css/dist/img/logo.png" class="user-image" alt="Company Logo" />
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
                                  <li class="user-footer">
                                    <?php if (Yii::$app->user->can('System Administrator')): ?>
                                        <div class="pull-left" style="margin-left:2.5%">
                                            <a href="./../../frontend/web/" class="btn btn-default btn-flat glyphicon glyphicon-transfer"> Frontend</a>
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
                     
                         
                        <li class="treeview">
                            <a href=""><i class="glyphicon glyphicon-user"></i> <span>User Management</span> <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href="<?= Url::toRoute(['/user/index'])?>"><i class="fa fa-circle-o"></i>Find A User</a></li>
                                <li><a href="<?= Url::toRoute(['/user/index', 'user_type' =>3])?>"><i class="fa fa-circle-o"></i>Find Employee</a></li>
                                <li><a href="<?= Url::toRoute(['/user/index', 'user_type' => 2])?>"><i class="fa fa-circle-o"></i>Find Student</a></li>
                                
                                <li class="treeview">
                                    <a href="">
                                        <i class="fa fa-circle-o"></i> <span>User Creation</span> <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li><a href="<?= Url::toRoute(['/user/create-full-user'])?>"><i class="fa fa-circle-o"></i>Create Full User</a></li>
                                        <li><a href="<?= Url::toRoute(['/user/create-lecturer'])?>"><i class="fa fa-circle-o"></i>Create Lecturer Account</a></li>
                                    </ul>
                                </li>
                                
                                <li><a href="<?= Url::toRoute(['/employee/assign-password'])?>"><i class="fa fa-circle-o"></i>Assign Employee Password</a></li>
                           </ul>
                        </li>
                        
                        <li class="treeview">
                            <a href="">
                                <i class="glyphicon glyphicon-lock"></i> <span>Roles & Permissions</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <!--<li><a href="<?= Url::toRoute(['/auth-rule/index'])?>"><i class="fa fa-circle-o"></i>Manage Authorization Rules</a></li>-->
                                <li><a href="<?= Url::toRoute(['/auth-item/index', 'type' =>'Roles'])?>"><i class="fa fa-circle-o"></i>Manage Roles</a></li>
                                <li><a href="<?= Url::toRoute(['/auth-item/index', 'type' =>'Permissions'])?>"><i class="fa fa-circle-o"></i>Manage Permissions</a></li>
                                <li><a href="<?= Url::toRoute(['/auth-assignment/index'])?>"><i class="fa fa-circle-o"></i>Assign User Roles</a></li>
                                <li><a href="<?= Url::toRoute(['/auth-item-child/index', 'type' => 'assign-role-to-role'])?>"><i class="fa fa-circle-o"></i>Role Hierarchies</a></li>
                                <li><a href="<?= Url::toRoute(['/auth-item-child/index', 'type' => 'assign-permission-to-role'])?>"><i class="fa fa-circle-o"></i> Role Responsibilities</a></li>
                              </ul>
                        </li>
                        
                        <li class="treeview">
                            <a href=""><i class="fa fa-dashboard"></i> <span>CRUD Controllers</span> <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li class="active"><a href="#"><i class="fa fa-circle-o"></i>Under... development</a></li>
                                <!--<li><a href="<?= Url::toRoute(['/department/index'])?>"><i class="fa fa-circle-o"></i>View Departments</a></li>-->
                                <!--<li><a href="<?= Url::toRoute(['/auth-assignment/index'])?>"><i class="fa fa-circle-o"></i>Manage Authorization Rules</a></li>-->
                              </ul>
                        </li>
                  <?php endif;?>  
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
