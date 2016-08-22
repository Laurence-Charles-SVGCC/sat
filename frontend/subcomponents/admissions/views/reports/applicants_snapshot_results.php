<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;
    use yii\widgets\ActiveForm;

    use frontend\models\Application;
    
    $this->title = $header;
?>

<div class="body-content">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
            
            <div id="listing">
                <h2 class="custom_h2" style="margin-left:2.5%">Applicant Listing</h2>
                
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
                        <h2 class="custom_h2" style="margin-left:2.5%">Export List</h2>
                        <?php $form = ActiveForm::begin([
                            'action' => Url::to(['reports/export-snapshot']),
                        ]);?>
                            <div style="width:95%; margin: 0 auto"><br/>
                                <fieldset>
                                    <legend>1. Select one or more programmes for search:</legend>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <?= Html::checkboxList('offerings', null, $listing, []);?>
                                        </div>
                                    </div>
                                </fieldset><br/>
                                
                                <fieldset>
                                    <legend>2. Select priority of programme search:</legend>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <?= Html::radioList('ordering', null, [1 => 'First Choice', 2 => 'Second Choice', 3 => 'Child Choice'], ['class'=> 'form_field']);?>
                                        </div>
                                    </div>
                                </fieldset>

                                 <div class="form-group">
                                    <br/><?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: left']) ?>
                                </div>
                            </div>
                        <?php ActiveForm::end(); ?>
                            
                        
                        
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>

