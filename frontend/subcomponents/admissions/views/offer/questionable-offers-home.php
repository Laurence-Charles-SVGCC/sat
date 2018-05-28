<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;

    use frontend\models\Offer;

    if($offertype==1)
        $val = " Offers ";
    else 
        $val = " Invitations ";
    $this->title = 'Questionable' . $val;
    
     if ($offertype == 1)
        $this->params['breadcrumbs'][] = ['label' => 'Offer Listing', 'url' => Url::toRoute(['/subcomponents/admissions/offer', 'offertype' => 1])];
    elseif ($offertype == 2)
        $this->params['breadcrumbs'][] = ['label' => 'Offer Listing', 'url' => Url::toRoute(['/subcomponents/admissions/offer', 'offertype' => 2])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <?php if ($offertype == 1):?>
        <a href="<?= Url::toRoute(['/subcomponents/admissions/offer', 'offertype' => 1]);?>" title="Offer Management">
            <h1>Welcome to the Admissions Management System</h1>
        </a>
    <?php elseif ($offertype == 2):?>
        <a href="<?= Url::toRoute(['/subcomponents/admissions/offer', 'offertype' => 2]);?>" title="Offer Management">
            <h1>Welcome to the Admissions Management System</h1>
        </a>
    <?php endif;?>
</div>


<h2 class="text-center"><?= $this->title?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title">Categories of Questionable Offers</span>
    </div>
    
    <div class="box-body">
        <ul>
            <?php if($multiple_offers):?>
                <li>
                    <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'offertype' => $offertype, 'criteria' => 'mult']);?>" 
                        title="Multiple offers"
                        style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                        Click here to view successful applicants with possible multiple offers
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
        </ul>
    </div>
</div><br/>

<?php if($dataProvider):?>
    <div class="box box-primary no-padding" style ="font-size:1.2em;">
        <div class="box-body">
            <h3><?=$offer_type?></h3>
                
            <?= ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
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
        </div>
    </div>
<?php endif;?>