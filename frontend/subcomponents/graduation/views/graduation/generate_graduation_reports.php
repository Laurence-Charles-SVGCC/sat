<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\GraduationProgrammeCourse;

     $this->title = 'Generate Graduation Reports';
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
        Please select division and programme to view list of students eligible for graduation.
    </div><br/>
    
    <div class="box-body">
        <?php if($division_id== NULL) :?>
            <div>
                <span><strong>1. Select the division you wish to view programme listing for: </strong></span>
                <span class='dropdown' style="margin-left:2%;">
                    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                        Select division ...
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                        <li><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/generate-graduation-reports', 'division_id' => 4 ])?>">DASGS</a></li>
                        <li><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/generate-graduation-reports', 'division_id' => 5 ])?>">DTVE</a></li>
                    </ul>
                </span>
                <span>
            </div><br/><br/>
            
        <?php else: ?>
            
            <div>
                <span><strong>1. Current division under selection <span style="background-color:yellow">(<?= Division::find()->where(['divisionid' => $division_id])->one()->abbreviation ?>)</span>. To change division: </strong></span>
                <span class='dropdown' style="margin-left:2%;">
                    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                        Select division ...
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                        <?php if ($division_id == 4) :?>
                            <li style="background-color:yellow"><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/generate-graduation-reports', 'division_id' => 4 ])?>">DASGS</a></li>
                       <?php else: ?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/generate-graduation-reports', 'division_id' => 4 ])?>">DASGS</a></li>
                       <?php endif; ?>    
                            
                       <?php if ($division_id == 5) :?>
                            <li style="background-color:yellow"><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/generate-graduation-reports', 'division_id' => 5 ])?>">DTVE</a></li>
                        <?php else: ?>
                            <li><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/generate-graduation-reports', 'division_id' => 5 ])?>">DTVE</a></li>
                       <?php endif; ?>  
                    </ul>
                </span>
                <span>
            </div><br/><br/>
        <?php endif; ?>    
            
            
        <?php if($division_id != NULL) :?>
            <?php if($academic_year_id== NULL) :?>
                <?php if(empty($academic_years) == true) :?>
                    <span>No academic year have been found for the selected division.</span>
                <?php else: ?>
                    <div>
                        <span><strong>2. Select the academic year you wish to investigate: </strong></span>
                        <span class='dropdown' style="margin-left:2%;">
                            <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                                Select academic year ...
                                <span class='caret'></span>
                            </button>
                            <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                              <?php foreach($academic_years as $year_id => $title):?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/generate-graduation-reports', 'division_id' => $division_id, 'academic_year_id' => $academic_year_id])?>"><?= $title ?></a></li>
                               <?php endforeach;?>
                            </ul>
                        </span>
                    </div><br/><br/>
                <?php endif; ?>
                    
            <?php else: ?>
                    
                <?php if(empty($academic_years) == true) :?>
                    <span>No academic year have been found for the selected division.</span>
                <?php else: ?>
                    <div>
                        <span><strong>2. Current academic_year under selection <span style="background-color:yellow">(<?= AcademicYear::find()->where(['academicyearid' => $academic_year_id])->one()->title ?>)</span>. To change year: </strong></span>
                        <span class='dropdown' style="margin-left:2%;">
                            <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                                Select academic year ...
                                <span class='caret'></span>
                            </button>
                            <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                               <?php foreach($academic_years as $year_id => $title):?>
                                    <?php if ($academic_year_id == $year_id) :?>
                                        <li style="background-color:yellow"><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/generate-graduation-reports', 'division_id' => $division_id, 'academic_year_id' => $year_id])?>"><?= $title ?></a></li>
                                    <?php else: ?>
                                        <li><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/generate-graduation-reports', 'division_id' => $division_id, 'academic_year_id' => $year_id])?>"><?= $title ?></a></li>
                                    <?php endif; ?>   
                               <?php endforeach;?>
                            </ul>
                        </span>
                    </div><br/><br/>
                <?php endif; ?>   
            <?php endif; ?>
        <?php endif; ?>
                
                
        <?php if($division_id != NULL && $academic_year_id != NULL) :?>
            <?php if(empty($programmes) == true) :?>
                <span>No programmes have been found for the selected division.</span>
            <?php else: ?>
                <div>
                    <span><strong>3. Select the programme you wish to view prospective graduants listing for: </strong></span>
                    <span class='dropdown' style="margin-left:2%;">
                        <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                            Select programme ...
                            <span class='caret'></span>
                        </button>
                        <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                           <?php foreach($programmes as $programmecatalogid => $programme_name):?>
                                <?php if (empty(GraduationProgrammeCourse::find()->where(['programmecatalogid' => $programmecatalogid, 'isactive' => 1, 'isdeleted' => 0])->all()) == false): ?>
                                    <li><a href="<?= Url::toRoute(['/subcomponents/graduation/graduation/generate-graduation-reports', 'division_id' => $division_id, 'academic_year_id' => $academic_year_id, 'programme_catalog_id' => $programmecatalogid])?>"><?= $programme_name ?></a></li>
                                <?php endif; ?>
                            <?php endforeach;?>
                        </ul>
                    </span>
                </div><br/>
            <?php endif; ?>
        <?php endif; ?>
    </div><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
</div>


<?php if ($division_id != NULL && $academic_year_id != NULL && $programme_catalog_id != NULL && $graduation_reports_dataprovider) : ?>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
        <div class="box-header without-border">
            <h2><?= $current_programme . " Prospective Graduants " . $current_year ?></h2>
        </div>
       
        <?= GridView::widget([
            'dataProvider' => $graduation_reports_dataprovider,
            'columns' => 
                [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'Student ID',
                        'format' => 'html',
                        'value' => function($row, $division_id, $programme_catalog_id)
                        {
                             return Html::a($row['username'], 
                                            Url::to(['graduation/review-student-graduation-report', 'division_id' => $division_id, 'programmecatalog_id' => $programme_catalog_id,
                                                        'graduation_report_id' => $row['graduationreportid']]));
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
//                    [
//                        'attribute' => 'programme',
//                        'format' => 'text',
//                        'label' => 'Programme'
//                    ],
                    [
                        'attribute' => 'total_passes',
                        'format' => 'text',
                        'label' => 'Total Passes'
                    ],
                    [
                        'attribute' => 'total_credits',
                        'format' => 'text',
                        'label' => 'Total Credits'
                    ],                  
                    [
                        'attribute' => 'iseligible',
                        'format' => 'text',
                        'label' => 'Gradution Eligibilty'
                    ],   
                    [
                        'attribute' => 'approvedby',
                        'format' => 'text',
                        'label' => 'Approved By'
                    ],   
                ],
            ]); 
        ?>     
    </div>
<?php endif; ?>

