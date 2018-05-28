<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    $this->title = 'Application Period Setup Step-4';
    $this->params['breadcrumbs'][] = ['label' => 'Period Listing', 'url' => Url::toRoute(['/subcomponents/applications/application-periods/view-periods'])];
    $this->params['breadcrumbs'][] = ['label' => 'Setup Dashboard', 'url' => Url::toRoute(['initiate-period', 'id' => $period->applicationperiodid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
    <div class="box-header with-border">
        <span class="box-title">Assign Programmes</span>
    </div>
    
    <div class="box-body">
        <?php $form = ActiveForm::begin() ?>
            <div class="alert alert-info">
                Please select all programmes you wish to create an academic offer for this application period.
            </div>                      
            
            <?php for($i = 0 ; $i < count($programmes) ; $i++): ?>
                <div class="row">
                    <div class="col-md-5">
                        <?= $form->field($offerings[$i], '['.$i.']isactive')->checkbox(
                            [   'unchecked' => false,
                                'label' => $programmes[$i]->getFormalProgrammeName()]) ?>
                    </div>
                    
                    <div class="col-md-3">
                        <?= $form->field($offerings[$i], '['.$i.']spaces')->label('Capacity')->textInput() ?>
                    </div>
                    
                    <div class="col-md-2">
                        <?= $form->field($offerings[$i], '['.$i.']interviewneeded')->checkbox(['label' => 'Interview Needed']) ?>
                    </div>
                    
                     <div class="col-md-2">
                        <?= $form->field($offerings[$i], '['.$i.']credits_required')->label('Graduation Credits')->textInput() ?>
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