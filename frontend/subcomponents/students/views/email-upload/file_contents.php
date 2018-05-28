<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;

    $this->title = "Email Upload Dashboard";
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em">
     <div class="box-header with-border">
         <span class="box-title"><?= $this->title?></span>
    </div>
    
    <div class="box-body">
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