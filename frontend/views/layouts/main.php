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

    $avatar = "css/dist/img/logo.png";      // Default avatar

    if (Yii::$app->user->isGuest == false)
    {
        $employee = Employee::find()->where(['personid' => Yii::$app->user->getId()])->one();
        $employee_name = $employee->firstname . " " . $employee->lastname;
        $employee_username = Yii::$app->user->identity->username;
        $title = EmployeeTitle::findOne(['employeetitleid' => $employee->employeetitleid]);
        $employee_job_title = $title? $title->name: "";
        if ($employee->gender == "m")
        {
            $avatar = "css/dist/img/avatar_male(150_150).png";
        }
        elseif ($employee->gender == "f")
        {
            $avatar = "css/dist/img/avatar_female(150_150).png";
        }
    }
    else
    {
        $employee_name = "Error";
        $employee_username = "Unknown";
        $employee_job_title = "Error: Please login";
    }

    $theme = (ApplicationSettings::getApplicationSettings()->is_online == true) ? "skin-green-light sidebar-mini" : "skin-red-light sidebar-mini";

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

        <body class=<?= $theme ?>>
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

                              <?php if (Yii::$app->user->isGuest == false): ?>
                                <div class="navbar-custom-menu">
                                <ul class="nav navbar-nav">
                                  <!-- User Account: style can be found in dropdown.less -->
                                  <li class="dropdown user user-menu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <span class="hidden-xs"><?= $employee_name ?></span>
                                    </a>

                                    <ul class="dropdown-menu">
                                      <!-- User image -->
                                      <li class="user-header">
                                        <img src=<?= $avatar ?> class="img-circle" alt="User Image" />
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
                              <?php endif; ?>
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
                            <?= Yii::$app->controller->renderPartial('//layouts/administration_settings_partial'); ?>
                        <?php endif;?>


                       <?php if (Yii::$app->user->can('System Administrator')  || Yii::$app->user->can('Registrar')): ?>
                          <?= Yii::$app->controller->renderPartial('//layouts/apply_administration_partial'); ?>
                       <?php endif;?>


                       <?php if (Yii::$app->user->can('admissions')): ?>
                          <?= Yii::$app->controller->renderPartial('//layouts/admissions_partial'); ?>
                       <?php endif; ?>


                        <?php if (Yii::$app->user->can('registry')): ?>
                            <?= Yii::$app->controller->renderPartial('//layouts/registry_partial'); ?>
                        <?php endif; ?>


                        <?php if (Yii::$app->user->can('students')): ?>
                            <?= Yii::$app->controller->renderPartial('//layouts/students_partial'); ?>
                        <?php endif; ?>


                        <?php if (Yii::$app->user->can('gradebook')): ?>
                          <?= Yii::$app->controller->renderPartial('//layouts/gradebook_partial'); ?>
                        <?php endif; ?>


                       <?php if (Yii::$app->user->can('Bursar')  || Yii::$app->user->can('Bursary Staff')):?>
                           <?= Yii::$app->controller->renderPartial('//layouts/payments_partial'); ?>
                       <?php endif; ?>

                       <?php if (false):?>
                           <?= Yii::$app->controller->renderPartial('//layouts/legacy_partial'); ?>
                       <?php endif;?>


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
                        <section class="content-header">
                            <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
                        </section><br/><br/>

                        <!-- Content Header (Page header) -->
                        <div><?= Alert::widget() ?></div>

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
