<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    
    $this->title = 'Application Period Setup Step-3';
    $this->params['breadcrumbs'][] = ['label' => 'Period Listing', 'url' => Url::toRoute(['/subcomponents/applications/application-periods/view-periods'])];
    $this->params['breadcrumbs'][] = ['label' => 'Setup Dashboard', 'url' => Url::toRoute(['initiate-period', 'id' => $period->applicationperiodid])];
    $this->params['breadcrumbs'][] = $this->title;
?>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
    <div class="box-header with-border">
        <span class="box-title"> Programme Catalog Approval</span>
    </div>
    
    <div class="box-body">
        <ul>
            <li>
                <p>
                You are currently in the process of creating an application period session for
                the <?= $period->getDivisionName()?>.
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
                        <td><?= Html::a(' Add New Programme',['manage-application-periods/add-programme-to-catalog'], ['class' => 'btn btn-block btn-info pull-left', 'style' => 'margin:10px;']);?></td>
                        <td><?= Html::a(' Add New CAPE Subject',['manage-application-periods/add-cape-subject'], ['class' => 'btn btn-block btn-info glyphicon pull-left', 'style' => 'margin:10px']);?></td>
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
                            <td colspan="2"><?= Html::a(' Add New Programme',['manage-application-periods/add-programme-to-catalog'], ['class' => 'btn btn-block btn-lg btn-info glyphicon glyphicon-plus']);?></td>
                        </tr>
                    </table> 
            <?php endif;?>
        </div>
        
        <?php if($programmes == true):?>
            <?= Html::a('Approve Programme Catalog', ['manage-application-periods/period-setup-step-three', 'approve' => true], ['class' => 'btn btn-block btn-lg btn-success']);?>
       <?php endif;?>
    </div>
</div>