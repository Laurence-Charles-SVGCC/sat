<?php

/* 
 * Author: Laurence Charles
 * Date Created 12/09/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\file\FileInput;
    
    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\PackageType;
    use frontend\models\PackageProgress;
    
    $this->title = 'Email File Listing';
?>

<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/students/email-upload/index']);?>" title="Email Management">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/email.png" alt="email">
                <span class="custom_module_label">Welcome to the Email Management System</span> 
                <img src ="css/dist/img/header_images/email.png" alt="email" class="pull-right">
            </a>   
        </div>
        
        
        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title?></h1>
            <br/>
            
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
//                                                    'style' => 'margin-right:20px',
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
//                                                    'style' => 'margin-right:20px',
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
//                                                    'style' => 'margin-right:20px',
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
            
            <br/>
            <a style="margin-left:2.5%;" class="btn btn-danger glyphicon glyphicon-arrow-left pull-left" href=<?=Url::toRoute(['/subcomponents/students/email-upload/index']);?> role="button"> Back</a>
        </div>
    </div>
</div>

