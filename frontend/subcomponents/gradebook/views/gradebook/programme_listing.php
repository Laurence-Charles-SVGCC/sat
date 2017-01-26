<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\QualificationType;
    use frontend\models\AcademicYear;
    
    $this->title = $division_name . ': Programme Listing';
    $this->params['breadcrumbs'][] = ['label' => 'Gradebook', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/gradebook/gradebook/index']);?>" title="Grade Management Home">
        <h1>Welcome to the SVGCC Grade Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
    </div>
    
    <div class="box-body">
        <div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#programmes" aria-controls="programmes" role="tab" data-toggle="tab">All Programmes</a></li>
                    <?php 
                    $departments = Department::getDepartments($division_id);
                    foreach($departments as $department)
                    {   
                        $short_name = substr($department->name, 14);
                        $link = strval($department->departmentid);
                        echo "<li role='presentation'><a href='#$link' aria-controls='$link' role='tab' data-toggle='tab'>$short_name</a></li>";
                    }
                ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="programmes">                          
                    <br/>
                    <table class="table table-hover" style="width:95%; margin: 0 auto;">
                        <tr>
                            <th>Programme Name</th>
                            <th>View Cohorts</th> 
                        </tr>
                        <?php
                            for ($j = 0 ; $j < count($data[1]) ; $j++)  //loops through programmes
                            {
                                //Cape will be excluded from "All Programmes" listing 
                                if ( strcmp($data[1][$j][0]->name, "CAPE") != 0)
                                {
                                    echo "<tr>";
                                        $full_name = "";
                                        $pname = $data[1][$j][0]->name;

                                        $qualification = QualificationType::getQualificationAbbreviation($data[1][$j][0]->qualificationtypeid);
                                        if ($qualification != NULL  && strcmp($qualification,"") != 0)
                                            $full_name = $full_name . $qualification;

                                        $specialisation = $data[1][$j][0]->specialisation;
                                        if ( $specialisation == NULL  || strcmp($specialisation, "") == 0)
                                            $full_name = $full_name . $pname;
                                        else
                                            $full_name = $full_name . $pname . " (" . $specialisation . ")";

                                        echo "<td>$full_name</td>";
//                                                        echo "<td>View Cohort</td>";
                                        echo "<td>";                                  
                                            echo "<div class='dropdown'>
                                                <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                                  echo "Select Cohort...";
                                                  echo "<span class='caret'></span>";
                                                echo "</button>";
                                                echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>";
                                                    $cohort_count = $data[1][$j][1];
                                                    if ($cohort_count > 0)
                                                    {
                                                        for ($k = 0 ; $k < $cohort_count ; $k++)
                                                        {
                                                            $year_title = AcademicYear::getYearTitle($data[1][$j][2][$k]->academicyearid);
//                                                                        echo "<li><a href='#'>$year_title</a></li>"; 
                                                            $academic_year_id = $data[1][$j][2][$k]->academicyearid;
                                                            $academic_offering_id = $data[1][$j][2][$k]->academicofferingid;
                                                            $year_title = AcademicYear::getYearTitle($academic_year_id);
                                                            $hyperlink = Url::toRoute(['/subcomponents/gradebook/gradebook/students', 
                                                                                                'academicyearid' => $academic_year_id, 
                                                                                                'academicofferingid' => $academic_offering_id, 
                                                                                                'programmename' => $full_name,
                                                                                                'divisionid' => $division_id
                                                                                             ]);
                                                            echo "<li><a href='$hyperlink'>$year_title</a></li>";      
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "<li>This programme is yet to be offered</li>";  
                                                    }    
                                                echo "</ul>";
                                            echo "</div>";
                                        echo "</td>";                                                      
                                    echo "<tr>";   
                                }
                            }
                        ?>                                
                    </table>
                </div>

                <?php 
                    $depts = Department::getDepartments($division_id);
                    for($i = 0 ; $i < $data[0] ; $i++)
                    {        
                        $string_id = strval($depts[$i]->departmentid);
                        echo "<div role='tabpanel' class='tab-pane fade' id='$string_id'>";
                          echo "<br/>";
                            if (ProgrammeCatalog::getProgrammesByDepartment($depts[$i]->departmentid) == false)
//                                            echo "<p>Shit</p>";
                                  echo "<div class='alert alert-info' role='alert'>No programmes are currently attached to this department.</div>";
                            else
                            {                                                                                                                            
                                echo "<table class='table table-hover' style='width:95%; margin: 0 auto;'>";
                                    echo "<tr>";
                                        echo "<th>Programme Name</th>";
                                        echo "<th>View Cohorts</th>"; 
                                    echo "</tr>";
                                    for ($j = 0 ; $j < count($data[1]) ; $j++)
                                    {
                                        //If  programme belongs to department in question
                                        if ($data[1][$j][0]->departmentid == $depts[$i]->departmentid)
                                        {
                                            echo "<tr>";                                           
                                            $pname = $data[1][$j][0]->name;
                                            $full_name = "";
                                            $pname = $data[1][$j][0]->name;

                                            $qualification = QualificationType::getQualificationAbbreviation($data[1][$j][0]->qualificationtypeid);
                                            if ($qualification != NULL  && strcmp($qualification,"") != 0)
                                                $full_name = $full_name . $qualification;

                                            $specialisation = $data[1][$j][0]->specialisation;
                                            if ( $specialisation == NULL  || strcmp($specialisation, "") == 0)
                                                $full_name = $full_name . $pname;
                                            else
                                                $full_name = $full_name . $pname . " (" . $specialisation . ")";

                                                echo "<td>$full_name</td>";
//                                                            echo "<td>View Cohort</td>";
                                                echo "<td>";                                  
                                                    echo "<div class='dropdown'>
                                                        <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                                          echo "Select Cohort...";
                                                          echo "<span class='caret'></span>";
                                                        echo "</button>";
                                                        echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>";
                                                            $cohort_count = $data[1][$j][1];
                                                            if ($cohort_count > 0)
                                                            {
                                                                for ($k = 0 ; $k < $cohort_count ; $k++)
                                                                {
                                                                    $year_title = AcademicYear::getYearTitle($data[1][$j][2][$k]->academicyearid);
                                                                    $academic_year_id = $data[1][$j][2][$k]->academicyearid;
                                                                    $academic_offering_id = $data[1][$j][2][$k]->academicofferingid;
                                                                    $year_title = AcademicYear::getYearTitle($academic_year_id);
                                                                    $hyperlink = Url::toRoute(['/subcomponents/gradebook/gradebook/students', 
                                                                                                'academicyearid' => $academic_year_id, 
                                                                                                'academicofferingid' => $academic_offering_id , 
                                                                                                'programmename' => $full_name,
                                                                                                'divisionid' => $division_id
                                                                                             ]);
                                                                    echo "<li><a href='$hyperlink'>$year_title</a></li>";                                                     
                                                                }
                                                            }
                                                            else
                                                            {
                                                                echo "<li>This programme is yet to be offered</li>";  
                                                            }    
                                                        echo "</ul>";
                                                    echo "</div>";
                                                echo "</td>";                                                                                                   
                                            echo "<tr>";    
                                        }
                                    }
                                echo "</table>";
                            }
                        echo "</div>";                          
                    }    
                ?>
            </div><br/><br/>
        </div><br/><br/>
    </div><br/><br/>
</div>
