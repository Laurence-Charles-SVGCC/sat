<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;

    $this->title = 'Application Period Setup Step-5';
    $this->params['breadcrumbs'][] = ['label' => 'Period Listing', 'url' => Url::toRoute(['/subcomponents/applications/application-periods/view-periods'])];
    $this->params['breadcrumbs'][] = ['label' => 'Setup Dashboard', 'url' => Url::toRoute(['initiate-period', 'id' => $period->applicationperiodid])];
    $this->params['breadcrumbs'][] = $this->title;
?>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
    <div class="box-header with-border">
        <span class="box-title">Assign Cape Subjects</span>
    </div>
    
    <div class="box-body">
        <div class="alert alert-info">
            Please select all the CAPE subjects you wish to create an academic offer for this application period.
        </div>

        <?php $form = ActiveForm::begin();?>
            <?php for($j = 0 ; $j < count($subjects) ; $j++): ?>
                <div class="row">
                    <div class="col-md-5">
                        <?= $form->field($cape_subjects[$j], '['.$j.']isactive')->checkbox(
                            [   'unchecked' => false,
                                'label' => $subjects[$j]['name']
                            ]) 
                        ?>
                    </div>

                    <div class="col-md-2">
                        <?= $form->field($cape_subjects[$j], '['.$j.']unitcount')->dropDownList([NULL => 'Please Select', 1 => '1', 2 => '2']) ?>
                    </div>

                    <div class="col-md-2">
                        <?= $form->field($cape_subjects[$j], '['.$j.']capacity')->textInput() ?>
                    </div>

                     <div class="col-md-3">
                        <?= $form->field($subject_groups[$j], '['.$j.']capegroupid')->label('Group')->dropDownList(ArrayHelper::map($cape_groups, 'capegroupid', 'name'), ['prompt'=>'Select Cape Group']) ?>
                    </div>
                </div><hr/>
            <?php endfor; ?>
      
            <span class="pull-right">
                <?=Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'margin-right:20px;']);?>
                <?=Html::a(' Back',['manage-application-periods/view-application-period', 'id' => $period->applicationperiodid], ['class' => 'btn btn-danger', 'id' => 'back-button']);?>          
            </span>
        <?php ActiveForm::end();?>
    </div>
</div>