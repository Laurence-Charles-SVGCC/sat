<?php

/* 
 * Author: Laurence Charles
 * Date Created 02/05/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\file\FileInput;
    
    use common\models\User;
    
    $this->title = 'Upload Documents';
?>

<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/sms_4.png');?>" alt="Find A Student">
                <span class="custom_module_label">Welcome to the Student Management System</span> 
                <img src ="<?=Url::to('../images/sms_4.png');?>" alt="student avatar" class="pull-right">
            </a>    
        </div>
        
        
        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title?></h1>
            
            <br/>
            <div style="width:80%; margin: 0 auto; font-size: 20px;">
                <fieldset>
                    <legend class="custom_h2">File Listing</legend>
                    <table class='table table-hover' style='margin: 0 auto;'>
                        <?php if(!$saved_documents):?>
                            <h3>No files are currently attached to this note</h3>
                        <?php else:?>
                            <tr>
                                <th>Filename</th>
                                <th>Action</th>
                            </tr>
                            <?php foreach($saved_documents as $index=>$doc):?>
                                <tr>
                                    <td><?=  substr($doc,65)/*$doc*/?></td>
                                    <td>
                                        <?=Html::a(' Delete', 
                                                    ['log/delete-attachment', 'index' => $index, 'personid' => $personid, 'studentregistrationid' => $studentregistrationid, 'eventid' => $eventid, 'eventtypeid' => $eventtypeid, 'recordid' => $recordid], 
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
                        <?php endif;?>
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

                    <?= Html::a(' Cancel',
                                ['log/event-details', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid, 'eventid' => $eventid, 'eventtypeid' => $eventtypeid, 'recordid' => $recordid],
                                ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-right', 'style' => 'width:20%; margin-left:5%;']
                                );
                    ?>
                    <?= Html::submitButton('Upload', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:20%;']);?>

                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

