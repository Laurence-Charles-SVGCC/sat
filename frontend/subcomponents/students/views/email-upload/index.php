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
                <div id="email-dashboard-options">
                    Please select appropriate action.
                    <?= Html::radioList('email-action', null, [ '0' => 'Upload New File(s)', '1' => 'View Uploaded File(s)'], ['class'=> 'form_field', 'onclick'=> 'toggleEmailActions();']);?>
               </div><br/>
                    
                <div id="upload-new-file" style="display:none">
                    <a class="btn btn-success glyphicon glyphicon-upload" href=<?=Url::toRoute(['/subcomponents/students/email-upload/upload-email-file']);?> role="button">  Upload File(s)</a>
                </div> 

                <div id="process-file" style="display:none">
                    <a class="btn btn-success glyphicon glyphicon-folder-open" href=<?=Url::toRoute(['/subcomponents/students/email-upload/view-email-files']);?> role="button">  View File(s)</a>
                </div>
        </div>
    </div>
</div>


