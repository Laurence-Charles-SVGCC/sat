<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;

    if($search_status == "pending")
        $this->title = 'Current Applicant Search';
    elseif($search_status == "pending-unlimited")
        $this->title = 'Applicant Search';
    elseif($search_status == "successful")
        $this->title = 'Successful Applicant Search';
    
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'search_status' => $search_status]);?>" title="Find  Past Applicant">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:60%; margin: 0 auto;">
    <h2 class="text-center"><?= $this->title?></h2>
    
    <?php if ($search_status == "pending"):?>
        <div class="box-header with-border">
            <span class="box-title">
                Welcome. This module facilitates the search for all applicants associated 
                with the current open application periods.  
            </span>
        </div>
    <?php elseif ($search_status == "pending-unlimited"):?>
       <div class="box-header with-border">
            <span class="box-title">
                Welcome. This module facilitates the search for applicants independant
                of application period.
            </span>
       </div>
    <?php elseif ($search_status == "successful"):?>
        <div class="box-header with-border">
            <span class="box-title">
                Welcome. This module facilitates the search for applicants who have been 
                given an offer. 
            </span>
        </div>
    <?php endif;?>
    
    <?php $form = ActiveForm::begin(['action' => Url::to(['admissions/find-current-applicant', 'status' => $search_status])]); ?>
        <div class="box-body">
             <div>
                There are three ways in which you can navigate this application.
                <ol>
                    <li>You may begin your search based on your Applicant ID.</li>
                    <li>You may begin your search based on your Applicant Name.</li>
                    <li>You may begin your search based on your Email Address.</li>
                </ol>
            </div>
            
            <p>
                Please select a method by which to begin your search.
                <?= Html::radioList('search_how', null, ['applicantid' => 'By Applicant ID' , 'name' => 'By Applicant Name', 'email' => 'By Email'], ['class'=> 'form_field', 'onclick'=> 'checkSearchHow();']);?>
            </p>

            <div id="applicantid" style="display:none">
                <?= Html::label( 'Applicant ID',  'studentid_label'); ?>
                <?= Html::input('text', 'applicantid_field'); ?>
                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right']) ?>
            </div>

            <div id="name" style="display:none">
                <?= Html::label( 'First Name',  'firstname_label'); ?>
                <?= Html::input('text', 'FirstName_field'); ?> <br/><br/>

                <?= Html::label( 'Last Name',  'lastname_label'); ?>
                <?= Html::input('text', 'LastName_field'); ?> 

                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right']) ?>
            </div>

            <div id="email" style="display:none">
                <?= Html::label( 'Email',  'email_label'); ?>
                <?= Html::input('text', 'email_field'); ?>
                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div><hr>

<?php if (($search_status == "pending" || $search_status == "pending-unlimited") && $dataProvider == true) : ?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
        <h3><?= "Search results for: " . $info_string ?></h3>
        <?= $this->render('pending_applicants_results', [
                            'dataProvider' => $dataProvider,
                            'info_string' => $info_string,
                            'search_status' => $search_status,
                            ]
                        ) 
        ?>
   </div>

<?php elseif ($search_status == "successful"  && $dataProvider == true) : ?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
        <h3><?= "Search results for: " . $info_string ?></h3>
        <?= $this->render('successful_applicants_results', [
                            'dataProvider' => $dataProvider,
                            'info_string' => $info_string,
                            'search_status' => $search_status,
                            ]
                        ) 
        ?>
   </div>
<?php endif; ?>