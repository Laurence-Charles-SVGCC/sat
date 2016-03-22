<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\ApplicationPeriod;
    use frontend\models\Division;

    /* @var $this yii\web\View */
    /* @var $dataProvider yii\data\ActiveDataProvider */


    $active_periods = ApplicationPeriod::getOpenPeriodIDs();
    if (in_array(4, $active_periods) == true)
    {
        $filter_criteria['none'] = 'No Filter' ; 
        $filter_criteria['division'] = 'By Division'; 
        $filter_criteria['programme'] = 'By Programme'; 
        $filter_criteria['cape_subject'] = 'By Cape Subject'; 
        $filter_criteria['ispublished'] = 'Is Published?'; 
        $filter_criteria['revoked'] = 'Is Revoked?'; 
    }
    else
    {
        $filter_criteria['none'] = 'No Filter'; 
        $filter_criteria['division'] = 'By Division'; 
        $filter_criteria['programme'] = 'By Programme';
        $filter_criteria['ispublished'] = 'Is Published?'; 
        $filter_criteria['revoked'] = 'Is Revoked?';
    }

    $this->title = $divisionabbr . ' Offers for ' . $applicationperiodname;
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
        
        <?php if($offer_issues): ?>
            <br/><div style="font-size:16px; width: 95%; margin: 0 auto;">
                <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home']);?>" 
                   title="Questionable Offers"
                   style="font-size:16px; width: 100%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                    Click Here To Review Questionable Offers
                </a>
            </div>
        <?php endif;?>
            
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>

            <div class="row">
                <div class="col-lg-9">
                    <?php $form = ActiveForm::begin(
                        [
                            'action' => Url::to(['offer/update-view']),
                        ]
                        ); ?>
                    
                        <div style="margin-left:0.5%">
                            <p class="general_text">
                                Please select a filtering criteria.
                                <?= Html::radioList('offer_filter', null, $filter_criteria, ['class'=> 'form_field', 'onclick'=> 'filterOffer();']);?>
                             </p>
                            
                            <div id="offer-home" style="display:none">
                                <a class="btn btn-success" href=<?=Url::toRoute(['/subcomponents/admissions/offer']);?> role="button">  Remove Filter</a>
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
                            
                            <div id="offer-published" style="display:none">
                                <a class="btn btn-success" href=<?=Url::toRoute(['/subcomponents/admissions/offer', 'criteria' => 'ispublished']);?> role="button">  View Published Offers</a>
                            </div>
                            
                            <div id="offer-revoked" style="display:none">
                                <a class="btn btn-success" href=<?=Url::toRoute(['/subcomponents/admissions/offer', 'criteria' => 'revoked']);?> role="button">  View Revoked Offers</a>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                    
                    <br/><div style="margin-left:0.5%">
                        <p class="general_text">
                            Would you like to export the Offers listing?
                            <?= Html::radioList('export_options', null, ["Yes" => "Yes", "No" => "No"], ['class'=> 'form_field', 'onclick'=> 'toggleExport();']);?>
                        </p>
                        
                        <div id="export-buttons" style="display:none">
                            <?= Html::a('Export Valid Offers', ['export-valid-offers'], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Export All Offers', ['export-all-offers'], ['class' => 'btn btn-primary']) ?>
                        </div>
                        
                        <?php if (Yii::$app->user->can('publishOffer')): ?>
                            <br/>
                            <p class="general_text">
                                Would you like to publish outstanding offers?
                                <?= Html::radioList('publish_options', null, ["Yes" => "Yes", "No" => "No"], ['class'=> 'form_field', 'onclick'=> 'togglePublish();']);?>
                            </p>

                            <div id="publish-button" style="display:none">
                                <?= Html::a('Bulk Publish', ['bulk-publish'], ['class' => 'btn btn-primary', 'style' => 'margin-left:15px']) ?>
                                
                                <?php
                                    $periods = ApplicationPeriod::periodIncomplete();
                                    if ($periods == true)
                                    {
                                        foreach ($periods as $period) 
                                        {
                                            echo Html::a('Bulk Publish ' . Division::getDivisionAbbreviation($period->divisionid), ['bulk-publish', 'division' => Division::getDivisionAbbreviation($period->divisionid)], ['class' => 'btn btn-primary', 'style' => 'margin-left:15px']);
                                        }
                                    }

                               ?>
                            </div>
                        <?php endif; ?>
                    </div>
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
                                       Url::to(['offer/view', 'id' => $row['offerid']]));
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
                ],
            ]); ?>
        </div>
    </div>
</div>
