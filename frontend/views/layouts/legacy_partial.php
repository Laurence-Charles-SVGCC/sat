<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>

<li class="treeview">
    <a href="">
        <i class="glyphicon glyphicon-hourglass"></i> <span>Legacy</span> <i class="fa fa-angle-left pull-right"></i>
    </a>

    <ul class="treeview-menu">
        <li class="treeview">
            <a href="">
                <i class="fa fa-circle-o"></i><span>CRUD Controls</span> <i class="fa fa-angle-left pull-right"></i>
            </a>

            <ul class="treeview-menu">
                <li><a href="<?= Url::toRoute(['/subcomponents/legacy/level/index'])?>"><i class="fa fa-circle-o"></i>Levels</a></li>
                <li><a href="<?= Url::toRoute(['/subcomponents/legacy/faculty/index'])?>"><i class="fa fa-circle-o"></i>Faculties</a></li>
            </ul>
        </li>

        <li></li>
    </ul> 
</li>

