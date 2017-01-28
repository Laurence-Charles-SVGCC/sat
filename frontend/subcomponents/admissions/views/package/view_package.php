<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use yii\helpers\ArrayHelper;
    
    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\Package;
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
    
    $this->title = 'View Package';
    $this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => Url::toRoute(['/subcomponents/admissions/package'])];
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
     
     <div class="box-body">
         <fieldset id="config-package">
             <legend><strong>Package Details</strong></legend>
            <?php
                $form = ActiveForm::begin();
                    echo "<br/>";
                    echo "<table class='table table-hover' style='width:100%; margin: 0 auto;'>";   
                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'> Package Name</th>";
                            echo "<td>{$form->field($package, 'name')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true])}</td>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'>Application Period</th>";
                            echo "<td>{$form->field($package, 'applicationperiodid')->label('', ['class'=> 'form-label'])->dropDownList(ArrayHelper::map(ApplicationPeriod::periodIncomplete(), 'applicationperiodid', 'name'), ['readonly' => true, 'disabled' => true])}</td>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'>Package Type</th>";
                            echo "<td>{$form->field($package, 'packagetypeid')->label('', ['class'=> 'form-label'])->dropDownList(ArrayHelper::map(PackageType::find()->all(), 'packagetypeid', 'description'), ['prompt'=>'Select Package Type', 'readonly' => true, 'disabled' => true])}</td>";
                        echo "</tr>";

                        if($package->commencementdate)
                        {
                            echo "<tr>";
                                echo "<th style='width:25%; vertical-align:middle'>Commencement Date</th>";
                                echo "<td>{$form->field($package, 'commencementdate')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true])}</td>";
                            echo "</tr>";
                        }

                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'>Number of Attachments</th>";
                            echo "<td>{$form->field($package, 'documentcount')->label('', ['class'=> 'form-label'])->dropDownList($document_count, ['readonly' => true, 'disabled' => true])}</td>";
                        echo "</tr>"; 

                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'>Email Title</th>";
                            echo "<td>{$form->field($package, 'emailtitle')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true])}</td>";
                        echo "</tr>"; 

                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'>Email Introductory Statements</th>";
                            if ($package->packageid  && ($package->packagetypeid==1 || $package->packagetypeid==2))
                            {
                                $text= date("l F j, Y") . "<br/>" . "Dear [firstname] [lastname]";
                                echo "<td>";
                                    echo Html::textarea('email-intro', $text, ['rows' => 10, 'maxlength' => true, 'style' => 'font-size:14px; width:100%', 'disabled' => true]);
                                echo "</td>";
                            }
                            elseif ($package->packageid  && $package->packagetypeid==3)
                            {
                                $text= date("l F j, Y") . "                                                                                                 "
                                        . "                                                                                     Dear [firstname] [lastname],"
                                        . "                                                                                                                 "
                                        . "                                                                                      "  
                                        . "We are pleased to inform you that you have been invited to interview for a place in the  "
                                        . "[programme name] at the [division_name] commencing on " . $package->commencementdate .  ".";
                                echo "<td>";
                                    echo Html::textarea('email-intro', $text, ['rows' => 10, 'maxlength' => true, 'style' => 'font-size:14px; width:100%', 'disabled' => true]);
                                echo "</td>";
                            }        
                            elseif ($package->packageid  && $package->packagetypeid==4)
                            {
                                $text= date("l F j, Y") . "                                                                                                  "                                                                                                  
                                        . "                                                                                     Dear [firstname] [lastname],"
                                        . "                                                                                                                 "
                                        . "                                                                                      "  
                                        . "We are pleased to inform you that your application to the St. Vincent and the Grenadines Community College has been successful." 
                                        . "  You are offered a place in the [programme name] at the [division_name] commencing on " . $package->commencementdate .  ".      "
                                        . "Your Student Number is: [student number]";
                                echo "<td>";
                                    echo Html::textarea('email-intro', $text, ['rows' => 10, 'maxlength' => true, 'style' => 'font-size:14px;  width:100%', 'disabled' => true]);
                                echo "</td>";
                            }
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'>Email Content</th>";
                            echo "<td>{$form->field($package, 'emailcontent')->label('', ['class'=> 'form-label'])->textArea(['rows' => 50, 'maxlength' => true, 'style' => 'font-size:14px;', 'readonly' => true])}</td>";
                        echo "</tr>"; 
                    echo "</table>"; 
                ActiveForm::end();    
            ?>
        </fieldset><br/><br/>


        <fieldset>
            <legend><strong>File Listing</strong></legend>
            <?php if (!$saved_documents):?>
                <h3>No files are attached to the current offer.</h3>
            <?php else:?>
                <ul>
                    <?php foreach($saved_documents as $index=>$doc):?>
                        <li><?=substr($doc,42)?></li>
                    <?php endforeach;?>    
                </ul>
            <?php endif;?>
        </fieldset><br/>

        <?= Html::a(' Back',['package/index'], ['class' => 'btn btn-danger pull-right', 'style' => 'width:10%; margin-left:5%;']);?>
     </div>
</div>