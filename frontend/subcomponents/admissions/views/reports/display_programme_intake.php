<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;

    use frontend\models\Offer;
    
    $this->title = $page_title;
    
     $this->params['breadcrumbs'][] = ['label' => 'Find A Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/reports/find-unregistered-applicants']);?>" title="Unregister Students">
        <h1>Welcome to the Student Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<?= Html::hiddenInput('application_periodid', $application_periodid); ?>
<?= Html::hiddenInput('programmeid', $programmeid); ?>
<?= Html::hiddenInput('criteria', $criteria); ?>

<div class="text-center no-padding">
    <h2><?= Html::encode($this->title) ?></h2>
</div>


<?php if( $academic_offering_in_second_year == 1):?>
    <div class="box box-primary" style="font-size:1.1em">
        <div class="box-header with-border">
            <span class="box-title"><?= $progression_header?></span>
         </div>

        <div class="box-body">
            <div id="progression-export">
                    <?= ExportMenu::widget([
                        'dataProvider' => $progression_dataProvider,
                        'columns' => [
                                [
                                    'attribute' => 'name',
                                    'format' => 'text',
                                    'label' => 'Programme'
                                ],
                                [
                                    'attribute' => 'enrolled',
                                    'format' => 'text',
                                    'label' => 'Total Enrolled'
                                ],
                                [
                                    'attribute' => 'total_current',
                                    'format' => 'text',
                                    'label' => 'Total Current'
                                ],
                                [
                                    'attribute' => 'enrolled_males',
                                    'format' => 'text',
                                    'label' => 'Enrolled Males'
                                ],
                                [
                                    'attribute' => 'current_male_present_count',
                                    'format' => 'text',
                                    'label' => 'Present Males'
                                ],
                                [
                                    'attribute' => 'curent_male_iscurrent_count',
                                    'format' => 'text',
                                    'label' => 'Male - Current'
                                ],
                                [
                                    'attribute' => 'current_male_probation_count',
                                    'format' => 'text',
                                    'label' => 'Male - Probationary Retention'
                                ],
                                [
                                    'attribute' => 'current_male_academic_withdrawn_count',
                                    'format' => 'text',
                                    'label' => 'Male - Academic Withdrawal'
                                ],
                                [
                                    'attribute' => 'current_male_voluntary_withdrawn_count',
                                    'format' => 'text',
                                    'label' => 'Male - Voluntary Withdrawal'
                                ],
                                [
                                    'attribute' => 'current_male_other_count',
                                    'format' => 'text',
                                    'label' => 'Male - Other'
                                ],
                                [
                                    'attribute' => 'enrolled_females',
                                    'format' => 'text',
                                    'label' => 'Enrolled Females'
                                ],
                                [
                                    'attribute' => 'current_female_present_count',
                                    'format' => 'text',
                                    'label' => 'Present Females'
                                ],
                                [
                                    'attribute' => 'curent_female_iscurrent_count',
                                    'format' => 'text',
                                    'label' => 'Female - Current'
                                ],
                                [
                                    'attribute' => 'current_female_probation_count',
                                    'format' => 'text',
                                    'label' => 'Female - Probationary Retention'
                                ],
                                [
                                    'attribute' => 'current_female_academic_withdrawn_count',
                                    'format' => 'text',
                                    'label' => 'Female - Academic Withdrawal'
                                ],
                                [
                                    'attribute' => 'current_female_voluntary_withdrawn_count',
                                    'format' => 'text',
                                    'label' => 'Female - Voluntary Withdrawal'
                                ],
                                [
                                    'attribute' => 'current_female_other_count',
                                    'format' => 'text',
                                    'label' => 'Female - Other'
                                ],
                            ],
                        'fontAwesome' => true,
                        'dropdownOptions' => [
                            'label' => 'Select Export Type',
                            'class' => 'btn btn-default'
                        ],
                        'asDropdown' => false,
                        'showColumnSelector' => false,
                        'filename' => $progression_filename,
                        'exportConfig' => [
                            ExportMenu::FORMAT_TEXT => false,
                            ExportMenu::FORMAT_HTML => false,
                            ExportMenu::FORMAT_EXCEL => false,
                            ExportMenu::FORMAT_EXCEL_X => false
                        ],
                    ]);
                ?>
            </div>

            <div id="progression-display">
                <?= GridView::widget([
                        'dataProvider' => $progression_dataProvider,
                        'options' => [],
                        'columns' => [
                            [
                                'attribute' => 'name',
                                'format' => 'text',
                                'label' => 'Programme'
                            ],
                            [
                                'attribute' => 'enrolled',
                                'format' => 'text',
                                'label' => 'Total Enrolled'
                            ],
                            [
                                'attribute' => 'total_current',
                                'format' => 'text',
                                'label' => 'Total Current'
                            ],
                            [
                                'attribute' => 'enrolled_males',
                                'format' => 'text',
                                'label' => 'Enrolled Males'
                            ],
                            [
                                'attribute' => 'current_male_present_count',
                                'format' => 'text',
                                'label' => 'Present Males'
                            ],
                            [
                                'attribute' => 'curent_male_iscurrent_count',
                                'format' => 'text',
                                'label' => 'Male - Current'
                            ],
                            [
                                'attribute' => 'current_male_probation_count',
                                'format' => 'text',
                                'label' => 'Male- Probationary Retention'
                            ],
                            [
                                'attribute' => 'current_male_academic_withdrawn_count',
                                'format' => 'text',
                                'label' => 'Male- Academic Withdrawal'
                            ],
                            [
                                'attribute' => 'current_male_voluntary_withdrawn_count',
                                'format' => 'text',
                                'label' => 'Male- Voluntary Withdrawal'
                            ],
                            [
                                'attribute' => 'current_male_other_count',
                                'format' => 'text',
                                'label' => 'Male - Other'
                            ],
                            [
                                'attribute' => 'enrolled_females',
                                'format' => 'text',
                                'label' => 'Enrolled Females'
                            ],
                            [
                                'attribute' => 'current_female_present_count',
                                'format' => 'text',
                                'label' => 'Present Females'
                            ],
                            [
                                'attribute' => 'curent_female_iscurrent_count',
                                'format' => 'text',
                                'label' => 'Female- Current'
                            ],
                            [
                                'attribute' => 'current_female_probation_count',
                                'format' => 'text',
                                'label' => 'Female- Probationary Retention'
                            ],
                            [
                                'attribute' => 'current_female_academic_withdrawn_count',
                                'format' => 'text',
                                'label' => 'Female- Academic Withdrawal'
                            ],
                            [
                                'attribute' => 'current_female_voluntary_withdrawn_count',
                                'format' => 'text',
                                'label' => 'Female- Voluntary Withdrawal'
                            ],
                            [
                                'attribute' => 'current_female_other_count',
                                'format' => 'text',
                                'label' => 'Female - Other'
                            ],
                        ],
                    ]); 
                ?>
            </div>
        </div>
    </div><br/>
<?php endif;?>


<?php if($summary_dataProvider):?>
    <div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
        <div class="box-header with-border">
            <span class="box-title"><?= $summary_header?></span>
         </div>

        <div class="box-body">
            <div id="summary-export">
                <?= ExportMenu::widget([
                        'dataProvider' => $summary_dataProvider,
                        'columns' => [
                                [
                                    'attribute' => 'name',
                                    'format' => 'text',
                                    'label' => 'Programme/Subject'
                                ],
                                [
                                    'attribute' => 'accepted_males',
                                    'format' => 'text',
                                    'label' => 'Accepted Males'
                                ],
                                [
                                    'attribute' => 'accepted_females',
                                    'format' => 'text',
                                    'label' => 'Accepted Females'
                                ],
                                [
                                    'attribute' => 'accepted',
                                    'format' => 'text',
                                    'label' => 'Number of Students Accepted'
                                ],
                                [
                                    'attribute' => 'enrolled_males',
                                    'format' => 'text',
                                    'label' => 'Enrolled Males'
                                ],
                                [
                                    'attribute' => 'enrolled_females',
                                    'format' => 'text',
                                    'label' => 'Enrolled Females'
                                ],
                                [
                                    'attribute' => 'enrolled',
                                    'format' => 'text',
                                    'label' => 'Number of Students Enrolled'
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
                            ExportMenu::FORMAT_EXCEL_X => false
                        ],
                    ]);
                ?>
            </div>

            <div id="summary-details">
                <?= GridView::widget([
                        'dataProvider' => $summary_dataProvider,
                        'options' => [],
                        'columns' => [
                            [
                                'attribute' => 'name',
                                'format' => 'text',
                                'label' => 'Programme/Subject'
                            ],
                            [
                                'attribute' => 'accepted_males',
                                'format' => 'text',
                                'label' => 'Accepted Males'
                            ],
                            [
                                'attribute' => 'accepted_females',
                                'format' => 'text',
                                'label' => 'Accepted Females'
                            ],
                            [
                                'attribute' => 'accepted',
                                'format' => 'text',
                                'label' => 'Number of Students Accepted'
                            ],
                            [
                                'attribute' => 'enrolled_males',
                                'format' => 'text',
                                'label' => 'Enrolled Males'
                            ],
                            [
                                'attribute' => 'enrolled_females',
                                'format' => 'text',
                                'label' => 'Enrolled Females'
                            ],
                            [
                                'attribute' => 'enrolled',
                                'format' => 'text',
                                'label' => 'Number of Students Enrolled'
                            ],
                        ],
                    ]); 
                ?>
            </div>
        </div>
    </div><br/>
<?php endif;?>


<?php if($accepted_dataProvider):?>
    <div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
        <div class="box-header with-border">
            <span class="box-title"><?= $accepted_header?></span>
         </div>

        <div class="box-body">
            <div id="accepted-listing">
                <?= ExportMenu::widget([
                        'dataProvider' => $accepted_dataProvider,
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
                                    'attribute' => 'secondary_school',
                                    'format' => 'text',
                                    'label' => 'Secondary School'
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
                        'filename' => $accepted_filename,
                        'exportConfig' => [
                            ExportMenu::FORMAT_TEXT => false,
                            ExportMenu::FORMAT_HTML => false,
                            ExportMenu::FORMAT_EXCEL => false,
                            ExportMenu::FORMAT_EXCEL_X => false
                        ],
                    ]);
                ?>
            </div>

            <div id="accepted-details">
                <?= GridView::widget([
                        'dataProvider' => $accepted_dataProvider,
                        'options' => [],
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
                                'attribute' => 'secondary_school',
                                'format' => 'text',
                                'label' => 'Secondary School'
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
    </div><br/>
<?php endif;?>


<?php if($enrolled_dataProvider):?>
    <div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
        <div class="box-header with-border">
            <span class="box-title"><?= $enrolled_header?></span>
         </div>

        <div class="box-body">
           <div id="accepted-export">
                <?= ExportMenu::widget([
                        'dataProvider' => $enrolled_dataProvider,
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
                                    'attribute' => 'registrationdate',
                                    'format' => 'text',
                                    'label' => 'Date of Registration'
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
                            ExportMenu::FORMAT_EXCEL_X => false
                        ],
                    ]);
                ?>
            </div>

            <div id="enrolled-details">
                <?= GridView::widget([
                        'dataProvider' => $enrolled_dataProvider,
                        'options' => [],
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
                                'attribute' => 'registrationdate',
                                'format' => 'text',
                                'label' => 'Date of Registration'
                            ],
                        ],
                    ]); 
                ?>
            </div>
        </div>
    </div><br/>
<?php endif;?>