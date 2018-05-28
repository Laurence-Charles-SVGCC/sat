<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\ApplicationPeriod;

    $this->title = 'Generate Student Benificiery Listing';
    $this->params['breadcrumbs'][] = $this->title;
   
    $dasgs_programme_search_criteria = [
        '0' => 'Programmes',
        '1' => 'CAPE Subjects',
        '2' => 'All Programmes',
    ];
    
    $non_dasgs_programme_search_criteria = [
        '0' => 'Programmes',
        '1' => 'All Programmes',
    ];
    
?>

<div class="report-index">
    <div class = "custom_wrapper">
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
                
            <div style="margin-left:2.5%"><br/>
                 <div class='dropdown'>
                    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                        <strong>Please select the application period you wish to investigate...</strong>
                        <span class='caret'></span>
                    </button>

                     <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                         <?php
                                if ($periods)
                                {
                                    foreach ($periods as $period)
                                    {
                                        $hyperlink = Url::toRoute(['/subcomponents/payments/reports/generate-insurance-listing/', 
                                                                                'applicationperiodid' => $period->applicationperiodid
                                                                            ]);
                                        echo "<li><a href='$hyperlink'>$period->name</a></li>";
                                    }
                                }
                                else
                                {
                                    echo "<li>No application periods found</li>";  
                                }    
                          ?>
                     </ul>
                 </div>
             </div>
                
        </div>
    </div>
</div>
