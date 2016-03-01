<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
//    use yii\grid\GridView;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;

    //$type = ucfirst($type);
    $this->title = $status_name;
    $this->params['breadcrumbs'][] = ['label' => 'Review Applicants', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-period-form">
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
           
            <?php 
                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'format' => 'html',
                        'label' => 'Applicant ID',
                        'value' => function($row) use ($application_status)
                            {
                                $middlename = $row['middlename'] ? $row['middlename'] : "";
                                return Html::a($row['username'], 
                                       Url::to(['process-applications/view-applicant-certificates',
                                                'applicantid' => $row['applicantid'],
                                                'programme' => $row['programme'], 
                                                'application_status' => $application_status
                                               ]));
                            }
                    ],
                    [
                        'attribute' => 'firstname',
                        'format' => 'text',
                        'label' => 'First Name'
                    ],
                    [
                        'attribute' => 'middlename',
                        'format' => 'text',
                        'label' => 'Middle Name(s)'
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
                        'attribute' => 'subjects_no',
                        'format' => 'text',
                        'label' => 'No. of Subjects'
                    ],
                    [
                        'attribute' => 'ones_no',
                        'format' => 'text',
                        'label' => 'No. of Ones'
                    ],
                    [
                        'attribute' => 'twos_no',
                        'format' => 'text',
                        'label' => 'No. of Twos'
                    ],
                    [
                        'attribute' => 'threes_no',
                        'format' => 'text',
                        'label' => 'No. of Threes'
                    ],
                ];
            
            
                if ($status_name == "InterviewOffer")   
                {
                    echo "<div style = 'margin-left: 2.5%;'>";
                        echo ExportMenu::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => $gridColumns,
                            'fontAwesome' => true,
                            'dropdownOptions' => [
                                'label' => 'Select Export Type',
                                'class' => 'btn btn-default'
                            ],
                            'asDropdown' => false,
                            'showColumnSelector' => false,

                            'exportConfig' => [
                                ExportMenu::FORMAT_TEXT => false,
                                ExportMenu::FORMAT_CSV => false,
                                ExportMenu::FORMAT_HTML => false,
                                ExportMenu::FORMAT_EXCEL => false,
                                ExportMenu::FORMAT_EXCEL_X => false
                            ],
                        ]);
                    echo "</div>";
                }
            ?>
            
            <?php $form = ActiveForm::begin(
                    [
                        'action' => Url::to(['review-applications/update-view']),
                    ]
            ); ?>
            
                <?= Html::hiddenInput('application_status', $application_status); ?>
                <?= Html::hiddenInput('division_id', $division_id); ?>
            
                <div class="body-content">
                    <?php if(count($programmes) > 1):?>
                        <br/><p style="font-size:20px">If you wish to filter the results by programme, use the dropdownlist below.</p>

                        <div class="row">
                            <div class="col-lg-8">
                                <?= Html::label( 'Select Filtering Criteria',  'programme'); ?>
                                <?= Html::dropDownList('programme', null, $programmes, [ 'style' => 'font-size:20px', 'onchange' => 'showUpdateButton();']); ?>
                            </div>

                            <div class="col-lg-4">
                                <?= Html::submitButton('Update View', ['class' => 'btn btn-success']) ?>
                            </div> <br/> 
                        </div>
                    <?php endif;?>
                </div><br/>
            <?php ActiveForm::end(); ?>
                
            
            <?= GridView::widget(
                [
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                    'columns' => $gridColumns,
                ]); 
            ?>
        </div>
    </div>
</div>