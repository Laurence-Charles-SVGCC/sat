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
    
    $this->title = "Club Details";
?>


<div class="site-index">
    <div class = "custom_wrapper">

        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title?></h1>

            </br> 
            <table class='table table-hover' style='width:80%; margin: 0 auto;'>
                <tr>
                    <th style='width:30%; vertical-align:middle'>Name</th>
                    <td><?=$name;?></td>
                </tr>

                <tr>
                    <th style='width:30%; vertical-align:middle'>Description</th>
                    <td><?=$description?></td>
                </tr>

                <tr>
                    <th style='width:30%; vertical-align:middle'>Motto</th>
                    <td><?=$motto?></td>
                </tr>

                <tr>
                    <th style='width:30%; vertical-align:middle'>Year Established</th>
                    <td><?=$yearfounded?></td>
                </tr>
            </table><br/>

            <?= Html::a(' Back',['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger pull-right', 'style' => 'width:20%; margin-right:10%;']);?>
        </div>
    </div>
</div>

