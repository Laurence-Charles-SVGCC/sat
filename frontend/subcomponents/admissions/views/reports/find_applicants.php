<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\ApplicationPeriod;

    $this->title = 'Reports Dashboard';
    //$this->params['breadcrumbs'][] = $this->title;
    
    $report_categories = [
        '0' => 'Programme',
        '1' => 'Applicant Summary',
        '2' => 'Exception Reports',
    ];
    
    $period_scope = [
        '0' => 'Application Period Specific',
        '1' => 'Application Period Aggregate',
    ];
    
    $dasgs_programme_search_criteria = [
        '0' => 'All Programmes',
        '1' => 'Associate Programmes',
        '2' => 'CAPE Subjects',
    ];
            
     $none_dasgs_programme_search_criteria = [
        '0' => 'All Programmes'
    ];


?>

<div class="report-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
           
            <?php
                $form = ActiveForm::begin(
                    [
                        'action' => Url::to(['reports/generate-applicant-listing']),
                    ]); 
            ?>
                <?= Html::hiddenInput('preparelisting_baseUrl', Url::home(true)); ?>
            
                <div style="margin-left:2.5%"><br/>
                    <div id="application-period-scope">
                        1. Please select a application period scope.
                        <?= Html::radioList('period-scope', null, $period_scope, ['class'=> 'form_field', 'onclick'=> 'togglePeriodScope();']);?>
                    </div></br>

                    <div id="application-period-specific" style="display:none">
                        <div id="application-period">
                            <?= Html::label('2. Application Period: ', 'period_label'); ?>
                            <?= Html::dropDownList('period',  "Select...", $periods, ['id' => 'period_field', 'onchange' => 'togglePeriod();']) ; ?>
                        </div></br>

                        <div id="report-body" style="display:none">
                            <p class="general_text">
                                3. Please select an additional filtering criteria.
                                <?= Html::radioList('report-category', null, $report_categories, ['class'=> 'form_field', 'onclick'=> 'toggleCategories();']);?>
                            </p>

                            <div id="programme" style="display:none">
                                <div id="dasgs-programme-options" style="display:none">
                                    4. Please select programme search criteria.
                                    <?= Html::radioList('dasgs-programme-search-criteria', null, $dasgs_programme_search_criteria, ['class'=> 'form_field', 'onclick'=> 'toggleDasgsProgrameSearchCriteria();PrepareListing(event);']);?>
                                </div><br/>
                                
                                <div id="dasgs-all-listing" style="display:none">
                                    <?= Html::label('5. All Programmes Listing: ', 'all_programme_label'); ?>
                                    <?= Html::dropDownList('prog1',  "Select...", ['' => 'Select...'], ['id' => 'dasgs_all_programme_field', 'onchange' => 'toggleSearchButton();']) ; ?>
                                </div>
                                
                                <div id="assoc-listing" style="display:none">
                                    <?= Html::label('5. Associate Programme Listing: ', 'assoc_programme_label'); ?>
                                    <?= Html::dropDownList('prog2',  "Select...", ['' => 'Select...'], ['id' => 'assoc_programme_field', 'onchange' => 'toggleSearchButton();']) ; ?>
                                </div>
                                
                                <div id="cape-listing" style="display:none">
                                    <?= Html::label('5. Cape Subject Listing: ', 'cape_subject_label'); ?>
                                    <?= Html::dropDownList('prog3',  "Select...", ['' => 'Select...'], ['id' => 'cape_subject_field', 'onchange' => 'toggleSearchButton();']) ; ?>
                                </div>
                                
                                
                                
                                <div id="none-dasgs-programme-options" style="display:none">
                                    4. Please select programme search criteria.
                                    <?= Html::radioList('none-dasgs-programme-search-criteria', null, $none_dasgs_programme_search_criteria, ['class'=> 'form_field', 'onclick'=> 'toggleNoneDasgsProgrameSearchCriteria();PrepareListing(event);']);?>
                                </div>
                                
                                
                                <div id="none-dasgs-all-listing" style="display:none">
                                    <br/><?= Html::label('5. All Programmes Listing: ', 'none_dasgs_all_programme_label'); ?>
                                    <?= Html::dropDownList('prog4',  "Select...", ['' => 'Select...'], ['id' => 'none_dasgs_all_programme_field', 'onchange' => 'toggleSearchButton();']) ; ?>
                                </div>
                            </div>

                            <div id="applicant-summary" style="display:none">
                                <?= Html::submitButton('Generate Full Applicant Listing', ['class' => 'btn btn-md btn-success', 'style' => 'margin-left:10%']) ?>
                            </div>

                            <div id="exception-reports" style="display:none; margin-left:27.5%">
                                <?php
                                    echo "<div id='exception-dropdown' class='dropdown'>";
                                        echo "<button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                            echo "Select Exception Report...";
                                            echo "<span class='caret'></span>";
                                        echo "</button>";
                                        echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>";
                                            $borderline_pass_maths_pass_english = Url::toRoute(['/subcomponents/admissions/reports/borderline', 'passmaths' => 1, 'passenglish' => 1]);
                                            $borderline_pass_maths_fail_english = Url::toRoute(['/subcomponents/admissions/reports/borderline', 'passmaths' => 1, 'passenglish' => 0]);
                                            $borderline_fail_maths_pass_english = Url::toRoute(['/subcomponents/admissions/reports/borderline', 'passmaths' => 0, 'passenglish' => 1]);
                                            
                                            $failed_verification = Url::toRoute(['/subcomponents/admissions/reports/failed-verification']);
//                                            $unregistered_applicants = Url::toRoute(['/subcomponents/admissions/reports/get-unregistered-applicants']);
                                            
                                            echo "<li><a target='_blank' href='$borderline_pass_maths_pass_english'>Borderline - Maths(P) English(P)</a></li>";
                                            echo "<li><a target='_blank' href='$borderline_pass_maths_fail_english'>Borderline - Maths(P) English(F)</a></li>";
                                            echo "<li><a target='_blank' href='$borderline_fail_maths_pass_english'>Borderline - Maths(F) English(P)</a></li>";
                                            if (Yii::$app->user->can('System Administrator'))
                                            {
                                                echo "<li><a target='_blank' href='$failed_verification'>Failed Verification</a></li>";
                                            }
//                                            echo "<li><a target='_blank' href='$unregistered_applicants'>Unregistered Applicants</a></li>";
                                            
                                        echo "</ul>";
                                    echo "</div>";
                                ?>
                            </div>
                        </div>
                    </div> 


                    <div id="application-period-aggregate" style="display:none">
                        <h3>No aggregate reports available at this time.</h3><br/>
                        <img style="display: block; margin: auto;" src ="<?=Url::to('css/dist/img/under_construction.jpg');?>" alt="Under Construction">
                    </div>

            
                    <div id="submit-button"  style="display:none">
                        <br/><?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: left']) ?>
                    </div>
                    
                </div>
            <?php ActiveForm::end(); ?>
            
        </div>
    </div>
</div>
