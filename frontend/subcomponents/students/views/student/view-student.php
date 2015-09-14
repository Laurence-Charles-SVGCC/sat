<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Student View';
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-period-form">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <h2>Details for: 
        <?= $student->title . " " . $student->firstname . " " . $student->middlename . " " . $student->lastname 
        . " (" . $username . ")" ?>
    </h2>
    <h3>Student Registration(s)</h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'order',
                'format' => 'text',
                'label' => 'Application Choice Order'
            ],
            [
                'attribute' => 'applicationid',
                'format' => 'text',
                'label' => 'Application ID',
            ],
            [
                'attribute' => 'programme_name',
                'format' => 'text',
                'label' => 'Programme',
            ],
            'active:boolean',
            [
                'format' => 'html',
                'label' => 'Registration Actions',
                'value' => function($row)
                    {
                        
                       // if (Yii::$app->user->can('editRegistration'))
                        //{
                           return Html::a('Edit', 
                                        Url::to(['student/edit-registration']),
                                                ['class' => 'btn btn-primary']);
                        //}
                        return 'N/A';   
                    }
            ],
        ],
    ]); ?>
    <?php ActiveForm::begin(
    [
        'action' => Url::to(['student/student-actions'])
    ]); ?>
        <?= Html::hiddenInput('username', $username); ?>
        <?php //if (Yii::$app->user->can('viewStudentPersonal')): ?>
            <?= Html::submitButton('View Personal Details', ['class' => 'btn btn-success', 'name' => 'view_personal']); ?>
        <?php //endif; ?>
        <?php //if (Yii::$app->user->can('editStudentPersonal')): ?>
            <?= Html::submitButton('Edit Personal Details', ['class' => 'btn btn-success', 'name' => 'edit_personal']); ?>
        <?php //endif; ?>
         <?php //if (Yii::$app->user->can('addRegistration')): ?>
            <?= Html::submitButton('Add Registration', ['class' => 'btn btn-success', 'name' => 'add_registration']); ?>
        <?php //endif; ?>
    <?php ActiveForm::end(); ?>
    
</div>