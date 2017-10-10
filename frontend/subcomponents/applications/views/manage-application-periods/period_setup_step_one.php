<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\ActiveField;
    use yii\helpers\ArrayHelper;
    use yii\jui\DatePicker;
    
    use frontend\models\Division;
    use frontend\models\ApplicationPeriodType;
    use frontend\models\AcademicYear;

    $this->title = 'Application Period Setup Step-1';
    $this->params['breadcrumbs'][] = ['label' => 'Period Listing', 'url' => Url::toRoute(['/subcomponents/applications/application-periods/view-periods'])];
    $this->params['breadcrumbs'][] = ['label' => 'Setup Dashboard', 'url' => Url::toRoute(['initiate-period', 'id' => $period->applicationperiodid])];
    $this->params['breadcrumbs'][] = $this->title;
?>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<h2 class="text-center">Confirm Academic Year Availability</h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em; width:90%; margin: 0 auto; font-size: 20px;"><br/>
    <?php $form = ActiveForm::begin(); ?>
        <?= Html::hiddenInput('divisionid', $divisionid); ?>
        <?= Html::hiddenInput('applicationperiodtypeid', $applicationperiodtypeid); ?>
        <div class="box-body">
             <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="divisionid">Division:</label>
                <span class="dropdown no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9">
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                          <?php if ($divisionid == NULL):?>
                            Select Division...
                         <?php else:?>
                            Change division from <?= Division::find()->where(['divisionid' => $divisionid])->one()->abbreviation;?>....
                         <?php endif;?> 
                         <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu"  aria-labelledby="dropdownMenu1">
                          <li><a href="<?= Url::toRoute(['period-setup-step-one', 'divisionid' => 4])?>" >Division of Arts Sciences and General Studies (DASGS)</a></li>
                          <li><a href="<?= Url::toRoute(['period-setup-step-one', 'divisionid' => 5])?>">Division of Technical and Vocational Edication (DTVE)</a></li>
                          <li><a href="<?= Url::toRoute(['period-setup-step-one', 'divisionid' => 6])?>">Division of Teacher Education (DTE)</a></li>
                          <li><a href="<?= Url::toRoute(['period-setup-step-one', 'divisionid' => 7])?>">Division of Nursing Education (DNE)</a></li>
                      </ul>
                  </span>
              </div><br/><br/>
           
            <?php if ($divisionid != NULL) :?>
                <div class="form-group">
                    <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="divisionid">Period Type:</label>
                    <span class="dropdown no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <?php if ($applicationperiodtypeid == NULL):?>
                                Select Application Period Type...
                             <?php else:?>
                                Change from <?= ApplicationPeriodType::find()->where(['applicationperiodtypeid' => $applicationperiodtypeid])->one()->name;?> time ....
                             <?php endif;?> 
                        </button>
                        <ul class="dropdown-menu"  aria-labelledby="dropdownMenu1">
                            <li><a href="<?= Url::toRoute(['period-setup-step-one', 'divisionid' => $divisionid, 'applicationperiodtypeid' => 1])?>">Full time</a></li>
                            <li><a href="<?= Url::toRoute(['period-setup-step-one', 'divisionid' => $divisionid, 'applicationperiodtypeid' => 2])?>">Part time</a></li>
                        </ul>
                    </span>
                </div>
            <?php endif;?>

            <?php if($divisionid != NULL && $applicationperiodtypeid != NULL  && empty($result_set) == false):?>
<!--              <div>Academic Year Exists: <?= $result_set[0] ?></div>
              <div>Application Period Exists: <?= $result_set[1] ?></div>-->
            <?php endif;?>
              
            <?php if($divisionid != NULL && $applicationperiodtypeid != NULL  && empty($result_set) == false):?>
                <?= Html::hiddenInput('academic_year_exists', $result_set[0]); ?>
                <?= Html::hiddenInput('application_period_exists', $result_set[1]); ?>
              
               <!-- If both academic year and application period already exists-->
                <?php if ($result_set[0] == 1 && $result_set[1] == 1):?>
                    <div class="alert in alert-block fade alert-success mainButtons" style = "display:none">
                        An application period matching the selected division and application period type already exists.
                    </div> 
               
               <!-- If neither academic year application period exist -->
                <?php elseif ($result_set[0] == 0 && $result_set[1] == 0):?><br/><br/>
                    <div class="box box-primary table-responsive no-padding" style = "font-size:1.1em; width:98%; margin: 0 auto; font-size: 20px;">
                        <div class="alert alert-info">
                            You are required to initialize a new academic year.  Please complete the form below.
                        </div>
                        
                        <div class="box-body">
                            <div class="form-group">
                                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">Title:</label>
                                <?= $form->field($new_year, 'title')
                                                ->label("")
                                                ->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
                             </div>
                            
                            <div class="form-group">
                                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="start_date">Start Date:</label>
                               <?= $form->field($new_year, 'startdate')
                                                ->label("")
                                                ->widget(DatePicker::classname(), 
                                                        ['clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]) ?>
                             </div>
                            
                            <div class="form-group">
                                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="end_date">End Date:</label>
                               <?= $form->field($new_year, 'enddate')
                                                ->label("")
                                                ->widget(DatePicker::classname(), 
                                                        ['clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]) ?>
                             </div>
                        </div>
                    </div>
               
                    <div class="box-footer">
                        <span class = "pull-right">
                            <?= Html::submitButton(' Update', ['class' => 'btn btn-success', 'style' => 'margin-right:20px', 'onclick' => 'generateAcademicYearBlanks();']);?>
                            <?= Html::a(' Cancel', ['initiate-period', 'id' => $period->applicationperiodid], ['class' => 'btn  btn-danger', ]);?>
                        </span>
                    </div>
               
               <!-- If academic year exists but period does not exist -->
                <?php elseif ($result_set[0] == 1 && $result_set[1] == 0):?>
                    <div class="box-footer">
                        <span class = "pull-right">
                            <?= Html::submitButton(' Update', ['class' => 'btn btn-success', 'style' => 'margin-right:20px', 'onclick' => 'generateAcademicYearBlanks();']);?>
                            <?= Html::a(' Cancel', ['initiate-period', 'id' => $period->applicationperiodid], ['class' => 'btn  btn-danger']);?>
                        </span>
                    </div>
                <?php endif;?>
            <?php endif;?>
            <br/><br/><br/><br/>
        </div>
    <?php ActiveForm::end()?>
</div>