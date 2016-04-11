<?php

/* 
 * Author: Laurence Charles
 * Date Created 09/04/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    
    use frontend\models\Package;

    $this->title = 'Packages Summary';
?>

<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        
        <div class="custom_body">                           
            </br>                              
            <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Packages Summary
                    <?php if (Package::getIncompletePackageID() == true):?>
                        <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/package/initiate-package', 'recordid' => ApplicationPeriod::getIncompletePeriodID()]);?> role="button"> Complete-Package-Setup</a>
                    <?php else:?>
                        <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/package/initiate-package']);?> role="button"> Initiate-Package-Setup</a>
                    <?php endif;?>
                </div>


                <?php 
                    if($packages == false)
                    {
                        echo "<h3>No completed packages exist</h3>";
                    }
                    else
                    {
                        //Table
                        echo "<table class='table table-condensed' style='margin: 0 auto;'>";
                            foreach ($packages as $package) 
                            {
                                echo "<tr>";
                                    echo "<th rowspan='5' style='vertical-align:top; text-align:center; font-size:1.2em;'>{$package['package_name']}";
                                        echo "<div style='margin-top:20px'>";
                                            if(Yii::$app->user->can('Registrar')  && Package::safeToDelete($package['id']) == true)
                                            {
                                                echo Html::a(' Delete', 
                                                            ['package/delete-package', 'recordid' => $package["id"]], 
                                                            ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                'data' => [
                                                                    'confirm' => 'Are you sure you want to delete this item?',
                                                                    'method' => 'post',
                                                                ],
                                                                'style' => 'margin-right:20px',
                                                            ]);
                                            }
                                            if(Yii::$app->user->can('Registrar'))
                                            {
                                                echo Html::a(' Edit', 
                                                                    ['package/edit-package', 'recordid' => $package["id"]], 
                                                                    ['class' => 'btn btn-info glyphicon glyphicon-pencil',
                                                                        'style' => 'margin-right:20px',
                                                                    ]);
                                            }
                                        echo "</div>";
                                    echo "</th>";

                                    echo "<th>Application Period</th>";
                                    echo "<td>{$package["period_name"]}</td>";
                                    
                                    echo "<th>Package Type</th>";
                                    echo "<td>{$package["type"]}</td>";
                                echo "</tr>";
                                
                                echo "<tr>";
                                    echo "<th>Last Modified By</th>";
                                    echo "<td>{$package["last_modified_by"]}</td>";
                                    
                                    echo "<th>Setup Status</th>";
                                    echo "<td>{$package["progress"]}</td>";
                                echo "</tr>";
                            }
                        echo "</table>";
                    }
                ?>
            </div>
        </div>
            
            
            
            
        </div>
        
        
        
        
        
     </div>
 </div>
        

