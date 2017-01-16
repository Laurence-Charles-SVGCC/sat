<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\file\FileInput;
    
    $this->title = 'Upload File';
    $this->params['breadcrumbs'][] = ['label' => 'Email Dashboard', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
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
       <div class="alert alert-info" role="alert">
            <strong>
                Please read the file requirement below and make the necessary correction to your files before upload.
                If criteria is not met file will upload, but will not be able to be processed.
            </strong>
        </div>
        
         <ol>
            <li>File must not exceed 100 records.</li>
            <li>File must contain a title row.</li>
            <li>All rows must contain seven(2) columns.</li>
            <li>
                All records must contain the following columns in the given ordered:
                <ol type="i">
                    <li>username</li>
                    <li>email</li>
                </ol>
            </li>
        </ol>
    </div>
    
    <div class="box-footer">
        <?php 
                $form = ActiveForm::begin([
                    'id' => 'upload-email-address-files',
                    'options' => [
                        'enctype' => 'multipart/form-data'
                    ]
                ]) 
            ?>

                <?= $form->field($model, 'files[]')
                        ->label('Select file you would like to upload:', ['class'=> 'form-label'])
                        ->fileInput(['multiple' => true]); 
                ?>

                <span class = "pull-right">
                    <?= Html::submitButton(' Upload', ['class' => 'glyphicon glyphicon-upload btn btn-success pull-left', 'style' => 'margin-right:20px']);?>
                    <?= Html::a(' Cancel',['email-upload/index'], ['class' => 'btn btn-danger']);?>
                </span>
        
            <?php ActiveForm::end() ?>
    </div>
</div>

