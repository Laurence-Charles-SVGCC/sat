 <?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $this->title = $name;
    $this->params['breadcrumbs'][] = ['label' => 'Application Periods', 'url' => Url::toRoute(['/subcomponents/applications/application-periods/view-periods'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header without-border">
        <span class="box-title"><?= $this->title ?></span>
    
        <span class="dropdown pull-right">
            <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                Select Action...
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <li><a  href="<?=Url::toRoute(['/subcomponents/applications/manage-application-periods/edit-application-period', 'id' => $id]);?>"> Edit </a></li>
                <?php if ($iscomplete == 0 && $applicationperiodstatusid ==  5):?>
                    <li><a  href="<?=Url::toRoute(['/subcomponents/applications/manage-application-periods/manage-programme-offerings', 'id' => $id]);?>"> Manage Programmes </a></li>
                <?php endif; ?>
            </ul>
          </span>
    </div>
           
   
    <div class="box-body">  
        <div class="panel panel-default">
            <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Summary</div>
            
            <table class="table table-hover">
                <tr>
                    <th>Academic Year</th>
                    <td><?= $academic_year ?></td>
                    <th>Division</th>
                    <td><?= $division ?></td>
                </tr>
                
                <tr>
                     <th>Onsite Start Date</th>
                    <td><?= $onsiteenddate ?></td>
                    <th>Onsite End Date</th>
                    <td><?= $onsiteenddate ?></td>
                </tr>
                
                <tr>
                    <th>Offsite Start Date</th>
                    <td><?= $offsitestartdate ?></td>
                    <th>Offsite End Date</th>
                    <td><?= $offsiteenddate ?></td>
                </tr>
                
                <tr>
                    <th>Fulltime /Parttime</th>
                    <td><?= $period_type ?></td>
                   <th>Creator</th>
                    <td><?= $creator ?></td>
                </tr>
               
                <tr>
                    <th>Status</th>
                    <td><?= $period_status ?></td>
                    <th>Applicant Visibility</th>
                    <td><?= $applicant_visibility ?></td>
                </tr>
            </table><br/>
            
            <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Programme Listing</div>
            <div>
                <?php if (empty($programmes) == true): ?>
                    <p><strong>No programmes are currently assigned to this application period.</strong></p>
                <?php else:?>
                   <table class="table table-hover">
                       <tr>
                           <th>Name</th>
                           <th>Expected Intake</th>
                           <th>Actual Intake</th>
                       </tr>
                       
                       <?php foreach ($programmes as $programme):?>
                       <tr>
                           <td><?= $programme[0] ?></td>
                           <td><?= $programme[1] ?></td>
                           <td><?= $programme[2] ?></td>
                       </tr>
                       <?php endforeach;?>
                   </table>
                <?php endif;?>
            </div><br/>
            
            <?php if(empty($cape_offerings) == false): ?>
            <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">CAPE Subject Listing</div>
            <div>
                <table class="table table-responsive">
                    <tr>
                        <th>Subject</th>
                        <th>Group</th>
                        <th>Expected Intake</th>
                        <th>Actual Intake</th>
                    </tr>

                    <?php foreach($cape_offerings as $cape_offering):?>
                        <tr>
                            <td><?= $cape_offering[0] ?></td>
                            <td><?= $cape_offering[1] ?></td>
                            <td><?= $cape_offering[2] ?></td>
                            <td><?= $cape_offering[3] ?></td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </div>
            <?php endif;?>
        </div>
    </div><br/>
 </div>