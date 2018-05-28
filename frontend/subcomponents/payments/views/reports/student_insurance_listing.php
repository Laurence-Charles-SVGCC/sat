<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;

    use frontend\models\Offer;
    
    $this->title = 'Student Benificiery Listing';
    $this->params['breadcrumbs'][] = ['label' => 'Generate Listing', 'url' => Url::toRoute(['/subcomponents/payments/reports/find-beneficieries'])];
    $this->params['breadcrumbs'][] = $this->title;
    
?>
<div class="body-content">
    <div class = "custom_wrapper">
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
            
            <div style = 'margin-left: 2.5%;'>
                <?php if($accepted_dataProvider):?>
                    <div id="insurance-accepted-listing">
                        <h2 class="custom_h2" style="margin-left:2.5%"><?= $accepted_header?></h2>
                        <div id="insurance-accepted-listing-export" style="margin-left:2.5%">
                            <p>Click the link below to export the full listing.</p>
                            <?= ExportMenu::widget([
                                    'dataProvider' => $accepted_dataProvider,
                                    'columns' => [
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
                                                'attribute' => 'dateofbirth',
                                                'format' => 'text',
                                                'label' => 'Date Of Birth'
                                            ],
                                            [
                                                'attribute' => 'phone',
                                                'format' => 'text',
                                                'label' => 'Phone'
                                            ],
                                            [
                                                'attribute' => 'email',
                                                'format' => 'text',
                                                'label' => 'Email'
                                            ],
                                            [
                                                'attribute' => 'address',
                                                'format' => 'text',
                                                'label' => 'Address'
                                            ],
                                            [
                                                'attribute' => 'beneficiery_name',
                                                'format' => 'text',
                                                'label' => 'Beneficiery Name'
                                            ],
                                            [
                                                'attribute' => 'beneficiery_address',
                                                'format' => 'text',
                                                'label' => 'Beneficiery Address'
                                            ],
                                            [
                                                'attribute' => 'beneficiery_number',
                                                'format' => 'text',
                                                'label' => 'Beneficiery Contact'
                                            ],
                                        ],
                                    'fontAwesome' => true,
                                    'dropdownOptions' => [
                                        'label' => 'Select Export Type',
                                        'class' => 'btn btn-default'
                                    ],
                                    'asDropdown' => false,
                                    'showColumnSelector' => false,
                                    'filename' => $accepted_filename,
                                    'exportConfig' => [
                                        ExportMenu::FORMAT_TEXT => false,
                                        ExportMenu::FORMAT_HTML => false,
                                        ExportMenu::FORMAT_EXCEL => false,
                                        ExportMenu::FORMAT_EXCEL_X => false,
                                         ExportMenu::FORMAT_PDF => false
                                    ],
                                ]);
                            ?>
                        </div>

                        <div id="insurance-accepted-listing-overview">
                            <?= GridView::widget([
                                    'dataProvider' => $accepted_dataProvider,
                                    'options' => ['style' => 'width: 98%; margin: 0 auto;'],
                                    'columns' => [
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
//                                        [
//                                            'attribute' => 'middlename',
//                                            'format' => 'text',
//                                            'label' => 'Middle Name'
//                                        ],
                                        [
                                            'attribute' => 'lastname',
                                            'format' => 'text',
                                            'label' => 'Last Name'
                                        ],
                                        [
                                            'attribute' => 'dateofbirth',
                                            'format' => 'text',
                                            'label' => 'DOB (Y/M/D)'
                                        ],
                                        [
                                            'attribute' => 'phone',
                                            'format' => 'text',
                                            'label' => 'Phone'
                                        ],
                                        [
                                            'attribute' => 'address',
                                            'format' => 'text',
                                            'label' => 'Address'
                                        ],
                                        [
                                            'attribute' => 'beneficiery_name',
                                            'format' => 'text',
                                            'label' => 'Beneficiery Name'
                                        ],
                                        [
                                            'attribute' => 'beneficiery_address',
                                            'format' => 'text',
                                            'label' => 'Beneficiery Address'
                                        ],
                                        [
                                            'attribute' => 'beneficiery_number',
                                            'format' => 'text',
                                            'label' => 'Beneficiery Contact'
                                        ],
                                    ],
                                ]); 
                            ?>
                        </div>
                <?php endif;?>
                
                        
               <?php if($enrolled_dataProvider):?>
                    <div id="insurance-enrolled-listing">
                        <h2 class="custom_h2" style="margin-left:2.5%"><?= $enrolled_header?></h2>
                        <div id="insurance-enrolled-listing-export" style="margin-left:2.5%">
                             <p>Click the link below to export the full listing.</p>
                            <?= ExportMenu::widget([
                                    'dataProvider' => $enrolled_dataProvider,
                                    'columns' => [
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
                                                'attribute' => 'dateofbirth',
                                                'format' => 'text',
                                                'label' => 'Date Of Birth'
                                            ],
                                            [
                                                'attribute' => 'phone',
                                                'format' => 'text',
                                                'label' => 'Phone'
                                            ],
                                            [
                                                'attribute' => 'email',
                                                'format' => 'text',
                                                'label' => 'Email'
                                            ],
                                            [
                                                'attribute' => 'address',
                                                'format' => 'text',
                                                'label' => 'Address'
                                            ],
                                            [
                                                'attribute' => 'beneficiery_name',
                                                'format' => 'text',
                                                'label' => 'Beneficiery Name'
                                            ],
                                            [
                                                'attribute' => 'beneficiery_address',
                                                'format' => 'text',
                                                'label' => 'Beneficiery Address'
                                            ],
                                            [
                                                'attribute' => 'beneficiery_number',
                                                'format' => 'text',
                                                'label' => 'Beneficiery Contact'
                                            ],
                                        ],
                                    'fontAwesome' => true,
                                    'dropdownOptions' => [
                                        'label' => 'Select Export Type',
                                        'class' => 'btn btn-default'
                                    ],
                                    'asDropdown' => false,
                                    'showColumnSelector' => false,
                                    'filename' => $enrolled_filename,
                                    'exportConfig' => [
                                        ExportMenu::FORMAT_TEXT => false,
                                        ExportMenu::FORMAT_HTML => false,
                                        ExportMenu::FORMAT_EXCEL => false,
                                        ExportMenu::FORMAT_EXCEL_X => false,
                                         ExportMenu::FORMAT_PDF => false
                                    ],
                                ]);
                            ?>
                        </div>

                        <div id="insurance-enrolled-listing-overview">
                            <?= GridView::widget([
                                    'dataProvider' => $enrolled_dataProvider,
                                    'options' => ['style' => 'width: 98%; margin: 0 auto;'],
                                    'columns' => [
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
//                                        [
//                                            'attribute' => 'middlename',
//                                            'format' => 'text',
//                                            'label' => 'Middle Name'
//                                        ],
                                        [
                                            'attribute' => 'lastname',
                                            'format' => 'text',
                                            'label' => 'Last Name'
                                        ],
                                        [
                                            'attribute' => 'dateofbirth',
                                            'format' => 'text',
                                            'label' => 'DOB (Y/M/D)'
                                        ],
                                        [
                                            'attribute' => 'phone',
                                            'format' => 'text',
                                            'label' => 'Phone'
                                        ],
                                        [
                                            'attribute' => 'address',
                                            'format' => 'text',
                                            'label' => 'Address'
                                        ],
                                        [
                                            'attribute' => 'beneficiery_name',
                                            'format' => 'text',
                                            'label' => 'Beneficiery Name'
                                        ],
                                        [
                                            'attribute' => 'beneficiery_address',
                                            'format' => 'text',
                                            'label' => 'Beneficiery Address'
                                        ],
                                        [
                                            'attribute' => 'beneficiery_number',
                                            'format' => 'text',
                                            'label' => 'Beneficiery Contact'
                                        ],
                                    ],
                                ]); 
                            ?>
                        </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>