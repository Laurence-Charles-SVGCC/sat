<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\ApplicationPeriod;

    /* @var $this yii\web\View */
    /* @var $dataProvider yii\data\ActiveDataProvider */


    $active_periods = ApplicationPeriod::getOpenPeriodIDs();
    if (in_array(4, $active_periods) == true)
    {
        $filter_criteria['none'] = 'No Filter' ; 
        $filter_criteria['division'] = 'By Division'; 
        $filter_criteria['programme'] = 'By Programme'; 
        $filter_criteria['cape_subject'] = 'By Cape Subject'; 
    }
    else
    {
        $filter_criteria['none'] = 'No Filter'; 
        $filter_criteria['division'] = 'By Division'; 
        $filter_criteria['programme'] = 'By Programme';
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
                <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-issue-details']);?>" 
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
                    
                        <div style="margin-left:2.5%">
                            <p class="general_text">
                                Please select a filtering criteria.
                                <?= Html::radioList('offer_filter', null, $filter_criteria, ['class'=> 'form_field', 'onclick'=> 'filterOffer();']);?>
                                
                            </p>

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
                        </div>
                    <?php ActiveForm::end(); ?>
                    
                    <br/><div style="margin-left:2.5%">
                        <?php if (Yii::$app->user->can('publishOffer')): ?>
                            <?= Html::a('Bulk Publish', ['bulk-publish'], ['class' => 'btn btn-primary']) ?>
                        <?php endif; ?>
                        <?= Html::a('Export Valid Offers', ['export-valid-offers'], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Export All Offers', ['export-all-offers'], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
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
