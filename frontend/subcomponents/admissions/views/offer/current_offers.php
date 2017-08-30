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
    use frontend\models\AcademicOffering;

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
        </div><br/><br/>
    <?php ActiveForm::end(); ?>
</div><br/>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <div class="box-title">
            <?php if ($offertype == 2 && $incomplete_periods == true) : ?>
                 <span>Interview Scheduling , Report Generation & Publishing</span>
            <?php else: ?>
                <span> Report Generation & Publishing</span>
             <?php endif ?>
        </div>
    </div>
    
    <?php if($dataProvider->getTotalCount() > 0):?>
        <br/><div style="margin-left:0.5%">
             <?php if ($offertype == 2 && $incomplete_periods == true) : ?>
                <div><strong>Prepare interview schedule by name:</strong><br/>
                    <span>Select the application period you wish to prepare interview schedule for?</span>
                    <span class='dropdown' style="margin-left:2%">
                        <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                            Select application period...
                            <span class='caret'></span>
                        </button>
                        <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                            <?php
                                foreach ($incomplete_periods as $period)
                                {
                                    $label_a_g = $period->name . " - Surnames (A-G)";
                                    $label_h_n = $period->name . " - Surnames (H-N)";
                                    $label_o_z = $period->name . " - Surnames (O-Z)";
                                    $hyperlink_a_g = Url::toRoute(['/subcomponents/admissions/offer/schedule-interviews-by-lastname/', 
                                                                              'applicationperiod_id' => $period->applicationperiodid,
                                                                              'offertype' => $offertype,
                                                                              'lower_bound' => 'A',
                                                                              'upper_bound' => 'G']);
                                    $hyperlink_h_n = Url::toRoute(['/subcomponents/admissions/offer/schedule-interviews-by-lastname/', 
                                                                              'applicationperiod_id' => $period->applicationperiodid,
                                                                              'offertype' => $offertype,
                                                                              'lower_bound' => 'H',
                                                                              'upper_bound' => 'N']);
                                    $hyperlink_o_z = Url::toRoute(['/subcomponents/admissions/offer/schedule-interviews-by-lastname/', 
                                                                              'applicationperiod_id' => $period->applicationperiodid,
                                                                              'offertype' => $offertype,
                                                                              'lower_bound' => 'O',
                                                                              'upper_bound' => 'Z']);
                                    echo "<li><a href='$hyperlink_a_g'>$label_a_g</a></li>";  
                                    echo "<li><a href='$hyperlink_h_n'>$label_h_n</a></li>";  
                                    echo "<li><a href='$hyperlink_o_z'>$label_o_z</a></li>";  
                                }
                            ?>
                        </ul>
                    </span><br/><br/>
                </div>
            
                <div><strong>Prepare interview schedule by programme:</strong>
                    <span class='dropdown' style="margin-left:2%">
                        <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                            Select application period...
                            <span class='caret'></span>
                        </button>
                        <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                            <?php
                                foreach ($incomplete_periods as $period)
                                {
                                    foreach( $programme_objects as $prog)
                                    {
                                        $academic_offering = AcademicOffering::find()
                                                ->where(['programmecatalogid' => $prog->programmecatalogid, 'applicationperiodid' => $period->applicationperiodid,
                                                    'interviewneeded' => 1, 'isactive' => 1, 'isdeleted' => 0])
                                                ->one();
                                        if ($academic_offering == true)
                                        {
                                            $label = $prog->getFullName();
                                            $hyperlink = Url::toRoute(['/subcomponents/admissions/offer/schedule-interviews-by-programme/', 
                                                                                      'academic_offering_id' => $academic_offering->academicofferingid,
                                                                                      'offertype' => $offertype]); 
                                            echo "<li><a href='$hyperlink'>$label</a></li>";  
                                        }
                                    }
                                }
                            ?>
                        </ul>
                    </span><br/><br/>
                </div>
              <?php endif ?>
            
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
                    <ol>
                        <?php
                            $periods = ApplicationPeriod::periodIncomplete();
                            if ($periods > 0)
                            {
                                if (Offer::anyPendingOfferExists($periods, $offertype) == true  &&  Package::hasCompletePackage(1, 1, $offertype) == false)
                                {
                                    echo "<p><strong>Requiste package(s)  must be configured before offers can be published.<strong></p>";
                                }
                                elseif (Offer::anyPendingOfferExists($periods, $offertype) == false  &&  Package::hasCompletePackage(1, 1, $offertype) == true)
                                {
                                    echo "<p><strong>Offer package(s) are configured; but no offers currently exist to publish.</strong></p>";
                                }
                                else
                                {
                                     //Bulk Publish All Divisions
                                    if (Offer::anyPendingOfferExists($periods, $offertype) == true  &&  Package::hasCompletePackage(1, 1, $offertype) == true)
                                    {
                                        echo "<li>";
                                            echo "<span>Publish All Pending Offers :</span>";
                                            echo Html::a('Bulk Publish', ['package/bulk-publish', 'category' => 1,  'sub_category' => $offertype], ['class' => 'btn btn-primary', 'style' => 'margin-left:15px']) . "<br/></br/>";
                                        echo "</li>";
                                        echo "<br/>";
                                    }
                                    
                                    //Bulk Publish By Division
                                    foreach ($periods as $period) 
                                    {
                                        if(Offer::offerExists($period->applicationperiodid, $offertype) == true  && Package::hasCompletePackage($period->divisionid, 1, $offertype) == true)
                                        {
                                            echo "<li>";
                                                echo "<span>" . Division::getDivisionAbbreviation($period->divisionid) . "  Offers :</span>";
                                                echo Html::a('Bulk Publish ' . Division::getDivisionAbbreviation($period->divisionid), ['package/bulk-publish', 'category' => 1,  'sub_category' => $offertype, 'divisionid' => $period->divisionid], ['class' => 'btn btn-primary', 'style' => 'margin-left:15px']);
                                            echo "</li>";
                                            
                                        }
                                    }
                                    
                                    //Publish By Programme
                                    if (Offer::anyPendingOfferExists($periods, $offertype) == true  &&  Package::hasCompletePackage(1, 1) == true && count($progs_with_pending_offers) > 0)
                                    {    
                                        echo "<br/></br>";
                                        echo "<li>";
                                            echo "<div class='dropdown'>
                                                <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                                echo "Click to publish offers by programme...";
                                                echo "<span class='caret'></span>";
                                                echo "</button>";
                                                echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>";
                                                    foreach ($progs_with_pending_offers as $key=>$prog_with_pending_offer)
                                                    {
                                                        $hyperlink = Url::toRoute(['/subcomponents/admissions/package/bulk-publish', 
                                                                                                'category' => 1,
                                                                                                'sub_category' => $offertype,
                                                                                                'divisionid' => $period->divisionid,
                                                                                                'academicofferingid' => $key]);
                                                        echo "<li><a href='$hyperlink'>$prog_with_pending_offer</a></li>";  
                                                    }
                                                echo "</ul>";
                                            echo "</div>";
                                        echo "</li>";
                                    }
                                }
                            }
                       ?>
                   </ol>
                </div>
            <?php endif; ?>
        </div><br/><br/>
    <?php endif; ?>
</div><br/>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <div class="box-title">
            <span>Offer Listing</span>
        </div>
    </div>
    
    <div class="box-body">
        <?php if ($offertype == 1) :?>
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
        
        <?php elseif ($offertype == 2) :?>
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
                    [
                        'label' => 'Appointment',
                        'format' => 'html',
                        'value' => function($row)
                        {
                            if ($row['appointment'] == NULL)
                            {
                                return Html::a('Set Appointment ', 
                                                    ['offer/schedule-interview', 'offerid' => $row['offerid'],  'offertype' => $row['offertype']], 
                                                    ['class' => 'btn btn-default']);
                            }
                            else
                            {
                                return Html::a($row['appointment'], 
                                                    ['offer/schedule-interview', 'offerid' => $row['offerid'],  'offertype' => $row['offertype']]);
                            }
                        }
                    ],
                ],
            ]); ?>
        <?php endif;?>
    </div>
</div>