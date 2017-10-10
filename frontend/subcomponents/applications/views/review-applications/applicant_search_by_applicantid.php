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
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="email">Applicant ID:</label>
                <?= Html::input('text', 'applicantid_field', null, ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]); ?>
            </div><br/>
        </div>
    
        <div class="box-footer pull-right">
           <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div><br/><br/>

<?php if ($dataProvider) : ?>
   <div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
        <div class="box-header with-border">
            <span class="box-title">Applicant Progress Stages</span>
        </div>

        <div class="box-body">
            <button type="button" class="btn"> 1. Account Pending</button>--->
            <button type="button" class="btn"> 2. Account Created</button>--->
            <button type="button" class="btn"> 3. Programme(s) Selected</button>--->
            <button type="button" class="btn"> 4. Submitted</button>--->
            <button type="button" class="btn"> 5. Verified</button>--->
            <button type="button" class="btn"> 6. Processed</button>--->
            <button type="button" class="btn"> 7. Removed</button>
        </div><br/>
    </div><br/>


    <div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
        <div class="box-header with-border">
            <span class="box-title"><?= "Search results for -  " . $info_string ?></span>
        </div>
        
        <div class="box-body">
             <?= $this->render('_search_results', ['dataProvider' => $dataProvider]) ?>
        </div>
    </div>
<?php endif; ?>