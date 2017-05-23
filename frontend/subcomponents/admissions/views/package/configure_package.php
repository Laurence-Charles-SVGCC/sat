<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    
    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\PackageType;
    use frontend\models\PackageProgress;
    
    $document_count = [
        0 => 'No attachments',
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
        9 => '9',
        10 => '10',
    ];
    
    $this->title = 'Configure Package';
    
    $this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => Url::toRoute(['/subcomponents/admissions/package'])];
    if ($recordid == true)
        $this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['/subcomponents/admissions/package/initiate-package', 'recordid' => $recordid])];
    else
        $this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['/subcomponents/admissions/package'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/package']);?>" title="Manage Packages">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">Name:</label>
               <?php if ($name_changeable == true):?>
                    <?= $form->field($package, 'name')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
               <?php else:?>
                    <?= $form->field($package, 'name')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
               <?php endif;?>
               
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="applicationperiodid">Application Period:</label>
               <?= $form->field($package, 'applicationperiodid')->label('')->dropDownList(ArrayHelper::map(ApplicationPeriod::periodIncomplete(), 'applicationperiodid', 'name'), ['prompt'=>'Select Application Period', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="packagetypeid">Package Type:</label>
               <?= $form->field($package, 'packagetypeid')->label('')->dropDownList(ArrayHelper::map(PackageType::find()->all(), 'packagetypeid', 'description'), ['onchange' => 'showCommencementDate();', 'prompt'=>'Select Package Type', 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="commencementdate">Commencement Date:</label>
               <?= $form->field($package, 'commencementdate')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="documentcount">Document Count:</label>
               <?= $form->field($package, 'documentcount')->label('')->dropDownList($document_count, ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="emailtitle">Email Title:</label>
               <?=$form->field($package, 'emailtitle')->label('')->textInput(['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
            
             <?php if ($package->packageid  && $package->packagetypeid != 3) :?>
                <div class="form-group">
                   <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="email-intro">Email Introductory Statements:</label>
                   <?php 
                        if ($package->packageid  && ($package->packagetypeid==1 || $package->packagetypeid==2))
                        {
                            $text= date("l F j, Y") . "<br/>" . "Dear [firstname] [lastname]";
                            echo "<span>";
                                echo Html::textarea('email-intro', $text, ['rows' => 10, 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);
                            echo "</span>";
                        }
    //                    elseif ($package->packageid  && $package->packagetypeid==3)
    //                    {
    //                        $text= date("l F j, Y") . "                                                                                                 "
    //                                . "                                                                                     Dear [firstname] [lastname],"
    //                                . "                                                                                                                 "
    //                                . "                                                                                      "  
    //                                . "We are pleased to inform you that you have been invited to interview for a place in the  "
    //                                . "[programme name] at the [division_name] commencing on " . $package->commencementdate .  ".";
    //                        echo "<span>";
    //                            echo Html::textarea('email-intro', $text, ['rows' => 10, 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);
    //                        echo "</span>";
    //                    }        
                         elseif ($package->packageid  && $package->packagetypeid==4)
                        {
                            $text= date("l F j, Y") . "                                                                                                  "                                                                                                  
                                    . "                                                                                     Dear [firstname] [lastname],"
                                    . "                                                                                                                 "
                                    . "                                                                                      "  
                                    . "We are pleased to inform you that your application to the St. Vincent and the Grenadines Community College has been successful." 
                                    . "  You are offered a place in the [programme name] at the [division_name] commencing on " . $package->commencementdate .  ".      "
                                    . "Your Student Number is: [student number]";
                            echo "<span>";
                                echo Html::textarea('email-intro', $text, ['rows' => 10, 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);
                            echo "</span>";
                        }
                    ?>
                </div><br/><br/><br/><br/>
             <?php endif;?>
            

            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="emailcontent">Email Content:</label>
               <?= $form->field($package, 'emailcontent')->label('')->textArea(['rows' => 50, 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>

         
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="disclaimer">Disclaimer:</label>
               <?= $form->field($package, 'disclaimer')->label('')->textArea(['rows' => 5, 'class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']);?>
            </div>
        </div>

        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                
                <?php if ($recordid == true):?>
                    <?= Html::a(' Cancel', ['package/initiate-package', 'recordid' => $recordid], ['class' => 'btn  btn-danger']);?>
                <?php else:?>
                    <?= Html::a(' Cancel', ['package/initiate-package'], ['class' => 'btn  btn-danger']);?>
                <?php endif;?>
               
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>
