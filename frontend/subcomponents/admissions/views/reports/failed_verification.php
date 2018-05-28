<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;

    use frontend\models\Application;
    
    $this->title = $header;
    $this->params['breadcrumbs'][] = ['label' => 'Report Dashboard', 'url' => Url::toRoute(['/subcomponents/admissions/reports'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <div class="box-body">
        <div style = "margin-left: 2.5%;" id="correction-controls">
            <?php if(Application::hasVerificationFailures(4) ==true || Application::hasVerificationFailures(5) ==true
                        || Application::hasVerificationFailures(6) ==true || Application::hasVerificationFailures(7) ==true):?>
                         <h3>Resolution Links</h3>
                         <p>Click on of the following links to resolve the verification errors.</p>
            <?php endif;?>

            <?php if(Application::hasVerificationFailures(4) ==true):?>
                <a class="btn btn-success" style="width:20%; margin:0 auto;" 
                    href=<?=Url::toRoute(['/subcomponents/admissions/reports/resolve-verification-failures','divisionid' => 4]);?> role="button"> Resolve DASGS Applicants
                </a>
            <?php endif;?>

            <?php if(Application::hasVerificationFailures(5) ==true):?>
                <a class="btn btn-success" style="width:20%; margin:0 auto;" 
                    href=<?=Url::toRoute(['/subcomponents/admissions/reports/resolve-verification-failures','divisionid' => 5]);?> role="button"> Resolve DTVEApplicants
                </a>
            <?php endif;?>

            <?php if(Application::hasVerificationFailures(6) == true):?>
                <a class="btn btn-success" style="width:20%; margin:0 auto;" 
                    href=<?=Url::toRoute(['/subcomponents/admissions/reports/resolve-verification-failures','divisionid' => 6]);?> role="button"> Resolve DTE Applicants
                </a>
            <?php endif;?>

            <?php if(Application::hasVerificationFailures(7) ==true):?>
                <a class="btn btn-success" style="width:20%; margin:0 auto;" 
                    href=<?=Url::toRoute(['/subcomponents/admissions/reports/resolve-verification-failures','divisionid' => 7]);?> role="button"> Resolve DNE Applicants
                </a>
            <?php endif;?>
        </div><br/>

        <div id="listing">
            <?php if($dataProvider):?>
                <h3 style="margin-left:2.5%">Applicant Listing</h3>
                <p style="margin-left:2.5%">Click one of the following links to download a detailed version of the report seen below.</p>

                <div style = 'margin-left: 2.5%;'>

                    <?= ExportMenu::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                    [
                                        'attribute' => 'personid',
                                        'format' => 'text',
                                        'label' => 'Person-ID'
                                    ],
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
                                    [
                                        'attribute' => 'division',
                                        'format' => 'text',
                                        'label' => 'Division'
                                    ],
                                    [
                                        'attribute' => 'year',
                                        'format' => 'text',
                                        'label' => 'Year'
                                    ],
                                    [
                                        'attribute' => 'centre',
                                        'format' => 'text',
                                        'label' => 'Examination Centre'
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
                    ?><br/>


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
                                    'attribute' => 'programme',
                                    'format' => 'text',
                                    'label' => 'Programme'
                                ],
                                [
                                    'attribute' => 'division',
                                    'format' => 'text',
                                    'label' => 'Division'
                                ],
                                [
                                    'attribute' => 'year',
                                    'format' => 'text',
                                    'label' => 'Year'
                                ],
                            ],
                        ]); 
                    ?>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>