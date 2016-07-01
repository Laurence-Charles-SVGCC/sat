<?php

/* 
 * Author: Laurence Charles
 * Date Created: 27/04/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\grid\GridView;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    
    $this->title = 'Programme Cordinator  Control Panel';
?>


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
                <h1 class="custom_h1"><?=$this->title?></h1>
                
                <br/>
                <div class="cordinator-programme-result">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
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
                                'attribute' => 'exambody',
                                'format' => 'text',
                                'label' => 'Exam Body'
                            ],
                            [
                                'attribute' => 'programmetype',
                                'format' => 'text',
                                'label' => 'Type'
                            ],     
                            [
                                'attribute' => 'duration',
                                'format' => 'text',
                                'label' => 'Duration'
                            ],
                            [
                                'attribute' => 'creationdate',
                                'format' => 'text',
                                'label' => 'Created'
                            ],
                        ],
                    ]); ?>     
                </div>
            </div>
        </div>
    </div>



