<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\CapeSubject;
    use frontend\models\CapeGroup;

    $this->title = 'Manage Programme Offerings';
    $this->params['breadcrumbs'][] = ['label' => 'Application Periods', 'url' => Url::toRoute(['/subcomponents/applications/application-periods/view-periods'])];
    $this->params['breadcrumbs'][] = ['label' => $period->name, 'url' => Url::toRoute(['/subcomponents/applications/manage-application-periods/view-application-period', 'id' => $period->applicationperiodid])];
    $this->params['breadcrumbs'][] = $this->title;
?>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/>

<h2 class="text-center"><?= $period->getDivision()->name ?> Offerings</h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
    <div class="box-body">
        <div class="alert alert-info" style ="font-size:1.1em">
            Please carefully review the list of programmes below.  If you wish to make an addition to this list please click the appropriate button.
        </div>

        <div id="programme-list">
            <?php if ($period->divisionid == 4):?>
                <table class="table table-condensed">
                    <tr>
                        <th></th>
                        <th><strong>Programmes</strong></th>
                        <th><strong>Cape Subjects</strong></th>
                    </tr>

                    <?php for($i = 0 ; $i < $offerings_limit ; $i++):?>
                        <tr>
                            <td><?=($i+1)?></td>
                            <?php if($i < count($programmes)):?>
                                <td><?= $programmes[$i]->getFormalProgrammeName() ?></td>
                            <?php else:?> 
                                <td></td>
                            <?php endif;?>

                            <?php if($i < $offerings_limit):?>
                                <td><?=$subjects[$i]["name"]?></td>
                            <?php else:?> 
                                <td></td>
                            <?php endif;?>
                        </tr>
                    <?php endfor;?>

                    <tr>
                        <td></td>
                        <td><?= Html::a(' Add New Programme',['manage-application-periods/add-programme-to-catalog','id' => $period->applicationperiodid ], ['class' => 'btn btn-block btn-success pull-left', 'style' => 'margin:10px;']);?></td>
                        <td><?= Html::a(' Add New CAPE Subject',['manage-application-periods/add-cape-subject', 'id' => $period->applicationperiodid], ['class' => 'btn btn-block btn-success glyphicon pull-left', 'style' => 'margin:10px']);?></td>
                    </tr>
                </table> 


            <?php else:?>
                <table class="table table-condensed">
                    <tr>
                        <th></th>
                        <th><strong>Programmes</strong></th>
                    </tr>

                    <?php for($i = 0 ; $i < $programme_count ; $i++):?>
                        <tr>
                            <td><?=($i+1)?></td>
                            <?php if($i < count($programmes)):?>
                                <td><?= $programmes[$i]->getFormalProgrammeName() ?></td>
                            <?php else:?> 
                                <td></td>
                            <?php endif;?>
                        </tr>
                    <?php endfor;?>

                    <tr>
                        <td colspan="2"><?= Html::a(' Add New Programme',['manage-application-periods/add-programme-to-catalog', 'id' => $period->applicationperiodid], ['class' => 'btn btn-block btn-lg btn-success glyphicon glyphicon-plus pull-left']);?></td>
                    </tr>
                </table> 
            <?php endif;?>
        </div>

        <ul>
            <li>
                <p id="finished-entering-programmes">
                    Do you have any programs left to add to the list above?
                    <?= Html::radioList('more-programmes', 'yes', ['yes' => 'Yes', 'no' =>'No'], ['class'=> 'form-field', 'onclick'=> 'toggleAcademicOfferingForm()']);?>               
                </p>
            </li>
        </ul>

        <?php
            $form = ActiveForm::begin([
                'id' => 'add-academic-offering-form',
                'options' => [
                    'style' => 'display:none; font-size:16px;']
            ]);?>

                <div class="alert alert-info" style ="font-size:1.1em">
                    Please select all programmes you wish to create an academic offer for this application period.
                </div>
        
                <?php for($i = 0 ; $i < count($programmes) ; $i++): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($offerings[$i], '['.$i.']programmecatalogid')->checkbox(
                                [   'unchecked' => false,
                                    'label' => $programmes[$i]->getFormalProgrammeName()
                                ]) 
                            ?>
                        </div>
                        
                        <div class="col-md-3">
                            <?= $form->field($offerings[$i], '['.$i.']spaces')->label('Capacity')->textInput() ?>
                        </div>
                        
                        <div class="col-md-3">
                            <?= $form->field($offerings[$i], '['.$i.']interviewneeded')->checkbox(['label' => 'Interview Needed']) ?>
                        </div>
                    </div>
                <?php endfor; ?>


                 <?php if ($period->divisionid == 4):?>
                    <div class="alert alert-info" style ="font-size:1.1em">
                        Please select all the CAPE subjects you wish to create an academic offer for this application period.
                    </div>

                    <?php for($j = 0 ; $j < count($subjects) ; $j++): ?>
                        <div class="row">
                            <div class="col-md-3">
                                <?= $form->field($cape_subjects[$j], '['.$j.']subjectname')->checkbox(
                                    [   'unchecked' => false,
                                        'label' => $subjects[$j]['name']
                                    ]) 
                                ?>
                            </div>

                            <div class="col-md-3">
                                <?= $form->field($cape_subjects[$j], '['.$j.']unitcount')->dropDownList([NULL => 'Please Select', 1 => '1', 2 => '2']) ?>
                            </div>

                            <div class="col-md-3">
                                <?= $form->field($cape_subjects[$j], '['.$j.']capacity')->textInput() ?>
                            </div>

                             <div class="col-md-3">
                                <?= $form->field($subject_groups[$j], '['.$j.']capegroupid')->label('Group')->dropDownList(ArrayHelper::map(CapeGroup::find()->all(), 'capegroupid', 'name'), ['prompt'=>'Select Cape Group']) ?>
                            </div>
                        </div>
                    <hr>
                    <?php endfor; ?>
                <?php endif;?>
      
            <span class="pull-right">
                <?=Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'margin-right:20px;']);?>
                <?=Html::a(' Back',['manage-application-periods/view-application-period', 'id' => $period->applicationperiodid], ['class' => 'btn btn-danger', 'id' => 'back-button']);?>              
            </span>
    <?php ActiveForm::end();?>
    </div>
</div>