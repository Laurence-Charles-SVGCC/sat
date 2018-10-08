<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    use frontend\models\Event;
    use frontend\models\EventType;

    $this->title = "Record Details";

    $this->params['breadcrumbs'][] = ['label' => 'Find An Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = ['label' => 'Student Profile', 'url' => Url::toRoute(['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-body">
        <div class="panel panel-default">
            <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Record
                <span class='dropdown pull-right'>
                    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                        Select Action
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                        <?php if(Yii::$app->user->can('editEvent')):?>
                            <li><a href=<?=Url::toRoute(['/subcomponents/students/log/edit-event', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid, 'eventid' => $event->eventid, 'eventtypeid' => $event->eventtypeid, 'recordid' => $recordid])?>>Edit</a></li>
                        <?php endif;?>
                        <?php if(Yii::$app->user->can('editEvent')):?>
                            <li><a href=<?=Url::toRoute(['/subcomponents/students/log/attach-documents', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid, 'eventid' => $event->eventid, 'eventtypeid' => $event->eventtypeid, 'recordid' => $recordid])?>>Attach Document</a></li>
                        <?php endif;?>
                        <?php if(Yii::$app->user->can('deleteEvent')):?>
                            <li><a href=<?=Url::toRoute(['/subcomponents/students/log/delete-event', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid, 'eventid' => $event->eventid, 'eventtypeid' => $event->eventtypeid, 'recordid' => $recordid])?>>Delete</a></li>
                        <?php endif;?>
                    </ul>
                </span>
            </div><br/>

            <table class='table table-hover'>
                <tr>
                    <th style='width:30%; vertical-align:middle'>Summary</th>
                    <td><?=$event_details->summary?></td>
                </tr>

                <tr>
                    <th style='width:30%; vertical-align:middle'>Description</th>
                    <td><?=$event_details->description?></td>
                </tr>

                <tr>
                    <th style='width:30%; vertical-align:middle'>Start Date</th>
                    <td><?=$event_details->startdate?></td>
                </tr>

                <tr>
                    <th style='width:30%; vertical-align:middle'>End Date</th>
                    <td><?=$event_details->enddate?></td>
                </tr>
            </table>
        </div><br/>

        <fieldset>
            <legend><strong>File Listing</strong></legend>
            <table class='table table-hover' style='margin: 0 auto;'>
                <?php if(!$saved_documents):?>
                    <p>No files are currently attached to this note</p>
                <?php else:?>
                    <tr>
                        <th>Filename</th>
                        <th>Download</th>
                        <th>Delete</th>
                    </tr>
                    <?php foreach($saved_documents as $index=>$doc):?>
                        <tr>
                            <td><?=  substr($doc,65)/*$doc*/?></td>
                            <?php
                                $filename = substr($doc,65);
                            ?>
                            <td>
                                <?= Html::a(' ',
                                            ['log/download-event-attachment',  'index' => $index, 'personid' => $personid, 'studentregistrationid' => $studentregistrationid, 'eventid' => $event->eventid, 'eventtypeid' => $event->eventtypeid, 'recordid' => $recordid],
                                            ['class' => 'btn btn-success glyphicon glyphicon-download-alt']
                                        ) ?>
                            <td>
                                <?=Html::a(' ',
                                            ['log/delete-attachment', 'index' => $index, 'personid' => $personid, 'studentregistrationid' => $studentregistrationid, 'eventid' => $event->eventid, 'eventtypeid' => $event->eventtypeid, 'recordid' => $recordid],
                                            ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                'data' => [
                                                    'confirm' => 'Are you sure you want to delete this item?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </table>
        </fieldset>
    </div>

    <div class="box-footer pull-right">
        <?= Html::a(' Back', ['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn  btn-danger']);?>
    </div>
</div>
