<?php

/* 
 * Author: Laurence Charles
 * Date Created 12/04/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\file\FileInput;
    
    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\PackageType;
    use frontend\models\PackageProgress;
    
    $this->title = 'Upload Attachments';
?>

<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>   
        </div>
        
        
        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title?></h1>
            
            <br/>
            <div style="width:80%; margin: 0 auto; font-size: 20px;">
            
                <?php if ($mandatory_delete == true):?>
                    <p>You are reach your stipulated number of documents, you must either change the limit or 
                        delete a file.
                    </p><br/>
                    
                    <fieldset>
                        <legend class="custom_h2">File Listing</legend>
                        <table class='table table-hover' style='margin: 0 auto;'>
                            <tr>
                                <th>Current Files</th>
                                <th>Action</th>
                            </tr>
                            <?php foreach($saved_documents as $index=>$doc):?>
                                <tr>
                                    <td><?=  substr($doc,58)/*$doc*/?></td>
                                    <td>
                                        <?=Html::a(' Delete', 
                                                    ['package/delete-attachment', 'recordid' => $recordid, 'count' => $count, 'index' => $index], 
                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                        'data' => [
                                                            'confirm' => 'Are you sure you want to delete this item?',
                                                            'method' => 'post',
                                                        ],
                                                        'style' => 'margin-right:20px',
                                                    ]);
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        </table>
                    </fieldset><br/><br/>
                    <?= Html::a(' Cancel',['package/initiate-package', 'recordid' => $recordid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-right', 'style' => 'width:20%; margin-left:5%;']);?>

                <?php else:?>
                    <fieldset>
                        <legend class="custom_h2">Current File Listing</legend>
                        <table class='table table-hover' style='margin: 0 auto;'>
                            <tr>
                                <th>Filename</th>
                                <th>Action</th>
                            </tr>
                            <?php foreach($saved_documents as $index=>$doc):?>
                                <tr>
                                    <td><?=substr($doc,24)?></td>
                                    <td>
                                        <?=Html::a(' Delete', 
                                                    ['package/delete-attachment', 'recordid' => $recordid, 'count' => $count, 'index' => $index], 
                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                        'data' => [
                                                            'confirm' => 'Are you sure you want to delete this item?',
                                                            'method' => 'post',
                                                        ],
                                                        'style' => 'margin-right:20px',
                                                    ]);
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        </table>
                    </fieldset><br/><br/>
                    
                    <?php 
                        $form = ActiveForm::begin([
                            'id' => 'upload-attachments',
                            'options' => [
                                'enctype' => 'multipart/form-data'
                            ]
                        ]) 
                    ?>

                        <?= $form->field($model, 'files[]')
                                ->label('Select documents you would like to attach to package:', 
                                        [
                                            'class'=> 'form-label',
                                        ])
                                ->fileInput(
                                        [
                                            'multiple' => true,
                                            'style' => 'text-align: center; font: bold 25px Arial, Helvetica, Geneva, sans-serif; color: #4B4B55;text-shadow: #fffeff 0 1px 0; padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #e4e4e4;'
                                        ]); ?>

                        <br/>
                        <?= Html::a(' Cancel',['package/initiate-package', 'recordid' => $recordid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-right', 'style' => 'width:20%; margin-left:5%;']);?>
                        <?= Html::submitButton('Upload', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:20%;']);?>

                    <?php ActiveForm::end() ?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

