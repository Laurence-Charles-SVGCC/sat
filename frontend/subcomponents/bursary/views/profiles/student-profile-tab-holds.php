<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div role="tabpanel" class="tab-pane" id="student-holds">
  <div class="panel panel-default">
    <div class="panel-heading">
      <span>Holds</span>
      <a class="btn btn-success btn-md pull-right" href=<?= Url::toRoute(['holds/add-financial-hold', 'username' => $username]); ?> role="button">
        Add
      </a><br /><br />
    </div>

    <div class="panel-body">
      <?=
        GridView::widget(
          [
            'dataProvider' => $financialHoldsDataProvider,
            'columns' => [
              [
                'attribute' => 'holdName',
                'format' => 'html',
                'label' => 'Hold Name',
                'value' => function ($row) {
                  return Html::a(
                    $row['holdName'],
                    Url::to(['holds/view-hold', 'id' => $row['id']]),
                    ['id' => 'view-hold-button']
                  );
                }
              ],
              [
                'attribute' => 'registrationDetails',
                'format' => 'text',
                'label' => 'Programme'
              ],
              [
                'attribute' => 'holdDetails',
                'format' => 'text',
                'label' => 'Notes'
              ],
              [
                'attribute' => 'appliedDetails',
                'format' => 'text',
                'label' => 'Applied'
              ],
              [
                'attribute' => 'holdStatus',
                'format' => 'text',
                'label' => 'Hold Status'
              ],
              [
                'attribute' => 'notificationStatus',
                'format' => 'text',
                'label' => 'Notification Status'
              ],
              [
                'attribute' => 'resolvedDetails',
                'format' => 'text',
                'label' => 'Resolved'
              ],
            ],
          ]
        );
      ?>
    </div>
  </div>
</div>