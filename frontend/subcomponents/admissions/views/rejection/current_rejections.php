<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\ApplicationPeriod;
    use frontend\models\Division;
    use frontend\models\Rejection;
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

    if ($rejectiontype == 1)
            $rejection_name = "Pre-Interview";
        else
            $rejection_name = "Post-Interview";
    $this->title = $divisionabbr . ' ' . $rejection_name .   ' Rejections for ' . $applicationperiodname;
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
        
        <?php if($rejection_issues): ?>
            <br/><div style="font-size:16px; width: 95%; margin: 0 auto;">
                <a href="<?= Url::toRoute(['/subcomponents/admissions/rejection/rejection-details-home' , 'rejectiontype' => $rejectiontype]);?>" 
                   title="Questionable Rejections"
                   style="font-size:16px; width: 100%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                    Click Here To Review Questionable Rejections
                </a>
            </div>
        <?php endif;?>
            
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
            
            <div class="row">
                <div class="col-lg-9">
                    <?php $form = ActiveForm::begin(
                        [
                            'action' => Url::to(['rejection/update-view', 'rejectiontype' => $rejectiontype]),
                        ]
                        ); ?>

                        <div style="margin-left:0.5%">
                            <p class="general_text">
                                Please select a filtering criteria.
                                <?= Html::radioList('rejection_filter', null, $filter_criteria, ['class'=> 'form_field', 'onclick'=> 'filterRejection();']);?>
                             </p>

                            <div id="rejection-home" style="display:none">
                                <a class="btn btn-success" href=<?=Url::toRoute(['/subcomponents/admissions/rejection', 'rejectiontype' => $rejectiontype]);?> role="button">  Remove Filter</a>
                            </div>

                            <div id="rejection-division" style="display:none">
                                <?= Html::label( 'Divisions',  'programme'); ?>
                                <?= Html::dropDownList('rejection-division-field', null, $divisions, ['id' => 'rejection-division-field', 'onchange' => 'showRejectionFilterButton1();']) ; ?>
                                <span id="divisional-filter-button" style="display:none;">
                                    <?= Html::submitButton('Filter', ['class' => 'btn btn-success', 'style' => 'margin-left:60%;']) ?>
                                </span>
                            </div>

                            <div id="rejection-programme" style="display:none">
                                <?= Html::label( 'Programmes',  'programme'); ?>
                                <?= Html::dropDownList('rejection-programme-field', null, $programmes, ['id' => 'rejection-programme-field', 'onchange' => 'showRejectionFilterButton2();']) ; ?>
                                <span id="programme-filter-button" style="display:none;">
                                    <?= Html::submitButton('Filter', ['class' => 'btn btn-success', 'style' => 'margin-left:75%;']) ?>
                                </span>
                            </div>
                            
                           
                            <div id="rejection-cape" style="display:none">
                                <?= Html::label( 'CAPE Subjects',  'cape'); ?>
                                <?= Html::dropDownList('rejection-cape-field', null, $cape_subjects, ['id' => 'rejection-cape-field', 'onchange' => 'showRejectionFilterButton3();']) ; ?>
                                <span id="cape-filter-button" style="display:none;">
                                    <?= Html::submitButton('Filter', ['class' => 'btn btn-success', 'style' => 'margin-left:50%;']) ?>
                                </span>
                            </div>

                            <div id="rejection-awaiting-publish" style="display:none">
                                <a class="btn btn-success" href=<?=Url::toRoute(['/subcomponents/admissions/rejection', 'rejectiontype' => $rejectiontype , 'criteria' => 'awaiting-publish']);?> role="button">  View Pending Rejections</a>
                            </div> 

                            <div id="rejection-published" style="display:none">
                                <a class="btn btn-success" href=<?=Url::toRoute(['/subcomponents/admissions/rejection', 'rejectiontype' => $rejectiontype, 'criteria' => 'ispublished']);?> role="button">  View Published Rejections</a>
                            </div>

                            <div id="rejection-revoked" style="display:none">
                                <a class="btn btn-success" href=<?=Url::toRoute(['/subcomponents/admissions/rejection', 'rejectiontype' => $rejectiontype, 'criteria' => 'revoked']);?> role="button">  View Revoked Rejections</a>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                        
                    <?php if($dataProvider->getTotalCount() > 0):?>
                        <br/><div style="margin-left:0.5%">
                            <p class="general_text">
                                Would you like to export the Rejections listing?
                                <?= Html::radioList('export_options', null, ["Yes" => "Yes", "No" => "No"], ['class'=> 'form_field', 'onclick'=> 'toggleExport();']);?>
                            </p>

                            <div id="export-buttons" style="display:none">
                                <?= Html::a('Export All Rejections', ['export-all-rejections', 'rejectiontype' => $rejectiontype], ['class' => 'btn btn-primary']) ?>
                                <?php if(Rejection::hasPendingRejections() == true):?>
                                    <?= Html::a('Export Pending Rejections', ['export-unpublished-rejections', 'rejectiontype' => $rejectiontype], ['class' => 'btn btn-primary']) ?>
                                <?php endif;?>
                                <?php if(Rejection::hasPublishedRejections() == true):?>
                                    <?= Html::a('Export Published Rejections', ['export-published-rejections', 'rejectiontype' => $rejectiontype], ['class' => 'btn btn-primary']) ?>
                                <?php endif;?>
                                <?php if(Rejection::hasRevokededRejections() == true):?>
                                    <?= Html::a('Export Revoked Rejections', ['export-revoked-rejections', 'rejectiontype' => $rejectiontype], ['class' => 'btn btn-warning']) ?>
                                <?php endif;?>
                            </div>

                            <?php if (Yii::$app->user->can('publishRejection')): ?>
                                <br/>
                                <p class="general_text">
                                    Would you like to publish outstanding rejections?
                                    <?= Html::radioList('publish_options', null, ["Yes" => "Yes", "No" => "No"], ['class'=> 'form_field', 'onclick'=> 'togglePublish();']);?>
                                </p>

                                <div id="publish-button" style="display:none">
                                    <?php
                                        $periods = ApplicationPeriod::periodIncomplete();
                                        if (Rejection::anyRejectionExists($periods, $rejectiontype) == false  ||  Package::hasCompletePackage(1, 0) == false)
                                           echo "<p><strong>No rejections can be published at this time. Please ensure the requiste packages have been created.</strong></p>";
                                        
                                        if ($periods == true)
                                        {
                                            foreach ($periods as $period) 
                                            {
                                                if(Rejection::rejectionExists($period->applicationperiodid, $rejectiontype) == true  && Package::hasCompletePackage($period->divisionid, 0, $rejectiontype) == true)
                                                    echo Html::a('Bulk Publish ' . Division::getDivisionAbbreviation($period->divisionid), ['package/bulk-publish', 'category' => 2,  'sub_category' => $rejectiontype, 'divisionid' => $period->divisionid], ['class' => 'btn btn-primary', 'style' => 'margin-left:15px']);
                                            }
                                        }

                                   ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif;?>
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
                        'attribute' => 'rejectionid',
                        'label' => 'Rescind',
                        'format' => 'html',
                        'value' => function($row)
                         {
                            if (Yii::$app->user->can('deleteRejection'))
                            {
                                if($row['revokedby'] == "N/A"  &&  $row['ispublished'] == 0)
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
            ]); ?>
        </div>
    </div>
</div>
