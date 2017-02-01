<?php
    use yii\widgets\Breadcrumbs;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $this->title = 'Edit ExtraCurricular Activites';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find An Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = ['label' => 'Student Profile', 'url' => Url::toRoute(['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistration->studentregistrationid])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">
        <h1>Welcome to the Student Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
    </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="nationalsports">National Sports:</label>
                <?= $form->field($applicant, 'nationalsports')->label('')->textArea(['rows' => '5', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="othersports">Recreational Sports:</label>
                <?= $form->field($applicant, 'othersports')->label('')->textArea(['rows' => '5', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="clubs">Club Participation:</label>
                <?= $form->field($applicant, 'clubs')->label('')->textArea(['rows' => '5', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="otherinterests">Other Interests:</label>
                <?= $form->field($applicant, 'otherinterests')->label('')->textArea(['rows' => '5', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div>
        </div>
    
        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistration->studentregistrationid], ['class' => 'btn  btn-danger']);?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>      
            
            
            
            
    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/sms_4.png" alt="student avatar">
                    <span class="custom_module_label">Welcome to the Student Management System</span> 
                    <img src ="css/dist/img/header_images/sms_4.png" alt="student avatar" class="pull-right">
                </a>   
            </div>
            
            <div class="custom_body">
                <h1 class="custom_h1">Edit Extracurricular Activities</h1>

                <?php
                    $form = ActiveForm::begin([
                                'id' => 'edit-excurricular-activities',
                                'options' => [
                                ],
                            ]);

                        echo "<br/>";
                            echo "<table class='table table-hover' style='width:95%; margin: 0 auto;'>";
                                echo "<tr>";
                                    echo "<th>National Sports</th>";
                                        echo "<td>{$form->field($applicant, 'nationalsports')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Recreational Sports</th>";
                                        echo "<td>{$form->field($applicant, 'othersports')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Club Memberships</th>";
                                        echo "<td>{$form->field($applicant, 'clubs')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Other Interests</th>";
                                        echo "<td>{$form->field($applicant, 'otherinterests')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "</tr>";
                            echo "</table>"; 

                            echo "<br/>";

                            echo Html::a(' Cancel',['profile/student-profile', 'personid' => $applicant->personid, 'studentregistrationid' => $studentregistration->studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                            echo Html::submitButton('Update', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);
                        ActiveForm::end();    
                    ?>
            </div>
        </div>
    </div>
        
                    



