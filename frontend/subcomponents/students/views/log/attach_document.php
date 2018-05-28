<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\file\FileInput;
    
    use common\models\User;
    
    $this->title = 'Upload Documents';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find An Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = ['label' => 'Student Profile', 'url' => Url::toRoute(['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?=$this->title?></span>
    </div>
    
    <div class="box-body">
        <fieldset>
            <legend><strong>File Listing</strong></legend>
            <table class='table table-hover' style='margin: 0 auto;'>
                <?php if(!$saved_documents):?>
                    <p>No files are currently attached to this note</p>
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
        </fieldset><br/>
    </div>
    
    <?php 
        $form = ActiveForm::begin([
            'id' => 'upload-attachments',
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]) 
    ?>
        <div class="box-body">
            <?= $form->field($model, 'files[]')
                            ->label('Select documents you would like to attach to package:', ['class'=> 'form-label',])
                            ->fileInput(['multiple' => true,]); 
            ?>
        </div>
    
        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['log/event-details', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid, 'eventid' => $eventid, 'eventtypeid' => $eventtypeid, 'recordid' => $recordid], ['class' => 'btn  btn-danger']);?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
