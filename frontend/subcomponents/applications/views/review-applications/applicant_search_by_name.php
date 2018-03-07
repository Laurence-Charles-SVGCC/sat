<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\grid\GridView;

    $this->title = 'Applicant Search';
    $this->params['breadcrumbs'][] = $this->title;
?>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <h2 class="text-center"><?= $this->title?></h2>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
             <div>
                This module facilitates the search for applicant accounts.  You will be able to explore get an account  overview of 
                an applicant that would have began the application process.
            </div><br/>

            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="email"> First Name:</label>
                <?= Html::input('text', 'fname_field', null, ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]); ?>
            </div><br/><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="email"> Last Name:</label>
                <?= Html::input('text', 'lname_field', null, ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]); ?>
            </div><br/>
        </div>
    
        <div class="box-footer pull-right">
           <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div><br/>

<?php if ($dataProvider) : ?>
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
                <div class="box-header with-border">
                    <span class="box-title">Pre 2018 Stages</span>
                </div>
                <ol>
                    <li>Account Pending</li>
                    <li>Account Created</li>
                    <li>Programme(s) Selected</li>
                    <li>Submitted</li>
                    <li>Verified</li>
                    <li>Processed</li>
                </ol>
             </div>
        </div>
        
        <div class="col-md-3">
            <div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
                <div class="box-header with-border">
                    <span class="box-title">DASGS/DTVE Stages</span>
                </div>
                <ol>
                    <li>Account Pending</li>
                    <li>Account Created</li>
                    <li>Programme(s) Selected</li>
                    <li>Profile</li>
                    <li>Extracurricular Activities</li>
                    <li>Contacts</li>
                    <li>Addresses</li>
                    <li>Relatives</li>
                    <li>Primary Attendance</li>
                    <li>Secondary Attendance</li>
                    <li>Tertiary Attendance</li>
                    <li>Academic Qualifications</li>
                    <li>Post Sec. Qualifications</li>
                     <li>Submitted</li>
                    <li>Verified</li>
                    <li>Processed</li>
                </ol>
             </div>
        </div>
        
        <div class="col-md-3">
            <div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
                <div class="box-header with-border">
                    <span class="box-title">DTE Stages</span>
                </div>
                <ol>
                    <li>Account Pending</li>
                    <li>Account Created</li>
                    <li>Programme(s) Selected</li>
                    <li>Profile</li>
                    <li>Extracurricular Activities</li>
                    <li>Contacts</li>
                    <li>Addresses</li>
                    <li>Relatives</li>
                    <li>Primary Attendance</li>
                    <li>Secondary Attendance</li>
                    <li>Tertiary Attendance</li>
                    <li>Academic Qualifications</li>
                    <li>Post Sec. Qualifications</li>
                    <li>DTE Information</li>
                    <li>Teaching Experience</li>
                    <li>General Work Experience</li>
                    <li>References</li>
                    <li>Criminal Record</li>
                    <li>Submitted</li>
                    <li>Verified</li>
                    <li>Processed</li>
                </ol>
             </div>
        </div>
        
        <div class="col-md-3">
            <div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
                <div class="box-header with-border">
                    <span class="box-title">DNE Stages</span>
                </div>
                <ol>
                   <li>Account Pending</li>
                    <li>Account Created</li>
                    <li>Programme(s) Selected</li>
                    <li>Profile</li>
                    <li>Extracurricular Activities</li>
                    <li>Contacts</li>
                    <li>Addresses</li>
                    <li>Relatives</li>
                    <li>Primary Attendance</li>
                    <li>Secondary Attendance</li>
                    <li>Tertiary Attendance</li>
                    <li>Academic Qualifications</li>
                    <li>Post Sec. Qualifications</li>
                    <li>DNE Information</li>
                    <li>Nursing Experience</li>
                    <li>General Work Experience</li>
                    <li>References</li>
                    <li>Criminal Record</li>
                    <li>Submitted</li>
                    <li>Verified</li>
                    <li>Processed</li>
                </ol>
             </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
                <div class="box-header with-border">
                    <span class="box-title"><?= "Search results for -  " . $info_string ?></span>
                </div>

                <div class="box-body">
                     <?= $this->render('_search_results', ['dataProvider' => $dataProvider]) ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>