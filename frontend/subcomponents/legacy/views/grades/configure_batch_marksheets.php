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

    $this->title = 'New Batch Configuration';
    $this->params['breadcrumbs'][] = ['label' => 'Batch Listing', 'url' => ['grades/find-batches']];
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
    <h2 class="text-center"><?= $this->title?></h2>
    
    <div class="box-body">
        <div class="year-listing">
            <p><strong>Select the year filter below;</strong></p>
            <ul>
                <?php foreach ($years as $year): ?>
                    <?php if ($yearid == $year->legacyyearid):?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/legacy/grades/configure-batch-marksheets', 'yearid' => $year->legacyyearid]);?>" style="background-color: yellow"><?= $year->name ;?></a></li>
                    <?php else:?>
                        <li><a href="<?= Url::toRoute(['/subcomponents/legacy/grades/configure-batch-marksheets', 'yearid' => $year->legacyyearid]);?>"><?= $year->name ;?></a></li>
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
                            <li><a href="<?= Url::toRoute(['/subcomponents/legacy/grades/configure-batch-marksheets', 'yearid' => $yearid, 'termid' => $term->legacytermid]);?>"  style="background-color: yellow"><?= $term->name ;?></a></li>
                        <?php else: ?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/legacy/grades/configure-batch-marksheets', 'yearid' => $yearid, 'termid' => $term->legacytermid]);?>"><?= $term->name ;?></a></li>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
            </div>
            
            <?php if ($levels) : ?>
                <br/>
                <div class="level-listing">
                    <p><strong>Select the level filter below;</strong></p>
                    <ul>
                        <?php foreach ($levels as $level): ?>
                            <?php if ($levelid == $level->legacylevelid):?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/legacy/grades/configure-batch-marksheets', 'yearid' => $yearid, 'termid' => $termid, 'levelid' => $level->legacylevelid]);?>" style="background-color: yellow"><?= $level->name ;?></a></li>
                            <?php else: ?>
                                <li><a href="<?= Url::toRoute(['/subcomponents/legacy/grades/configure-batch-marksheets', 'yearid' => $yearid, 'termid' => $termid, 'levelid' => $level->legacylevelid]);?>"><?= $level->name ;?></a></li>
                            <?php endif;?>
                       <?php endforeach;?>
                    </ul>
                </div>
            <?php endif;?>

            <?php if ($dataProvider) : ?>
                <br/><h3><?= "Search Criteria :  " . $info_string;?></h3>
                <?php if($batchid != null):?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Subject Type</th>
                                <th>Year</th>
                                <th>Term</th>
                                <th>Level</th>
                                <th>No. of Students</th>
                                <th>Record Entry Controls</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php for($i = 0 ; $i < count($batches_container) ; $i++):?>
                            <tr>
                                <td><?= $batches_container[$i]['subject'];?></td>
                                <td><?= $batches_container[$i]['subject_type'];?></td>
                                <td><?= $batches_container[$i]['year'];?></td>
                                <td><?= $batches_container[$i]['term'];?></td>
                                <td><?= $batches_container[$i]['level'];?></td>
                                <td><?= $batches_container[$i]['student_count'];?></td>
                                <td>
                                    <div class='dropdown'>
                                        <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                                             Select number of students in class...
                                             <span class='caret'></span>
                                         </button>
                                         <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                                            <?php for ($i = 1 ; $i <= 50 ; $i++): ?>
                                                <li>
                                                    <a href="<?= Url::toRoute(['/subcomponents/legacy/grades/add-marksheets/', 'batchid' => $batchid, 'count' => $i]);?>"><?= $i ;?></a>
                                               </li>
                                            <?php endfor;?>
                                          </ul>
                                     </div>
                                </td>
                            </tr>
                            <?php endfor;?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="legacy_batch_listing">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                [
                                    'format' => 'html',
                                    'label' => 'Subject',
                                    'value' => function($row)
                                    {
                                        return Html::a($row['subject_name'], Url::to(['grades/add-marksheets', 'batchid' => $row['batchid']]));
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
            <?php endif;?>
        <?php elseif ($termid != null && $terms == false):?>
            <div class="alert in alert-block fade alert-warning">
                No associated term records found. You must create term records first, then retry new  gradesheet configuration.
            </div>
        <?php endif;?>
    </div>
</div>