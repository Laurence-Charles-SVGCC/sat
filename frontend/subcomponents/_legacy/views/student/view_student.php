<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;

     $this->title = 'Student Profile';
     $this->params['breadcrumbs'][] = ['label' => 'Student Search', 'url' => ['find-a-student']];
     $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/legacy/student/find-a-student']);?>" title="Legacy Student Home">
        <h1>Welcome to the Legacy Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:99%; margin: 0 auto;">
    <h2 class="text-center"><?= $this->title?></h2>

    <div class="box-body"> 
        <div role="tabpanel" class="tab-pane fade in active" id="legacy-general"> 
            <br/>
            <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">
                    General
                    <?php if (true/*Yii::$app->user->can('createUpdateStudent')*/): ?>
                        <?= Html::a(' Edit', ['student/update-student', 'id' => $student->legacystudentid], ['class' => 'btn btn-info pull-right', 'style' => 'margin-right: 1%;']) ?>
                    <?php endif; ?>
                </div>
                
                <table class="table table-hover" style="margin: 0 auto;">
                    <tr>
                        <?php if ($student->address == false): ?>
                            <td rowspan="2"> 
                        <?php else: ?>        
                            <td rowspan="3"> 
                       <?php endif;?>
                            <?php if (strcasecmp($student->gender, "Male") == 0): ?>
                                <img src="css/dist/img/avatar_male(150_150).png" alt="avatar_male" class="img-rounded">
                            <?php elseif (strcasecmp($student->gender, "Female") == 0): ?>
                                <img src="css/dist/img/avatar_female(150_150).png" alt="avatar_female" class="img-rounded">
                            <?php endif;?>
                        </td>
                        <th>Full Name</th>
                        <td><?=$student->title . ". " . $student->firstname . " " . $student->middlename . " " . $student->lastname ;?></td>
                        <th>Gender</th>
                        <td><?=$student->gender;?></td>
                    </tr>

                    <tr>
                        <th>Faculty</th>
                        <td><?=$faculty ;?></td>
                        <th>Admission Year</th>
                        <td><?=$admission_year;?></td>
                    </tr>
                    
                    <?php if ($student->address != false): ?>
                        <tr>
                            <th>Address</th>
                            <td colspan="3"><?= $student->address ;?></td>
                        </tr>
                    <?php endif;?>
                </table>
            </div>
        </div>

        <br/>
        <h2 class="custom_h2">Transcript</h2>
        <?php if($records == false):?>
        <p style="margin-left:5%"><strong>No records found</strong></p>
        <?php else:?>
            <div role="tabpanel" class="tab-pane fade in active" id="legacy-transcript"> 
                <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                    <?php foreach($records as $record):?>
                        <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em"><?=$record["name"];?></div>
                        <table class="table table-hover" style="margin: 0 auto;">
                            <?php foreach($record["details"] as $term):?>
                                <tr>
                                   <th colspan='7' style="color:lightgreen;font-weight:bold; font-size:1.2em"><?=$term["name"]?></th>
                                </tr>
                                <?php foreach($term["details"] as $subject):?>
                                    <tr>
                                        <?php if (true/*Yii::$app->user->can('editLEgacyMarksheet')*/): ?>
                                        <th>
                                            <a href="<?= Url::toRoute(['/subcomponents/legacy/grades/edit-grade', 'studentid' => $student->legacystudentid, 'batchid' => $subject["details"]["batchid"]]);?>" title="Edit Grades">
                                                <?=$subject["name"]?>
                                            </a>
                                        </th>
                                        <?php else: ?>
                                            <th><?=$subject["name"]?></th>
                                        <?php endif; ?>
                                        
                                        <th>Term</>
                                        <td><?=$subject["details"]["term"]?></th>
                                        <th>Exam</>
                                        <td><?=$subject["details"]["exam"]?></th>
                                        <th>Final</>
                                        <td><?=$subject["details"]["final"]?></th>
                                    </tr>
                                <?php endforeach;?>
                            <?php endforeach;?>
                        </table>

                    <?php endforeach;?>
                </div>
                <br/>
            <?php endif;?>
        </div>
    </div>
</div>