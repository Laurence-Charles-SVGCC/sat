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

    $this->title = 'Application Period Setup Step-3';
    
    $options = [
       'yes' => 'Yes',
       'no' =>'No'
   ];
    
    $this->params['breadcrumbs'][] = ['label' => 'Period Listing', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/manage-application-period'])];
    $this->params['breadcrumbs'][] = ['label' => 'Setup Dashboard', 'url' => Url::toRoute(['admissions/initiate-period', 'recordid' => $period->applicationperiodid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/package']);?>" title="Manage Packages">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/>

<h2 class="text-center">Assign Programmes</h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
    <div class="box-body">
        <ul>
            <li>
                <p>
                You are currently in the process of creating an application period session for
                the <?=Division::getDivisionName($period->divisionid)?>.
                </p>
            </li>

            <li>
                <p>
                    Please carefully review the list of programmes below.  If you wish to make an addition to this list
                    please click the appropriate button.
                </p>
            </li>
        </ul>

        <div id="programme-list">
            <br/>
            <?php if ($period->divisionid == 4):?>
                <?php $count = (count($programmes) > count($subjects))?count($programmes):count($subjects);?>
                <table class="table table-condensed">
                    <tr>
                        <th></th>
                        <th><strong>Programmes</strong></th>
                        <th><strong>Cape Subjects</strong></th>
                    </tr>

                    <?php for($i = 0 ; $i < $count ; $i++):?>
                        <tr>
                            <td><?=($i+1)?></td>
                            <?php if($i < count($programmes)):?>
                                <?php 
                                    if($programmes[$i]["specialisation"]!=NULL || strcmp($programmes[$i]["specialisation"],"") != 0)
                                        $specialisation = " (" . $programmes[$i]["specialisation"] . ")";
                                    else
                                        $specialisation = "";
                                ?>
                                <td><?= $programmes[$i]["qualification"] . ". " . $programmes[$i]["name"] . $specialisation?></td>
                            <?php else:?> 
                                <td></td>
                            <?php endif;?>

                            <?php if($i < $count):?>
                                <td><?=$subjects[$i]["name"]?></td>
                            <?php else:?> 
                                <td></td>
                            <?php endif;?>
                        </tr>
                    <?php endfor;?>

                    <tr>
                        <td></td>
                        <td><?= Html::a(' Add New Programme',['admissions/add-programme-catalog'], ['class' => 'btn btn-block btn-lg btn-success glyphicon glyphicon-plus pull-left', 'style' => 'margin:10px;']);?></th>
                        <td><?= Html::a(' Add New CAPE Subject',['admissions/add-cape-subject'], ['class' => 'btn btn-block btn-lg btn-success glyphicon glyphicon-plus pull-left', 'style' => 'margin:10px']);?></td>
                    </tr>
                </table> 


            <?php else:?>
                <?php $count = count($programmes);?>
                    <table class="table table-condensed">
                        <tr>
                            <th></th>
                            <th><strong>Programmes</strong></th>
                        </tr>

                        <?php for($i = 0 ; $i < $count ; $i++):?>
                            <tr>
                                <td><?=($i+1)?></td>
                                <?php if($i < count($programmes)):?>
                                    <?php 
                                        if($programmes[$i]["specialisation"]!=NULL || strcmp($programmes[$i]["specialisation"],"") != 0)
                                            $specialisation = " (" . $programmes[$i]["specialisation"] . ")";
                                        else
                                            $specialisation = "";
                                    ?>
                                        <td><?= $programmes[$i]["qualification"] . ". " . $programmes[$i]["name"] . $specialisation?></td>
                                <?php else:?> 
                                    <td></td>
                                <?php endif;?>
                            </tr>
                        <?php endfor;?>

                        <tr>
                            <td colspan="2"><?= Html::a(' Add New Programme',['admissions/add-programme-catalog'], ['class' => 'btn btn-block btn-lg btn-success glyphicon glyphicon-plus pull-left']);?></th>
                        </tr>
                    </table> 
            <?php endif;?>
        </div>

        <ul>
            <li>
                <p id="finished-entering-programmes">
                    Do you have any programs left to add to the list above?
                    <?= Html::radioList('more-programmes', 'yes', $options, ['class'=> 'form-field', 'onclick'=> 'toggleAcademicOfferingForm()']);?>               
                </p>
            </li>
        </ul>

        <?php
            $form = ActiveForm::begin([
                'id' => 'add-academic-offering-form',
                'options' => [
                    'style' => 'display:none;font-size:16px;'
                ],
            ]);?>

                <br/><p style ="font-size:18px;">Please select all programmes you wish to create an academic offer for this application period</p>                      
                <?php for($i = 0 ; $i < count($programmes) ; $i++): ?>
                    <?php 
                        if($programmes[$i]["specialisation"]!=NULL || strcmp($programmes[$i]["specialisation"],"") != 0)
                            $specialisation = " (" . $programmes[$i]["specialisation"] . ")";
                        else
                            $specialisation = "";
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($offerings[$i], '['.$i.']programmecatalogid')->checkbox(
                                [   'unchecked' => false,
                                    'label' => $programmes[$i]["qualification"] . ". " . $programmes[$i]["name"] . $specialisation 
                                ]) 
                            ?>
                        </div>
                        <div class="col-md-2">
                            <?= $form->field($offerings[$i], '['.$i.']spaces')->label('Capacity')->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($offerings[$i], '['.$i.']interviewneeded')->checkbox(['label' => 'Interview Needed']) ?>
                        </div>
                    </div>
                <?php endfor; ?>


                 <?php if ($period->divisionid == 4):?>
                    <br/><p style ="font-size:18px;">Please select all the CAPE subjects you wish to create an academic offer for this application period</p>

                    <?php for($j = 0 ; $j < count($subjects) ; $j++): ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($cape_subjects[$j], '['.$j.']subjectname')->checkbox(
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

                             <div class="col-md-4">
                                <?= $form->field($subject_groups[$j], '['.$j.']capegroupid')->label('Group')->dropDownList(ArrayHelper::map(CapeGroup::find()->all(), 'capegroupid', 'name'), ['prompt'=>'Select Cape Group']) ?>
                            </div>
                        </div>
                    <hr>
                    <?php endfor; ?>
                <?php endif;?>
      
            <span class="pull-right">
                <?=Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'margin-right:20px;']);?>
                <?=Html::a(' Back',['admissions/initiate-period', 'recordid' => $period->applicationperiodid], ['class' => 'btn btn-danger', 'id' => 'back-button']);?>              
            </span>
    <?php ActiveForm::end();?>
    </div>
</div>