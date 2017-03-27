<?php
     use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    use yii\grid\GridView;
    
    use frontend\models\LegacyYear;
    use frontend\models\LegacyFaculty;

    $this->title = 'Batch Listing';
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/legacy/grades/find-batches']);?>" title="Legacy Batches Home">
        <h1>Welcome to the Legacy Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <h2 class="text-center">
        <?= $this->title?>
        <?= Html::a('Create New Gradesheet', ['grades/configure-batch-marksheets'], ['class' => 'btn btn-info pull-right', 'style' => 'margin-right:1%']) ?>
    </h2>
    
    <div class="box-body">
        <div class="year-listing">
            <p><strong>Select the year filter below;</strong></p>
            <ul>
                <?php foreach ($years as $year): ?>
                    <?php if(LegacyYear::yearHasRecordedGrade($year->legacyyearid)):?>
                        <?php if ($yearid == $year->legacyyearid):?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/legacy/grades/find-batches', 'yearid' => $year->legacyyearid]);?>" style="background-color: yellow"><?= $year->name ;?></a></li>
                        <?php else:?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/legacy/grades/find-batches', 'yearid' => $year->legacyyearid]);?>"><?= $year->name ;?></a></li>
                        <?php endif;?>
                    <?php else: ?>
                        <li><?= $year->name ;?></li>
                    <?php endif;?>
                <?php endforeach;?>
            </ul>
        </div>
        
        <?php if ($terms) : ?>
            <br/>
            <div class="term-listing">
                <p><strong>Select the term filter below;</strong></p>
                <ul>
                    <?php foreach ($terms as $term): ?>
                        <?php if ($termid == $term->legacytermid):?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/legacy/grades/find-batches', 'yearid' => $yearid, 'termid' => $term->legacytermid]);?>" style="background-color: yellow"><?= $term->name ;?></a></li>
                        <?php else:?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/legacy/grades/find-batches', 'yearid' => $yearid, 'termid' => $term->legacytermid]);?>"><?= $term->name ;?></a></li>
                        <?php endif;?>
                   <?php endforeach;?>
                </ul>
            </div>
        <?php endif;?>
        
        <?php if ($levels) : ?>
             <br/>
            <div class="level-listing">
                <p><strong>Select the level filter below;</strong></p>
                <ul>
                    <?php foreach ($levels as $level): ?>
                        <?php if ($levelid == $level->legacylevelid): ?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/legacy/grades/find-batches', 'yearid' => $yearid, 'termid' => $termid, 'levelid' => $level->legacylevelid]);?>" style="background-color: yellow"><?= $level->name ;?></a></li>
                        <?php else: ?>
                           <li><a href="<?= Url::toRoute(['/subcomponents/legacy/grades/find-batches', 'yearid' => $yearid, 'termid' => $termid, 'levelid' => $level->legacylevelid]);?>"><?= $level->name ;?></a></li>
                       <?php endif; ?>
                   <?php endforeach;?>
                </ul>
            </div>
        <?php endif;?>
        
        <?php if ($dataProvider) : ?>
            <br/>
            <div class="legacy_batch_listing">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'format' => 'html',
                            'label' => 'Subject',
                            'value' => function($row)
                            {
                                return Html::a($row['subject'], Url::to(['grades/update-batch-marksheet', 'batchid' => $row['batchid']]));
                            }
                        ],
                        [
                            'attribute' => 'subject_type',
                            'format' => 'text',
                            'label' => 'Examination Body'
                        ],
                        [
                            'attribute' => 'year',
                            'format' => 'text',
                            'label' => 'Year'
                        ],
                        [
                            'attribute' => 'term',
                            'format' => 'text',
                            'label' => 'Term'
                        ],
                        [
                            'attribute' => 'level',
                            'format' => 'text',
                            'label' => 'Level'
                        ],
                        [
                            'attribute' => 'student_count',
                            'format' => 'text',
                            'label' => 'No. of Students'
                        ],
                    ],
                ]); ?>     
            </div>
        <?php endif;?>
    </div>
</div>