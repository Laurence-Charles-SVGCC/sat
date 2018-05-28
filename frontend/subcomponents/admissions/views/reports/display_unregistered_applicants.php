<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;

    use frontend\models\Offer;
    
    $this->title = $header;
    
    $this->params['breadcrumbs'][] = ['label' => 'Find Unregistered', 'url' => Url::toRoute(['/subcomponents/admissions/reports/find-unregistered-applicants'])];
    $this->params['breadcrumbs'][] = ['label' => 'Find A Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <div class="box-body">
        <?php if($dataProvider):?>
            <div id="export-listing">
                <?= Html::hiddenInput('application_periodid', $application_periodid); ?>
                <h4>Click one of the following links to download the listing seen below.</h4>
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
                                    'attribute' => 'potentialstudentid',
                                    'format' => 'text',
                                    'label' => 'StudentID'
                                ],
                                [
                                    'attribute' => 'email',
                                    'format' => 'text',
                                    'label' => 'Email'
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
        <?php endif;?>
            
        <div id="listing">
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                    'columns' => [
                        [
                            'format' => 'html',
                            'label' => 'Applicant ID',
                            'value' => function($row)
                                {
                                    return Html::a($row['username'], 
                                                         Url::to(['view-applicant/applicant-profile',
                                                                  'search_status' => 'successful',
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
                            'attribute' => 'potentialstudentid',
                            'format' => 'text',
                            'label' => 'StudentID'
                        ],
                        [
                            'attribute' => 'email',
                            'format' => 'text',
                            'label' => 'Email'
                        ],
                        [
                            'attribute' => 'programme',
                            'format' => 'text',
                            'label' => 'Programme'
                        ],
                    ],
                ]); 
            ?>
        </div>
    </div>
</div>