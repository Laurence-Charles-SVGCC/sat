<?php

/* 
 * Author: Laurence Charles
 * Date Created 08/02/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    
    use frontend\models\ApplicationPeriod;

    $this->title = 'Application Periods Summary';
?>

<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        
        <div class="custom_body">                           
            </br>                              
            <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Application Periods Summary
                    <?php if (ApplicationPeriod::hasIncompletePeriod() == true):?>
                        <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/admissions/initiate-period', 'recordid' => ApplicationPeriod::getIncompletePeriodID()]);?> role="button"> Complete-Period-Setup</a>
                    <?php else:?>
                        <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/admissions/initiate-period']);?> role="button"> Initiate-Period-Setup</a>
                    <?php endif;?>
                </div>


                <?php 
                    if($periods == false)
                    {
                        echo "<h3>No active application period records exist</h3>";
                    }
                    else
                    {
                        //Table
                        echo "<table class='table table-condensed' style='margin: 0 auto;'>";
                            foreach ($periods as $period) 
                            {
                                echo "<tr>";
                                    echo "<th rowspan='5' style='vertical-align:top; text-align:center; font-size:1.2em;'>{$period['name']}";
                                        echo "<div style='margin-top:20px'>";
                                            if(Yii::$app->user->can('admissions')  && ApplicationPeriod::canSafeToDelete($period['id']) == true)
                                            {
                                                echo Html::a(' Delete', 
                                            ['application-period/delete-application-period', 'recordid' => $period["id"]], 
                                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                        'data' => [
                                                                            'confirm' => 'Are you sure you want to delete this item?',
                                                                            'method' => 'post',
                                                                        ],
                                                                        'style' => 'margin-right:20px',
                                                                    ]);
                                            }
                                            if(Yii::$app->user->can('admissions'))
                                            {
                                                echo Html::a(' Edit', 
                                                                    ['application-period/edit-application-period', 'recordid' => $period["id"]], 
                                                                    ['class' => 'btn btn-info glyphicon glyphicon-pencil',
                                                                        'style' => 'margin-right:20px',
                                                                    ]);
                                            }
                                        echo "</div>";
                                    echo "</th>";

                                    echo "<th>Division</th>";
                                    echo "<td>{$period["division"]}</td>";
                                    
                                    echo "<th>Year</th>";
                                    echo "<td>{$period["year"]}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Onsite Start Date</th>";                                                      
                                    echo "<td>{$period["onsitestartdate"]}</td>";
                                    
                                    echo "<th>Onsite End Date</th>";                                                      
                                    echo "<td>{$period["onsiteenddate"]}</td>";
                                echo "</tr>";
                                
                                echo "<tr>";
                                    echo "<th>Off-site Start Date</th>";                                                      
                                    echo "<td>{$period["offsitestartdate"]}</td>";
                                    
                                    echo "<th>Off-site End Date</th>";                                                      
                                    echo "<td>{$period["offsiteenddate"]}</td>";
                                echo "</tr>";
                                
                                echo "<tr>";
                                    echo "<th>Type</th>";                                                      
                                    echo "<td>{$period["type"]}</td>";
                                    
                                    echo "<th>Status</th>";                                                      
                                    echo "<td>{$period["status"]}</td>";
                                echo "</tr>";
                                
                                echo "<tr>";
                                    echo "<th>Last Updated By</th>";                                                      
                                    echo "<td>{$period["creator"]}</td>";
                                    
                                    echo "<th>Is Complete</th>"; 
                                    if ($period["iscomplete"] == 1)
                                        echo "<td>Yes</td>";
                                    else
                                        echo "<td>No</td>";
                                echo "</tr>";
                            }
                        echo "</table>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>

        

