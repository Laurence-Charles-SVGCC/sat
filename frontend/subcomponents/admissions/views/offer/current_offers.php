<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\ApplicationPeriod;
    use frontend\models\Application;
    use frontend\models\Division;
    use frontend\models\Offer;
    use frontend\models\Package;

    /* @var $this yii\web\View */
    /* @var $dataProvider yii\data\ActiveDataProvider */


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
<div class="body-content">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <?php if($offer_issues): ?>
            <br/><div style="font-size:16px; width: 95%; margin: 0 auto;">
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
        <?php endif;?>
            
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>

            <div class="row">
                <div class="col-lg-9">
                    <?php $form = ActiveForm::begin(
                        [
                            'action' => Url::to(['offer/update-view', 'offertype' => $offertype]),
                        ]
                        ); ?>

                        <div style="margin-left:0.5%">
                            <p class="general_text">
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

                    <?php if($dataProvider->getTotalCount() > 0):?>
                        <br/><div style="margin-left:0.5%">
                            <p class="general_text">
                                Would you like to export the Offers listing?
                                <?= Html::radioList('export_options', null, ["Yes" => "Yes", "No" => "No"], ['class'=> 'form_field', 'onclick'=> 'toggleExport();']);?>
                            </p>

                            <div id="export-buttons" style="display:none">
                                <?= Html::a('Export All Offers', ['export-all-offers', 'offertype' => $offertype], ['class' => 'btn btn-primary']) ?>
                                
                                <?php if(Offer::hasPendingOffers() == true):?>
                                    <?= Html::a('Export Pending Offers', ['export-unpublished-offers', 'offertype' => $offertype], ['class' => 'btn btn-primary']) ?>
                                <?php endif;?>
                                
                                <?php if(Offer::hasPublishedOffers() == true):?>
                                    <?= Html::a('Export Published Offers', ['export-published-offers', 'offertype' => $offertype], ['class' => 'btn btn-primary']) ?>
                                <?php endif;?>
                                
                                <?php if(Offer::hasRevokededOffers() == true):?>
                                    <?= Html::a('Export Revoked Offers', ['export-revoked-offers', 'offertype' => $offertype], ['class' => 'btn btn-warning']) ?>
                                <?php endif;?>
                            </div>

                            <?php if (Yii::$app->user->can('publishOffer')): ?>
                                <br/>
                                <p class="general_text">
                                    Would you like to publish outstanding offers?
                                    <?= Html::radioList('publish_options', null, ["Yes" => "Yes", "No" => "No"], ['class'=> 'form_field', 'onclick'=> 'togglePublish();']);?>
                                </p>

                                <div id="publish-button" style="display:none">
                                    <?php
                                        $periods = ApplicationPeriod::periodIncomplete();
                                        if (Offer::anyOfferExists($periods, $offertype) == false  ||  Package::hasCompletePackage(1,1) == false)
                                            echo "<p><strong>No offers can be published at this time. Please ensure the requiste packages have been created.</strong></p>";
                                        
                                        if ($periods == true)
                                        {
                                            foreach ($periods as $period) 
                                            {
                                                if(Offer::offerExists($period->applicationperiodid, $offertype) == true  && Package::hasCompletePackage($period->divisionid, 1, $offertype) == true)
                                                    echo Html::a('Bulk Publish ' . Division::getDivisionAbbreviation($period->divisionid), ['package/bulk-publish', 'category' => 1,  'sub_category' => $offertype, 'divisionid' => $period->divisionid], ['class' => 'btn btn-primary', 'style' => 'margin-left:15px']);
                                            }
                                            echo "<br/><br/>";
                                            
                                            
                                            echo "<div class='dropdown'>";
                                                echo "<span style='margin-right:2.5%'>You may also publish offers by programme: </span>";
                                                echo "<button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                                echo "Select programme...";
                                                echo "<span class='caret'></span>";
                                                echo "</button>";
                                                echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>";
                                                    $keys = array_keys($progs_with_pending_offers);
                                                    $values = array_values($progs_with_pending_offers);
                                                   
                                                    if ($progs_with_pending_offers == true && count($keys) == count($values))
                                                    {
                                                        for ($i = 0 ; $i < count($keys) ; $i++)
                                                        {
                                                             $divisionid = Application::find()
                                                                     ->where(['academicofferingid' => $keys[$i], 'isactive' => 1, 'isdeleted' => 0])
                                                                     ->one()
                                                                     ->divisionid;
                                                             if ($divisionid == false)
                                                                 continue;
                                                             
                                                            $hyperlink = Url::toRoute(['/subcomponents/admissions/package/bulk-publish-by-offering/', 
                                                                                                    'category' => 1,
                                                                                                    'sub_category' => $offertype,
                                                                                                    'divisionid' => $divisionid,
                                                                                                    'academicofferingid' => $keys[$i],
                                                                                                 ]);
                                                             echo "<li><a href='$hyperlink'>$values[$i]</a></li>";  
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "<li>No offers have been found</li>";  
                                                    }
                                                echo "</ul>";
                                            echo "</div>";
                                        }
                                   ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            

            <br/>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['style' => 'width: 99%; margin: 0 auto;'],
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
                ],
            ]); ?>
        </div>
    </div>
</div>
