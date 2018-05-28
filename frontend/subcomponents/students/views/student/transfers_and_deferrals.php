<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    $this->title = 'Transfers and Deferrals';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find A Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <div class="box-body">
        <div>
            Please select which report you wish to view.
            <?= Html::radioList('listing_category', null, ['transfers' => 'Transfers',  'pre-registration-deferrals' => 'Pre-Registration Deferrals',  'post-registration-deferrals' => 'Post-Registration Deferrals'], ['class'=> 'form_field', 'onclick'=> 'checkTransferOrDeferral();', 'style' => 'margin-left:2.5%']);?>
        </div>
        
        <div id="transfers" style="display:none">
            <hr>
            <h3>Transfers</h3>
            <?php if ($transfers_provider) : ?>
                <p style="margin-left: 2.5%">
                    Click the following button to download a copy of the transfer listing.
                    <?= Html::a('Download Transfers', ['export-transfers'], ['class' => 'btn btn-primary', 'style' => 'margin-left: 2.5%']) ?>
                </p>

                <?= $this->render('transfer_results', [
                    'dataProvider' => $transfers_provider,
                ]) ?>
            <?php else:?>
                <p style="margin-left:2.5%"><strong>There are no recorded student transfers.</strong></p>
            <?php endif; ?>
        </div>

        <div id="pre-registration-deferrals" style="display:none">
            <hr>
            <h3>Pre-Registration Deferrals</h3>
            <?php if ($pre_registration_deferrals_provider) : ?>
                <p style="margin-left: 2.5%">
                    Click the following button to download a copy of the deferral listing.
                    <?= Html::a('Download Deferrals', ['export-pre-registration-deferrals'], ['class' => 'btn btn-primary', 'style' => 'margin-left: 2.5%']) ?>
                </p>

                <?= $this->render('pre_registration_deferral_results', [
                    'dataProvider' => $pre_registration_deferrals_provider,
                ]) ?>
            <?php else:?>
                <p style="margin-left:2.5%"><strong>There are no recorded student pre-registration deferrals.</strong></p>
            <?php endif; ?>
        </div>

        <div id="post-registration-deferrals" style="display:none">
            <hr>
            <h3>Post-Registration Deferrals</h3>
            <?php if ($post_registration_deferrals_provider) : ?>
                <p style="margin-left: 2.5%">
                    Click the following button to download a copy of the deferral listing.
                    <?= Html::a('Download Deferrals', ['export-post-registration-deferrals'], ['class' => 'btn btn-primary', 'style' => 'margin-left: 2.5%']) ?>
                </p>

                <?= $this->render('post_registration_deferral_results', [
                    'dataProvider' => $post_registration_deferrals_provider,
                ]) ?>
            <?php else:?>
                <p style="margin-left:2.5%"><strong>There are no recorded student post-registration deferrals.</strong></p>
            <?php endif; ?>
        </div>
    </div><br/>
</div>