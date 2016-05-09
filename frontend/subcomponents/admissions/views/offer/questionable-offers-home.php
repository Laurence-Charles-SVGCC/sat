<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

use frontend\models\Offer;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
if($offertype==1)
    $val = " Offers ";
else 
    $val = "Interview Invitations ";
$this->title = 'Questionable' . $val . 'Dashboard';
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
                <h2 class="custom_h2">Categories of Questionable <?php if($offertype==1)echo "Unconditional Offers:"; else echo"Interview Invitations:";?></h2>
                <ul>
                    <?php if($multiple_offers):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'offertype' => $offertype, 'criteria' => 'mult']);?>" 
                                title="Multiple offers"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicants with multiple offers
                            </a>
                        </li><br/>
                    <?php endif;?>
                        
                    <?php if($math_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'offertype' => $offertype, 'criteria' => 'maths']);?>" 
                                title="Lack CSEC Mathematics Pass"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicant lacking CSEC/GCE Mathematics
                             </a>
                        </li><br/>
                    <?php endif;?>
                    
                    <?php if($english_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'offertype' => $offertype, 'criteria' => 'english']);?>" 
                                title="Lack CSEC English Pass"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicant lacking CSEC/GCE English Language
                             </a>
                        </li><br/>
                    <?php endif;?>
                        
                    <?php if($subjects_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'offertype' => $offertype, 'criteria' => 'five_passes']);?>" 
                                title="Lack 5 CSEC Passes"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicants lacking five(5) CSEC/GCE passes
                             </a>
                        </li><br/>
                    <?php endif;?>
                    
                    <?php if($dte_science_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'offertype' => $offertype, 'criteria' => 'dte']);?>" 
                                title="Lack DTE Required Reelvant Science"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicants lacking DTE's the required relevant science
                             </a>
                        </li><br/>
                    <?php endif;?>
                        
                    <?php if($dne_science_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'offertype' => $offertype, 'criteria' => 'dne']);?>" 
                                title="Lack DNE Required Relvant Science"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicants lacking DNE's required relevant science
                             </a>
                        </li>
                    <?php endif;?>
                </oul>
            </div>
            
            <?php if($dataProvider):?>
                <h3 style="margin-left:2.5%"><?=$offer_type?></h3>
                
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
//                                                       Url::to(['offer/view', 'id' => $row['offerid']]));
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
                        'options' => ['style' => 'width: 95%; margin: 0 auto;'],
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
                                'attribute' => 'offerid',
                                'label' => 'Revoke',
                                'format' => 'html',
                                'value' => function($row, $offertype)
                                 {
                                    if (Yii::$app->user->can('deleteOffer'))
                                    {
                                        if($row['revokedby'] == "N/A")
                                        {
                                            return Html::a(' ', 
                                                    ['offer/revoke', 'id' => $row['offerid'], 'offertype' => $offertype], 
                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                        'data' => [
                                                            'confirm' => 'Are you sure you want to revoke this offer?',
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