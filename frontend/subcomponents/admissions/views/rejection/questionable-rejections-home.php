<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;

    $this->title = 'Questionable Rejections';
    
    if ($rejectiontype == 1)
        $this->params['breadcrumbs'][] = ['label' => 'Rejection Listing', 'url' => Url::toRoute(['/subcomponents/admissions/rejection', 'rejectiontype' => 1])];
    elseif ($rejectiontype == 2)
        $this->params['breadcrumbs'][] = ['label' => 'Rejection Listing', 'url' => Url::toRoute(['/subcomponents/admissions/rejection', 'rejectiontype' => 2])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<h2 class="text-center"><?= $this->title?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title">Categories of Questionable Rejections</span>
    </div>
    
    <div class="box-body">
        <ul>
            <?php if($math_req):?>
                <li>
                    <a href="<?= Url::toRoute(['/subcomponents/admissions/rejection/rejection-details-home', 'rejectiontype' => $rejectiontype, 'criteria' => 'maths']);?>" 
                        title="Has CSEC Mathematics Pass"
                        style="font-size:16px; width: 80%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                        Click here to view unsuccessful applicants who have CSEC/GCE Mathematics pass
                     </a>
                </li><br/>
            <?php endif;?>

            <?php if($english_req):?>
                <li>
                    <a href="<?= Url::toRoute(['/subcomponents/admissions/rejection/rejection-details-home', 'rejectiontype' => $rejectiontype, 'criteria' => 'english']);?>" 
                        title="Has CSEC English Pass"
                        style="font-size:16px; width: 80%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                        Click here to view unsuccessful applicants who have CSEC/GCE English Language pass
                     </a>
                </li><br/>
            <?php endif;?>

            <?php if($subjects_req):?>
                <li>
                    <a href="<?= Url::toRoute(['/subcomponents/admissions/rejection/rejection-details-home', 'rejectiontype' => $rejectiontype, 'criteria' => 'five_passes']);?>" 
                        title="Has 5 CSEC Passes"
                        style="font-size:16px; width: 80%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                        Click here to view unsuccessful applicants possessing five(5) CSEC/GCE passes
                     </a>
                </li><br/>
            <?php endif;?>

            <?php if($subjects_and_english_req):?>
                <li>
                    <a href="<?= Url::toRoute(['/subcomponents/admissions/rejection/rejection-details-home', 'rejectiontype' => $rejectiontype, 'criteria' => 'five_passes_and_english']);?>" 
                        title="Has 5 CSEC Passes with English"
                        style="font-size:16px; width: 80%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                        Click here to view unsuccessful applicants possessing five(5) CSEC/GCE passes including English Language
                     </a>
                </li><br/>
            <?php endif;?>

            <?php if($dte_science_req):?>
                <li>
                    <a href="<?= Url::toRoute(['/subcomponents/admissions/rejection/rejection-details-home', 'rejectiontype' => $rejectiontype, 'criteria' => 'dte']);?>" 
                        title="Has DTE Required Reelvant Science"
                        style="font-size:16px; width: 80%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                        Click here to view unsuccessful applicants who satisfy DTE's the required relevant science
                     </a>
                </li><br/>
            <?php endif;?>

            <?php if($dne_science_req):?>
                <li>
                    <a href="<?= Url::toRoute(['/subcomponents/admissions/rejection/rejection-details-home',  'rejectiontype' => $rejectiontype, 'criteria' => 'dne']);?>" 
                        title="Has DNE Required Relvant Science"
                        style="font-size:16px; width: 80%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                        Click here to view unsuccessful applicants who satisfy DNE's required relevant science
                     </a>
                </li>
            <?php endif;?>
        </ul>
    </div>
</div>



<?php if($dataProvider):?>
    <div class="box box-primary no-padding" style ="font-size:1.2em;">
        <div class="box-body">
            <h3><?=$rejection_type?></h3>
            <div>
                <?= ExportMenu::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                                'username',
                                'firstname',
                                'lastname',
                                'programme',
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
                                'issuedby',
                                'issuedate',
                                'revokedby',
                                'revokedate',
                                [
                                    'attribute' => 'ispublished',
                                    'format' => 'boolean',
                                    'label' => 'Published'
                                ],
                            ],
                        'fontAwesome' => true,
                        'dropdownOptions' => [
                            'label' => 'Select Export Type',
                            'class' => 'btn btn-default'
                        ],
                        'asDropdown' => false,
                        'showColumnSelector' => false,

                        'exportConfig' => [
                            ExportMenu::FORMAT_TEXT => false,
                            ExportMenu::FORMAT_HTML => false,
                            ExportMenu::FORMAT_EXCEL => false,
                            ExportMenu::FORMAT_EXCEL_X => false
                        ],
                    ]);
                ?>
            </div>

            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'options' => [],
                    'columns' => [
                        [
                            'attribute' => 'username',
                            'format' => 'html',
                            'value' => function($row)
                             {
                                return Html::a($row['username'], 
                                                Url::to(['process-applications/view-applicant-certificates',
                                                         'personid' => $row['personid'],
                                                         'programme' => $row['prog'], 
                                                         'application_status' => $row['status'],
                                                        ]));  
                              }
                        ],
                        'firstname',
                        'lastname',
                        'programme',
                        'issuedby',
                        'issuedate',
                        'revokedby',
                        'revokedate',
                        [
                            'attribute' => 'ispublished',
                            'format' => 'boolean',
                            'label' => 'Published'
                        ],
                        [
                            'attribute' => 'rejectionid',
                            'label' => 'Rescind',
                            'format' => 'html',
                            'value' => function($row)
                             {

                                if (Yii::$app->user->can('deleteRejection'))
                                {
                                    if($row['revokedby'] == "N/A")
                                    {
                                        return Html::a(' ', 
                                                ['rejection/rescind', 'id' => $row['rejectionid'], 'rejectiontype' => $row['rejectiontype']], 
                                                ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                    'data' => [
                                                        'confirm' => 'Are you sure you want to revoke this rejection?',
                                                        'method' => 'post',
                                                    ],
                                                ]);
                                    }
                                    else
                                        return "N/A";
                                }
                                else
                                {
                                    return "N/A";
                                }
                              }
                        ],
                    ],
                ]); 
            ?>
        </div>
    </div>
<?php endif;?>
    