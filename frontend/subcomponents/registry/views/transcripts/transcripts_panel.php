<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $this->title = 'Transcript Control Panel';
?>


    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/registry/transcripts/manage-clubs']);?>" title="Manage Transcripts">     
                    <img class="custom_logo_students" src ="<?=Url::to('../images/transcript.png');?>" alt="transcript avatar">
                    <span class="custom_module_label">Welcome to the Transcript Management System</span> 
                    <img src ="<?=Url::to('../images/transcript.png');?>" alt="transcript avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$this->title?></h1>
                
                </br>
                <img style="display: block; margin: auto;" src ="<?=Url::to('../images/under_construction.jpg');?>" alt="Under Construction">
                    
              
            </div>
        </div>
    </div>

