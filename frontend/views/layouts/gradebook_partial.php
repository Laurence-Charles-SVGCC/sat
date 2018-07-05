<?php
    use yii\helpers\Url;
?>

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

