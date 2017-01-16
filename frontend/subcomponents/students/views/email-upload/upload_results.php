<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;
    
    use common\models\User;
    
    $this->title = 'File Upload Report';
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
        </table><br/><br/>
        
        <?php if($dataProvider->totalCount == 0):?>
            <p>All student emails were updated successfully.</p>

        <?php else:?>
            <p>Click the link below to download error listing.</p>

            <?= ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                        [
                            'attribute' => 'username',
                            'format' => 'text',
                            'label' => 'Username'
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
            ?><br/><br/>
            
            <h3>Error Report</h3>
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'attribute' => 'username',
                            'format' => 'text',
                            'label' => 'Username'
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
