<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    $this->title = $programmename . ': Student Listing';
    $this->params['breadcrumbs'][] = ['label' => 'Gradebook', 'url' => ['index']];
    if (Yii::$app->user->can('Cordinator') == false)
        $this->params['breadcrumbs'][] = ['label' => 'Programme Listing', 'url' => ['index', 'id' => $division_id]];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/gradebook/gradebook/index']);?>" title="Grade Management Home">
        <h1>Welcome to the SVGCC Grade Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<h2 class="text-center"><?= $this->title?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title">Academic Year Details</span>
    </div>
    
    <div class="box-body">
        <table class="table table-hover">
            <tr>
                <th>Academic Year</th>
                <th>Programme Cordinator(s)</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>

            <tr>
                <td><?=$academicyear->title?></td>

                <?php if ($cordinator_details == false):?>
                    <td>No Cordinator(s) Assigned</td>
                <?php else:?>
                    <td><?=$cordinator_details?></td>
                <?php endif;?>

                <td><?=$academicyear->startdate?></td>
                <td><?=$academicyear->enddate?></td>
            </tr>
        </table>
    </div>
</div><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title">Search Results</span>
    </div>
    
    <div class="box-body">
        <div> 
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#all_students" aria-controls="all_students" role="tab" data-toggle="tab">All Students</a></li>
                <li role="presentation"><a href="#a_f" aria-controls="a_f" role="tab" data-toggle="tab">A-F</a></li>
                <li role="presentation"><a href="#g_l" aria-controls="g_l" role="tab" data-toggle="tab">G-L</a></li>
                <li role="presentation"><a href="#m_r" aria-controls="m_r" role="tab" data-toggle="tab">M-R</a></li>
                <li role="presentation"><a href="#s_z" aria-controls="s_z" role="tab" data-toggle="tab">S-Z</a></li>                          
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="all_students">                          
                    <?= GridView::widget([
                        'dataProvider' => $all_students_provider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'format' => 'html',
                                'label' => 'Student ID',
                                'value' => function($row)
                                    {
                                        return Html::a($row['studentno'], 
                                                        Url::to(['gradebook/transcript', 'personid' => $row['personid'], 'studentregistrationid' => $row['studentregistrationid']]));
                                    }
                            ],
                            [
                                'attribute' => 'firstname',
                                'format' => 'text',
                                'label' => 'First Name'
                            ],
                            [
                                'attribute' => 'middlename',
                                'format' => 'text',
                                'label' => 'Middle Name(s)'
                            ],
                            [
                                'attribute' => 'lastname',
                                'format' => 'text',
                                'label' => 'Last Name'
                            ],
                            [
                                'attribute' => 'gender',
                                'format' => 'text',
                                'label' => 'Gender'
                            ],
                            [
                                'attribute' => 'studentstatus',
                                'format' => 'text',
                                'label' => 'Student Status'
                            ],
                        ],
                    ]); ?>                                                
                </div>

                <div role="tabpanel" class="tab-pane fade" id="a_f">                          
                    <?= GridView::widget([
                        'dataProvider' => $a_f_provider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'format' => 'html',
                                'label' => 'Student ID',
                                'value' => function($row)
                                    {
                                        return Html::a($row['studentno'], 
                                                        Url::to(['gradebook/transcript', 'personid' => $row['personid'], 'studentregistrationid' => $row['studentregistrationid']]));
                                    }
                            ],
                            [
                                'attribute' => 'firstname',
                                'format' => 'text',
                                'label' => 'First Name'
                            ],
                            [
                                'attribute' => 'middlename',
                                'format' => 'text',
                                'label' => 'Middle Name(s)'
                            ],
                            [
                                'attribute' => 'lastname',
                                'format' => 'text',
                                'label' => 'Last Name'
                            ],
                            [
                                'attribute' => 'gender',
                                'format' => 'text',
                                'label' => 'Gender'
                            ],
                            [
                                'attribute' => 'studentstatus',
                                'format' => 'text',
                                'label' => 'Student Status'
                            ],
                        ],
                    ]); ?>           
                </div>

                <div role="tabpanel" class="tab-pane fade" id="g_l">                          
                    <?= GridView::widget([
                        'dataProvider' => $g_l_provider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'format' => 'html',
                                'label' => 'Student ID',
                                'value' => function($row)
                                    {
                                        return Html::a($row['studentno'], 
                                                        Url::to(['gradebook/transcript', 'personid' => $row['personid'], 'studentregistrationid' => $row['studentregistrationid']]));
                                    }
                            ],
                            [
                                'attribute' => 'firstname',
                                'format' => 'text',
                                'label' => 'First Name'
                            ],
                            [
                                'attribute' => 'middlename',
                                'format' => 'text',
                                'label' => 'Middle Name(s)'
                            ],
                            [
                                'attribute' => 'lastname',
                                'format' => 'text',
                                'label' => 'Last Name'
                            ],
                            [
                                'attribute' => 'gender',
                                'format' => 'text',
                                'label' => 'Gender'
                            ],
                            [
                                'attribute' => 'studentstatus',
                                'format' => 'text',
                                'label' => 'Student Status'
                            ],
                        ],
                    ]); ?> 

                </div>

                <div role="tabpanel" class="tab-pane fade" id="m_r">                          
                    <?= GridView::widget([
                        'dataProvider' => $m_r_provider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'format' => 'html',
                                'label' => 'Student ID',
                                'value' => function($row)
                                    {
                                        return Html::a($row['studentno'], 
                                                        Url::to(['gradebook/transcript', 'personid' => $row['personid'], 'studentregistrationid' => $row['studentregistrationid']]));
                                    }
                            ],
                            [
                                'attribute' => 'firstname',
                                'format' => 'text',
                                'label' => 'First Name'
                            ],
                            [
                                'attribute' => 'middlename',
                                'format' => 'text',
                                'label' => 'Middle Name(s)'
                            ],
                            [
                                'attribute' => 'lastname',
                                'format' => 'text',
                                'label' => 'Last Name'
                            ],
                            [
                                'attribute' => 'gender',
                                'format' => 'text',
                                'label' => 'Gender'
                            ],
                            [
                                'attribute' => 'studentstatus',
                                'format' => 'text',
                                'label' => 'Student Status'
                            ],
                        ],
                    ]); ?> 

                </div>

                <div role="tabpanel" class="tab-pane fade" id="s_z">                            
                    <?= GridView::widget([
                        'dataProvider' => $s_z_provider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'format' => 'html',
                                'label' => 'Student ID',
                                'value' => function($row)
                                    {
                                        return Html::a($row['studentno'], 
                                                        Url::to(['gradebook/transcript', 'personid' => $row['personid'], 'studentregistrationid' => $row['studentregistrationid']]));
                                    }
                            ],
                            [
                                'attribute' => 'firstname',
                                'format' => 'text',
                                'label' => 'First Name'
                            ],
                            [
                                'attribute' => 'middlename',
                                'format' => 'text',
                                'label' => 'Middle Name(s)'
                            ],
                            [
                                'attribute' => 'lastname',
                                'format' => 'text',
                                'label' => 'Last Name'
                            ],
                            [
                                'attribute' => 'gender',
                                'format' => 'text',
                                'label' => 'Gender'
                            ],
                            [
                                'attribute' => 'studentstatus',
                                'format' => 'text',
                                'label' => 'Student Status'
                            ],
                        ],
                    ]); ?> 

                </div>
            </div>
        </div>
    </div>
</div>