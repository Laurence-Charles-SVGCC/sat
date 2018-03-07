<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    $this->title = "Find Application Account";
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/applicant-registration/index']);?>" title="Find Application Account">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:60%; margin: 0 auto;">
    <h2 class="text-center"><?= $this->title?></h2>
    
    <?php $form = ActiveForm::begin(['action' => Url::to(['/subcomponents/admissions/applicant-registration/index'])]); ?>
        <div class="box-body">
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="email">Email:</label>
                <?= Html::input('text', 'email_field', null, ["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]); ?>
            </div><br/>
        </div>
    
        <div class="box-footer pull-right">
           <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div><hr>

<?php if ($dataProvider == true) : ?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
        <h3><?= "Search results for " . $info_string ?></h3>
        <?= $this->render('application_account_results', [
                            'dataProvider' => $dataProvider,
                            'info_string' => $info_string,
                            ]
                        ) 
        ?>
   </div>
<?php endif; ?>