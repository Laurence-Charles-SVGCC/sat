<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;
    
     $this->title = 'Batch Offering Report';
     $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<h2 class="text-center"><?=$this->title?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div style="width:98%; margin: 0 auto;">
        <?php if($dataprovider->getCount() > 0):?>
            <h3><?= "Search results for: " . $info_string ?></h3>
            <h4>Click one of following links to download a detailed copy of the following report.</h4>
            <?= ExportMenu::widget([
                    'dataProvider' => $dataprovider,
                    'columns' => [
                        [
                            'attribute' => 'batch_name',
                            'format' => 'text',
                            'label' => 'Batch Name'
                        ],
                        [
                            'attribute' => 'lecturer',
                            'format' => 'text',
                            'label' => 'Lecturer'
                        ],
                        [
                            'attribute' => 'batch_type',
                            'format' => 'text',
                            'label' => 'Batch Type'
                        ],
                        [
                            'attribute' => 'credits',
                            'format' => 'text',
                            'label' => 'Credits'
                        ],
                        [
                            'attribute' => 'courseworkweight',
                            'format' => 'text',
                            'label' => 'Coursework'
                        ],
                        [
                            'attribute' => 'examweight',
                            'format' => 'text',
                            'label' => 'Exam'
                        ],
                        [
                            'attribute' => 'code',
                            'format' => 'text',
                            'label' => 'Code'
                        ],
                        [
                            'attribute' => 'name',
                            'format' => 'text',
                            'label' => 'Name'
                        ],
                        [
                            'attribute' => 'semester',
                            'format' => 'text',
                            'label' => 'Semester'
                        ],
                        [
                            'attribute' => 'year',
                            'format' => 'text',
                            'label' => 'Year'
                        ],
                        [
                            'attribute' => 'course_type',
                            'format' => 'text',
                            'label' => 'Type'
                        ],
                        [
                            'attribute' => 'pass_criteria',
                            'format' => 'text',
                            'label' => 'Pass Criteria'
                        ],
                        [
                            'attribute' => 'pass_fail_status',
                            'format' => 'text',
                            'label' => 'GPA Consideration'
                        ],
                        [
                            'attribute' => 'programme_name',
                            'format' => 'text',
                            'label' => 'Programme Name'
                        ],
                    ],
                    'fontAwesome' => true,
                    'dropdownOptions' => [
                        'label' => 'Select Export Type',
                        'class' => 'btn btn-default',
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
            ?><br/><br/>
        <?php endif;?>

        
    </div>
</div>

