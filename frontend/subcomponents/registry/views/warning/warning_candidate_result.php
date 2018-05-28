<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use kartik\export\ExportMenu;
    
    $this->title = 'Warning Listing Results';
    $this->params['breadcrumbs'][] = ['label' => 'Warning Listing Generation', 'url' => Url::toRoute(['/subcomponents/registry/warning/index'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title">Generate New Listing</span>
     </div>
    
    <?php $form = ActiveForm::begin(['action' => Url::to(['warning/generate-warning-candidates'])]);?>
        <div class="box-body">
            <div class="form-group">
               <?= Html::label('Select application period you wish to generate withdrawal candidate list for: ', 'period_id_label'); ?>
               <?= Html::dropDownList('period-id',  "Select...", $periods, []) ; ?>                                   
           </div>
        </div>
   
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton('Generate', ['class' => 'btn btn-md btn-success pull-right', 'style' => 'margin-right:20px;']) ?>
            </span>
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
                <?= Html::a('Export Listing', ['export-warning-listing', 'application_periodid' => $application_periodid], ['class' => 'btn btn-primary']) ?>
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
                            'attribute' => 'student_status',
                            'format' => 'text',
                            'label' => 'Current Status'
                        ],
                        [
                            'attribute' => 'proposed_status',
                            'format' => 'text',
                            'label' => 'Proposed Status'
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
        <hr><h2>Academic Poor Performance Report</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Poor Performers</th>
                    <th>Proposed Academic Warning</th>
                    <th>Proposed Academic Probation</th>
                    <th>Other</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><?= $poor_performers ;?></td>
                    <td><?= $academic_warning ;?></td>
                    <td><?= $academic_probation ;?></td>
                    <td><?= $poor_performers - ($academic_warning + $academic_probation) ?></td>
                </tr>
            </tbody>
        </table><br/>
    </div><br/>
</div>