<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\grid\GridView;

    use backend\models\PersonType;

    $this->title = 'Users';
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/user/index']);?>" title="User Management Home">
        <h1>Welcome to the User Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:99%; margin: 0 auto;">
    <h2 class="text-center">
        <?= $this->title?>
        
        <?php if (Yii::$app->user->can('System Administrator')): ?>
           <?= Html::a(' Create New User', ['student/choose-create'], ['class' => 'btn btn-info pull-right', 'style' => 'margin-right: 1%;']) ?>
       <?php endif; ?>
    </h2>
    
    <div class="box-header with-border">
        <span class="box-title">Welcome. This module facilitates the search for all users. </span>
    </div>
    
    <div class="box-body">
        <div>
            There are three ways in which to initiate your search;
            <ol>
                <li>You may begin your search based on  Employee/Student Name.</li>
                <li>You may begin your search based on your UserName.</li>
                 <li>You may begin your search based on your PersonID.</li>
            </ol>
        </div> 
        
        <p>
            Please select a method by which to begin your search.
            <?= Html::radioList('search_type', null, ['name' => 'By Employee/Student Name' , 'username' => 'By UserName', 'personid' => 'By PersonID'], ['onclick'=> 'checkUserSearchCriteria();']);?>
        </p><br/>
        
        <?php $form = ActiveForm::begin();?>
            <div id="by_name" style="display:none">
                <?= Html::label( 'First Name',  'fname_label'); ?>
                <?= Html::input('text', 'fname_field', null, ['style' => 'width:40%']); ?> <br/><br/>

                <?= Html::label( 'Last Name',  'lname_label'); ?>
                <?= Html::input('text', 'lname_field', null, ['style' => 'width:40%']); ?> 

                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:40%;']) ?>
            </div>      
        
            <div id="by_username" style="display:none">
                <?= Html::label( 'UserName',  'username_label'); ?>
                <?= Html::input('text', 'username_field', null, ['style' => 'width:40%']); ?>
                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:40%;']) ?>
            </div>
        
            <div id="by_personid" style="display:none">
                <?= Html::label( 'PersonID',  'personid_label'); ?>
                <?= Html::input('text', 'personid_field', null, ['style' => 'width:40%']); ?>
                <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right; margin-right:40%;']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div><br/><br/>

<?php if ($dataProvider != NULL) : ?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
        <div class="box-header with-border">
            <span class="box-title"><?= "Search results for: " . $info_string ?></span>
        </div>
        
        <div class="box-body">
            <?= $this->render('user_listing', [
                'dataProvider' => $dataProvider,
                'info_string' => $info_string,
            ]) ?>
        </div>
    </div>
<?php endif; ?>





















