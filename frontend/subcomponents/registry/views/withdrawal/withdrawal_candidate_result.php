<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use kartik\export\ExportMenu;
    
    use frontend\models\Department;
    
    $this->title = 'Withdrawal Listing Results';
    
    $this->params['breadcrumbs'][] = ['label' => 'Withdrawal Listing Generation', 'url' => Url::toRoute(['/subcomponents/registry/withdrawal/index'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title">Generate New Listing</span>
     </div>
    
    <?php $form = ActiveForm::begin(['action' => Url::to(['withdrawal/generate-withdrawal-candidates'])]);?>
        <div class="box-body">
            <div class="form-group">
               <?= Html::label('Select application period you wish to generate withdrawal candidate list for: ', 'period_id_label'); ?>
               <?= Html::dropDownList('period-id',  "Select...", $periods, []) ; ?>                                   
           </div>

             <p class = "pull-right">
                <?= Html::submitButton('Generate', ['class' => 'btn btn-md btn-success pull-right', 'style' => 'margin-right:20px;']) ?>
            </p>
        </div>
    <?php ActiveForm::end(); ?>
</div><br/>



<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $title?></span>
     </div>

    <div class="box-body">
        <?php if ($dataProvider == true):?>
            <div>
                Click the following link to export a copy of the listing.
                <?= Html::a('Export Listing', ['export-withdrawal-listing', 'application_periodid' => $application_periodid], ['class' => 'btn btn-primary']) ?>
            </div><br/>
        <?php endif;?>
        
        <table class="table table-hover  ">
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
                            'attribute' => 'fails',
                            'format' => 'text',
                            'label' => 'Fails'
                        ],   
                        [
                            'attribute' => 'total_courses',
                            'format' => 'text',
                            'label' => 'Total Courses'
                        ],   
                        [
                            'attribute' => 'percentage_failed',
                            'format' => 'text',
                            'label' => 'Failure Rate (%)'
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
        </table>
        
        <hr><h2>Withdrawal Application Report</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Prospective Withdrawals</th>
                    <th>Current</th>
                    <th>Probationary Retention</th>
                    <th>Withdrawn</th>
                    <th>Voluntary Withdrawn</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><?= $prospective_withdrawals ;?></td>
                    <td><?= $current ;?></td>
                    <td><?= $probationary_retention ;?></td>
                    <td><?= $academic_withdrawal ;?></td>
                    <td><?= $voluntary_withdrawal ;?></td>
                </tr>
            </tbody>
        </table><br/>
    </div>
        
    <div class="box-footer">
        <?php if ($dataProvider && (Yii::$app->user->can('System Administrator')  || Yii::$app->user->can('Registrar'))) : ?> 
            <hr><h2>Student Promotion</h2>

            <ul>
                <li>
                    Click the button below to perform the promotion of students to the next level of their respective programmes.
                </li>
                <li>
                    Please ensure you would have updated the statuses of the students appearing
                    on the above list to <strong>Academic Withdrawal</strong> or <strong>Probationary Retention</strong>.
                </li>
                <li>
                    Clicking the button will ensure that all students that have not been withdrawn;
                    have their 'Current Level' promoted.
                </li>
            </ul><br/>

            <div>
                <a class="btn btn-success pull-left" style="width: 40%;margin-left:5%;margin-right: 5%;font-size:3 em;" href=<?=Url::toRoute(['/subcomponents/registry/withdrawal/promote-students', 'applicationperiodid' => $application_periodid]);?> role="button">  Promote Students</a>
                <a class="btn btn-warning" style="width: 40%;font-size:3 em;" href=<?=Url::toRoute(['/subcomponents/registry/withdrawal/undo-promotions', 'applicationperiodid' => $application_periodid]);?> role="button">  Undo Promotions</a>
            </div>
        <?php endif; ?>
    </div><br/>
</div>