<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\grid\GridView;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    
    $this->title = 'Cordinator  Control Panel';
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/programmes/programmes/programme-cordinator']);?>" title="Programme Management">
        <h1>Welcome to the Programme Management System</h1>
    </a>
</div>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  margin: 0 auto;">
    <h2 class="text-center"><?= $this->title?></h2>
    
    <?php if ($is_programme_cordinator):?>
        <div class="box-header with-border">
            <span class="box-title"> Programme(s) Cordinated </span>
        </div>

        <div class="box-body">
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'label' => 'Name',
                            'format' => 'html',
                            'value' => function($row)
                                {
                                    return Html::a($row['name'], 
                                                    Url::to(['programmes/programme-overview', 'programmecatalogid' => $row['programmecatalogid']]));
                                }
                        ],
                        [
                            'attribute' => 'qualificationtype',
                            'format' => 'text',
                            'label' => 'Qualification'
                        ],
                        [
                            'attribute' => 'specialisation',
                            'format' => 'text',
                            'label' => 'Specialisation'
                        ],
                        [
                            'attribute' => 'department',
                            'format' => 'text',
                            'label' => 'Department'
                        ],
                        [
                            'attribute' => 'programmetype',
                            'format' => 'text',
                            'label' => 'Type'
                        ],   
                    ],
                ]); 
            ?>   
        </div>
     <?php endif;?>
</div>

<!--
    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/registry/awards/manage-awards']);?>" title="Manage Programmes">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/programme.png" alt="scroll avatar">
                    <span class="custom_module_label" > Welcome to the Programme Management System</span> 
                    <img src ="css/dist/img/header_images/programme.png" alt="scroll avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$this->title?></h1><br/>
                
                <?php if ($is_programme_cordinator):?>
                    <fieldset class="programe-cordinator-result">
                        <legend class="custom_h2">Programme(s) Cordinated</legend>
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
        //                            ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'label' => 'Name',
                                        'format' => 'html',
                                        'value' => function($row)
                                            {
                                                return Html::a($row['name'], 
                                                                Url::to(['programmes/programme-overview', 'programmecatalogid' => $row['programmecatalogid']]));
                                            }
                                    ],
                                    [
                                        'attribute' => 'qualificationtype',
                                        'format' => 'text',
                                        'label' => 'Qualification'
                                    ],
                                    [
                                        'attribute' => 'specialisation',
                                        'format' => 'text',
                                        'label' => 'Specialisation'
                                    ],
                                    [
                                        'attribute' => 'department',
                                        'format' => 'text',
                                        'label' => 'Department'
                                    ],
        //                            [
        //                                'attribute' => 'exambody',
        //                                'format' => 'text',
        //                                'label' => 'Exam Body'
        //                            ],
                                    [
                                        'attribute' => 'programmetype',
                                        'format' => 'text',
                                        'label' => 'Type'
                                    ],     
        //                            [
        //                                'attribute' => 'duration',
        //                                'format' => 'text',
        //                                'label' => 'Duration'
        //                            ],
        //                            [
        //                                'attribute' => 'creationdate',
        //                                'format' => 'text',
        //                                'label' => 'Created'
        //                            ],
                                ],
                            ]); ?>     
                    </fieldset>        
              <?php endif;?>
            </div>
        </div>
    </div>
-->