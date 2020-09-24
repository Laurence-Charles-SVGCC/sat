<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

?>

<div role="tabpanel" class="tab-pane fade in active" id="certificates">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                <span>Verified Certificates</span>
                <?php if (Yii::$app->user->can('verifyApplicants')) :?>
                  <span>
                    <?=
                        Html::a(
                            "Verify Certificates",
                            Url::toRoute(
                                [
                                    '/subcomponents/admissions/verify-applicants/view-applicant-qualifications',
                                    'applicantid' => $applicant->personid,
                                    'centrename' => $centreName,
                                    'cseccentreid' => $cseccentreid,
                                    'type' => 'Pending'
                                ]
                            ),
                            ["class" => "btn btn-info pull-right"]
                        );
                    ?>
                  </span>
                <?php endif;?>
            </h4>
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
    </div>
</div>
