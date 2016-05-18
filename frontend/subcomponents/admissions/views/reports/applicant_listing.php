<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;

    use frontend\models\Offer;
    
    $this->title = $header;
?>
<div class="body-content">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
            
            <div id="listing">
                <?php if($dataProvider):?>
                    <h2 style="margin-left:2.5%">Applicant Listing</h3>

                    <div style = 'margin-left: 2.5%;'>
                        <?= Html::hiddenInput('application_periodid', $application_periodid); ?>
                        <?= Html::hiddenInput('programmeid', $programmeid); ?>
                        <?= Html::hiddenInput('criteria', $criteria); ?>
                        
                        <?= ExportMenu::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                        [
                                            'attribute' => 'username',
                                            'format' => 'text',
                                            'label' => 'Applicant ID'
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
                                            'attribute' => 'middlename',
                                            'format' => 'text',
                                            'label' => 'Middle Name'
                                        ],
                                        [
                                            'attribute' => 'lastname',
                                            'format' => 'text',
                                            'label' => 'Last Name'
                                        ],
                                        [
                                            'attribute' => 'firstchoice',
                                            'format' => 'text',
                                            'label' => 'First Choice'
                                        ],
                                        [
                                            'attribute' => 'secondchoice',
                                            'format' => 'text',
                                            'label' => 'Second Choice'
                                        ],
                                        [
                                            'attribute' => 'thirdchoice',
                                            'format' => 'text',
                                            'label' => 'Third Choice'
                                        ],
                                        [
                                            'attribute' => 'secondarysubjects',
                                            'format' => 'text',
                                            'label' => 'CSEC/GCE Subjects'
                                        ],
                                        [
                                            'attribute' => 'secondarypasses',
                                            'format' => 'text',
                                            'label' => 'CSEC/GCE Passes'
                                        ],
                                        [
                                            'attribute' => 'ones',
                                            'format' => 'text',
                                            'label' => 'CSEC/GCE 1s'
                                        ],
                                        [
                                            'attribute' => 'twos',
                                            'format' => 'text',
                                            'label' => 'CSEC/GCE 2s'
                                        ],
                                        [
                                            'attribute' => 'threes',
                                            'format' => 'text',
                                            'label' => 'CSEC/GCE 3s'
                                        ],
                                        [
                                            'attribute' => 'tertiarysubjects',
                                            'format' => 'text',
                                            'label' => 'CAPE Subjects'
                                        ],
                                        [
                                            'attribute' => 'tertiarypasses',
                                            'format' => 'text',
                                            'label' => 'CAPE Passes'
                                        ],
                                        [
                                            'attribute' => '1st',
                                            'format' => 'text',
                                            'label' => 'CAPE A'
                                        ],
                                        [
                                            'attribute' => '2nd',
                                            'format' => 'text',
                                            'label' => 'CAPE B'
                                        ],
                                        [
                                            'attribute' => '3rd',
                                            'format' => 'text',
                                            'label' => 'CAPE C'
                                        ],
                                    ],
                                'fontAwesome' => true,
                                'dropdownOptions' => [
                                    'label' => 'Select Export Type',
                                    'class' => 'btn btn-default'
                                ],
                                'asDropdown' => false,
                                'showColumnSelector' => false,
                                'filename' => $filename,
                                'exportConfig' => [
                                    ExportMenu::FORMAT_TEXT => false,
                                    ExportMenu::FORMAT_HTML => false,
                                    ExportMenu::FORMAT_EXCEL => false,
                                    ExportMenu::FORMAT_EXCEL_X => false
                                ],
                            ]);
                        ?>
                    </div>

                    
                    
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
                                    'attribute' => 'firstchoice',
                                    'format' => 'text',
                                    'label' => '1st Choice'
                                ],
                                [
                                    'attribute' => 'secondchoice',
                                    'format' => 'text',
                                    'label' => '2nd Choice'
                                ],
                                [
                                    'attribute' => 'thirdchoice',
                                    'format' => 'text',
                                    'label' => '3rd Choice'
                                ],
                                [
                                    'attribute' => 'secondarysubjects',
                                    'format' => 'text',
                                    'label' => 'CSEC / GCE Subjects'
                                ],
                                [
                                    'attribute' => 'secondarypasses',
                                    'format' => 'text',
                                    'label' => 'CSEC / GCE Passes'
                                ],
//                                [
//                                    'attribute' => 'ones',
//                                    'format' => 'text',
//                                    'label' => '1s'
//                                ],
//                                [
//                                    'attribute' => 'twos',
//                                    'format' => 'text',
//                                    'label' => '2s'
//                                ],
//                                [
//                                    'attribute' => 'threes',
//                                    'format' => 'text',
//                                    'label' => '3s'
//                                ],
                                [
                                    'attribute' => 'tertiarysubjects',
                                    'format' => 'text',
                                    'label' => 'CAPE Subjects'
                                ],
                                [
                                    'attribute' => 'tertiarypasses',
                                    'format' => 'text',
                                    'label' => 'CAPE Passes'
                                ],
//                                [
//                                    'attribute' => '1st',
//                                    'format' => 'text',
//                                    'label' => 'CAPE A'
//                                ],
//                                [
//                                    'attribute' => '2nd',
//                                    'format' => 'text',
//                                    'label' => 'CAPE B'
//                                ],
//                                [
//                                    'attribute' => '3rd',
//                                    'format' => 'text',
//                                    'label' => 'CAPE C'
//                                ],
                            ],
                        ]); 
                    ?>
                <?php endif;?>
            </div>
            
            <div id="summary">
                
            </div>    
            
        </div>
    </div>
</div>