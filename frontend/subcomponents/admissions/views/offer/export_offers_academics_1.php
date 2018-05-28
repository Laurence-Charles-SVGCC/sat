<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;
    
    $this->title = $title;
    $this->params['breadcrumbs'][] = $this->title;
?>

    <div class="body-content">
        <div class = "custom_wrapper">
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?= $title?></h1>
                <br/>
                
                <div id="offer-academics" style="width:95%; margin:0 auto">
                       <p>Click on the following links to download report seen below in the format of your choice</p>
                       <?= ExportMenu::widget([
                               'dataProvider' => $dataProvider,
                               'columns' => [
                                       [
                                           'attribute' => 'fullname',
                                           'format' => 'text',
                                           'label' => 'Fullname'
                                       ],
                                       [
                                           'attribute' => 'address',
                                           'format' => 'text',
                                           'label' => 'Address'
                                       ],
                                       [
                                           'attribute' => 'phone',
                                           'format' => 'text',
                                           'label' => 'Contact Number(s)'
                                       ],
                                       [
                                           'attribute' => 'programme',
                                           'format' => 'text',
                                           'label' => 'Programme'
                                       ],
                                       [
                                           'attribute' => 'subject',
                                           'format' => 'text',
                                           'label' => 'Subject'
                                       ],
                                       [
                                           'attribute' => 'proficiency',
                                           'format' => 'text',
                                           'label' => 'Details'
                                       ],
                                       [
                                           'attribute' => 'grade',
                                           'format' => 'text',
                                           'label' => 'Grade'
                                       ],
                                       [
                                           'attribute' => 'year',
                                           'format' => 'text',
                                           'label' => 'Year'
                                       ],
                                       [
                                           'attribute' => 'references',
                                           'format' => 'text',
                                           'label' => 'References'
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
                                    ExportMenu::FORMAT_PDF => false,
                                   ExportMenu::FORMAT_TEXT => false,
                                   ExportMenu::FORMAT_HTML => false,
                                   ExportMenu::FORMAT_EXCEL => false,
        //                                                    ExportMenu::FORMAT_EXCEL_X => false
                               ],
                           ]);
                       ?>
                           
                           
                        <br/>
                        <p>Test</p>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'options' => ['style' => 'width: 99%; margin: 0 auto;'],
                            'columns' => [
                                            [
                                               'attribute' => 'fullname',
                                               'format' => 'text',
                                               'label' => 'Fullname'
                                           ],
                                           [
                                               'attribute' => 'address',
                                               'format' => 'text',
                                               'label' => 'Address'
                                           ],
                                           [
                                               'attribute' => 'phone',
                                               'format' => 'text',
                                               'label' => 'Contact Number(s)'
                                           ],
                                           [
                                               'attribute' => 'programme',
                                               'format' => 'text',
                                               'label' => 'Programme'
                                           ],
                                           [
                                                'attribute' => 'subject',
                                                'format' => 'text',
                                                'label' => 'Subject'
                                            ],
                                            [
                                                'attribute' => 'proficiency',
                                                'format' => 'text',
                                                'label' => 'Details'
                                            ],
                                            [
                                                'attribute' => 'grade',
                                                'format' => 'text',
                                                'label' => 'Grade'
                                            ],
                                            [
                                                'attribute' => 'year',
                                                'format' => 'text',
                                                'label' => 'Year'
                                            ],
                                            [
                                                'attribute' => 'references',
                                                'format' => 'text',
                                                'label' => 'References'
                                            ],
                                ],
                            ]); 
                         ?>
                   
               </div>
            </div>
         </div>
     </div>

