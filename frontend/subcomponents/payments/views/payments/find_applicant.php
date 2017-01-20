<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;

    if($status == "applicant")
        $this->title = 'Current Applicant Search';
    elseif($status == "student")
        $this->title = 'Registered Student Search';
    
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/payments/payments/find-applicant-or-student', 'status' => $status, 'new_search' => 1]);?>" title="Find Applicant">
        <h1><?= $this->title?></h1>
    </a>
</div>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:60%; margin: 0 auto;">
    <?php if ($status == "applicant"):?>
        <div class="box-header with-border">
             <span class="box-title">This module facilitates the search for all applicant payments.</span>
         </div> 
    <?php elseif ($status == "successful"):?>
        <div class="box-header with-border">
            <span class="box-title">This module facilitates the search for applicants who have been given an offer.</span>
       </div>
    <?php endif;?>
         
    <?php $form = ActiveForm::begin(['action' => Url::to(['payments/find-applicant-or-student', 'status' => $status])]); ?>
        <div class="box-body">
            <div>
                There are three ways in which you can navigate this application.
                <ol>
                    <?php if($status == "applicant"): ?>
                        <li>You may begin your search based on your ApplicantID/StudentID.</li>
                    <?php elseif($status == "student"): ?>
                        <li>You may begin your search based on your StudentID.</li>
                   <?php endif;?>     
                        
                    <?php if($status == "applicant"): ?>
                        <li>You may begin your search based on your Applicant Name.</li>
                    <?php elseif($status == "student"): ?>
                        <li>You may begin your search based on your Student Name.</li>
                    <?php endif;?>   
                        
                    <li>You may begin your search based on your Email Address.</li>
                </ol>
            </div> 

            <p class="general_text">
                Please select a method by which to begin your search.
                <?= Html::radioList('search_how', null, ['applicantid' => 'By ID' , 'name' => 'By Name', 'email' => 'By Email'], ['class'=> 'form_field', 'onclick'=> 'checkSearchHow();']);?>
            </p>

            <div id="applicantid" style="display:none">
                <?= Html::label( 'ID',  'studentid_label'); ?>
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

<?php if ($dataProvider != NULL):?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
            <h3><?= "Search results for: " . $info_string ?></h3>
            <?= $this->render('find_applicant_student_results', [
                                'dataProvider' => $dataProvider,
                                'info_string' => $info_string,
                                'status' => $status,
            ]) ?>
        </div>
<?php endif;?>

