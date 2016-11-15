<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use kartik\export\ExportMenu;
    
    use frontend\models\Department;
    
    $this->title = 'Withdrawal Listing Results';
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/registry/withdrawl/index', 'new' => 1]);?>" title="Find A Student">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/sms_4.png" alt="student avatar">
                <span class="custom_module_label">Welcome to the Withdrawal Management</span> 
                <img src ="css/dist/img/header_images/sms_4.png" alt="student avatar" class="pull-right">
            </a>    
        </div>

        <div class="custom_body">
            <h1 class="custom_h1"><?= $this->title?></h1>

            <div style="width:95%; margin: 0 auto"><br/>
                <div>
                    <h3>Would you like to generate another listing?</h3>
                    
                    <?php $form = ActiveForm::begin(
                                [
//                                    'action' => Url::to(['withdrawal/index']),
                                    'action' => Url::to(['withdrawal/generate-withdrawal-candidates']),
                                ]); 
                    ?>

                        <div >
                            <?= Html::label('Select application period you wish to generate withdrawal candidate list for: ', 'period_id_label'); ?>
                            <?= Html::dropDownList('period-id',  "Select...", $periods, ['id' => 'period_id_field', 'onchange' => 'toggleSubmitButton();']) ; ?>

                            <?= Html::submitButton('Generate', ['class' => 'btn btn-md btn-success pull-right', 'style' => 'margin-right:5%; display:none', 'id' => 'withdrawal-submit-button']) ?>
                         </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div><br/><br/>
                    
                    
            <div id="withdrawal-candidates" style="width:95%; margin: 0 auto">
                <div id="withdrawal-export">
                    <p>
                        Click the following link to export a copy of the listing.
                        <?= Html::a('Export Listing', ['export-withdrawal-listing', 'application_periodid' => $application_periodid], ['class' => 'btn btn-primary']) ?>
                    </p><br/>
                    <!--<div id="withdrawal-export">
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
                </div><br/>-->

                <div id="withdrawal-display">
                    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'options' => [],
                            'columns' => [
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
            </div><br/>
            
            
            <div id="withdrawal-progress-report"  style="width:95%; margin: 0 auto">
                <hr><h2 class="custom_h2" >Withdrawal Application Report</h2>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Prospective Withdrawals</th>
                            <th>Current</th>
                            <th>Probationary Retention</th>
                            <th>Withdrawn</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td><?= $prospective_withdrawals ;?></td>
                            <td><?= $current ;?></td>
                            <td><?= $probationary_retention ;?></td>
                            <td><?= $academic_withdrawal ;?></td>
                        </tr>
                    </tbody>
                </table>
            </div><br/>
            
            
            <div id="promotion-options"  style="width:95%; margin: 0 auto">
                <?php if ($dataProvider && (Yii::$app->user->can('System Administrator')  || Yii::$app->user->can('Registrar'))) : ?> 
                    <hr><h2 class="custom_h2" style="margin-left:0px">Student Promotion</h2>

                    <ul>
                        <li>
                            Click the button below to perform the promotion of students from Level 1 to Level 2.
                        </li>
                        <li>
                            Please ensure you would have updated the statuses of the students appearing
                            on the above list to <strong>Academic Withdrawal</strong> or <strong>Probationary Retention</strong>.
                        </li>
                        <li>
                            Clicking the button will ensure that all students that have not been withdrawn;
                            have their 'Current Level' updated to Level 2.
                        </li>
                    </ul><br/>

                    <div>
                        <a class="btn btn-success pull-left" style="width: 40%;margin-left:5%;margin-right: 5%;font-size:3 em;" href=<?=Url::toRoute(['/subcomponents/registry/withdrawal/promote-students', 'applicationperiodid' => $application_periodid]);?> role="button">  Promote Students</a>
                        <a class="btn btn-warning" style="width: 40%;font-size:3 em;" href=<?=Url::toRoute(['/subcomponents/registry/withdrawal/undo-promotions', 'applicationperiodid' => $application_periodid]);?> role="button">  Undo Promotions</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
                