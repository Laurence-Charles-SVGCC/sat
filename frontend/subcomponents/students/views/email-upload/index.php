<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;

    $this->title = "Email Upload Dashboard";
?>
    
<div class="page-header text-center no-padding">
     <a href="<?= Url::toRoute(['/subcomponents/students/email-upload/index']);?>" title="Email Management">     
        <h1>Welcome to the Email Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em">
     <div class="box-header with-border">
         <span class="box-title"><?= $this->title?></span>
    </div>
    
    <div class="box-body">
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
