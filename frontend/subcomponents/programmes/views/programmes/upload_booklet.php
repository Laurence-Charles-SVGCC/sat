<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\file\FileInput;
    
    use common\models\User;
    
    $this->title = 'Upload Programme Booklet';
    
    $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => 'Programme Overview', 'url' => Url::to(['programmes/programme-overview',
                                                            'programmecatalogid' => $programmecatalogid
                                                            ])];
     $this->params['breadcrumbs'][] = ['label' => 'Academic Offering', 'url' => Url::to(['programmes/get-academic-offering', 'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid])];
    $this->params['breadcrumbs'][] = $this->title;
    
?>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin(['id' => 'upload-booklet', 'options' => ['enctype' => 'multipart/form-data']]);?>
        <div class="box-body">
             <?= $form->field($model, 'files[]')
                            ->label('Select programme booklet file:', ['class'=> 'form-label',])
                            ->fileInput(['multiple' => true,
                                        'style' => 'text-align: center; font: bold 18px Arial, Helvetica, Geneva, sans-serif; color: #4B4B55;text-shadow: #fffeff 0 1px 0; padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #e4e4e4;'
                                    ]); 
            ?>
        </div>
        
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Upload', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                 <?= Html::a(' Cancel',['programmes/get-academic-offering', 'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid],
                                        ['class' => 'btn btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>