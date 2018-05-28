<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use kartik\export\ExportMenu;
    
    use frontend\models\ApplicationPeriod;
    
    $this->title = 'Student Card Panel';
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:60%; margin: 0 auto;">
    <h2 class="text-center"><?= $this->title?></h2>
    
    <div class="box-header with-border">
        <span class="box-title">
            Welcome. This module facilitates the search for students who have been received offers to 
            attend the institution and their associated Institutional ID processing.
        </span>
    </div>
    
    <div class="box-body">
        <div>
            There are three ways in which you can navigate this application.
            <ol>
                <li>You may begin your search based on Student ID.</li>
                <li>You may begin your search based on Student Name.</li>
                <li>You may begin your search based on Application Period.</li>
            </ol>
        </div> 

        <p>
            Please select a method by which to begin your search.
            <?= Html::radioList('card_search_method', null, ['studentid' => 'By Student ID' , 'name' => 'By Student Name', 'period' => 'By Application Period'], ['class'=> 'form_field', 'onclick'=> 'cardSearch();']);?>
        </p>

        <div id="student-id" style="display:none">
            <?php ActiveForm::begin(['action' => Url::to(['card/index', 'criteria' => 'student-id'])]); ?>
                <?= Html::label( 'Student ID',  'label_studentid'); ?>
                <?= Html::input('text', 'field_studentid'); ?>
                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:5%']) ?>
            <?php ActiveForm::end(); ?>
        </div>

        <div id="student-name" style="display:none">
            <?php ActiveForm::begin(['action' => Url::to(['card/index', 'criteria' => 'student-name'])]);?>
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
    </div><br/><br/><br/>
</div><hr>



<?php if ($dataProvider == true) : ?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
        <div class="box-header with-border">
            <span class="box-title">
               <?= "Search Results By: " . $info_string ?>
            </span>
        </div>

        <div style = "margin-left: 2.5%">
            <p>Click the following link to download a student listing for the displayed application period.</p>
            <?= ExportMenu::widget([
                    'dataProvider' => $dataProvider,

                    'columns' => [
                            [
                                'attribute' => 'username',
                                'format' => 'text',
                                'label' => 'Student No.'
                            ],
                            [
                                'attribute' => 'title',
                                'format' => 'text',
                                'label' => 'Title'
                            ],
                            [
                                'attribute' => 'firstname',
                                'format' => 'text',
                                'label' => 'First Name'
                            ],
                            [
                                'attribute' => 'middlename',
                                'format' => 'text',
                                'label' => 'Middle Name'
                            ],
                            [
                                'attribute' => 'lastname',
                                'format' => 'text',
                                'label' => 'Last Name'
                            ],
                            [
                                'attribute' => 'email',
                                'format' => 'text',
                                'label' => 'Email'
                            ],
                            [
                                'attribute' => 'programme',
                                'format' => 'text',
                                'label' => 'Programme'
                            ],
                            [
                                'attribute' => 'division',
                                'format' => 'text',
                                'label' => 'Division'
                            ],
                            [
                                'attribute' => 'registrationdate',
                                'format' => 'text',
                                'label' => 'Date Registered'
                            ],
                        ],
                    'fontAwesome' => true,
                    'dropdownOptions' => [
                        'label' => 'Select Export Type',
                        'class' => 'btn btn-default'
                    ],
                    'asDropdown' => false,
                    'showColumnSelector' => false,
                    'filename' => $enrolled_filename,
                    'exportConfig' => [
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_EXCEL => false,
                        ExportMenu::FORMAT_EXCEL_X => false,
                        ExportMenu::FORMAT_PDF => false
                    ],
                ]);
            ?><br/>
        </div>

        <?= $this->render('search_results', 
                        [
                            'dataProvider' => $dataProvider,
                            'info_string' => $info_string,
                        ]); 
        ?>
    </div>
<?php endif;?>
