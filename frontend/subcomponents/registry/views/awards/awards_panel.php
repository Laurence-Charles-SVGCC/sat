<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $this->title = 'Awards Control Panel';
?>


    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/registry/awards/manage-awards']);?>" title="Manage Awards">     
                    <img class="custom_logo_students" src ="<?=Url::to('../images/award.png');?>" alt="award avatar">
                    <span class="custom_module_label" style="margin-left:5%;"> Welcome to the Award Management System</span> 
                    <img src ="<?=Url::to('../images/award.png');?>" alt="award avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$this->title?></h1>
                
                </br>                              
                <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Awards Listing
                        <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/registry/awards/configure-award', 'action' => 'create']);?> role="button"> Create Award</a>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>

