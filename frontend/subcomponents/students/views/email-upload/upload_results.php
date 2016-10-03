<?php

/* 
 * Author: Laurence Charles
 * Date Created 29/09/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;
    
    use common\models\User;
    
    $this->title = 'File Upload Report';
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
            <h1 class="custom_h1"><?=$this->title?></h1><br/>
            
            <div style="width:95%; margin: 0 auto;">
                
                <table class="table table-striped">
                    <tr>
                        <th>Filename</th>
                        <th>Total</th>
                        <th>Successful</th>
                        <th>Successful (%)</th>
                    </tr>
                    
                    <tr>
                        <td><?= substr($filename,52)?></td>
                        <td><?=$total;?></td>
                        <td><?=$successful;?></td>
                        <td>
                            <small class='pull-right'><?=$percentage?>%</small>
                            <div class='progress xs'>
                                <div class='progress-bar progress-bar-green' style='width: <?=$percentage;?>%' role='progressbar' aria-valuenow='<?=$percentage;?>' aria-valuemin='0' aria-valuemax='100'>
                                    <span class='sr-only'><?=$percentage;?>%</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
                
                <?php if($dataProvider == false):?>
                    <p>All student emails were updated successfully.</p>

                <?php else:?>
                    <p>Click the link below to download the listing.</p>

                    <?= ExportMenu::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                                [
                                    'attribute' => 'username',
                                    'format' => 'text',
                                    'label' => 'Username'
                                ],
                                [
                                    'attribute' => 'title',
                                    'format' => 'text',
                                    'label' => 'Title'
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
                                [
                                    'attribute' => 'error',
                                    'format' => 'text',
                                    'label' => 'Error Message'
                                ],
                            ],
                            'fontAwesome' => true,
                            'dropdownOptions' => [
                                'label' => 'Select Export Type',
                                'class' => 'btn btn-default'
                            ],
                            'asDropdown' => false,
                            'showColumnSelector' => false,
                            'filename' => $filename,
                            'exportConfig' => [
                                ExportMenu::FORMAT_TEXT => false,
                                ExportMenu::FORMAT_HTML => false,
                                ExportMenu::FORMAT_EXCEL => false,
                                ExportMenu::FORMAT_EXCEL_X => false,
                                ExportMenu::FORMAT_PDF => false
                            ],
                        ]);
                    ?><br/>

                    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'options' => ['style' => 'width: 100%; margin: 0 auto;'],
                            'columns' => [
                                [
                                    'attribute' => 'username',
                                    'format' => 'text',
                                    'label' => 'Username'
                                ],
                                [
                                    'attribute' => 'title',
                                    'format' => 'text',
                                    'label' => 'Title'
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
                                [
                                    'attribute' => 'error',
                                    'format' => 'text',
                                    'label' => 'Error Message'
                                ],
                            ],
                        ]); 
                    ?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

