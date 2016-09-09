<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/bootstrap/css/bootstrap.min.css',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
        //'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
        'css/dist/css/AdminLTE.min.css',
        'css/dist/css/skins/_all-skins.min.css',
        'css/plugins/iCheck/flat/blue.css',
        'css/plugins/morris/morris.css',
        'css/plugins/jvectormap/jquery-jvectormap-1.2.2.css',
        'css/plugins/datepicker/datepicker3.css',
        'css/plugins/daterangepicker/daterangepicker-bs3.css',
        'css/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
        'css/gradebook.css',
    ];
    public $js = [
        'css/bootstrap/js/bootstrap.min.js',
        'css/plugins/sparkline/jquery.sparkline.min.js',
        'css/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        'css/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        'css/plugins/knob/jquery.knob.js',
        //'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js',
        'css/plugins/daterangepicker/daterangepicker.js',
        'css/plugins/datepicker/bootstrap-datepicker.js',
        'css/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
        'css/plugins/slimScroll/jquery.slimscroll.min.js',
        'css/plugins/fastclick/fastclick.min.js',
        'css/dist/js/app.min.js',
        /*'css/dist/js/pages/dashboard.js', Clashes with DosAmigos Datepicker*/
        'css/dist/js/demo.js',
        'js/gradebook/home.js',
        'js/gradebook/programme_listing.js',
        'js/gradebook/edit_assessment.js',
        'js/students/students.js',
        'js/students/ajax_functions.js',
        'js/students/transfers_and_deferrals.js',
        'js/admissions/admissions.js',
        'js/admissions/reports.js',
        'js/admissions/register_student.js',
        'js/admissions/qualification_ajax_functions.js',
        'js/admissions/period_setup_one.js',
        'js/admissions/verify-applicants/view-applicant-qualifications.js',
        'js/registry/registry.js',
        'js/programmes/programmes.js',
        'js/programmes/cordinator.js',
        'js/legacy/students.js',
        'js/legacy/batch.js',
        'js/legacy/grades.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
