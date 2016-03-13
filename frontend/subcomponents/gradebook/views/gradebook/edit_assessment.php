<?php

/* 
 * 'Edit Assessment' view of any course [Associate || CAPE] for users with authorization to edit assessments
 * Author: Laurence Charles
 * Date Created: 16/12/2015
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    
    use frontend\models\AcademicStatus;
    use frontend\models\BatchStudent;

?>

    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/gradebook/gradebook/index']);?>" title="Gradebook Home">     
                    <img class="custom_logo" src ="<?=Url::to('../images/grade_a+.png');?>" alt="A+">
                    <span class="custom_module_label">Welcome to the SVGCC Grade Management System</span> 
                    <img src ="<?=Url::to('../images/grade_a+.png');?>" alt="A+">
                </a>        
            </div>
            
            <div class="custom_body">
                <h1 class="custom_h1">Edit Assessment</h1>

                <?php 
                    if ($edittable == NULL || $edittable == false || $non_edittable == NULL || $non_edittable == false )
                    {
                        echo "<h1 class='custom_h2'>Assessment can not be editted at this time</h1>";
                    }

                    else
                    {
                        $form = ActiveForm::begin([
                            //'action' => Url::to(['gradebook/index']),
                            'id' => 'edit-assessment-form',
                            'options' => [
//                                    'class' => 'form-layout form-inline'
//                                    'class' => 'form-inline',
                            ],
                        ]);

                            echo "<br/>";
                            echo "<table class='table table-bordered' style='width:95%; margin: 0 auto;'>";
                                echo "<tr>";
                                    echo "<th>Name</th>";
                                        echo "<td>{$non_edittable['name']}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Assessment Category</th>";
                                        echo "<td>{$non_edittable['category']}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Assessment Type</th>";
                                        echo "<td>{$non_edittable['type']}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Group/Individual</th>";
                                        echo "<td>{$non_edittable['participation']}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Lecturer</th>";
                                        echo "<td>{$non_edittable['lecturer']}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Weight(%)</th>";
                                        echo "<td>{$non_edittable['weight']}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Date Administered</th>";
                                        echo "<td>{$non_edittable['date']}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Marks Attained</th>";
                                        echo "<td>{$form->field($edittable, 'marksattained')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Total Marks</th>";
                                        echo "<td>{$non_edittable['total_marks']}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Questions Link</th>";
                                        if (strcmp($non_edittable['questions'],"") != 0 || $non_edittable['questions'] != NULL)
                                            echo "<td>{$non_edittable['questions']}</td>";
                                        else
                                            echo "<td>File not uploaded</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Mark Scheme</th>";
                                        if (strcmp($non_edittable['markscheme'],"") != 0 || $non_edittable['markscheme'] != NULL)
                                            echo "<td><a href=''>{$non_edittable['markscheme']}</a></td>";
                                        else
                                            echo "<td>File not uploaded</td>";
                                echo "</tr>";   
                            echo "</table>"; 

                            echo "<br/>";

                            echo Html::a(' Cancel',['edit-assessments-cancel', 'studentregistrationid' => $non_edittable['studentregistrationid'], 'code' => $code, 'name' => $name, 'batchid' => $non_edittable['batchid']], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                            echo Html::submitButton('Update', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);


                        ActiveForm::end();      
                    }                    
                ?>
            </div>
        </div>
    </div>


    



