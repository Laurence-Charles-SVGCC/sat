<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;
    use yii\widgets\ActiveForm;

    use frontend\models\Application;
    
    $this->title = $header;
    $this->params['breadcrumbs'][] = ['label' => 'Report Dashboard', 'url' => Url::toRoute(['/subcomponents/admissions/reports/snapshot'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/reports/snapshot']);?>" title="Snapshot Reports Home">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/>

<h2 class="text-center"><?=$this->title?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title">Applicant Listing</span>
    </div>
    
    <div class="box-body" id="listing" style="width:98%; margin: 0 auto;">
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['style' => 'width: 100%; margin: 0 auto;'],
                'columns' => [
                    [
                        'attribute' => 'username',
                        'format' => 'text',
                        'label' => 'Username'
                    ],
                    [
                        'attribute' => 'title',
                        'format' => 'text',
                        'label' => 'Title'
                    ],
                    [
                        'attribute' => 'firstname',
                        'format' => 'text',
                        'label' => 'First Name'
                    ],
                    [
                        'attribute' => 'lastname',
                        'format' => 'text',
                        'label' => 'Last Name'
                    ],
                    [
                        'attribute' => 'programme',
                        'format' => 'text',
                        'label' => 'Programme'
                    ],
                    [
                        'attribute' => 'email',
                        'format' => 'text',
                        'label' => 'Email'
                    ],
                    [
                        'attribute' => 'phone',
                        'format' => 'text',
                        'label' => 'Phone Number(s)'
                    ],
                ],
            ]); 
        ?>

        <?php if ($dataProvider):?>
            <h3 style="margin-left:2.5%">Export List</h3>
            <?php $form = ActiveForm::begin(['action' => Url::to(['reports/export-snapshot', 'selected_ordering' => $selected_ordering]),]);?>
                <div style="width:98%; margin: 0 auto"><br/>
                    <fieldset>
                        <legend>1. Select one or more programmes for search:</legend>
                        <div class="row">
                            <div class="col-lg-9">
                                <?= Html::checkboxList('offerings', null, $listing, []);?>
                            </div>
                        </div>
                    </fieldset>

<!--                                <fieldset>
                        <legend>2. Select priority of programme search:</legend>
                        <div class="row">
                            <div class="col-lg-3">
                                <?= Html::radioList('ordering', null, [1 => 'First Choice', 2 => 'Second Choice', 3 => 'Child Choice'], ['class'=> 'form_field']);?>
                            </div>
                        </div>
                    </fieldset>-->

                     <div class="form-group">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: left']) ?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        <?php endif;?>
   </div>
</div>