<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\file\FileInput;
    
    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\PackageType;
    use frontend\models\PackageProgress;
    
    $this->title = 'Email File Listing';
    $this->params['breadcrumbs'][] = ['label' => 'Email Dashboard', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
     <a href="<?= Url::toRoute(['/subcomponents/students/email-upload/index']);?>" title="Email Management">     
        <h1>Welcome to the Email Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em">
     <div class="box-header with-border">
         <span class="box-title"><?= $this->title?></span>
    </div>
    
    <div class="box-body">
        <?php if ($files):?>
            <div style="width:95%; margin: 0 auto;">
                <table class='table table-hover' >
                    <tr>
                        <th>Filename</th>
                        <th>Download</th>
                        <th>Update Records</th>
                        <th>Delete</th>
                    </tr>
                    <?php foreach($files as $index=>$doc):?>
                        <tr>
                            <td><?= substr($doc,52)?></td>

                            <td>
                                <?=Html::a(' ', 
                                        ['email-upload/download-file', 'index' => $index], 
                                        ['class' => 'btn btn-info glyphicon glyphicon-download-alt',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to download this file?',
                                                'method' => 'post',
                                            ],
                                        ]);
                                ?>
                            </td>

                            <td>
                                <?=Html::a(' ', 
                                        ['email-upload/process-file', 'index' => $index], 
                                        ['class' => 'btn btn-success glyphicon glyphicon-play',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to process this file?',
                                                'method' => 'post',
                                            ],
                                        ]);
                                ?>
                            </td>

                            <td>
                                <?=Html::a(' ', 
                                            ['email-upload/delete-file', 'index' => $index], 
                                            ['class' => 'btn btn-warning glyphicon glyphicon-remove',
                                                'data' => [
                                                    'confirm' => 'Are you sure you want to delete this file?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </div>
        <?php else:?>
            <div style="width:95%; margin: 0 auto;">
                No files are have been uploaded yet.
            </div>
        <?php endif;?>
    </div>
    
    <div class="box-footer">
        <span class = "pull-right">
            <a style="margin-left:2.5%;" class="btn btn-danger" href=<?=Url::toRoute(['/subcomponents/students/email-upload/index']);?> role="button"> Back</a>
        </span>
    </div>
</div>

