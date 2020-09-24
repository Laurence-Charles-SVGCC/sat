<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

?>

<div role="tabpanel" class="tab-pane fade in active" id="academic-qualifications">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4>Certificates</h4>
    </div>

    <div class="panel-body">
      <?=
          GridView::widget(
              [
                  'dataProvider' => $verifiedCsecQualificationsDataProvider,
                  'options' => [],
                  'columns' => [
                      [
                          'attribute' => 'examinationBodyAbbreviation',
                          'format' => 'text',
                          'label' => 'Examination Body'
                      ],
                      [
                          'attribute' => 'year',
                          'format' => 'text',
                          'label' => 'Year'
                      ],
                      [
                          'attribute' => 'proficiency',
                          'format' => 'text',
                          'label' => 'Proficiency',
                      ],
                      [
                          'attribute' => 'subject',
                          'format' => 'text',
                          'label' => 'Subject',
                      ],
                      [
                          'attribute' => 'grade',
                          'format' => 'text',
                          'label' => 'Grade',
                      ],
                  ],
              ]
          );
      ?>
    </div>

    <?php if ($postSecondaryQualification == true):?>
      <div class="panel-heading">
        <h4>Post Secondary Qualification</h4>
      </div>

      <div class="panel-body">
        <table class='table table-hover'>
          <tr>
            <th style='width:50%'><strong>Name of Degree</strong></th>
            <td style='width:50%'><?= $postSecondaryQualification->name; ?></td>
          </tr>

          <tr>
            <th style='width:50%'><strong>Awarding Institution</strong></th>
            <td style='width:50%'>
              <?= $postSecondaryQualification->awardinginstitution;?>
            </td>
          </tr>

          <tr>
            <th style='width:50%'><strong>Year Degree Awarded</strong></th>
            <td style='width:50%'>
              <?= $postSecondaryQualification->yearawarded;?>
            </td>
          </tr>
        </table>
      </div>
    <?php endif;?>

    <?php if ($externalQualification == true) :?>
      <div class="panel-heading">
        <h4>External Qualifications</h4>
      </div>

      <div class="panel-body">
        <table class='table table-hover'>
            <tr>
                <th style="width:35%">Awarding Institution</th>
                <td style="width:65%">
                  <?= $externalQualification->awardinginstitution?>
                </td>
            </tr>
            <tr>
                <th style="width:35%">Name of Degree</th>
                <td style="width:65%"><?= $externalQualification->name?></td>
            </tr>
            <tr>
                <th style="width:35%">Year Awarded</th>
                <td style="width:65%">
                  <?= $externalQualification->yearawarded?>
                </td>
            </tr>
        </table>
      </div>
    <?php endif;?>
  </div>
</div>
