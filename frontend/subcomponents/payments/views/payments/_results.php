<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CsecCentreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="verify-applicants-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'html',
                'label' => 'Centre Name',
                'value' => function($row)
                    {
                       return Html::a($row['centre_name'], 
                               Url::to(['verify-applicants/centre-details', 'centre_id' => $row['centre_id'], 'centre_name' => $row['centre_name']]));
                    }
            ],
            [
                'attribute' => 'status',
                'format' => 'text',
                'label' => 'Status'
            ],
            [
                'attribute' => 'applicants_verified',
                'format' => 'text',
                'label' => 'Applicants Verified'
            ],
            [
                'attribute' => 'total_received',
                'format' => 'text',
                'label' => 'Total Received Applicants'
            ],
            [
                'format' => 'html',
                'label' => 'Percentage Completed',
                'value' => function($row)
                    {
                            $value = $row['percentage_completed'];
                           return 
                            "<small class='pull-right'>$value%</small>
                             <div class='progress xs'>
                              <div class='progress-bar progress-bar-green' style='width: $value%' role='progressbar' aria-valuenow='$value' aria-valuemin='0' aria-valuemax='100'>
                                <span class='sr-only'>$value%</span>
                              </div>
                             </div>
                            ";
                    }
            ],
        ],
    ]); ?>

</div>