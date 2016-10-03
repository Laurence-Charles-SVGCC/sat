<?php

/* 
 * Author: Laurence Charles
 * Date Created: 25/09/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;

    $this->title = "Email Upload Dashboard";
?>
    
<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/students/email-upload/index']);?>" title="Email Management">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/email.png" alt="email">
                <span class="custom_module_label">Welcome to the Email Management System</span> 
                <img src ="css/dist/img/header_images/email.png" alt="email" class="pull-right">
            </a>        
        </div>

        <div class="custom_body"> 
            <h1 class="custom_h1"><?= $this->title;?></h1>

            <div style="margin-left:2.5%"><br/>
                <p>Record Count = <?=$count;?></p>
                <p>Filename = <?=$filename;?></p>
                <p>New Filename = <?=$new_filename;?></p>
                <p>Column count = <?=$columns;?></p>
                <p>Username = <?=$username;?></p>
                <p>Email = <?=$school_email;?></p>
        </div>
    </div>
</div>


