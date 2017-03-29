<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use yii\widgets\ActiveForm;
    use yii\data\ArrayDataProvider;
    
    
    
    use frontend\models\CapeSubject;
    use frontend\models\Application;
    use frontend\models\ApplicationCapesubject;
    use frontend\models\Employee;
    use frontend\models\AcademicStatus;
    use frontend\models\BatchStudentCape;
    use frontend\models\BatchStudent;
    use frontend\models\StudentRegistration;
    use frontend\models\Hold;
    use frontend\models\ExternalQualification;
    use frontend\models\NursingAdditionalInfo;
    use frontend\models\TeachingAdditionalInfo;
    use frontend\models\NurseWorkExperience;
    use frontend\models\CriminalRecord;
    use frontend\models\PostSecondaryQualification;
    use frontend\models\Offer;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\AcademicYear;
    
    $this->title = 'Employee Profile';
    
    $this->params['breadcrumbs'][] = ['label' => 'User Listing', 'url' => Url::toRoute(['/user/index'])];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/user/index']);?>" title="User Management Home">
        <h1>Welcome to the User Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <h2 class="text-center"><?=$employee->title . ". " . $employee->firstname . " " . $employee->middlename . " " . $employee->lastname ;?></h2>

    <div class="box-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
            <li role="presentation"><a href="#academics" aria-controls="academics" role="tab" data-toggle="tab">Academics</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="profile"> 
                <br/>
                <div class="panel panel-default" style="width:100%; margin: 0 auto;">  
                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">
                        Profile
                        <a class="btn btn-info pull-right" href=<?=Url::toRoute(['/employee/edit-profile', 'personid' => $employee->personid]);?> role="button"> Edit</a>
                    </div>

                    <!-- Table -->
                    <table class="table table-hover" style="margin: 0 auto;">
                        <tr>
                            <td rowspan="3"> 
                                <?php if($employee->gender == NULL): ?>
                                    <img src="css/dist/img/avatar_neutral(200_200).png" alt="avatar_neutral" class="img-rounded">
                                <?php else:?>
                                    <?php if (strcasecmp($employee->gender, "male") == 0): ?>
                                        <img src="css/dist/img/avatar_male(150_150).png" alt="avatar_male" class="img-rounded">
                                    <?php elseif (strcasecmp($employee->gender, "female") == 0): ?>
                                        <img src="css/dist/img/avatar_female(150_150).png" alt="avatar_female" class="img-rounded">
                                    <?php endif;?>
                                <?php endif;?>
                            </td>
                            <th>Username</th>
                            <td><?=$user->username;?></td>
                            <th>Job Title</th>
                            <td><?= $employee_title;?></td>
                        </tr>

                        <tr>
                            <th>Full Name</th>
                            <td><?=$employee->title . ". " . $employee->firstname . " " . $employee->middlename . " " . $employee->lastname ;?></td>
                            <th>Gender</th>
                            <td><?=$employee->gender;?></td>
                        </tr>

                        <tr>
                            <th>Date Of Birth</th>
                            <td><?=$employee->dateofbirth;?></td>
                            <th>Marital Status</th>
                            <td><?=$employee->maritalstatus;?></td>                                  
                        </tr>

                        <tr>
                            <td></td>
                            <th>Nationality</th>
                            <td><?=$employee->nationality;?></td>
                            <th>Place Of Birth</th>
                            <td><?=$employee->placeofbirth;?></td>                                  
                        </tr>

                        <tr>
                            <td></td>
                            <th>Religion</th>
                            <td><?=$employee->religion;?></td> 
                            <th>National ID # </th>
                            <td><?=$employee->nationalidnumber;?></td>  
                        </tr>

                        <tr>
                            <td></td>
                            <th>National Insurance Service #</th>
                            <td><?=$employee->nationalinsurancenumber;?></td>  
                            <th>Inland Revenue #</th>
                            <td><?=$employee->inlandrevenuenumber;?></td>  
                        </tr>
                    </table>
                </div>
            </div>
            
            <div role="tabpanel" class="tab-pane fade" id="academics"> 
                <h2 class="custom_h2">Academics</h2>
                </br>
                <img style="display: block; margin: auto;" src ="css/dist/img/under_construction.jpg" alt="Under Construction">
            </div>
        </div>
    </div>
</div>
            
            

