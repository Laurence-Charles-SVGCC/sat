<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\ApplicationPeriod;
    use frontend\models\Application;
    use frontend\models\Division;
    use frontend\models\Offer;
    use frontend\models\Package;

    $active_periods = ApplicationPeriod::getOpenPeriodIDs();
    if (in_array(4, $active_periods) == true)
    {
        $filter_criteria['none'] = 'No Filter' ; 
        $filter_criteria['division'] = 'By Division'; 
        $filter_criteria['programme'] = 'By Programme'; 
        $filter_criteria['cape_subject'] = 'By Cape Subject';
        $filter_criteria['pending'] = 'Pending';
        $filter_criteria['ispublished'] = 'Published'; 
        $filter_criteria['revoked'] = 'Is Revoked?'; 
    }
    else
    {
        $filter_criteria['none'] = 'No Filter'; 
        $filter_criteria['division'] = 'By Division'; 
        $filter_criteria['programme'] = 'By Programme';
        $filter_criteria['pending'] = 'Pending';
        $filter_criteria['ispublished'] = 'Published'; 
        $filter_criteria['revoked'] = 'Is Revoked?';
    }

    if ($offertype == 1)
        $this->title = $divisionabbr . ' Unconditional Offers for ' . $applicationperiodname;
    else
       $this->title = $divisionabbr . ' Interviewees for ' . $applicationperiodname;
    
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

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/>

<h2 class="text-center"><?= $this->title?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <?php if($offer_issues): ?>
        <div class="box-header with-border">
            <div style="font-size:16px; width: 95%; margin: 0 auto;">
                <?php if($offertype==1):?>
                    <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'offertype' => $offertype]);?>" 
                       title="Questionable Offers"
                       style="font-size:16px; width: 100%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                        Click Here To Review Questionable Offers
                    </a>
                <?php elseif ($offertype == 2):?>
                    <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'offertype' => $offertype]);?>" 
                       title="Questionable Offers"
                       style="font-size:16px; width: 100%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                        Click Here To Review Questionable Interviewees
                    </a>
                <?php endif;?>
            </div>
        </div>
    <?php endif;?>
    
    <?php $form = ActiveForm::begin(['action' => Url::to(['offer/update-view', 'offertype' => $offertype]),]); ?>
        <div class="box-body">
            <p>
                Please select a filtering criteria.
                <?= Html::radioList('offer_filter', null, $filter_criteria, ['class'=> 'form_field', 'onclick'=> 'filterOffer();']);?>
             </p>

            <div id="offer-home" style="display:none">
                <a class="btn btn-success" href=<?=Url::toRoute(['/subcomponents/admissions/offer', 'offertype' => $offertype]);?> role="button">  Remove Filter</a>
            </div>

            <div id="offer-division" style="display:none">
                <?= Html::label( 'Divisions',  'programme'); ?>
                <?= Html::dropDownList('offer-division-field', null, $divisions, ['id' => 'offer-division-field', 'onchange' => 'showFilterButton1();']) ; ?>
                <span id="divisional-filter-button" style="display:none;">
                    <?= Html::submitButton('Filter', ['class' => 'btn btn-success', 'style' => 'margin-left:60%;']) ?>
                </span>
            </div>

            <div id="offer-programme" style="display:none">
                <?= Html::label( 'Programmes',  'programme'); ?>
                <?= Html::dropDownList('offer-programme-field', null, $programmes, ['id' => 'offer-programme-field', 'onchange' => 'showFilterButton2();']) ; ?>
                <span id="programme-filter-button" style="display:none;">
                    <?= Html::submitButton('Filter', ['class' => 'btn btn-success', 'style' => 'margin-left:75%;']) ?>
                </span>
            </div>

            <div id="offer-cape" style="display:none">
                <?= Html::label( 'CAPE Subjects',  'cape'); ?>
                <?= Html::dropDownList('offer-cape-field', null, $cape_subjects, ['id' => 'offer-cape-field', 'onchange' => 'showFilterButton3();']) ; ?>
                <span id="cape-filter-button" style="display:none;">
                    <?= Html::submitButton('Filter', ['class' => 'btn btn-success', 'style' => 'margin-left:50%;']) ?>
                </span>
            </div>

            <div id="offer-awaiting-publish" style="display:none">
                <a class="btn btn-success" href=<?=Url::toRoute(['/subcomponents/admissions/offer', 'offertype' => $offertype, 'criteria' => 'awaiting-publish']);?> role="button">  View Pending Offers</a>
            </div> 

            <div id="offer-published" style="display:none">
                <a class="btn btn-success" href=<?=Url::toRoute(['/subcomponents/admissions/offer', 'offertype' => $offertype, 'criteria' => 'ispublished']);?> role="button">  View Published Offers</a>
            </div>

            <div id="offer-revoked" style="display:none">
                <a class="btn btn-success" href=<?=Url::toRoute(['/subcomponents/admissions/offer', 'offertype' => $offertype, 'criteria' => 'revoked']);?> role="button">  View Revoked Offers</a>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div><br/>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <div class="box-title">
            <span>Offer Listing</span>
        </div>
    </div>
    
    <div class="box-body">
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
                    'label' => 'Sent'
                ],
                [
                    'attribute' => 'offerid',
                    'label' => 'Revoke',
                    'format' => 'html',
                    'value' => function($row) 
                     {
                        if (Yii::$app->user->can('deleteOffer'))
                        {
                            if(($row['offertype'] == 2  &&  Offer::hasActiveFullOffer($row['personid']) == true)  || $row['ispublished'] == 1)
                                return "N/A";
                            else
                            {
                                if($row['revokedby'] == "N/A")
                                {
                                    return Html::a(' ', 
                                            ['revoke', 'id' => $row['offerid'], 'offertype' => $row['offertype']], 
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
                        }
                        else
                        {
                            return "N/A";
                        }
                      }
                ],
                [
                    'label' => 'Publish',
                    'format' => 'html',
                    'value' => function($row)
                     {
                        if (Yii::$app->user->can('publishOffer'))
                        {
                            if (Package::hasCompletePackage($row['divisionid'], 1, $row['offertype'] ) == false)
                            {
                                return "N/A";
                            }
                            else 
                            {
                                if ($row['ispublished'] == 1)
                                {
                                    return Html::a(' ', 
                                                ['package/publish-single', 'category' => 1, 'itemid' => $row['offerid'], 'divisionid' => $row['divisionid']], 
                                                ['class' => 'btn btn-warning glyphicon glyphicon-repeat',
                                                    'data' => [
                                                        'confirm' => 'This offer has been issued before. Are you sure you want to re-publish this offer?',
                                                        'method' => 'post',
                                                    ],
                                                ]);
                                }
                                else
                                {
                                    return Html::a(' ', 
                                                ['package/publish-single', 'category' => 1,  'itemid' => $row['offerid'], 'divisionid' =>$row['divisionid']], 
                                                ['class' => 'btn btn-success glyphicon glyphicon-send',
                                                    'data' => [
                                                        'confirm' => 'Are you sure you want to publish this offer?',
                                                        'method' => 'post',
                                                    ],
                                                ]);
                                }
                            }
                        }
                        else
                        {
                            return "N/A";
                        }
                    }
                ],
            ],
        ]); ?>
    </div>
</div>