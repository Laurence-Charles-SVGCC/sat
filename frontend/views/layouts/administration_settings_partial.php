<?php
    use yii\helpers\Url;
    use frontend\models\ApplicationSettings;
?>

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