<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;

     $this->title = 'Graduation Requirements';
     $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
      <h1>Welcome to the Graduation Management System</h1>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
    </div>
    
    <div class="alert alert-info" style = "width:98%; margin: 0 auto">
        Navigate to the programme of your choice to investigate the courses listed as requirements for student graduation from that programme.
    </div><br/>
    
    <div class="box-body">
        <div>
            <span><strong> Select the division you wish to view programme listing for: </strong></span>
            <span class='dropdown' style="margin-left:2%;">
                <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                    Select division ...
                    <span class='caret'></span>
                </button>
                <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                    <li><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/programme-graduation-requirements', 'division_id' => 4 ])?>">DASGS</a></li>
                    <li><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/programme-graduation-requirements', 'division_id' => 5 ])?>">DTVE</a></li>
                </ul>
            </span>
        </div>
    </div><br/><br/><br/><br/><br/>
</div>


<?php if ($division_id != NULL && $programme_catalog_dataprovider) : ?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
        <div class="box-header without-border">
            <h2><?= "Programme Catalog for: " . $info_string ?></h2>
        </div>
       
        <?= GridView::widget([
            'dataProvider' => $programme_catalog_dataprovider,
            'columns' => 
                [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'format' => 'html',
                        'value' => function($row)
                        {
                            // if programme is CAPE
                            if ($row['programmecatalogid'] == 10)
                            {
                                return $row['name'];
                            }
                            else
                            {
                                return Html::a($row['name'], 
                                            Url::to(['graduation/view-course-catalog', 'division_id' => $row['division_id'], 'programmecatalog_id' => $row['programmecatalogid']]));
                            }
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
                        'label' => 'Duration (yrs)'
                    ],
                ],
            ]); 
        ?>     
    </div>
<?php endif; ?>

