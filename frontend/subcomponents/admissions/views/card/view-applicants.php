<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $divisionabbr . ' Offers for ' . $applicationperiodname;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body"> 
            <h1><?= Html::encode($this->title) ?></h1>
            <?php ActiveForm::begin(
            [
                'action' => Url::to(['card/update-applicants'])
            ]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'options' => ['style' => 'width:98%; margin: 0 auto;'],
                    'columns' => [
                        [
                           'attribute' => 'studentno',
                            'label' => 'Student No.',
                        ],
                        'title',
                        'firstname',
                        'lastname',
                        'programme',
                        'registered:boolean',
                        'published:boolean',
                        [
                            'label' => 'Picture Taken',
                            'format' => 'raw',
                            'value' => function($row)
                             {
                                return  $row['studentreg'] ? Html::Checkbox('receivedpicture[' . $row['studentreg']->studentregistrationid . ']',
                                        $row['studentreg']->receivedpicture ,['label' => NULL]) : 'N/A';
                             }
                        ],
                        [
                            'label' => 'Card Ready',
                            'format' => 'raw',
                            'value' => function($row)
                             {
                                return  $row['studentreg'] ? Html::Checkbox('cardready[' . $row['studentreg']->studentregistrationid . ']',
                                        $row['studentreg']->cardready , ['label' => NULL]) : 'N/A';
                             }
                        ],
                        [
                            'label' => 'Card Collected',
                            'format' => 'raw',
                            'value' => function($row)
                             {
                                return  $row['studentreg'] ? Html::Checkbox('cardcollected[' . $row['studentreg']->studentregistrationid . ']',
                                        $row['studentreg']->cardcollected ,['label' => NULL]) : 'N/A';
                             }
                        ],
                        [

                            'format' => 'raw',
                            'value' => function($row)
                             {
                                return  $row['studentreg'] ? Html::HiddenInput('studentreg[]',  $row['studentreg']->studentregistrationid) : Null;
                             }
                        ],

                    ],
                ]); ?>
                    <?php if (Yii::$app->user->can('updateStudentCard')): ?>
                        <?= Html::submitButton('Update Card Data', ['class' => 'btn btn-success', 'name' => 'register']); ?>
                    <?php endif; ?>    
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
