<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
   
    use frontend\models\AcademicOffering;

    $this->title = "Interview Appointment Management";
    $this->params['breadcrumbs'][] = $this->title;
?>


<h2 class="text-center"><?= $this->title?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <div class="box-title">
            <span><?= $this->title?></span>
        </div>
    </div>
    
    <div class="box-body">
        <?php if ($application_periods == true) : ?>
            <div><strong>Prepare schedule by applicant lastname:</strong>
                <span class='dropdown' style="margin-left:2%">
                    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                        Select application period...
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                        <?php
                            foreach ($application_periods as $period)
                            {
                                $label_a_g = $period->name . " - Surnames (A-G)";
                                $label_h_n = $period->name . " - Surnames (H-N)";
                                $label_o_z = $period->name . " - Surnames (O-Z)";
                                $hyperlink_a_g = Url::toRoute(['/subcomponents/admissions/interview-appointments/schedule-interviews-by-lastname/', 
                                                                          'applicationperiod_id' => $period->applicationperiodid,
                                                                          'offertype' => 2, 'lower_bound' => 'A', 'upper_bound' => 'G']);
                                $hyperlink_h_n = Url::toRoute(['/subcomponents/admissions/interview-appointments/schedule-interviews-by-lastname/', 
                                                                          'applicationperiod_id' => $period->applicationperiodid,
                                                                          'offertype' => 2, 'lower_bound' => 'H', 'upper_bound' => 'N']);
                                $hyperlink_o_z = Url::toRoute(['/subcomponents/admissions/interview-appointments/schedule-interviews-by-lastname/', 
                                                                          'applicationperiod_id' => $period->applicationperiodid,
                                                                          'offertype' => 2, 'lower_bound' => 'O', 'upper_bound' => 'Z']);
                                echo "<li><a href='$hyperlink_a_g'>$label_a_g</a></li>";  
                                echo "<li><a href='$hyperlink_h_n'>$label_h_n</a></li>";  
                                echo "<li><a href='$hyperlink_o_z'>$label_o_z</a></li>";  
                            }
                        ?>
                    </ul>
                </span><br/><br/>
            </div><br/>
        
            <div><strong>Prepare schedule by programme:</strong>
                <span class='dropdown' style="margin-left:2%">
                    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                        Select application period...
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                        <?php
                            foreach ($application_periods  as $period)
                            {
                                foreach( $programme_objects as $prog)
                                {
                                    $academic_offering = AcademicOffering::find()
                                            ->where(['programmecatalogid' => $prog->programmecatalogid, 'applicationperiodid' => $period->applicationperiodid,
                                                'interviewneeded' => 1, 'isactive' => 1, 'isdeleted' => 0])
                                            ->one();
                                    if ($academic_offering == true)
                                    {
                                        $label = $prog->getFullName();
                                        $hyperlink = Url::toRoute(['/subcomponents/admissions/interview-appointments/schedule-interviews-by-programme/', 
                                                                                  'academic_offering_id' => $academic_offering->academicofferingid, 'offertype' => 2]); 
                                        echo "<li><a href='$hyperlink'>$label</a></li>";  
                                    }
                                }
                            }
                        ?>
                    </ul>
                </span><br/><br/>
            </div><br/><br/><br/><br/>
        <?php else:?>
            <span>No interviews offers are available for scheduling.</span>
        <?php endif;?>
    </div>
</div>