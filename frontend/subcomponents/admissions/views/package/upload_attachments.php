<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\file\FileInput;
    
    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\PackageType;
    use frontend\models\PackageProgress;
    
    $this->title = 'Upload Attachments';
    
    $this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => Url::toRoute(['/subcomponents/admissions/package'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
   
    <?php if ($mandatory_delete == true):?>
        <div class="box-body">
            <p>You are reach your stipulated number of documents, you must either change the limit or 
                delete a file.
            </p><br/>

            <fieldset>
                <legend><strong>File Listing</strong></legend>
                <table class='table table-hover' style='margin: 0 auto;'>
                    <tr>
                        <th>File Name</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach($saved_documents as $index=>$doc):?>
                        <tr>
                            <td><?=  substr($doc,58)/*$doc*/?></td>
                            <td>
                                <?=Html::a(' Delete', 
                                            ['package/delete-attachment', 'recordid' => $recordid, 'count' => $count, 'index' => $index], 
                                            ['class' => 'btn btn-danger',
                                                'data' => [
                                                    'confirm' => 'Are you sure you want to delete this item?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </fieldset>
        </div>
    
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::a(' Back', ['package/initiate-package', 'recordid' => $recordid], ['class' => 'btn  btn-danger']);?>         
            </span>
        </div>
    <?php else:?>
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>
            <div class="box-body">
                <?= $form->field($model, 'files[]')
                                ->label('Select documents you would like to attach to package:')
                                ->fileInput(['multiple' => true]); 
                ?>
            </div>

            <div class="box-footer">
                <span class = "pull-right">
                    <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                    <?= Html::a(' Cancel', ['package/initiate-package', 'recordid' => $recordid], ['class' => 'btn  btn-danger']);?>         
                </span>
            </div>
            <?php ActiveForm::end(); ?>
    <?php endif;?>
</div>