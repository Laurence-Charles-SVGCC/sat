<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    use frontend\models\ApplicationPeriod;

    /* @var $this yii\web\View */
    /* @var $searchModel frontend\models\CsecCentreSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = 'Student Card Panel';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="verif-applicants-index">
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
            
            <br/>
            <fieldset style="width:60%; margin:0 auto">
                <legend>Student Search</legend>
                <p>
                    Welcome. This module facilitates the search for students 
                    who have been received offers to attend the institution
                    and their associated Institutional ID processing.
                </p>
                
                <div>
                    There are three ways in which you can navigate this application.
                    <ol>
                        <li>You may begin your search based on Student ID.</li>
                        <li>You may begin your search based on Student Name.</li>
                        <li>You may begin your search based on Application Period.</li>
                    </ol>
                </div> 
                
                <p class="general_text">
                    Please select a method by which to begin your search.
                    <?= Html::radioList('card_search_method', null, ['studentid' => 'By Student ID' , 'name' => 'By Student Name', 'period' => 'By Application Period'], ['class'=> 'form_field', 'onclick'=> 'cardSearch();']);?>
                </p>
                
                
                <div id="student-id" style="display:none">
                    <?php 
                        ActiveForm::begin(
                            [
                                'action' => Url::to(['card/index', 'criteria' => 'student-id'])
                            ]); 
                    ?>
                        <?= Html::label( 'Student ID',  'label_studentid'); ?>
                        <?= Html::input('text', 'field_studentid'); ?>
                        <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:5%']) ?>
                    <?php ActiveForm::end(); ?>
                </div>

                <div id="student-name" style="display:none">
                    <?php 
                        ActiveForm::begin(
                            [
                                'action' => Url::to(['card/index', 'criteria' => 'student-name'])
                            ]); 
                    ?>
                        <?= Html::label( 'First Name',  'label_firstname'); ?>
                        <?= Html::input('text', 'field_firstname'); ?> <br/><br/>

                        <?= Html::label( 'Last Name',  'label_lastname'); ?>
                        <?= Html::input('text', 'field_lastname'); ?> 

                        <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:5%']) ?>
                    <?php ActiveForm::end(); ?>
                </div>

                <div id="applicaiton-period" style="display:none">
                    <div class='dropdown'>
                        <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                            Select your intended period
                            <span class='caret'></span>
                        </button>
                        <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                            <?php 
                                $periods = ApplicationPeriod::find()
                                        ->innerJoin('application', '`application_period`.`divisionid` = `application`.`divisionid`')
                                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                                        ->where(['application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                                                'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1
                                            ])
                                        ->all();
                                if($periods)
                                {
                                    foreach($periods as $period)
                                    {
                                        $hyperlink = Url::to(['card/index', 'criteria' => 'application-period', 'periodid' => $period->applicationperiodid]);
                                        echo "<li><a href=$hyperlink>$period->name</a></li>"; 
                                    }
                                }
                                else
                                {
                                     echo "<li>No active application periods exist</li>";
                                }
                            ?> 
                        </ul>
                    </div>
                </div>
            </fieldset>
            
            <?php if ($dataProvider == true) : ?>
                <h3><?= "Search Results By: " . $info_string ?></h3>
                <?= $this->render('search_results', 
                                [
                                    'dataProvider' => $dataProvider,
                                    'info_string' => $info_string,
                                ]); 
                ?>
            <?php endif;?>
        </div>
    </div>
</div>
