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
            
            <div id="unregistered-applicant-listing">
                <?php if($dataProvider):?>
                    <h2 style="margin-left:2.5%">Unregistered Applicants Listing</h3>

                    <div style = 'margin-left: 2.5%;'>
                        <?= Html::hiddenInput('application_periodid', $application_periodid); ?> 
                        
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
                                            'attribute' => 'programme',
                                            'format' => 'text',
                                            'label' => 'Programme'
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
                    </div><br/>

                    
                    
                    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                            'columns' => [
//                                [
//                                    'attribute' => 'username',
//                                    'format' => 'text',
//                                    'label' => 'Username'
//                                ],
                                [
                                    'format' => 'html',
                                    'label' => 'Applicant ID',
                                    'value' => function($row)
                                        {
                                            return Html::a($row['username'], 
                                                                 Url::to(['view-applicant/applicant-profile',
                                                                          'applicantusername' => $row['username'],
                                                                          'unrestricted' => true
                                                                         ])
                                                             );
                                        }
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
                            ],
                        ]); 
                    ?>
                <?php endif;?>
            </div>
           
        </div>
    </div>
</div>