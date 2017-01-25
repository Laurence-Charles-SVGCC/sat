<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\Department;
    
    $this->title = 'Active Academic Holds';
    $this->params['breadcrumbs'][] = ['label' => 'Find A Student', 'url' => ['find-a-student']];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/students/student/view-active-academic-holds']);?>" title="Academic Holds">
        <h1>Welcome to the Student Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <div class="box-body">
        <div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#divisions" aria-controls="divisions" role="tab" data-toggle="tab">All Divisions</a></li>
                    <?php 
                        foreach($divisions as $division)
                        {   
                            $division_abbreviation = $division->abbreviation;
                            echo "<li role='presentation'><a href='#$division_abbreviation' aria-controls='$division_abbreviation' role='tab' data-toggle='tab'>$division_abbreviation</a></li>";
                        }
                ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="divisions">                          
                    <br/>
                    <?php if ($all_provider):?>
                        <?= GridView::widget([
                            'dataProvider' => $all_provider,
                            'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'format' => 'html',
                                    'label' => 'Student ID',
                                    'value' => function($row)
                                        {
                                           return Html::a($row['studentid'], 
                                                              Url::to(['profile/student-profile', 
                                                                        'personid' => $row['personid'], 
                                                                        'studentregistrationid' => $row['studentregistrationid']
                                                                    ]));
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
                                    'attribute' => 'programme',
                                    'format' => 'text',
                                    'label' => 'Progamme'
                                ],
                                [
                                    'attribute' => 'holdtype',
                                    'format' => 'text',
                                    'label' => 'Hold Type'
                                ],
                                [
                                    'attribute' => 'wasnotified',
                                    'format' => 'text',
                                    'label' => 'Notification Status',
                                    'value' => function($row)
                                            {
                                                if($row['wasnotified'] == 0)
                                                {
                                                    return "Notification Pending";
                                                }
                                                else
                                                {
                                                    return "Notification Sent";
                                                }
                                            },
                                ],
                                [
                                    'format' => 'html',
                                    'label' => 'Update Status',
                                    'value' => function($row)
                                        {
                                           if($row['wasnotified'] == 0)
                                           {
                                                return Html::a("Set to Notified",
                                                                   Url::to(['student/view-active-academic-holds', 
                                                                             'notified' => 1, 
                                                                            'studentholdid' => $row['studentholdid'],
                                                                            ])
                                                            );
                                           }
                                           else
                                           {
                                                return Html::a("Set to Pending",
                                                                     Url::to(['student/view-active-academic-holds', 
                                                                              'notified' => 0, 
                                                                              'studentholdid' => $row['studentholdid'],
                                                                          ])
                                                              );
                                           }
                                        }
                                ],
                            ],
                        ]);?>
                    <?php else:?>
                        <h4>There are no active academic holds for any students enrolled in the St. Vincent and the Grenadine Community College</h4>
                    <?php endif;?>
                </div><!--End of all division panel-->


                <div role="tabpanel" class="tab-pane fade" id="DASGS">                          
                    <br/>
                    <?php if ($dasgs_provider):?>
                        <?= GridView::widget([
                            'dataProvider' => $dasgs_provider,
                            //'filterModel' => $searchModel,
                            'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'format' => 'html',
                                    'label' => 'Student ID',
                                    'value' => function($row)
                                        {
                                           return Html::a($row['studentid'], 
                                                              Url::to(['profile/student-profile', 
                                                                        'personid' => $row['personid'], 
                                                                        'studentregistrationid' => $row['studentregistrationid']
                                                                    ]));
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
                                    'attribute' => 'programme',
                                    'format' => 'text',
                                    'label' => 'Progamme'
                                ],
                                [
                                    'attribute' => 'holdtype',
                                    'format' => 'text',
                                    'label' => 'Hold Type'
                                ],
                                [
                                    'attribute' => 'wasnotified',
                                    'format' => 'text',
                                    'label' => 'Notification Status',
                                    'value' => function($row)
                                            {
                                                if($row['wasnotified'] == 0)
                                                {
                                                    return "Notification Pending";
                                                }
                                                else
                                                {
                                                    return "Notification Sent";
                                                }
                                            },
                                ],
                                [
                                    'format' => 'html',
                                    'label' => 'Update Status',
                                    'value' => function($row)
                                        {
                                           if($row['wasnotified'] == 0)
                                           {
                                                return Html::a("Set to Notified",
                                                                   Url::to(['student/view-active-academic-holds', 
                                                                             'notified' => 1, 
                                                                            'studentholdid' => $row['studentholdid'],
                                                                            ])
                                                            );
                                           }
                                           else
                                           {
                                                return Html::a("Set to Pending",
                                                                     Url::to(['student/view-active-academic-holds', 
                                                                              'notified' => 0, 
                                                                              'studentholdid' => $row['studentholdid'],
                                                                          ])
                                                              );
                                           }
                                        }
                                ],
                            ],
                        ]);?>
                    <?php else:?>
                        <h4>There are no active academic holds for any students enrolled in the Division of Arts and General Sciences</h4>
                    <?php endif;?>
                </div><!--End of DASGS panel-->



                <div role="tabpanel" class="tab-pane fade" id="DTVE">                          
                    <br/>
                        <?= GridView::widget([
                            'dataProvider' => $dtve_provider,
                            //'filterModel' => $searchModel,
                            'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'format' => 'html',
                                    'label' => 'Student ID',
                                    'value' => function($row)
                                        {
                                           return Html::a($row['studentid'], 
                                                              Url::to(['profile/student-profile', 
                                                                        'personid' => $row['personid'], 
                                                                        'studentregistrationid' => $row['studentregistrationid']
                                                                    ]));
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
                                    'attribute' => 'programme',
                                    'format' => 'text',
                                    'label' => 'Progamme'
                                ],
                                [
                                    'attribute' => 'holdtype',
                                    'format' => 'text',
                                    'label' => 'Hold Type'
                                ],
                                [
                                    'attribute' => 'wasnotified',
                                    'format' => 'text',
                                    'label' => 'Notification Status',
                                    'value' => function($row)
                                            {
                                                if($row['wasnotified'] == 0)
                                                {
                                                    return "Notification Pending";
                                                }
                                                else
                                                {
                                                    return "Notification Sent";
                                                }
                                            },
                                ],
                                [
                                    'format' => 'html',
                                    'label' => 'Update Status',
                                    'value' => function($row)
                                        {
                                           if($row['wasnotified'] == 0)
                                           {
                                                return Html::a("Set to Notified",
                                                                   Url::to(['student/view-active-academic-holds', 
                                                                             'notified' => 1, 
                                                                            'studentholdid' => $row['studentholdid'],
                                                                            ])
                                                            );
                                           }
                                           else
                                           {
                                                return Html::a("Set to Pending",
                                                                     Url::to(['student/view-active-academic-holds', 
                                                                              'notified' => 0, 
                                                                              'studentholdid' => $row['studentholdid'],
                                                                          ])
                                                              );
                                           }
                                        }
                                ],
                            ],
                        ]);?>   
                </div><!--End of DTVE panel-->



                <div role="tabpanel" class="tab-pane fade" id="DTE">                          
                    <br/>
                        <?= GridView::widget([
                            'dataProvider' => $dte_provider,
                            //'filterModel' => $searchModel,
                            'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'format' => 'html',
                                    'label' => 'Student ID',
                                    'value' => function($row)
                                        {
                                           return Html::a($row['studentid'], 
                                                              Url::to(['profile/student-profile', 
                                                                        'personid' => $row['personid'], 
                                                                        'studentregistrationid' => $row['studentregistrationid']
                                                                    ]));
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
                                    'attribute' => 'programme',
                                    'format' => 'text',
                                    'label' => 'Progamme'
                                ],
                                [
                                    'attribute' => 'holdtype',
                                    'format' => 'text',
                                    'label' => 'Hold Type'
                                ],
                                [
                                    'attribute' => 'wasnotified',
                                    'format' => 'text',
                                    'label' => 'Notification Status',
                                    'value' => function($row)
                                            {
                                                if($row['wasnotified'] == 0)
                                                {
                                                    return "Notification Pending";
                                                }
                                                else
                                                {
                                                    return "Notification Sent";
                                                }
                                            },
                                ],
                                [
                                    'format' => 'html',
                                    'label' => 'Update Status',
                                    'value' => function($row)
                                        {
                                           if($row['wasnotified'] == 0)
                                           {
                                                return Html::a("Set to Notified",
                                                                   Url::to(['student/view-active-academic-holds', 
                                                                             'notified' => 1, 
                                                                            'studentholdid' => $row['studentholdid'],
                                                                            ])
                                                            );
                                           }
                                           else
                                           {
                                                return Html::a("Set to Pending",
                                                                     Url::to(['student/view-active-academic-holds', 
                                                                              'notified' => 0, 
                                                                              'studentholdid' => $row['studentholdid'],
                                                                          ])
                                                              );
                                           }
                                        }
                                ],
                            ],
                        ]);?>      
                </div><!--End of DTE panel-->



                <div role="tabpanel" class="tab-pane fade" id="DNE">                          
                    <br/>
                        <?= GridView::widget([
                            'dataProvider' => $dne_provider,
                            //'filterModel' => $searchModel,
                            'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'format' => 'html',
                                    'label' => 'Student ID',
                                    'value' => function($row)
                                        {
                                           return Html::a($row['studentid'], 
                                                              Url::to(['profile/student-profile', 
                                                                        'personid' => $row['personid'], 
                                                                        'studentregistrationid' => $row['studentregistrationid']
                                                                    ]));
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
                                    'attribute' => 'programme',
                                    'format' => 'text',
                                    'label' => 'Progamme'
                                ],
                                [
                                    'attribute' => 'holdtype',
                                    'format' => 'text',
                                    'label' => 'Hold Type'
                                ],
                                [
                                    'attribute' => 'wasnotified',
                                    'format' => 'text',
                                    'label' => 'Notification Status',
                                    'value' => function($row)
                                            {
                                                if($row['wasnotified'] == 0)
                                                {
                                                    return "Notification Pending";
                                                }
                                                else
                                                {
                                                    return "Notification Sent";
                                                }
                                            },
                                ],
                                [
                                    'format' => 'html',
                                    'label' => 'Update Status',
                                    'value' => function($row)
                                        {
                                           if($row['wasnotified'] == 0)
                                           {
                                                return Html::a("Set to Notified",
                                                                   Url::to(['student/view-active-academic-holds', 
                                                                             'notified' => 1, 
                                                                            'studentholdid' => $row['studentholdid'],
                                                                            ])
                                                            );
                                           }
                                           else
                                           {
                                                return Html::a("Set to Pending",
                                                                     Url::to(['student/view-active-academic-holds', 
                                                                              'notified' => 0, 
                                                                              'studentholdid' => $row['studentholdid'],
                                                                          ])
                                                              );
                                           }
                                        }
                                ],
                            ],
                        ]);?> 
                </div><!--End of DASGS panel-->

            </div><!--End of all panels-->
        </div>
    </div><br/>
</div>























