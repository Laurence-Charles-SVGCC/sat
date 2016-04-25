<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $this->title = 'Clubs Control Panel';
?>


    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/registry/clubs/manage-clubs']);?>" title="Manage Clubs">     
                    <img class="custom_logo_students" src ="<?=Url::to('../images/club.png');?>" alt="club avatar">
                    <span class="custom_module_label" style="margin-left:8%;">Welcome to the Club Management System</span> 
                    <img src ="<?=Url::to('../images/club.png');?>" alt="club avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$this->title?></h1>
                
                </br>                              
                <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Clubs Listing
                        <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/registry/clubs/create-club']);?> role="button"> Create Club</a>
                    </div>
                    
                    </br>
                    <img style="display: block; margin: auto;" src ="<?=Url::to('../images/under_construction.jpg');?>" alt="Under Construction">
                    
                    
                </div>
            </div>
        </div>
    </div>

