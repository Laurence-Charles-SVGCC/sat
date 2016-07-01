<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\AwardCategory;
    use frontend\models\AwardType;
    use frontend\models\AwardScope;
    use frontend\models\Award;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Division;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    
    
    $this->title = "Awardee Listing: " .$award->name ;
?>


    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/registry/awards/manage-awards']);?>" title="Manage Awards">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/award.png" alt="award avatar">
                    <span class="custom_module_label" style="margin-left:5%;"> Welcome to the Award Management System</span> 
                    <img src ="css/dist/img/header_images/award.png" alt="award avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$this->title?></h1>
                
                </br>                              
                <table class='table table-hover' style='width:90%; margin: 0 auto;'>
                    <tr>
                        <th>Student ID</th>
                        <th>Title</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Date of Award</th>
                    </tr>
                    
                    <?php foreach($awardees as $awardee):?>
                        <tr>
                            <td>
                                <a href=<?=Url::toRoute(['/subcomponents/students/profile/student-profile', 'personid' => $awardee['personid'], 'studentregistrationid' => $awardee['studentregistrationid']])?>>
                                    <?=$awardee["username"];?>
                                </a>
                            </td>
                            <td><?=$awardee["title"];?></td>
                            <td><?=$awardee["firstname"];?></td>
                            <td><?=$awardee["lastname"];?></td>
                            <td><?=$awardee["dateawarded"];?></td>
                        </tr>
                    <?php endforeach;?>
                </table><br/>

                <?= Html::a(' Back',['awards/manage-awards'], ['class' => 'btn btn-block btn-danger glyphicon glyphicon-remove-circle pull-right', 'style' => 'width:15%; margin-right:5%;']);?>
                
            </div>
        </div>
    </div>


