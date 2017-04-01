<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use yii\widgets\ActiveForm;
    use yii\data\ArrayDataProvider;
    
    use backend\models\AuthAssignment;
    
    
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
            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
            <?php if($employee_title == "Junior Lecturer"  || $employee_title == "Lecturer"):?>
                <li role="presentation"><a href="#academics" aria-controls="academics" role="tab" data-toggle="tab">Academics</a></li>
            <?php endif;?>
            <li class='active' role="presentation"><a href="#roles_permissions" aria-controls="roles_permissions" role="tab" data-toggle="tab">Roles & Permissions</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="profile"> 
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
                                <?php if($employee->gender == false): ?>
                                    <img src="css/dist/img/avatar_neutral(200_200).png" alt="avatar_neutral" class="img-rounded">
                                <?php else: ?>
                                    <?php if (strcasecmp($employee->gender, "m") == 0  || strcasecmp($employee->gender, "male") == 0): ?>
                                        <img src="css/dist/img/avatar_male(150_150).png" alt="avatar_male" class="img-rounded">
                                    <?php elseif (strcasecmp($employee->gender, "f") == 0 || strcasecmp($employee->gender, "female") == 0): ?>
                                        <img src="css/dist/img/avatar_female(150_150).png" alt="avatar_female" class="img-rounded">
                                    <?php endif;?>
                                <?php endif; ?>
                            </td>
                            <th>Username</th>
                            <td><?=$user->username;?></td>
                            <th>Job Title</th>
                            <td><?= $employee_title;?></td>
                        </tr>

                        <tr>
                            <th>Division</th>
                            <td><?=$employee_division;?></td>  
                            <th>Department</th>
                            <td><?=$employee_department;?></td>  
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
            
            <div role="tabpanel" class="tab-pane fade in active" id="roles_permissions"> 
                <h2 class="custom_h2">Roles and Permissions</h2>
                <br/>
                <div class="panel panel-default" style="width:100%; margin: 0 auto;">  
                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">
                        Roles
                        <?php if(AuthAssignment::find()->where(['user_id' => $employee->personid])->count() > 0):?>
                            <a class="btn btn-info pull-right" href=<?=Url::toRoute(['/employee/edit-role', 'personid' => $employee->personid]);?> role="button"> Edit Role</a>
                        <?php else:?>
                            <a class="btn btn-info pull-right" href=<?=Url::toRoute(['/employee/assign-role', 'personid' => $employee->personid]);?> role="button">Assign Role</a>
                        <?php endif;?>
                    </div>
                    <!-- Role Table -->
                    <table class="table table-hover" style="margin: 0 auto;">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Description</th>
                                <th>Ancestor Roles</th>
                                <th>Descendant Roles</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php foreach($roles as $role):?>
                                <tr>
                                    <td><?= $role["name"] ;?></td>
                                    <td><?= $role["description"] ;?></td>
                                    <!--<td><?= "Ancestors" ;?></td>-->
                                    <td>
                                        <ol>
                                            <?php foreach ($ancestors as $ancestor):?>
                                                <li><?= $ancestor;?></li>
                                            <?php endforeach;?>
                                        </ol>
                                    </td>
                                    <td>
                                        <ol>
                                            <?php foreach ($descendants as $descendant):?>
                                                <li><?= $descendant;?></li>
                                            <?php endforeach;?>
                                        </ol>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    
                    
                    <br/>
                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">
                        Permissions
                        <a class="btn btn-info pull-right" href="#" role="button"> Edit Permissions</a>
                    </div>
                    <div id="permission-details">
                        <?= GridView::widget([
                                'dataProvider' => $permission_dataProvider,
                                'columns' => [
                                    [
                                        'attribute' => 'name',
                                        'format' => 'text',
                                        'label' => 'Name'
                                    ],
                                    [
                                        'attribute' => 'description',
                                        'format' => 'text',
                                        'label' => 'Description'
                                    ],
                                ],
                            ]); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            
            

