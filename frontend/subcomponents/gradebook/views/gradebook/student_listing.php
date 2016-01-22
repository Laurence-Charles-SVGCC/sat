<?php

/* 
 * 'Student_Listing' view
 * Author: Laurence Charles
 * Date Created: 09/12/2015
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    /* @var $this yii\web\View */
    $this->title = 'Student Listing';
    $this->params['breadcrumbs'][] = ['label' => 'Gradebook', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => 'Programme Listing', 'url' => ['index', 'id' => $division_id]];
    $this->params['breadcrumbs'][] = $this->title;
?>

    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/gradebook/gradebook/index']);?>" title="Gradebook Home">     
                    <img class="custom_logo" src ="<?=Url::to('../images/grade_a+.png');?>" alt="A+">
                    <span class="custom_module_label">Welcome to the SVGCC Grade Management System</span> 
                    <img src ="<?=Url::to('../images/grade_a+.png');?>" alt="A+">
                </a>        
            </div>
        
            <div class="custom_body">                
                <div class="module_body">
                    <h1 class="custom_h1"><?= $programmename?> : Student Listing</h1>
                    
                    <h2 style="margin-left:20px">Academic Year Details</h2>
                    <br/>
                    <table class="table table-hover table-bordered" style="width:95%; margin: 0 auto;">
                        <tr>
                            <th>Academic Year</th>
                            <th>Programme Cordinator</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>

                        <tr>
                            <td><?=$academicyear->title?></td>

                            <?php if ($cordinator == false):?>
                                <td>No Cordinator Assigned</td>
                            <?php else:?>
                                <td><?=$cordinator->title . ". " . $cordinator->firstname . " " . $cordinator->lastname ?></td>
                            <?php endif;?>

                            <td><?=$academicyear->startdate?></td>
                            <td><?=$academicyear->enddate?></td>
                        </tr>
                    </table><br/>        
                    
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
                                    //'filterModel' => $searchModel,
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
                                    //'filterModel' => $searchModel,
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
                                    //'filterModel' => $searchModel,
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
                                    //'filterModel' => $searchModel,
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
                                    //'filterModel' => $searchModel,
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
        </div>
    </div>

    

