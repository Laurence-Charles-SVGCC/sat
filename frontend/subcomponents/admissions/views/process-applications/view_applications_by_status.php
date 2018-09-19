<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;

    use frontend\models\EmployeeDepartment;

    $this->title = $status_name;
    $this->params['breadcrumbs'][] = ['label' => 'Review Applicants', 'url' => Url::toRoute(['/subcomponents/admissions/process-applications'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<h2 class="text-center"><?= $this->title;?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
    <?php if (in_array($status_name, ["Pending","Shortlist", "Borderline"]) &&  EmployeeDepartment::getUserDivision() == 1):?>
        <div class="box-header with-border">
            <span class="box-title">
                <div class="pull-right" style="margin-right:2.5%">
                    <?=Html::a(' Generate  Eligible Listing',
                                ['process-applications/generate-eligible-listing', 'status' => $status_name],
                                ['class' => 'btn btn-info']);
                    ?>
                </div>
            </span>
        </div>
    <?php endif;?>

    <div class="box-body">
         <?php
            $gridColumns = [
//                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'format' => 'html',
                    'label' => 'Applicant ID',
                    'value' => function($row) use ($application_status, $programme_id)
                        {
                            if($application_status == 0)
                            {
                                return Html::a($row['username'],
                                       Url::to(['process-applications/view-exception-applicant-certificates',
                                                'personid' => $row['personid'],
                                               ]));
                            }
                            else
                            {
                                return Html::a($row['username'],
                                       Url::to(['process-applications/view-applicant-certificates',
                                                'personid' => $row['personid'],
                                                'programme' => $row['programme'],
                                                'application_status' => $application_status,
                                                'programme_id' => $programme_id,
                                               ]));
                            }
                        }
                ],
                [
                    'attribute' => 'firstname',
                    'format' => 'text',
                    'label' => 'First Name'
                ],
//                    [
//                        'attribute' => 'middlename',
//                        'format' => 'text',
//                        'label' => 'Middle Name(s)'
//                    ],
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
                [
                    'attribute' => 'can_edit',
                    'format' => 'text',
                    'label' => 'Access Status'
                ],
            ];
        ?>

        <?php if ($status_name == "InterviewOffer"  ||  $status_name == "Offer"
            ||  $status_name == "Rejected"  || $status_name == "RejectedConditionalOffer"
                ):?>
            <div>
                <p><strong>Click one of the following links to download a copy of the listing shown below.</strong></p>
                <?= ExportMenu::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => $gridColumns,
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
    //                                ExportMenu::FORMAT_CSV => false,
                            ExportMenu::FORMAT_HTML => false,
                            ExportMenu::FORMAT_EXCEL => false,
                            ExportMenu::FORMAT_EXCEL_X => false
                        ],
                ]);?>
           </div>
        <?php endif; ?>

        <?php $form = ActiveForm::begin(['action' => Url::to(['process-applications/update-view']),]); ?>

            <?= Html::hiddenInput('application_status', $application_status); ?>
            <?= Html::hiddenInput('division_id', $division_id); ?>

            <div class="body-content">
                <?php if(count($programmes) > 1):?>
                <br/><p><strong>If you wish to filter the results by programme, use the dropdownlist below.</strong></p>

                    <div class="row">
                        <div class="col-lg-8">
                            <?= Html::label( 'Select Filtering Criteria',  'programme'); ?>
                            <?= Html::dropDownList('programme', null, $programmes, [ 'style' => 'font-size:20px;'/*, 'onclick' => 'showUpdateButton();'*/]); ?>
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
                'options' => [],
                'columns' => $gridColumns,
            ]);
        ?>
    </div>
</div>
