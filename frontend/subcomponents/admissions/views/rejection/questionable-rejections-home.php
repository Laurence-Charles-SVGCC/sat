<?php

    use yii\helpers\Html;
    //use yii\grid\GridView;
    use yii\helpers\Url;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;

    /* @var $this yii\web\View */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = 'Questionable Rejection Dashboard';
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="body-content">
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
            <div style="margin-left:2.5%">
                <h2 class="custom_h2">Categories of Questionable Rejections:</h2>
                <ul>
                    <?php if($math_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/rejection/rejection-details-home', 'rejectiontype' => $rejectiontype, 'criteria' => 'maths']);?>" 
                                title="Lack CSEC Mathematics Pass"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view unsuccessful applicants who have CSEC Mathematics pass
                             </a>
                        </li><br/>
                    <?php endif;?>
                    
                    <?php if($english_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/rejection/rejection-details-home', 'rejectiontype' => $rejectiontype, 'criteria' => 'english']);?>" 
                                title="Lack CSEC English Pass"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view unsuccessful applicants who have CSEC English Language pass
                             </a>
                        </li><br/>
                    <?php endif;?>
                        
                    <?php if($subjects_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/rejection/rejection-details-home', 'rejectiontype' => $rejectiontype, 'criteria' => 'five_passes']);?>" 
                                title="Lack 5 CSEC Passes"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view unsuccessful applicants possessing five(5) CSEC passes
                             </a>
                        </li><br/>
                    <?php endif;?>
                    
                    <?php if($dte_science_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/rejection/rejection-details-home', 'rejectiontype' => $rejectiontype, 'criteria' => 'dte']);?>" 
                                title="Lack DTE Required Reelvant Science"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view unsuccessful applicants who satisfy DTE's the required relevant science
                             </a>
                        </li><br/>
                    <?php endif;?>
                        
                    <?php if($dne_science_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/rejection/rejection-details-home',  'rejectiontype' => $rejectiontype, 'criteria' => 'dne']);?>" 
                                title="Lack DNE Required Relvant Science"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view unsuccessful applicants who satisfy DNE's required relevant science
                             </a>
                        </li>
                    <?php endif;?>
                </ul>
            </div>
            
            <?php if($dataProvider):?>
                <h3 style="margin-left:2.5%"><?=$rejection_type?></h3>
                
                <div style = 'margin-left: 2.5%;'>
                    <?= ExportMenu::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
//                                    [
//                                        'attribute' => 'username',
//                                        'format' => 'html',
//                                        'value' => function($row)
//                                         {
//                                            return Html::a($row['username'], 
//                                                            Url::to(['process-applications/view-applicant-certificates',
//                                                                     'personid' => $row['personid'],
//                                                                     'programme' => $row['prog'], 
//                                                                     'application_status' => $row['status'],
//                                                                    ]));  
//                                          }
//                                    ],
                                    'username',
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
                        'options' => ['style' => 'width: 98%; margin: 0 auto;'],
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
            <?php endif;?>
            
        </div>
    </div>
</div>