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

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:60%; margin: 0 auto;">
    <h2 class="text-center"><?= $this->title?></h2>
    
    <div class="box-header with-border">
        <span class="box-title">
            Welcome. This module facilitates the search for applicant accounts.  You will be able to explore get an account  overview of 
            an applicant that would have began the application process.
        </span>
    </div>
    
    <div class="box-body">
        <div>
            There are three ways in which you can navigate this application.
            <ol>
                <li>You may begin your search based on your Division of choice.</li>

                <li>You may begin your search based on your Student ID.</li>

                <li>You may begin your search based on your Student Name.</li>
            </ol>
        </div> 

        <?php $form = ActiveForm::begin();?>

            <p class="general_text">
                Please select a method by which to begin your search.
                <?= Html::radioList('search_type', null, ['division' => 'By Division' , 'studentid' => 'By StudentID', 'studentname' => 'By Student Name'], ['class'=> 'form_field', 'onclick'=> 'checkSearchType();']);?>
            </p>

            <div id="by_div" style="display:none">
                <?php /*if ((Yii::$app->user->can('Deputy Dean') || Yii::$app->user->can('Dean')  || Yii::$app->user->can('Divisional Staff'))  && !Yii::$app->user->can('System Administrator')):*/?>
                <?php if (EmployeeDepartment::getUserDivision() != 1):?>
                    <?= Html::dropDownList('division', null, Division::getDivisionsAssignedTo(Yii::$app->user->identity->personid));?>
                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?> 
                <?php else:?>
                    <?= Html::dropDownList('division', null, Division::getAllDivisions());?>
                    <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>                               
                <?php endif; ?>
            </div>

            <div id="by_id" style="display:none">
                <?= Html::label( 'Student ID',  'id_label'); ?>
                <?= Html::input('text', 'id_field'); ?>
                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>
            </div>

            <div id="by_name" style="display:none">
                <?= Html::label( 'First Name',  'fname_label'); ?>
                <?= Html::input('text', 'fname_field'); ?> <br/><br/>

                <?= Html::label( 'Last Name',  'lname_label'); ?>
                <?= Html::input('text', 'lname_field'); ?> 

                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:25%;']) ?>
            </div>            
        <?php ActiveForm::end(); ?>
    </div>
</div><br/><br/>

<?php if (empty($dataProvider) == false) : ?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
        <div class="box-header with-border">
            <span class="box-title"><?= "Search results for: " . $info_string ?></span>
        </div>
        
        <div class="box-body">
            <div>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'format' => 'html',
                            'label' => 'Student ID',
                            'value' => function($row)
                                {
                                    return Html::a($row['email'], 
                                                    Url::to(['review_applications/applicant-profile', 'email' => $row['email']]));
                                }
                        ],
                        [
                            'attribute' => 'firstname',
                            'format' => 'text',
                            'label' => 'First Name'
                        ],
                        [
                            'attribute' => 'lastname',
                            'format' => 'text',
                            'label' => 'Last Name'
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'text',
                            'label' => 'Status'
                        ],
                        [
                            'attribute' => 'incomplete_component',
                            'format' => 'text',
                            'label' => 'Pending Application Components'
                        ],
                    ],
                ]); ?>     
            </div>
        </div>
    </div>
<?php endif; ?>