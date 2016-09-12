<?php
    
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use kartik\export\ExportMenu;
?>



<div id="withdrawal-candidates">
    <div id="withdrawal-export">
        <p>Click the link below to export a copy of the listing.</p>
        <?= ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                        [
                            'attribute' => 'username',
                            'format' => 'text',
                            'label' => 'Student No.'
                        ],
                        [
                            'attribute' => 'title',
                            'format' => 'text',
                            'label' => 'Title'
                        ],
                        [
                            'attribute' => 'first_name',
                            'format' => 'text',
                            'label' => 'First Name'
                        ],
                        [
                            'attribute' => 'middle_name',
                            'format' => 'text',
                            'label' => 'Middle Name'
                        ],
                        [
                            'attribute' => 'last_name',
                            'format' => 'text',
                            'label' => 'Last Name'
                        ],
                        [
                            'attribute' => 'programme',
                            'format' => 'text',
                            'label' => 'Programme'
                        ],
                        [
                            'attribute' => 'current_level',
                            'format' => 'text',
                            'label' => 'Level'
                        ],
                        [
                            'attribute' => 'student_status',
                            'format' => 'text',
                            'label' => 'Status'
                        ],
                        [
                            'attribute' => 'email',
                            'format' => 'text',
                            'label' => 'Email'
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
                    ExportMenu::FORMAT_EXCEL_X => false,
                    ExportMenu::FORMAT_PDF => false
                ],
            ]);
        ?>
    </div><br/>

    <div id="withdrawal-display">
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => [],
                'columns' => [
//                    [
//                        'attribute' => 'username',
//                        'format' => 'text',
//                        'label' => 'Username'
//                    ],
                    [
                        'format' => 'html',
                        'label' => 'Student No.',
                        'value' => function($row)
                        {
                           return Html::a($row['username'], 
                                    Url::to(['/subcomponents/students/profile/student-profile', 'personid' => $row['personid'], 'studentregistrationid' => $row['student_registrationid']]));

                        }
                    ],
                    [
                        'attribute' => 'first_name',
                        'format' => 'text',
                        'label' => 'First Name'
                    ],
                    [                
                        'attribute' => 'last_name',
                        'format' => 'text',
                        'label' => 'Last Name'
                    ],
                    [
                        'attribute' => 'programme',
                        'format' => 'text',
                        'label' => 'Programme'
                    ],
                    [
                        'attribute' => 'current_level',
                        'format' => 'text',
                        'label' => 'Level'
                    ],
                    [
                        'attribute' => 'student_status',
                        'format' => 'text',
                        'label' => 'Status'
                    ],
                    [
                        'attribute' => 'email',
                        'format' => 'text',
                        'label' => 'Email'
                    ],
                ],
            ]); 
        ?>
    </div>
</div>