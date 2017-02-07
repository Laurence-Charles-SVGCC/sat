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
            <p>Please select a method by which to begin your search.</p>

            <div id="email">
                <?= Html::label( 'Email',  'email_label'); ?>
                <?= Html::input('text', 'email_field'); ?>
                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right']) ?>
            </div>
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