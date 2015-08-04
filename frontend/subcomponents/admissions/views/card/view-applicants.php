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

    <h1><?= Html::encode($this->title) ?></h1>
    <?php ActiveForm::begin(
    [
        'action' => Url::to(['card/update-applicants'])
    ]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'title',
            'firstname',
            'middlename',
            'lastname',
            'programme',
            [
               'attribute' => 'studentno',
                'label' => 'Student No.',
            ],
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
    
        <?= Html::submitButton('Update Card Data', ['class' => 'btn btn-success', 'name' => 'register']); ?>
    <?php ActiveForm::end(); ?>

</div>
